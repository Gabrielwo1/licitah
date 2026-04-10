<?php

// Definir o domínio do cookie para todos os subdomínios

function paraURL($name) {
    $name = iconv('UTF-8', 'ASCII//TRANSLIT', $name);
    $url = str_replace(' ', '-', $name);
    $url = preg_replace('/[^A-Za-z0-9\-]/', '', $url);
    $url = strtolower($url);
    return $url;
}

function preConstantes($value) {
    $constants = get_defined_constants(true);
    if (!isset($constants['user'])) {
        return false;
    }
    foreach ($constants['user'] as $name => $val) {
        if ($name === $value) {
            return true;
        }
    }
    return false;
}

date_default_timezone_set('America/Sao_Paulo');

// ─── PostgreSQL / mysqli compatibility result wrapper ─────────────────────────

class PgResult {
    private $rows;
    private $pos = 0;
    public $num_rows;

    public function __construct(array $rows) {
        $this->rows    = $rows;
        $this->num_rows = count($rows);
    }

    public function fetch_assoc() {
        if ($this->pos >= count($this->rows)) return null;
        return $this->rows[$this->pos++];
    }

    public function fetch_array($mode = MYSQLI_BOTH) {
        if ($this->pos >= count($this->rows)) return null;
        $row = $this->rows[$this->pos++];
        if ($mode === MYSQLI_ASSOC) return $row;
        $indexed = array_values($row);
        if ($mode === MYSQLI_NUM) return $indexed;
        return array_merge($row, $indexed); // MYSQLI_BOTH
    }

    public function fetch_row() {
        return $this->fetch_array(MYSQLI_NUM);
    }

    public function fetch_all($mode = MYSQLI_ASSOC) {
        $out = [];
        while ($row = $this->fetch_assoc()) $out[] = $row;
        return $out;
    }

    public function free() {}
    public function close() {}
    public function data_seek($offset) { $this->pos = $offset; }
}

// ─── PostgreSQL / mysqli compatibility statement wrapper ──────────────────────

class PgStatement {
    private $pdo;
    private $sql;
    private $params   = [];
    private $types    = '';
    private $stmt     = null;
    public  $insert_id = 0;
    public  $affected_rows = 0;
    public  $num_rows = 0;

    public function __construct(PDO $pdo, string $sql) {
        $this->pdo = $pdo;
        // Convert MySQL ? placeholders to numbered $1,$2 for PDO pgsql
        $this->sql = $sql;
    }

    public function bind_param(string $types, &...$vars) {
        $this->types = $types;
        $this->params = $vars;
    }

    public function execute() {
        $sql = PgConn::translateSQL($this->sql);
        // Convert ? to $1, $2 ... for pgsql
        $idx = 0;
        $converted = preg_replace_callback('/\?/', function($m) use (&$idx) {
            return '$' . (++$idx);
        }, $sql);
        try {
            $stmt = $this->pdo->prepare($converted);
            $values = [];
            foreach ($this->params as $v) {
                $values[] = $v;
            }
            $stmt->execute($values);
            $this->affected_rows = $stmt->rowCount();
            $this->stmt = $stmt;
            return true;
        } catch (PDOException $e) {
            error_log('[PgStatement] Execute error: ' . $e->getMessage() . ' SQL: ' . $converted);
            return false;
        }
    }

    public function get_result() {
        if (!$this->stmt) return false;
        $rows = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        return new PgResult($rows);
    }

    public function fetch_assoc() {
        if (!$this->stmt) return null;
        return $this->stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function store_result() { return true; }
    public function close() {}
    public function free_result() {}
}

// ─── PostgreSQL / mysqli compatibility connection wrapper ─────────────────────

class PgConn {
    private PDO $pdo;
    public  int $insert_id      = 0;
    public  int $affected_rows  = 0;
    public  string $connect_error = '';

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Translate common MySQL-specific SQL to PostgreSQL
    public static function translateSQL(string $sql): string {
        // Backticks → double quotes
        $sql = preg_replace('/`(\w+)`/', '"$1"', $sql);

        // IFNULL → COALESCE
        $sql = preg_replace('/\bIFNULL\s*\(/i', 'COALESCE(', $sql);

        // GROUP_CONCAT(x SEPARATOR ',') → string_agg(x, ',')
        $sql = preg_replace_callback(
            '/\bGROUP_CONCAT\s*\(\s*(.+?)\s+SEPARATOR\s+([\'"][^\'"]*[\'"])\s*\)/is',
            fn($m) => "string_agg(CAST({$m[1]} AS TEXT), {$m[2]})",
            $sql
        );
        $sql = preg_replace_callback(
            '/\bGROUP_CONCAT\s*\((.+?)\)/is',
            fn($m) => "string_agg(CAST({$m[1]} AS TEXT), ',')",
            $sql
        );

        // RAND() → RANDOM()
        $sql = preg_replace('/\bRAND\s*\(\s*\)/i', 'RANDOM()', $sql);

        // INSERT IGNORE INTO → INSERT INTO ... ON CONFLICT DO NOTHING
        if (preg_match('/\bINSERT\s+IGNORE\s+INTO\b/i', $sql)) {
            $sql = preg_replace('/\bINSERT\s+IGNORE\s+INTO\b/i', 'INSERT INTO', $sql);
            $sql = rtrim(rtrim($sql), ';') . ' ON CONFLICT DO NOTHING';
        }

        // ON DUPLICATE KEY UPDATE → ON CONFLICT handling
        // Pattern: ON DUPLICATE KEY UPDATE col = col  (no-op / "insert if not exists")
        // becomes: ON CONFLICT DO NOTHING
        // Pattern: ON DUPLICATE KEY UPDATE col = value  (real upsert)
        // becomes: ON CONFLICT DO UPDATE SET col = EXCLUDED.col
        $sql = preg_replace_callback(
            '/\bON\s+DUPLICATE\s+KEY\s+UPDATE\s+(.+?)(?=;|$)/is',
            function($m) {
                $updates = trim($m[1]);
                // Check if all assignments are col = col (no-op)
                $assignments = preg_split('/\s*,\s*/', $updates);
                $all_noop = true;
                $pg_sets = [];
                foreach ($assignments as $assign) {
                    // col = col  OR  "col" = "col"
                    if (preg_match('/^["`]?(\w+)["`]?\s*=\s*["`]?\1["`]?$/', trim($assign))) {
                        continue; // skip no-op
                    }
                    $all_noop = false;
                    // Convert VALUES(col) → EXCLUDED.col
                    $assign = preg_replace('/VALUES\s*\(\s*["`]?(\w+)["`]?\s*\)/i', 'EXCLUDED."$1"', $assign);
                    $pg_sets[] = $assign;
                }
                if ($all_noop || empty($pg_sets)) {
                    return 'ON CONFLICT DO NOTHING';
                }
                return 'ON CONFLICT DO UPDATE SET ' . implode(', ', $pg_sets);
            },
            $sql
        );

        // DATE_FORMAT(col, '%Y-%m-%d') → TO_CHAR(col, 'YYYY-MM-DD')
        $sql = preg_replace_callback(
            '/\bDATE_FORMAT\s*\(\s*(.+?)\s*,\s*[\'"]([^\'"]+)[\'"]\s*\)/i',
            function($m) {
                $fmt = str_replace(['%Y','%m','%d','%H','%i','%s'],
                                   ['YYYY','MM','DD','HH24','MI','SS'], $m[2]);
                return "TO_CHAR({$m[1]}, '{$fmt}')";
            },
            $sql
        );

        // FIND_IN_SET(x, y) → x = ANY(string_to_array(y, ','))
        $sql = preg_replace_callback(
            '/\bFIND_IN_SET\s*\(\s*(.+?)\s*,\s*(.+?)\s*\)/i',
            fn($m) => "{$m[1]} = ANY(string_to_array({$m[2]}, ','))",
            $sql
        );

        // LIMIT x,y → LIMIT y OFFSET x  (MySQL positional LIMIT)
        $sql = preg_replace('/\bLIMIT\s+(\d+)\s*,\s*(\d+)/i', 'LIMIT $2 OFFSET $1', $sql);

        // Engine/charset clauses (if somehow in queries)
        $sql = preg_replace('/\bENGINE\s*=\s*\w+/i', '', $sql);

        return $sql;
    }

    public function query(string $sql) {
        $sql = self::translateSQL($sql);
        try {
            $stmt = $this->pdo->query($sql);
            $this->affected_rows = $stmt->rowCount();
            // Get last inserted ID if INSERT (uses currval of sequence via lastval())
            if (stripos(ltrim($sql), 'INSERT') === 0) {
                try {
                    $idStmt = $this->pdo->query('SELECT lastval()');
                    $this->insert_id = (int)$idStmt->fetchColumn();
                } catch (Exception $e) {
                    $this->insert_id = 0;
                }
            }
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return new PgResult($rows);
        } catch (PDOException $e) {
            error_log('[PgConn] Query error: ' . $e->getMessage() . "\nSQL: " . $sql);
            return false;
        }
    }

    public function prepare(string $sql): PgStatement {
        return new PgStatement($this->pdo, $sql);
    }

    public function real_escape_string(string $str): string {
        // PDO quote adds surrounding quotes — we strip them
        $quoted = $this->pdo->quote($str);
        return substr($quoted, 1, -1);
    }

    public function escape_string(string $str): string {
        return $this->real_escape_string($str);
    }

    public function set_charset(string $charset): bool {
        return true; // PostgreSQL uses UTF-8 by default
    }

    public function close(): bool {
        return true;
    }

    // Proxy property reads
    public function __get(string $name) {
        if ($name === 'insert_id')     return $this->insert_id;
        if ($name === 'affected_rows') return $this->affected_rows;
        if ($name === 'connect_error') return $this->connect_error;
        return null;
    }
}

// ─── Connection factories ─────────────────────────────────────────────────────

function _loadConfig(): void {
    if (!preConstantes('PG_DSN')) {
        include __DIR__ . '/../conteudo/config.php';
    }
}

/**
 * Returns a PgConn (mysqli-compatible wrapper over PostgreSQL PDO).
 * Drop-in replacement for the old mysqli conn().
 */
function conn(): PgConn {
    _loadConfig();
    $dsn      = PG_DSN;
    $usuario  = PG_USUARIO;
    $senha    = PG_SENHA;

    try {
        $pdo = new PDO($dsn, $usuario, $senha, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        // Set Brazil timezone
        $pdo->exec("SET TIME ZONE 'America/Sao_Paulo'");
        return new PgConn($pdo);
    } catch (PDOException $e) {
        die('Falha na conexão com o banco: ' . $e->getMessage());
    }
}

/**
 * Returns a raw PDO PostgreSQL connection.
 */
function connPdo(): PDO {
    _loadConfig();
    $dsn     = PG_DSN;
    $usuario = PG_USUARIO;
    $senha   = PG_SENHA;

    try {
        $pdo = new PDO($dsn, $usuario, $senha, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $pdo->exec("SET TIME ZONE 'America/Sao_Paulo'");
        return $pdo;
    } catch (PDOException $e) {
        die('Falha na conexão PDO: ' . $e->getMessage());
    }
}

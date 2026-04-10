<?php
include __DIR__ . "/../../../../admin/conn.php";

class Relatorio {
    private $conn;
    private $user;
    private $acao;
    private $comeco;
    private $fim;
    
    public function __construct() {
        $this->conn = conn();
        $this->user = $_SESSION["id"] ?? false;
        $this->acao = $_POST["acao"] ?? false;
        $this->comeco = $_POST["start"] ?? false;
        $this->fim = $_POST["end"] ?? false;
    }
    
    private function validarDatas() {
        if (!$this->comeco || !$this->fim) {
            return ["erro" => true, "mensagem" => "Não foi enviado um período inicial e final"];
        }

        $dataComeco = DateTime::createFromFormat('Y-m-d H:i:s', $this->comeco);
        $dataFim = DateTime::createFromFormat('Y-m-d H:i:s', $this->fim);
        

        if (!$dataComeco) {
            $dataComeco = DateTime::createFromFormat('Y-m-d', $this->comeco);
        }
        if (!$dataFim) {
            $dataFim = DateTime::createFromFormat('Y-m-d', $this->fim);
        }
        
        if (!$dataComeco || !$dataFim) {
            return ["erro" => true, "mensagem" => "Formato de data inválido"];
        }
        
        if ($dataFim <= $dataComeco) {
            return ["erro" => true, "mensagem" => "A data de fim deve ser posterior à data de começo"];
        }
        
        return ["sucesso" => true];
    }
    
    public function sessoes($live = false) {
    if (!$live) {
        $validacao = $this->validarDatas();
        if (isset($validacao["erro"])) {
            return $validacao;
        }
    }
    
    try {
        if ($live) {
            // Query otimizada para sessões live com todas as métricas
            $sql = "SELECT 
                        COUNT(*) as total,
                        COUNT(DISTINCT CASE WHEN analytic_ip IS NOT NULL AND analytic_ip != '' THEN analytic_ip END) as ips_distintos,
                        COUNT(CASE WHEN analytic_ativo = '1' THEN 1 END) as usuarios_ativos,
                        COUNT(CASE WHEN analytic_ativo = '0' THEN 1 END) as usuarios_desativados,
                        COUNT(CASE WHEN analytic_usuario > 0 THEN 1 END) as usuarios_logados,
                        COUNT(CASE WHEN analytic_usuario IS NULL OR analytic_usuario = 0 THEN 1 END) as usuarios_nao_logados,
                        COUNT(DISTINCT CASE WHEN analytic_dispositivo IS NOT NULL AND analytic_dispositivo != '' THEN analytic_dispositivo END) as dispositivos_distintos
                    FROM analytics 
                    WHERE analytic_ativo = '1'";
            
            $stmt = $this->conn->prepare($sql);
        } else {
            // Query otimizada para período específico com todas as métricas
            $sql = "SELECT 
                        COUNT(*) as total,
                        COUNT(DISTINCT CASE WHEN analytic_ip IS NOT NULL AND analytic_ip != '' THEN analytic_ip END) as ips_distintos,
                        COUNT(CASE WHEN analytic_ativo = '1' THEN 1 END) as usuarios_ativos,
                        COUNT(CASE WHEN analytic_ativo = '0' THEN 1 END) as usuarios_desativados,
                        COUNT(CASE WHEN analytic_usuario > 0 THEN 1 END) as usuarios_logados,
                        COUNT(CASE WHEN analytic_usuario IS NULL OR analytic_usuario = 0 THEN 1 END) as usuarios_nao_logados,
                        COUNT(DISTINCT CASE WHEN analytic_dispositivo IS NOT NULL AND analytic_dispositivo != '' THEN analytic_dispositivo END) as dispositivos_distintos
                    FROM analytics 
                    WHERE analytic_data BETWEEN ? AND ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $this->comeco, $this->fim);
        }
        
        $stmt->execute();
        $resultado = $stmt->get_result();
        $dados = $resultado->fetch_assoc();
        
        // Estrutura otimizada da resposta
        $resposta = [
            "total" => (int) $dados["total"],
            "ips" => [
                "distintos" => (int) $dados["ips_distintos"]
            ],
            "usuarios" => [
                "ativos" => (int) $dados["usuarios_ativos"],
                "desativados" => (int) $dados["usuarios_desativados"],
                "logados" => (int) $dados["usuarios_logados"],
                "nao_logados" => (int) $dados["usuarios_nao_logados"]
            ],
            "dispositivos" => [
                "distintos" => (int) $dados["dispositivos_distintos"]
            ],
            "periodo" => $live ? "live" : [
                "inicio" => $this->comeco,
                "fim" => $this->fim
            ],
            "timestamp" => date('Y-m-d H:i:s')
        ];
        
        $stmt->close();
        
        return ["sucesso" => true, "infos" => $resposta];
        
    } catch (Exception $e) {
        return [
            "erro" => true, 
            "mensagem" => "Erro ao consultar dados: " . $e->getMessage()
        ];
    }
}

    public function dispositivos($live = false) {
    if (!$live) {
        $validacao = $this->validarDatas();
        if ($validacao["erro"]) {
            return $validacao;
        }
    }
    
    try {
        // Primeiro: contar ocorrências de cada dispositivo
        if ($live) {
            $sql = "SELECT 
                        analytic_dispositivo as dispositivo,
                        COUNT(*) as total_acessos
                    FROM analytics 
                    WHERE analytic_ativo = '1' 
                      AND analytic_dispositivo IS NOT NULL 
                      AND analytic_dispositivo != ''
                    GROUP BY analytic_dispositivo";
            
            $stmt = $this->conn->prepare($sql);
        } else {
            $sql = "SELECT 
                        analytic_dispositivo as dispositivo,
                        COUNT(*) as total_acessos
                    FROM analytics 
                    WHERE analytic_data BETWEEN ? AND ?
                      AND analytic_dispositivo IS NOT NULL 
                      AND analytic_dispositivo != ''
                    GROUP BY analytic_dispositivo";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $this->comeco, $this->fim);
        }
        
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        $dispositivos_acessos = [];
        $total_dispositivos = 0;
        $total_acessos = 0;
        
        while ($row = $resultado->fetch_assoc()) {
            $dispositivos_acessos[] = [
                'dispositivo' => $row['dispositivo'],
                'acessos' => (int) $row['total_acessos']
            ];
            $total_dispositivos++;
            $total_acessos += (int) $row['total_acessos'];
        }
        
        $stmt->close();
        
        // Segundo: buscar detalhes dos dispositivos na tabela analytics_dispositivos
        if (!empty($dispositivos_acessos)) {
            $dispositivos_ids = array_column($dispositivos_acessos, 'dispositivo');
            $placeholders = str_repeat('?,', count($dispositivos_ids) - 1) . '?';
            
            $sql_detalhes = "SELECT 
                                analytics_dispositivo_id,
                                analytics_dispositivo_tipo,
                                analytics_dispositivo_plataforma,
                                analytics_dispositivo_navegador,
                                analytics_dispositivo_bot
                            FROM analytics_dispositivos 
                            WHERE analytics_dispositivo_id IN ($placeholders)";
            
            $stmt_detalhes = $this->conn->prepare($sql_detalhes);
            $stmt_detalhes->bind_param(str_repeat('i', count($dispositivos_ids)), ...$dispositivos_ids);
            $stmt_detalhes->execute();
            
            $resultado_detalhes = $stmt_detalhes->get_result();
            $detalhes_dispositivos = [];
            
            while ($row = $resultado_detalhes->fetch_assoc()) {
                $detalhes_dispositivos[$row['analytics_dispositivo_id']] = [
                    'tipo' => (int) $row['analytics_dispositivo_tipo'],
                    'plataforma' => (int) $row['analytics_dispositivo_plataforma'],
                    'navegador' => (int) $row['analytics_dispositivo_navegador'],
                    'bot' => (int) $row['analytics_dispositivo_bot']
                ];
            }
            
            $stmt_detalhes->close();
            
            // Terceiro: Processar estatísticas agrupadas
            $estatisticas = [
                'tipos' => [],
                'plataformas' => [],
                'navegadores' => [],
                'usuarios' => ['reais' => 0, 'bots' => 0]
            ];
            
            foreach ($dispositivos_acessos as $dispositivo_acesso) {
                $dispositivo_id = $dispositivo_acesso['dispositivo'];
                $acessos = $dispositivo_acesso['acessos'];
                
                if (isset($detalhes_dispositivos[$dispositivo_id])) {
                    $detalhes = $detalhes_dispositivos[$dispositivo_id];
                    
                    // Agregar por tipo
                    $tipo = $detalhes['tipo'];
                    if (!isset($estatisticas['tipos'][$tipo])) {
                        $estatisticas['tipos'][$tipo] = 0;
                    }
                    $estatisticas['tipos'][$tipo] += $acessos;
                    
                    // Agregar por plataforma
                    $plataforma = $detalhes['plataforma'];
                    if (!isset($estatisticas['plataformas'][$plataforma])) {
                        $estatisticas['plataformas'][$plataforma] = 0;
                    }
                    $estatisticas['plataformas'][$plataforma] += $acessos;
                    
                    // Agregar por navegador
                    $navegador = $detalhes['navegador'];
                    if (!isset($estatisticas['navegadores'][$navegador])) {
                        $estatisticas['navegadores'][$navegador] = 0;
                    }
                    $estatisticas['navegadores'][$navegador] += $acessos;
                    
                    // Agregar usuários reais vs bots
                    if ($detalhes['bot'] == 1) {
                        $estatisticas['usuarios']['bots'] += $acessos;
                    } else {
                        $estatisticas['usuarios']['reais'] += $acessos;
                    }
                }
            }
            
            // Ordenar estatísticas por quantidade de acessos (decrescente)
            arsort($estatisticas['tipos']);
            arsort($estatisticas['plataformas']);
            arsort($estatisticas['navegadores']);
        }
        
        $resposta = [
            'resumo' => [
                'total_dispositivos_distintos' => $total_dispositivos,
                'total_acessos' => $total_acessos
            ],
            'estatisticas' => $estatisticas,
            'periodo' => $live ? 'live' : [
                'inicio' => $this->comeco,
                'fim' => $this->fim
            ],
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        return ["sucesso" => true, "infos" => $resposta];
        
    } catch (Exception $e) {
        return [
            "erro" => true,
            "mensagem" => "Erro ao consultar dispositivos: " . $e->getMessage()
        ];
    }
}

    public function abas($live = false) {
    if (!$live) {
        $validacao = $this->validarDatas();
        if ($validacao["erro"]) {
            return $validacao;
        }
    }
    
    try {
        if ($live) {
            // Para live: conta apenas abas ativas no momento
            $sql = "SELECT COUNT(*) as total_abas FROM analytics_abas WHERE analytics_aba_ativa = '1'";
            $stmt = $this->conn->prepare($sql);
        } else {
            // Para período: conta abas que estavam ativas no período
            $sql = "SELECT COUNT(*) as total_abas 
                    FROM analytics_abas aa
                    INNER JOIN analytics an ON aa.analytics_aba_sessao = an.analytic_sessao
                    WHERE an.analytic_data BETWEEN ? AND ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $this->comeco, $this->fim);
        }
        
        $stmt->execute();
        $resultado = $stmt->get_result();
        $dados = $resultado->fetch_assoc();
        $stmt->close();
        
        $resposta = [
            'total_abas' => (int) $dados['total_abas'],
            'periodo' => $live ? 'live' : [
                'inicio' => $this->comeco,
                'fim' => $this->fim
            ],
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        return ["sucesso" => true, "infos" => $resposta];
        
    } catch (Exception $e) {
        return [
            "erro" => true,
            "mensagem" => "Erro ao consultar abas: " . $e->getMessage()
        ];
    }
}

   
    public function usuariosLogados() {
    try {
        $sql = "SELECT 
                    COUNT(*) as total,
                    GROUP_CONCAT(DISTINCT analytic_usuario ORDER BY analytic_usuario) as ids_usuarios
                FROM analytics 
                WHERE analytic_ativo = '1' 
                  AND analytic_usuario IS NOT NULL 
                  AND analytic_usuario > 0";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $dados = $resultado->fetch_assoc();
        $stmt->close();
        
        // Converte a string de IDs em array
        $ids_array = [];
        $usuarios_detalhes = [];
        
        if (!empty($dados['ids_usuarios'])) {
            $ids_array = array_map('intval', explode(',', $dados['ids_usuarios']));
            
            // Busca detalhes dos usuários
            $placeholders = str_repeat('?,', count($ids_array) - 1) . '?';
            $sql_usuarios = "SELECT 
                                usuario_id,
                                usuario_display as name, 
                                usuario_user as user, 
                                usuario_foto as foto, 
                                usuario_capa as capa, 
                                usuario_funcao as funcao 
                            FROM usuarios 
                            WHERE usuario_id IN ($placeholders)
                            ORDER BY usuario_display";
            
            $stmt_usuarios = $this->conn->prepare($sql_usuarios);
            $stmt_usuarios->bind_param(str_repeat('i', count($ids_array)), ...$ids_array);
            $stmt_usuarios->execute();
            
            $resultado_usuarios = $stmt_usuarios->get_result();
            while ($usuario = $resultado_usuarios->fetch_assoc()) {
                $usuarios_detalhes[] = [
                    'id' => (int) $usuario['usuario_id'],
                    'name' => $usuario['name'],
                    'user' => $usuario['user'],
                    'foto' => $usuario['foto'],
                    'capa' => $usuario['capa'],
                    'funcao' => $usuario['funcao']
                ];
            }
            
            $stmt_usuarios->close();
        }
        
        $resposta = [
            'total_usuarios_logados' => (int) $dados['total'],
            'usuarios' => $usuarios_detalhes,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        return ["sucesso" => true, "infos" => $resposta];
        
    } catch (Exception $e) {
        return [
            "erro" => true,
            "mensagem" => "Erro ao consultar usuários logados: " . $e->getMessage()
        ];
    }
}

    public function home() {
        $sessoes = $this->sessoes(true);
        
        $dispositivos = $this->dispositivos(true);
        
        $abas = $this->abas(true);
        
        $usuarios = $this->usuariosLogados();
      
        $resposta = ["sucesso"=>true];
        $resposta["sessoes"] = $sessoes["infos"];
        $resposta["dispositivos"] = $dispositivos["infos"];
        $resposta["abas"] = $abas["infos"];
        $resposta["logados"] = $usuarios["infos"];
        
        return  $resposta;
    }
    
  
    public function relatorioPeriodo() {
        $validacao = $this->validarDatas();
        if ($validacao["erro"]) {
            return $validacao;
        }
        
        try {
            // Query mais detalhada para relatório
            $sql = "SELECT 
                        COUNT(*) as total,
                        COUNT(CASE WHEN analytic_ativo = '1' THEN 1 END) as ativos,
                        COUNT(CASE WHEN analytic_ativo = '0' THEN 1 END) as inativos,
                        MIN(analytic_data) as primeiro_registro,
                        MAX(analytic_data) as ultimo_registro
                    FROM analytics 
                    WHERE analytic_data BETWEEN ? AND ?";
                    
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $this->comeco, $this->fim);
            $stmt->execute();
            
            $resultado = $stmt->get_result();
            $dados = $resultado->fetch_assoc();
            
            $resposta = [
                "total" => (int) $dados["total"],
                "ativos" => (int) $dados["ativos"],
                "inativos" => (int) $dados["inativos"],
                "primeiro_registro" => $dados["primeiro_registro"],
                "ultimo_registro" => $dados["ultimo_registro"],
                "periodo" => [
                    "inicio" => $this->comeco,
                    "fim" => $this->fim
                ]
            ];
            
            $stmt->close();
            
            return ["sucesso" => true, "infos" => $resposta];
            
        } catch (Exception $e) {
            return [
                "erro" => true,
                "mensagem" => "Erro ao gerar relatório: " . $e->getMessage()
            ];
        }
    }
    

    public function render() {
        switch ($this->acao) {
            case 'live':
                return $this->home();
                
            case 'periodo':
                return $this->sessoes(false);
                
            case 'relatorio':
                return $this->relatorioPeriodo();
                
            default:
                return [
                    "erro" => true,
                    "mensagem" => "Ação não reconhecida. Ações disponíveis: live, periodo, relatorio"
                ];
        }
    }
    
  
    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
    
 
    public function __destruct() {
        $this->close();
    }
}

// Exemplo de uso
try {
    // Para teste - remover em produção
    if (!isset($_POST["acao"])) {
        $_POST = ["acao" => "live"];
    }
    
    $relatorio = new Relatorio();
    $resposta = $relatorio->render();
    
    // Headers para JSON
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
} catch (Exception $e) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        "erro" => true,
        "mensagem" => "Erro interno: " . $e->getMessage()
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} finally {
    if (isset($relatorio)) {
        $relatorio->close();
    }
}
?>
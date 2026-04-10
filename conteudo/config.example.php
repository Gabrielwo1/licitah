<?php
// Copy this file to config.php and fill in your credentials
define("CHAVE", "your-secret-key-here");

// ── Neon PostgreSQL ────────────────────────────────────────────────────────────
define("PG_HOST",    "your-neon-host.neon.tech");
define("PG_BANCO",   "neondb");
define("PG_USUARIO", "neondb_owner");
define("PG_SENHA",   "your-password-here");
define("PG_DSN",     "pgsql:host=" . PG_HOST . ";dbname=" . PG_BANCO . ";sslmode=require");

// Aliases para código legado
define("SERVIDOR", PG_HOST);
define("BANCO",    PG_BANCO);
define("USUARIO",  PG_USUARIO);
define("SENHA",    PG_SENHA);

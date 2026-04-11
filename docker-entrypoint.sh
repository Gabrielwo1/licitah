#!/bin/bash
set -e

# Generate config.php from environment variables at container startup
# Set these env vars on Railway/Render:
#   PG_HOST, PG_BANCO, PG_USUARIO, PG_SENHA, APP_CHAVE

CONFIG_FILE="/var/www/html/conteudo/config.php"

cat > "$CONFIG_FILE" << EOF
<?php
define("CHAVE",      "${APP_CHAVE:-Pytho-scrip-RavCu-3fgBN-U5A3S}");
define("PG_HOST",    "${PG_HOST:-ep-green-rice-achbfzu9-pooler.sa-east-1.aws.neon.tech}");
define("PG_BANCO",   "${PG_BANCO:-neondb}");
define("PG_USUARIO", "${PG_USUARIO:-neondb_owner}");
define("PG_SENHA",   "${PG_SENHA:-npg_ZUmquPN5jz0t}");
define("PG_DSN",     "pgsql:host=${PG_HOST:-ep-green-rice-achbfzu9-pooler.sa-east-1.aws.neon.tech};dbname=${PG_BANCO:-neondb};sslmode=require");
define("SERVIDOR",   "${PG_HOST:-ep-green-rice-achbfzu9-pooler.sa-east-1.aws.neon.tech}");
define("BANCO",      "${PG_BANCO:-neondb}");
define("USUARIO",    "${PG_USUARIO:-neondb_owner}");
define("SENHA",      "${PG_SENHA:-npg_ZUmquPN5jz0t}");
EOF

echo "Config generated from environment variables."
exec "$@"

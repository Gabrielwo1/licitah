<?
$instalador = array(
    "sql"=>
    "CREATE TABLE uploads (
    upload_id INT PRIMARY KEY AUTO_INCREMENT,
    upload_nome VARCHAR(255) COLLATE utf8mb4_unicode_520_ci,
    upload_tipo VARCHAR(50) COLLATE utf8mb4_unicode_520_ci,
    upload_data DATETIME DEFAULT CURRENT_TIMESTAMP,
    upload_caminho VARCHAR(255) COLLATE utf8mb4_unicode_520_ci,
    upload_formato VARCHAR(10) COLLATE utf8mb4_unicode_520_ci,
    upload_tamanho INT,
    upload_autor INT
);",
    "nome"=>"midias",
    "metas"=>false
    );
?>
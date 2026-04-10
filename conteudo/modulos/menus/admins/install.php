<?

$instalador = array(
    "sql" => "CREATE TABLE menus (
    menu_id INT AUTO_INCREMENT PRIMARY KEY,
    menu_hash VARCHAR(15) NOT NULL,
    menu_nome VARCHAR(100),
    menu_espaco INT NOT NULL DEFAULT 0,
    menu_estrutura JSON
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci",
    "nome"=>"menus",
    "metas"=>"false"
    );


?>
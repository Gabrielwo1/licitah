<?

$url = "https://install.nown.com.br/arquivos/sistema/sistema.zip";
$zipFilePath = __DIR__ . "/zip.zip"; 
$extractPath = __DIR__ . "/.."; 
                
               
$zipContents = file_get_contents($url);
if ($zipContents !== false) {
    file_put_contents($zipFilePath, $zipContents);
    $zip = new ZipArchive;
    if ($zip->open($zipFilePath) === TRUE) {
        $zip->extractTo($extractPath);
        $zip->close();
        $resposta = ["sucesso" => true, "mensagem" => "Dependências baixadas e descompactadas com sucesso."];
    }else{
            $resposta = ["erro" => true, "mensagem" => "Erro ao abrir o arquivo ZIP ou ao descompactar."];
        }
    } else {
    $resposta = ["erro" => true, "mensagem" => "Não foi possível baixar o arquivo ZIP."];
    }
    
    
echo json_encode($resposta);

?> 
<?php
header('Content-Type: application/json');
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION["id"])) {
    //exit;
}

include 'conn.php';

// Função para gerar um nome aleatório único com base em um UUID
function generateUniqueRandomName() {
    $size = 15;
    $i = 0;
    
    $caracteres = "0123456789abcdefghijklmnopqrstuvxywz";
    $final = "";
    while($i < $size){
        $final .= $caracteres[rand(0, 34)];
        $i++;
    }
    
    return $final. '_' . time();
}

// Função para criar miniatura da imagem
function createThumbnail($sourceFile, $destinationFile, $width) {
    $sourceImage = imagecreatefromstring(file_get_contents($sourceFile));
    $sourceWidth = imagesx($sourceImage);
    $sourceHeight = imagesy($sourceImage);

    $height = intval($width * ($sourceHeight / $sourceWidth));

    $thumbnail = imagecreatetruecolor($width, $height);

    // Preservar transparência se a imagem original tiver
    $transparentColor = imagecolorallocatealpha($thumbnail, 0, 0, 0, 127);
    imagefill($thumbnail, 0, 0, $transparentColor);
    imagesavealpha($thumbnail, true);

    imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0, $width, $height, $sourceWidth, $sourceHeight);

    // Salvar a miniatura mantendo a transparência
    imagewebp($thumbnail, $destinationFile, 80);

    imagedestroy($thumbnail);
    imagedestroy($sourceImage);
}

// Verifica se um arquivo foi enviado
if (isset($_FILES['file']) && isset($_POST["pasta"])) {
    $file = $_FILES['file'];
    $pasta = $_POST["pasta"];

    // Verifica se não houve erros durante o upload
    if ($file['error'] === UPLOAD_ERR_OK) {
        $tempFilePath = $file['tmp_name'];
        $fileName = $file['name'];
        $fileType = mime_content_type($tempFilePath);

        // Define o diretório base para salvar o arquivo
        $baseUploadDir = __DIR__.'/../conteudo/uploads/';
        
        $anoAtual = date("Y");
        $mesAtual = date("m");

        // Verifica se o tipo de arquivo é permitido e define o diretório de destino adequado
        $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $allowedVideoTypes = ['video/mp4', 'video/avi', 'video/quicktime'];
        $allowedDocTypes = ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/pdf', 'text/plain'];
        
        $eImagem = false;
        if (in_array($fileType, $allowedImageTypes)) {
            $uploadDir = $baseUploadDir . 'imagens/' . $pasta . '/'.$anoAtual.'/'.$mesAtual.'/';
            $tipagem = "imagem";
             $eImagem = true;
        } elseif (in_array($fileType, $allowedVideoTypes)) {
            $uploadDir = $baseUploadDir . 'videos/' . $pasta  . '/'.$anoAtual.'/'.$mesAtual.'/';
            $tipagem = "video";
        } elseif (in_array($fileType, $allowedDocTypes)) {
            $uploadDir = $baseUploadDir . 'docs/' . $pasta  . '/'.$anoAtual.'/'.$mesAtual.'/';
            $tipagem = "arquivo";
        } else {
            // Tipo de arquivo não permitido
            $response = array(
                'erro' => true,
                'menssagem' => 'Tipo de arquivo não permitido.'
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        // Verifica se o diretório de destino existe, caso contrário, cria a pasta
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Gera um nome aleatório único para a pasta
        $randomFolderName = generateUniqueRandomName();
        $uploadDir .= $randomFolderName . '/';
        mkdir($uploadDir, 0755, true);

        // Move o arquivo para o diretório de destino
        if($eImagem){
            $trato = explode(".", $fileName);
            $trato = $trato[count($trato) - 1];
            $newFileName = "original.".$trato;
            $nomeOriginal = "original.".$trato;
        }else{
            $tratamento = explode(".", $fileName);
            $formato = $tratamento[count($tratamento) - 1];
            $newFileName = "arquivo.".$formato; 
             $trato = explode(".", $fileName);
             $trato = $trato[count($trato) - 1];
        }
        $newFilePath = $uploadDir . $newFileName;

        if (move_uploaded_file($tempFilePath, $newFilePath)) {
            // Caminho absoluto do arquivo salvo
            $absoluteFilePath = realpath($newFilePath);

            // Cria as miniaturas da imagem
            if (in_array($fileType, $allowedImageTypes)) {
                $thumbnailDir = $uploadDir;
              
                

                createThumbnail($newFilePath, $thumbnailDir . 'mini.webp', 100);
                createThumbnail($newFilePath, $thumbnailDir . 'pequena.webp', 300);
                createThumbnail($newFilePath, $thumbnailDir . 'media.webp', 600);
                createThumbnail($newFilePath, $thumbnailDir . 'grande.webp', 1200);
                
                $sourceImage = imagecreatefromstring(file_get_contents($newFilePath));
                $sourceWidth = intval(imagesx($sourceImage));
                createThumbnail($newFilePath, $thumbnailDir . 'otimizada.webp', $sourceWidth);

                
            }
            
            $fileSize = filesize($newFilePath);

            $userId = $_SESSION["id"];
    $conn = conn(); // Chame a função que cria a conexão com o banco de dados
    
    $url = explode("/uploads/", $newFilePath)[1];

    $insertSql = "INSERT INTO  uploads (upload_nome, upload_tipo, upload_data, upload_caminho, upload_formato, upload_tamanho, upload_autor)
                  VALUES ('$fileName', '$tipagem', NOW(), '$url', '$trato', '$fileSize', '$userId')";

    if ($conn->query($insertSql) === true) {
        // Arquivo inserido no banco de dados com sucesso
        $insertedFileId = $conn->insert_id; // Obter o ID do arquivo recém-inserido
    } else {
        // Erro ao inserir o arquivo no banco de dados
    }

    $conn->close();



            // Resposta de sucesso
            $response = array(
                'sucesso' => true,
                'menssagem' => 'Arquivo recebido e salvo com sucesso!',
                'file_path' => explode("/uploads/", $absoluteFilePath)[1]
            );
        } else {
            // Resposta de erro
            $response = array(
                'erro' => true,
                'menssagem' => 'Erro ao mover o arquivo para o diretório de destino.'
            );
        }
    } else {
        // Resposta de erro
         switch ($file['error']) {
            case UPLOAD_ERR_INI_SIZE:
                $errormenssagem = 'O arquivo excede o limite de tamanho definido no servidor.';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $errormenssagem = 'O arquivo excede o limite de tamanho definido no formulário.';
                break;
            case UPLOAD_ERR_PARTIAL:
                $errormenssagem = 'O upload do arquivo foi realizado parcialmente.';
                break;
            case UPLOAD_ERR_NO_FILE:
                $errormenssagem = 'Nenhum arquivo foi enviado.';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $errormenssagem = 'Diretório temporário não encontrado.';
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $errormenssagem = 'Erro ao gravar o arquivo no disco.';
                break;
            case UPLOAD_ERR_EXTENSION:
                $errormenssagem = 'Uma extensão do PHP interrompeu o upload do arquivo.';
                break;
            default:
                $errormenssagem = 'Erro desconhecido ao receber o arquivo.';
                break;
        }

        $response = array(
            'erro' => true,
            'menssagem' => $errormenssagem
        );
    }
} else {
    // Resposta de erro
    $response = array(
        'erro' => true,
        'menssagem' => 'Nenhum arquivo enviado.'
    );
}

// Retorna a resposta em formato JSON

echo json_encode($response);
?>

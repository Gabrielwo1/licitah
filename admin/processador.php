<?php

include __DIR__."/seguranca.php";

//$seguranca = new Seguranca(true);
//$seguranca->onlyLogados();
//$seguranca->setLimite(30, 60);


ini_set('memory_limit', '256M');

include __DIR__.'/conn.php';

class Upload{
    public $arquivo;
    public $diretorio;
    public $modulo;
    public $autor;
    public $tamanho;

    function __construct(){
         $this->arquivo = $_FILES['filepond'] ?? false;
    
        
         $this->diretorio = __DIR__."/../conteudo/uploads/";
         $this->modulo = $_GET["modulo"] ?? "newUploader";
         $this->autor = $_SESSION["id"] ?? false;
         
    }
    
    function geraId($size = 15) {

    $i = 0;
    
    $caracteres = "0123456789abcdefghijklmnopqrstuvxywz";
    $final = "";
    while($i < $size){
        $final .= $caracteres[rand(0, 34)];
        $i++;
    }
    
    return $final. '-' . time();
}
    
    function pasta($caminho){
        if (!file_exists($caminho)) {
            mkdir($caminho, 0777, true);
        }
    }
    
    function createThumbnail($sourceFile, $destinationFile, $width) {
        $sourceImage = imagecreatefromstring(file_get_contents($sourceFile));
        if (!$sourceImage) {
            return false; // Não conseguiu ler a imagem
        }

        $sourceWidth = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);

        $height = intval($width * ($sourceHeight / $sourceWidth));

        $thumbnail = imagecreatetruecolor($width, $height);

        // Preservar transparência se a imagem original tiver
        imagealphablending($thumbnail, false);
        $transparentColor = imagecolorallocatealpha($thumbnail, 0, 0, 0, 127);
        imagefill($thumbnail, 0, 0, $transparentColor);
        imagesavealpha($thumbnail, true);

        imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0, $width, $height, $sourceWidth, $sourceHeight);

        // Salvar a miniatura no formato WebP mantendo a transparência
        imagewebp($thumbnail, $destinationFile, 80);

        imagedestroy($thumbnail);
        imagedestroy($sourceImage);

        return true; // Sucesso na criação da miniatura
    }
    
    function corrigirMimeTypeAudio($fileMimeType, $tempFilePath, $nomeOriginal) {
    // Se já é claramente áudio, retorna como está
    if (str_starts_with($fileMimeType, 'audio/')) {
        return $fileMimeType;
    }
    
    // Se é video/webm, vamos verificar se realmente tem vídeo
    if ($fileMimeType === 'video/webm') {
        
        // Método 1: Verificar com finfo mais específico
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeDetalhado = $finfo->file($tempFilePath);
        
        // Método 2: Verificar por tamanho (áudio geralmente é menor)
        $tamanho = filesize($tempFilePath);
        
        // Método 3: Verificar por nome do arquivo (se vier do JavaScript de áudio)
        $nomeContemAudio = strpos(strtolower($nomeOriginal), 'audio') !== false;
        
        // Método 4: Verificar cabeçalho do arquivo WebM
        $ehSomenteAudio = $this->verificarWebMSomenteAudio($tempFilePath);
        
        // Se há evidências de que é áudio, corrigir o MIME type
        if ($ehSomenteAudio || $nomeContemAudio || ($tamanho < 5 * 1024 * 1024)) { // < 5MB provavelmente é áudio
            return 'audio/webm';
        }
    }
    
 
    
    return $fileMimeType;
}

    function render(){
        if(!$this->arquivo){
            return ["erro"=>true, "mensagem"=>"Nenhum arquivo válido enviado"];
        }
        
        if(!$this->autor){
           // return ["erro"=>true, "mensagem"=>"Função exclusiva para usuários logados"];
        }
        
        
        $anoAtual = date("Y");
        $mesAtual = date("m");


        $file = $this->arquivo;
        $this->tamanho = filesize($file['tmp_name']);
        if ($file['error'] === UPLOAD_ERR_OK) {
        

            $conn = conn();
            $tempFilePath = $file['tmp_name'];
            $tamanho = $this->tamanho;

            $usuario = $this->autor;
            $fileNameWithExtension = $file['name'];
            $nomeArquivo = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);


            $fileMimeType = mime_content_type($tempFilePath);
            $formato = pathinfo($file['name'], PATHINFO_EXTENSION);
            $identificador = $this->geraId();
            
            
            $fileHash = hash_file('sha256', $tempFilePath);
            
            $acao = "SELECT COUNT(*) as total_count, upload_url FROM uploads WHERE upload_assinatura = '$fileHash' AND upload_autor='$usuario'";
            $result = $conn->query($acao);
            if ($result) {
                $row = $result->fetch_assoc();
                if ($row['total_count'] > 0) {
                    return ["sucesso"=>true, "mensagem"=>"Arquivo já enviado anteriormente.", "nomeArquivo"=>$row["upload_url"], "tipo"=>$fileMimeType];
                }
            }           
            

$fileMimeType = $this->corrigirMimeTypeAudio($fileMimeType, $tempFilePath, $fileNameWithExtension);

            $tipagem = false;
            if (str_starts_with($fileMimeType, 'image/')) {
                $uploadDir = $this->diretorio . "imagens/".$this->modulo."/".$anoAtual."/".$mesAtual."/".$identificador."/";
                $filename =  $formato ? 'original.' . $formato : "original.jpeg";
                $tipagem = 1;
            } elseif (str_starts_with($fileMimeType, 'application/') || str_starts_with($fileMimeType, 'text/')) {
                $uploadDir = $this->diretorio . "documentos/".$this->modulo."/".$anoAtual."/".$mesAtual."/";
                $filename =  $identificador.'.' . $formato;
                $caminhoFinal = "documentos/".$this->modulo."/".$anoAtual."/".$mesAtual."/".$filename;
                $tipagem = 2;
            } elseif (str_starts_with($fileMimeType, 'video/')) {
                $uploadDir = $this->diretorio . "videos/".$this->modulo."/".$anoAtual."/".$mesAtual."/";
                $filename =  $identificador.'.'.$formato;
                $caminhoFinal = "videos/".$this->modulo."/".$anoAtual."/".$mesAtual."/".$filename;
                $tipagem = 3;
            }elseif (str_starts_with($fileMimeType, 'audio/')) {
            // Lógica para arquivos de áudio
            $uploadDir = $this->diretorio . "audios/".$this->modulo."/".$anoAtual."/".$mesAtual."/";
            $filename = $identificador.'.'.$formato;
            $caminhoFinal = "audios/".$this->modulo."/".$anoAtual."/".$mesAtual."/".$filename;
            $tipagem = 4; // Nova tipagem para áudio
            } else {
                return ["erro"=>true, "mensagem"=>"Tipo de arquivo não suportado."];
            }

            $this->pasta($uploadDir);

            $finalFilePath = $uploadDir . $filename;

            if (move_uploaded_file($tempFilePath, $finalFilePath)) {
                
                
                 if (str_starts_with($fileMimeType, 'image/')) {
            $uploadDir = $this->diretorio . "imagens/".$this->modulo."/".$anoAtual."/".$mesAtual."/".$identificador."/";
 
            $filename =  $formato ? 'original.' . $formato : "original.jpeg";
            $caminhoFinal = "imagens/".$this->modulo."/".$anoAtual."/".$mesAtual."/".$identificador."/".$filename;
            $finalFilePath = $uploadDir . $filename;

            // Código para mover o arquivo omitido para brevidade...

            // Após mover o arquivo, otimize para WebP
            $thumbnailDir = $uploadDir; // Diretório para salvar as imagens otimizadas
            $this->createThumbnail($finalFilePath, $thumbnailDir . 'mini.webp', 100);
            $this->createThumbnail($finalFilePath, $thumbnailDir . 'pequena.webp', 300);
            $this->createThumbnail($finalFilePath, $thumbnailDir . 'media.webp', 600);
            $this->createThumbnail($finalFilePath, $thumbnailDir . 'grande.webp', 1200);

            // Otimizar a imagem original para WebP sem alterar as dimensões
            $sourceImage = imagecreatefromstring(file_get_contents($finalFilePath));
            if ($sourceImage) {
                $sourceWidth = intval(imagesx($sourceImage));
                $this->createThumbnail($finalFilePath, $thumbnailDir . 'otimizada.webp', $sourceWidth);
            }
        }
        
            
                $hash = $this->geraId(32);
                
                $acao = "INSERT INTO  uploads ( upload_nome,  upload_tipo, upload_formato, upload_tamanho,  upload_hash, upload_autor, upload_url, upload_assinatura) VALUES ('$nomeArquivo', '$tipagem', '$formato',  '$tamanho',   '$hash',  '$usuario', '$caminhoFinal', '$fileHash')";
                // echo $acao;
                $conn->query($acao);
               
            
                return ["sucesso"=>true, "mensagem"=>"Arquivo enviado com sucesso.", "nomeArquivo"=>$caminhoFinal , "tipo"=>$fileMimeType];
            } else {
                return ["erro"=>true, "mensagem"=>"Erro ao mover o arquivo para a pasta de destino."];
            }
        } else {

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
            'menssagem' => $errormenssagem,
            'metas'=>["size"=>$this->tamanho]
        );
        
        return $response;
        }
    }
}

$acao = new Upload();
$resposta = $acao->render();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

?>

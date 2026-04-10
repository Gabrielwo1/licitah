<?php
class GeraIcone {
    private $iconSizes;
    private $dirIco;
    
    function __construct($diretorio = false) {
        $this->iconSizes = [
            '71', '89', '107', '142', '284', '150', '188', '225', '300', '600',
            '310x150', '388x188', '465x225', '620x300', '1240x600', '310', '388',
            '465', '620', '1240', '44', '55', '66', '88', '176', '50', '63', '75', '100',
            '200', '775x375', '930x450', '2480x1200', '16', '20', '24', '30', '32', '36',
            '40', '48', '60', '64', '72', '80', '96', '256', '512', '192', '144', '29', '57',
            '58', '76', '87', '114', '120', '128', '152', '167', '180', '1024'
        ];
        
        if (!$diretorio) {
            $this->dirIco = __DIR__ . "/../../../icones/";
        } else {
            $this->dirIco = __DIR__ . "/../../../icones/" . $diretorio . "/";
        }
        
        if (!is_dir($this->dirIco)) {
            mkdir($this->dirIco, 0755, true);
        }
    }
    
    function geraImagens($url) {
        // Baixa a imagem da URL fornecida
        $imagemOriginal = imagecreatefromstring(file_get_contents($url));
        if (!$imagemOriginal) {
            die("Não foi possível carregar a imagem.");
        }
        
        $originalWidth = imagesx($imagemOriginal);
        $originalHeight = imagesy($imagemOriginal);
        
        foreach ($this->iconSizes as $size) {
            if (strpos($size, 'x') !== false) {
                // Tamanho retangular
                [$targetWidth, $targetHeight] = explode('x', $size);
                $targetWidth = (int)$targetWidth;
                $targetHeight = (int)$targetHeight;
            } else {
                // Tamanho quadrado
                $targetWidth = $targetHeight = (int)$size;
            }
            
            // Calcula a proporção da nova imagem
            $ratio = min($targetWidth / $originalWidth, $targetHeight / $originalHeight);
            $newWidth = round($originalWidth * $ratio);
            $newHeight = round($originalHeight * $ratio);
            
            // Cria uma nova imagem com o tamanho especificado
            $novaImagem = imagecreatetruecolor($targetWidth, $targetHeight);
            
            // Preserva a transparência
            imagesavealpha($novaImagem, true);
            $corTransparente = imagecolorallocatealpha($novaImagem, 0, 0, 0, 127);
            imagefill($novaImagem, 0, 0, $corTransparente);
            
            // Calcula o posicionamento para centralizar (convertendo explicitamente para int)
            $xOffset = (int)(($targetWidth - $newWidth) / 2);
            $yOffset = (int)(($targetHeight - $newHeight) / 2);
            
            // Redimensiona a imagem original para se ajustar ao novo tamanho, mantendo a proporção e centralizando
            imagecopyresampled(
                $novaImagem, 
                $imagemOriginal, 
                $xOffset, 
                $yOffset, 
                0, 
                0, 
                $newWidth, 
                $newHeight, 
                $originalWidth, 
                $originalHeight
            );
            
            // Salva a nova imagem
            $nomeDoArquivo = $this->dirIco . "ico-{$size}.png";
            if (!imagepng($novaImagem, $nomeDoArquivo)) {
                error_log("Erro ao salvar imagem: " . $nomeDoArquivo);
            }
            
            // Libera a memória
            imagedestroy($novaImagem);
        }
        
        // Libera a memória da imagem original
        imagedestroy($imagemOriginal);
    }
}
?>
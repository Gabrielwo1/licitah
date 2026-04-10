<?
    class Montador{
        public $pagina;
        
        function __construct($pagina){
            $this->pagina = $pagina;
  
        }
        
        function componentes($padrao, $arquivo, $infos = false){
            
            $montador = $this;
            if($infos){
                extract($infos);
            }
            
            if($padrao == 'global'){
                include __DIR__.'/../../conteudo/partes/'.$arquivo.'.php';
            }
            else if($padrao == 'pagina'){
                include __DIR__.'/'.$this->pagina.'/componentes/'.$arquivo.'.php';
            }
            else{
                echo 'arquivo não encontrado';
            }
        }
        
        function imagem($pasta, $arquivo, $tipo = 'webp'){
            return SETUP['dominio'].'conteudo/media/'.$pasta.'/'.$arquivo.'.'.$tipo.'?v='.$this->limpaCache();
        }
        
        
        function limpaCache(){
            return 'v='.round(microtime(true) * 1000);
        }
    }

?>
<?


$publico = $publico."/publico";

$type = false;
$item = false;
$publicUrl = false;
switch(count($this->caminho)){
    case 1:
        if($this->autorizacaoAberto($this->caminho[0], "publicas")){
            if(is_dir($publico."/home") && file_exists($publico."/home/index.php")){
            include $publico."/home/index.php";
            $type = "home";

            }
        }else{
            $this->html = false;
            $this->js = false;
            if($this->blockAction){
                                            $this->first = $this->blockAction;
                                            $this->url = $this->blockAction;
                                            $this->fluxo();
                                            
                                      
                               
                                        }
        }
        break;
    default:
        if(is_dir($publico."/".$this->caminho[1])){
               if($this->autorizacaoAberto($this->caminho[0]."/".$this->caminho[1], "publicas")){
             $type = $this->caminho[1];
             if(count($this->caminho) == 2){
                    if(file_exists($publico."/".$this->caminho[1]."/lista.php")){
                        include $publico."/".$this->caminho[1]."/lista.php";
                        $type = "pai";
                    }
                }else{
                    if(file_exists($publico."/".$this->caminho[1]."/item.php")){
                        include $publico."/".$this->caminho[1]."/item.php";
                        $item = true;
                        $publicUrl = $this->caminho[2];
                        $type = "filho";
                    }
                }
               }else{
            $this->html = false;
            $this->js = false;
            if($this->blockAction){
                                            $this->first = $this->blockAction;
                                            $this->url = $this->blockAction;
                                            $this->fluxo();
                                            
                                      
                               
                                        }
        }
        }else{
             if($this->autorizacaoAberto($this->caminho[0]."/*", "publicas")){
                 if(is_dir($publico."/item") && file_exists($publico."/item/index.php")){
                 include $publico."/item/index.php";
                 $type = "item";
                 $publicUrl = $this->caminho[1];
            }
                 
             }
             else{
            $this->html = false;
            $this->js = false;
            if($this->blockAction){
                                            $this->first = $this->blockAction;
                                            $this->url = $this->blockAction;
                                            $this->fluxo();
                                            
                                      
                               
                                        }
        }

            
        }
        break;
}
if($type){
    $this->type = $type;
    if(file_exists($publico."/css.css")){
        $this->css = explode("/../", $publico)[1]."/css.css";
     }
}


?>

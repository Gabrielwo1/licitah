<?
$configs = [];

$caminho = __DIR__."/../../";
$modulos = scandir($caminho);


foreach($modulos as $modulo){
    if($modulo != "." && $modulo != ".." && $modulo != "configuracoes" && is_dir($caminho.$modulo)){
        if(file_exists($caminho.$modulo."/manifest.json") && is_dir($caminho.$modulo."/admins/setup")){
            $config = json_decode(file_get_contents($caminho.$modulo."/manifest.json"), true);
            
            if($config["ativo"] == "true"){
                  $lista = $config["configs"] ?? [];
   
            
            $subs = [];
            foreach($lista as $item){
                
                $sub= [
                    "nome"=>$item, 
                    ];
                    array_push($subs, $sub);
                }
                $array = [
                    "nome"=>$config["name"],
                    "icone"=>$config["icon"],
                    "subs"=>$subs
                    ];
                    array_push($configs, $array);
            }
            
          
            }
        }
    }
    
    
include __DIR__."/base.php";
?>

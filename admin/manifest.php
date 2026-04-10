<?
header('Content-Type: application/json');

include __DIR__."/../admin/conn.php";
class GeraManifesto{
    private $conn;
    private $host;
    function __construct(){
        $this->conn = conn();
  
        
        $this->host = defined('HOST') ? HOST : false;
   
        if($this->host){
                   $this->iconSizes = [
        '71','89','107','142','284','150','188','225','300','600',
        '310x150','388x188','465x225','620x300','1240x600','310','388',
        '465','620','1240','44','55','66','88','176','50','63','75','100',
        '200','775x375','930x450','2480x1200','16','20','24','30','32','36',
        '40','48','60','64','72','80','96','256','512','192','144','29','57',
        '58','76','87','114','120','128','152','167','180','1024'
        ];
        
        $this->dominio = json_decode(file_get_contents(__DIR__."/../conteudo/setup.json"), true)["dominio"];
        }
      
        
    }
    
    function close(){
        $this->conn->close();
    }
    
    function parse(){
        $seleciona = "SELECT wc_id as id FROM wildcards_contas WHERE wc_dominio='{$this->host}' LIMIT 1";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            return $dado["id"];
        }
        
        $primeiro = explode(".", $this->host)[0];
        
         $seleciona = "SELECT wc_id as id FROM wildcards_contas WHERE wc_url='{$primeiro}' LIMIT 1";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            return $dado["id"];
        }

    }
    
    function render(){
        
        if(!$this->host){
            if(file_exists(__DIR__."/../conteudo/manifest.json")){
                 return json_decode(file_get_contents(__DIR__."/../conteudo/manifest.json"));
            }else{
                return [];
            }
           
        }
        
        
        $id = $this->parse();
        if(!$id){
            return ["erro"=>true, "mensagem"=>"Não foi encontrado um dominio válido"];
        }
        
        $seleciona = "SELECT * FROM aplicativos WHERE aplicativo_wildcard='{$id}' LIMIT 1";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return ["erro"=>true, "mensagem"=>"Não foi encontrado um aplicativo válido configurado"];
        }
        
        
        $dado = $resultado->fetch_assoc();
        
         $manifesto = [
        "name" => $dado["aplicativo_nome"],
        "short_name" => $dado["aplicativo_curto"],
        "start_url" => $dado["aplicativo_inicial"] ?: "/",
        "display" => $dado["aplicativo_display"] ?: "standalone",
        "background_color" => $dado["aplicativo_background"] ?: "#ffffff",
        "theme_color" => $dado["aplicativo_cor"] ?: "#000000",
        "orientation" => $dado["aplicativo_orientacao"] ?: "portrait",
        "scope" => $dado["aplicativo_escopo"] ?: "/",
        "lang" => $dado["aplicativo_idioma"] ?: "pt-BR",
        "dir" => $dado["aplicativo_direcao"] ?: "auto",
        "description" => $dado["aplicativo_descricao"] ?: "",
        "id" => $dado["aplicativo_identificador"] ?: "app-" . $dado["aplicativo_id"],
        ];
        
        foreach($this->iconSizes as $size){
                        $nome = count(explode("x", $size)) == 2 ? $size : $size."x".$size;
                        $icones[] = [
                            "src"=>"https://".$this->dominio."/conteudo/icones/".$dado["aplicativo_id"]."/ico-".$size.".png",
                            "sizes"=> $nome
                            ];
                    }
                    $manifesto["icons"] = $icones;
    
    


    return $manifesto;
    }
}

$manifesto = new GeraManifesto();
$render = $manifesto->render();
$manifesto->close();
echo json_encode($render, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

?>
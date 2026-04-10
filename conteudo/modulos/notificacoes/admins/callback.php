<?
function call_salvo($retorno, $obj){
    if(isset($retorno["sucesso"]) && $obj->tipo !== "editar"){
        
     
        include_once __DIR__."/../../../../admin/notificacao.php";
        
        
        if($obj->banco == "notificacoes"){
            
            $id = $retorno["id"];
            
            $seleciona = "SELECT * FROM notificacoes WHERE notificacao_hash='$id'";
            $resultado = $obj->conn->query($seleciona);
            
            $dado = $resultado->fetch_assoc();
             
            $destinatario = $dado["notificacao_destinatario"];
            $body = $dado["notificacao_body"];
            $cabecalho = $dado["notificacao_cabecalho"];
            $id = $dado["notificacao_id"];
            $link = $dado["notificacao_link"] ?? false;
             
            $id = $obj->conn->real_escape_string($id); 
            $metas = "SELECT * FROM notificacoes_meta WHERE nm_notificacao='$id'";
            $resultados = $obj->conn->query($metas);
             
            $valores = [];
            if ($resultados) {
            if ($resultados->num_rows > 0) {
               while ($dado = $resultados->fetch_assoc()) {
                   $valores[$dado["nm_chave"]] = $dado["nm_valor"];
               }
            }
            $resultados->free(); 
                
            } else {
              echo "Erro na consulta: " . $obj->conn->error;
            }

        
        
            $notificacao = new Notificacao(intval($destinatario));
            $notificacao->mensagem(["header"=>$cabecalho , "body"=>$body, "link"=>$link]);
       
       
   

       
            if(isset($valores["inapp"]) && $valores["inapp"]){
              //  $app = $notificacao->inapp();
            }
       
            if(isset($valores["push"]) && $valores["push"]){
                $push = $notificacao->push();
            }
       
            if(isset($valores["whatsapp"]) && $valores["whatsapp"]){
                $whatsapp = $notificacao->whatsApp();
            }
       
            if(isset($valores["email"]) && $valores["email"]){
                $email = $notificacao->email();
            }
       
            if(isset($valores["sms"]) && $valores["sms"]){
                $sms = $notificacao->sms();
            }
        }
       
        //print_r($this->data);
    
    }
    return $retorno;
}


?>
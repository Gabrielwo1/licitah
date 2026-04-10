<?

     error_reporting(E_ALL);
 ini_set("display_errors", 1);
    class APiDeDados{
        function __construct(){
            
            $i = 1;
            
            // $orgaosPublicos = [];
            // while(true){
            //     $url = "https://api.portaldatransparencia.gov.br/api-de-dados/orgaos-siafi?pagina=".$i;

            //     $client = curl_init($url);
            
            
            //     $headers = ['chave-api-dados: abcfcf47d60c70a511d7956074378969'];
            
            
            //     curl_setopt($client, CURLOPT_HTTPHEADER, $headers);
            
            //     curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
            
            //     $response = curl_exec($client);
            
      
                
            //     $orgaos = json_decode($response, TRUE);
            //     if(isset($orgaos[0]['codigo'])){
            //         foreach ($orgaos as &$orgao) {
            //             array_push($orgaosPublicos, $orgao['codigo']);
            //         }
                    
            //         $i++;
            //     }
            //     else{
            //         break;
            //     }
                
            
            
            //     curl_close($client);
            // }
            
            // $i = 1;
            // while(true){
            //     $url = "https://api.portaldatransparencia.gov.br/api-de-dados/orgaos-siape?pagina=".$i;

            //     $client = curl_init($url);
            
            
            //     $headers = ['chave-api-dados: abcfcf47d60c70a511d7956074378969'];
            
            
            //     curl_setopt($client, CURLOPT_HTTPHEADER, $headers);
            
            //     curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
            
            //     $response = curl_exec($client);
            
      
                
            //     $orgaos = json_decode($response, TRUE);
            //     if(isset($orgaos[0]['codigo'])){
            //         foreach ($orgaos as &$orgao) {
            //             array_push($orgaosPublicos, $orgao['codigo']);
            //         }
                    
            //         $i++;
            //     }
            //     else{
            //         break;
            //     }
                
            
            
            //     curl_close($client);
            // }
            
            
         
            // print_r($orgaosPublicos);
            
            
              $i = 1;
            while(true){
                $url = "https://api.portaldatransparencia.gov.br/api-de-dados/licitacoes/ugs?pagina=".$i;

                $client = curl_init($url);
            
            
                $headers = ['chave-api-dados: abcfcf47d60c70a511d7956074378969'];
            
            
                curl_setopt($client, CURLOPT_HTTPHEADER, $headers);
            
                curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
            
                $response = curl_exec($client);
            
      
                
                $orgaos = json_decode($response, TRUE);
                if(isset($orgaos[0]['codigo'])){
                    foreach ($orgaos as &$orgao) {
                        echo '<pre>';
                        print_r($orgao);
                        echo '<pre>';
                        
                    }
                    
                    $i++;
                }
                else{
                    break;
                }
                
            
            
                curl_close($client);
            }
            

        }
        
    }
    
    
    
    new ApiDeDados();

?>
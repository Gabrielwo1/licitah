 <?
    $capcha = ver(["seguranca", "recapcha", "recapcha"]);
    ?>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acesso Negado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?
    if($capcha){
        echo '<script src="https://www.google.com/recaptcha/api.js"></script>';
    }
    ?>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        html, body{
            height: 100%;
        }
    </style>
    
   
    
  </head>
  <body>
    <div class="h-100 d-flex justify-content-center align-items-center">
        <div style="max-width:600px" class="text-center">
            <h2 style="font-size: 30px">ACESSO NEGADO</h2>
            
            
            <?
            if($capcha){
                $front = v(["seguranca", "recapcha", "chave_site"], false);
                
                echo '<p>O seu IP foi marcado como inseguro e os seus acessos ao site foram bloqueados. Caso você seja um usuário real, clique no botão abaixo!</p>';
                if($front){
                echo '<button id="verificador" class="g-recaptcha btn btn-primary" style="font-size:16; font-weight: 500"
                    data-sitekey="'.$front.'" 
                    data-callback="onSubmit" 
                    data-action="submit"><i class="bi bi-robot"></i> NÃO SOU ROBÔ</button>';
                }
                }else{
                    echo '<p>O seu IP foi marcado como inseguro e os seus acessos ao site foram bloqueados.</p>';
                }
                
               
            
            ?>
          
        </div>
        
        
    </div>
    
    <script>
    if(document.getElementById("verificador")){
        document.getElementById("verificador").addEventListener("click", function(){
        document.getElementById("verificador").innerHTML = `
            <div class="d-flex justify-content-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>`
        })
    }
          function onSubmit(token) {
            console.log("subimetendo")
              var data = new FormData();
              data.append("g-recaptcha-response", token)
              const xhttp = new XMLHttpRequest();
              xhttp.onload = function() {
                  var obj = JSON.parse(this.responseText)
                  if(obj.seguro){
                      window.location.reload();
                  }else{
                      alert("Acesso Não Autorizado");
                      document.getElementById("verificador").remove();
                  }
              }
              xhttp.open("POST", `/admin/recapcha.php`);
              xhttp.send(data);
   }
    </script>

  </body>
</html>
<style>

</style>


<?
$dominio = "https://".DOMINIO;
$bg = v(["manutencao","modo-manutencao","mode"], "light") == "light" ? "bg-light" : "bg-dark";
$color = v(["manutencao","modo-manutencao","mode"], "light") == "light" ? "text-dark" : "text-light";

$data = "";
$horario = "";
if(v(["manutencao","modo-manutencao","cronometro"], false) && v(["manutencao","modo-manutencao","data"], false)){
    $data = v(["manutencao","modo-manutencao","data"], false);
    
    if(v(["manutencao","modo-manutencao","horario"], false)){
        $horario = v(["manutencao","modo-manutencao","horario"], false);
    }
}



?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$nome?> | Site em Manutenção</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="<?=$dominio?>/assets/aplicativo/flipdown/flipdown.css" rel="stylesheet">
  </head>
  <body>
    <div class="d-flex justify-content-center align-items-center h-100 <?=$bg?> <?=$color?>" id="paginaManutencao">
    <div>
        <div class="text-center" style="max-width: 600px">
              <?

        if(v(["geral","logotipo","logomarca"], false) && v(["geral","logotipo","logomarca"], false) != '[]'){
            $img = "/conteudo/uploads/".json_decode(v(["geral","logotipo","logomarca"]), true)[0];
            echo '<div class="text-center mb-3"><img src="'.$img.'" style="max-width: 200px"></div>';
        }
        
        ?>
            <h2 class="text-center text-uppercase" style="font-weight: 500; font-size: 20px">
                <?=v(["manutencao","modo-manutencao","cabecalho"], "Site em Manutenção");?>
            </h2>
            <p class="w-75 m-auto my-3" style="font-size: 14px"><?=v(["manutencao","modo-manutencao","descricao"], "Site em Manutenção");?></p>
            
            <?
            if(v(["manutencao","modo-manutencao","cronometro"], false)){
                ?>
                  <div class="d-flex justify-content-center">
                      <div>
                          <div id="flipdown" class="flipdown" data-data="<?=$data?>" data-horario="<?=$horario?>"></div>
                      </div>
                  </div>

                <?
            }

            ?>
            
            
            <?
            if(v(["manutencao","modo-manutencao","whatsaApp"], false) && v(["manutencao","modo-manutencao","whatsaAppNumero"], false)){
                $numero = v(["manutencao","modo-manutencao","whatsaAppNumero"], false);
                echo ' <div class="mt-3">
                <a href="https://api.whatsapp.com/send?phone='.$numero.'" class="btn text-light fw-700" style="background-color: #25d366"><i class="bi bi-whatsapp"></i> Fale Conosco pelo WhatsApp</a>
            </div>';   
            }
            
            ?>
            
           
        </div>
    </div>
</div>

<script src="<?=$dominio?>/assets/aplicativo/flipdown/flipdown.js"></script>
      </body>
</html>

<script>
    document.addEventListener('DOMContentLoaded', () => {

    var div = document.getElementById("flipdown")
    var data = div.dataset.data
    var horario = div.dataset.horario;

  if(data){
      if(horario){
          data = `${data} ${horario}`
      }
      console.log()
      var twoDaysFromNow = new Date(data).getTime() / 1000;

  // Set up FlipDown
  var flipdown = new FlipDown(twoDaysFromNow , {
  headings: ["Dias", "Horas", "Minutos", "Segundos"],
})

    // Start the countdown
    .start()

    // Do something when the countdown ends
    .ifEnded(() => {
      console.log('The countdown has ended!');
    }); 
  }
 



  
  
});

</script>
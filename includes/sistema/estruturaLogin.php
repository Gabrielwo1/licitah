<?
                switch($this->caminho[0]){
    case 'acesso':
        if(isset($this->caminho[1]) && isset($this->caminho[2])){
            include __DIR__."/acesso/senha.php";
        }else{
            include __DIR__."/acesso/login.php";
        }
        break;
    }
    
        // include __DIR__."/acesso/cadastro.php";
    // include __DIR__."/acesso/criar-senha.php";

    //include __DIR__."/acesso/dois-fatores.php";
    // include __DIR__."/acesso/recuperar.php";
                
                ?>


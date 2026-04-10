<?php

header('Content-Type: application/json; charset=utf-8');

error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR);
ini_set("display_errors", 1);


class controleCNPJ {
    private $conn;
    
    function __construct() {
        $this->conn = conn();
    }
    
    private function verificacarExistencia($quantidade = false){
        if(!$quantidade){
            return ['sucesso'=> true, 'mensagem'=> 'Usuário permitido a criar quantidade ilimitada de CNPJS'];
        }

        
        $sql = "SELECT * FROM empresas_associacao WHERE ea_usuario = '{$_SESSION['id']}'";
        
        $resultado = $this->conn->query($sql);

        if($resultado->num_rows >= intval($quantidade)){
            return ['erro'=> true, 'mensagem'=> 'Limite de cnpjs atingido', 'quantidade' => true];
        }
        
        return ['sucesso'=> true, 'mensagem' => 'Limite de cnpjs não atingido'];
    }
    
    public function iniciar(){

        switch($_SESSION['funcao']){
            case 2:
                $resposta = $this->verificacarExistencia(1);
                break;
            case 18:
                $resposta = $this->verificacarExistencia(2);
                break;
            case 19:
                $resposta = $this->verificacarExistencia();
                break;
        }
        
        
        return $resposta;
    }

}

$acao = new controleCNPJ();

$existencia = $acao->iniciar();
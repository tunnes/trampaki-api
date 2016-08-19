<?php
#   TESTE INSERT    
    require_once('class/user.php');
    
    Class Primeira{
        public  function __construct(){
            echo 'Bem vindo a primeira pagina!';
            
            $usuarioTeste = new User();
            $usuarioTeste->setNome('Felipes');
            if($usuarioTeste->insert()){
                echo 'Cadastrado com exito!';
            };
            
        } 
    }
?>
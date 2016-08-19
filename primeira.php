<?php
#   TESTE INSERT    
    require_once('class/userRegistration.php');
    
    Class Primeira{
        public  function __construct(){
            echo 'Bem vindo a primeira pagina!';
            
            $usuarioTeste = new UserRegistration();
            $usuarioTeste->setNome('Ayrton');
            if($usuarioTeste->insert()){
                echo 'Cadastrado com exito!';
            };
            
        } 
    }
?>
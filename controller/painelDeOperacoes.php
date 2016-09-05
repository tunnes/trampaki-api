<?php
    session_start();
    class PainelDeOperacoes{
        public function __construct(){
        #   Abaixo verifico se o usuário esta logado, tendo como base no valor boleano 
        #   do campo 'logado' da 'SESSION' gerada, apartir de um possivel login ou cadastro.
            $_SESSION['logado'] ? $this->logado() : $this->deslogado();
            if(isset($_GET['logout'])){
                if($_GET['logout'] == 'ok'){
                    Login::deslogar();
                }
            }
            
        }
        
        public function logado(){
            $json = "usuario = {nome:".$_SESSION['nome']."}";
            return $json
        }
        public function deslogado(){
            echo "Voce nao esta logado.";
        }
    }
?>
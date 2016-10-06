<?php
    session_start();
    require_once('configuration/dataBase.php');
    require_once('model/BPO/login.php');
    
    class PaginaAutenticacao{
        
        public function __construct(){
            if($_POST["enviar"] == "true"){
                
            #   Passo 01 ----------------------------------------------------------------------------------------
            #   A função PHP 'filter_input()' tem como finalidade obter a variavel especifica do formulario.
            #   O 'FILTER_SANITIZE_MAGIC_QUOTES' retorna uma barra invertida na frente das aspas simples, neste
            #   passo estou recuperando os dados inseridos no formulario de login e verificando se são validos:
        
                $dadosLogin  = filter_input(INPUT_POST, "login", FILTER_SANITIZE_MAGIC_QUOTES);
                $dadosSenha  = filter_input(INPUT_POST, "senha", FILTER_SANITIZE_MAGIC_QUOTES);
                
                $loginDAO = LoginDAO::getInstance();
                $loginDAO->gerarAutenticacao($dadosLogin, $dadosSenha) ? header("Location: painel-de-operacoes") : print "login ou email invalido.";
                
            #   --------------------------------------------------------------------------------------------------
            }else{
                include('view/pagina-autenticacao.html');
            }
            
        }
    }
?>

<?php
    session_start();
    require_once('class/dataBase.php');
    require_once('class/login.php');
    
    class FormLogin{
        public function __construct(){
            if(isset($_POST['enviar'])){
            
            #   A função PHP 'filter_input()' tem como finalidade obter a variavel especifica do formulario.
            #   O 'FILTER_SANITIZE_MAGIC_QUOTES' retorna uma barra invertida na frente das aspas simples.
                echo '<hr>';
                echo 'Dados de acesso:';
                echo '<br>';
                echo $login = filter_input(INPUT_POST, "login", FILTER_SANITIZE_MAGIC_QUOTES);
                echo '<br>';
                echo $senha = filter_input(INPUT_POST, "senha", FILTER_SANITIZE_MAGIC_QUOTES);
                echo '<hr>';
                
                $logadaNoAr = new Login;
                $logadaNoAr->setLogin($login);
                $logadaNoAr->setSenha($senha);
                
                if ($logadaNoAr->logar()){
                #   A função 'header("Location")' redireciona o usuário pra o paramêtro fornecido 
                    header("Location: logado.php");
                }else{
                    echo 'Erro ao logar!';        
                }
            }
            include('view/testeLogin.html');
        }
    }
?>

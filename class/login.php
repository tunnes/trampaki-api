<?php
    require_once 'dataBase.php';
    
    class Login extends dataBase{
        protected $login;
        protected $senha;
        protected $email;
        
        public function __construct($login, $senha){
            $this->login = $login;
            $this->senha = $senha;
        }
        public function efetuarLogin(){
            
            $querySQL = "SELECT * FROM USUARIO WHERE cd_login = :cd_login AND cd_senha = :cd_senha";
            $comandoSQL= dataBase::prepare($querySQL);
            $comandoSQL->bindParam(':cd_login', $this->login, PDO::PARAM_STR);
            $comandoSQL->bindParam(':cd_senha', $this->senha, PDO::PARAM_STR);
            $comandoSQL->execute();
            
            if($comandoSQL->rowCount() == 1){
                
            #   A função 'fetch(PDO::FETCH_OBJ)' retorna um objeto com todos os registros resgatados
            #   do banco de dados.
                $usuario = $comandoSQL->fetch(PDO::FETCH_OBJ);
                
            #   Para controle de acesso a paginas e restrição de acesso
            #   foi feito o uso de variaveis de sessão '$_SESSION[]' uma variavel global
            #   que é invocada para controle de sessões.
                $_SESSION['cd_usuario']  = $usuario->cd_usuario;
                $_SESSION['nm_email']  = $usuario->nm_email;
                $_SESSION['cd_senha']  = $usuario->cd_senha;
                $_SESSION['cd_login']  = $usuario->cd_login;
                $_SESSION['nm_usuario']  = $usuario->nm_usuario;
                $_SESSION['cd_endereco'] = $usuario->cd_endereco;
                $_SESSION['cd_telefone']  = $usuario->cd_telefone;
                $_SESSION['nivelDeAcesso'] = "1";
                $_SESSION['logado'] = true;
                return true;
                
            }else{
                return false;
            }
        }
        public static function efetuarDeslog(){
            if(isset($_GET['logout'])){
                unset($_SESSION['logado']);
                session_destroy();
                header("Location: login");
            }
        }
    }
?> 
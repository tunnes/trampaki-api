<?php
    class Login{
        public $login;
        public $senha;
        
        public function __construct($login, $senha){
            $this->login = $login;
            $this->senha = $senha;
        }
        public function getSenha(){
            return $this->senha;
        }
        public function getLogin(){
            return $this->login;
        }
        public function efetuarLogin(){
            $bancoDeDados = Database::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM usuario WHERE cd_login = :cd_login AND cd_senha = :cd_senha");
            $comandoSQL->bindParam(':cd_login', $this->login, PDO::PARAM_STR);
            $comandoSQL->bindParam(':cd_senha', $this->senha, PDO::PARAM_STR);
            $comandoSQL->execute();
            return $comandoSQL->rowCount() == 1 ? $this->iniciarSessao($comandoSQL) : false;
        }
        public function efetuarDeslog(){
            if(isset($_GET['logout'])){
                unset($_SESSION['logado']);
                session_destroy();
                header("Location: login");
            }
        }
        private function iniciarSessao($comandoSQL){
            # A função 'fetch(PDO::FETCH_OBJ)' retorna um objeto com todos os registros
            # resgatados do banco de dados.
            $usuario = $comandoSQL->fetch(PDO::FETCH_OBJ);
            echo "Hum";        
            # Para controle de acesso a paginas e restrição de acesso foi feito o uso de
            # variaveis de sessão '$_SESSION[]' uma variavel global que é invocada.
            $_SESSION['cd_usuario']  = $usuario->cd_usuario;
            $_SESSION['logado'] = true;
            return true;
        }
    }
?> 
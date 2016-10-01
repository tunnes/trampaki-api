<?php
    class Login{
        private $login;
        private $senha;
        
        public function __construct($login, $senha){
            $this->login = $login;
            $this->senha = $senha;
        }
        public function getLogin(){
            return $this->login;
        }
        public function getSenha(){
            return $this->senha;
        }
        public function efetuarLogin(){
            $bancoDeDados = Database::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT usuario.*, login.* FROM login  
                                                        INNER JOIN usuario ON usuario.cd_login = login.cd_login
                                                        WHERE login.ds_login = :ds_login AND login.ds_senha = :ds_senha");
            $comandoSQL->bindParam(':ds_login', $this->login, PDO::PARAM_STR);
            $comandoSQL->bindParam(':ds_senha', $this->senha, PDO::PARAM_STR);
            $comandoSQL->execute();
            if($comandoSQL->rowCount() == 1){
                $comandoSQL->fetchAll(PDO::FETCH_OBJ);
                $codigoUsuario = $comandoSQL->cd_usuario;
                return $this->iniciarSessao($codigoUsuario);
            }else{
                return false;
            }
        }
        public function efetuarDeslog(){
            if(isset($_GET['logout'])){
                unset($_SESSION['logado']);
                session_destroy();
                header("Location: login");
            }
        }
        public function iniciarSessao($codigoUsuario){
            # Para controle de acesso a paginas e restrição de acesso foi feito o uso de
            # variaveis de sessão '$_SESSION[]' uma variavel global que é invocada
            
            $_SESSION['codigoUsuario']  = $codigoUsuario;
            $_SESSION['logado'] = true;
            return true;
        }
    }
?> 
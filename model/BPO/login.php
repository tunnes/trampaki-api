<?php
    class LoginBPO{
        private $codigoLogin;
        private $login;
        private $senha;
        
        public function __construct($codigoLogin, $login, $senha){
            $this->codigoLogin = $codigoLogin;
            $this->login = $login;
            $this->senha = $senha;
        }
        public function getCodigoLogin(){
            return $this->codigoLogin;
        }
        public function getLogin(){
            return $this->login;
        }
        public function getSenha(){
            return $this->senha;
        }
        public function efetuarDeslog(){
            if(isset($_GET['logout'])){
                unset($_SESSION['logado']);
                session_destroy();
                header("Location: login");
            }
        }
        public function iniciarSessao($objetoUsuario, $tipoUsuario){
            # Para controle de acesso a paginas e restrição de acesso foi feito o uso de
            # variaveis de sessão '$_SESSION[]' uma variavel global que é invocada
            
            $_SESSION['logado'] = true;
            $_SESSION['tipoUsuario'] = $tipoUsuario;
            $_SESSION['objetoUsuario'] = $objetoUsuario;
            
            
            return true;
        }
    }
?> 
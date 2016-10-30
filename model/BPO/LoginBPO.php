<?php
    class LoginBPO implements JsonSerializable{
        private $codigoLogin, $login, $senha, $token;
        
        public function __construct($codigoLogin, $login, $senha, $token){
            $this->codigoLogin = $codigoLogin;
            $this->login = $login;
            $this->senha = $senha;
            $this->token = $token;
            
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
        public function getToken(){
            return $this->token;
        }
        public function jsonSerialize(){
            return get_object_vars($this);
        }  
    }
?> 
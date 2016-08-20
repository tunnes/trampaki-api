<?php
    require_once 'dataBase.php';
    
    class Login extends dataBase{
        
        protected $table = 'usuario';
        private $login;
        private $senha;
        
        public function setLogin($login){
            $this->login = $login;
        }
        public function getLogin(){
            return $this->login;
        }
        public function setSenha($senha){
            $this->senha = $senha;
        }
        public function getSenha(){
            return $this->senha;
        }
        public function logar(){
            $querySQL = "SELECT * FROM $this->table WHERE cd_senha == :cd_senha AND cd_login == :cd_login)";
            $stmt =  dataBase::prepare($querySQL);
            $stmt->bindParam(':cd_login', $this->login, PDO::PARAM_STR);
            $stmt->bindParam(':cd_senha', $this->login, PDO::PARAM_STR);
            $stmt->execute();
            if($logar->rowCount() == 1){
                return true;
            }else{
                return false;
            }
        }
    }
?> 
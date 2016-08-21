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
            $querySQL = "SELECT * FROM `usuario` WHERE cd_login = :cd_login AND cd_senha = :cd_senha";;
            
            $stmt =  dataBase::prepare($querySQL);
            $stmt->bindParam(':cd_login', $this->login, PDO::PARAM_STR);
            $stmt->bindParam(':cd_senha', $this->senha, PDO::PARAM_STR);
            $stmt->execute();
            if($stmt->rowCount() == 1){
            #   Para controle de acesso a paginas e restrição de acesso
            #   foi feito o uso de variaveis de sessão '$_SESSION[]' um tipo de variavel global
            #   que é invocada para controle de sessões.
                $dados = $stmt->fetch(PDO::FETCH_OBJ);
                $_SESSION['nome'] = $dados->nm_usuario;
                $_SESSION['logado'] = true;
                return true;
            }else{
                return false;
            }
        }
        public static function deslogar(){
            if(isset($_GET['logout'])){
                unset($_SESSION['logado']);
                session_destroy();
                header("Location: login");
            }
        }
    }
?> 
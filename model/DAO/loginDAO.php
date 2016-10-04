<?php
    require_once('configuration/dataBase.php');
    require_once('model/BPO/login.php');
    
    class LoginDAO{
        private static $instance;
        public static function getInstance(){
            return !isset(self::$instance) ? self::$instance = new loginDAO() : self::$instance;
            
        }
        public function cadastrarLogin($login, $senha){
            $dataBase = DataBase::getInstance();
            $querySQL = "INSERT INTO login (ds_login, ds_senha) VALUES (:ds_login, :ds_senha)";
            $comandoSQL =  $dataBase->prepare($querySQL);
            $comandoSQL -> bindParam(':ds_login', $login);
            $comandoSQL -> bindParam(':ds_senha', $senha);
            $comandoSQL->execute();
            $loginBPO = new LoginBPO($dataBase->lastInsertId(), $login, $senha); 
            return $loginBPO; 
        }
        public function consultarLogin(Login $objetoLogin){
            $bancoDeDados = Database::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT usuario.*, login.* FROM login  
                                                        INNER JOIN usuario ON usuario.cd_login = login.cd_login
                                                        WHERE login.ds_login = :ds_login AND login.ds_senha = :ds_senha");
            $comandoSQL->bindParam(':ds_login', $objetoLogin->getLogin(), PDO::PARAM_STR);
            $comandoSQL->bindParam(':ds_senha', $objetoLogin->getLogin(), PDO::PARAM_STR);
            $comandoSQL->execute();
            
            if($comandoSQL->rowCount() == 1){
                $comandoSQL->fetchAll(PDO::FETCH_OBJ);
                $codigoUsuario = $comandoSQL->cd_usuario;
                $tipoUsuario   = $comandoSQL->cd_tipo;
                return $objetoLogin->iniciarSessao($codigoUsuario, $tipoUsuario);
            }else{
                return false;
            }
        }
    }
?>
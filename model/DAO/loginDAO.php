<?php
    require_once('configuration/dataBase.php');
    
    class loginDAO{
        private static $instance;
        public static function getInstance(){
            return !isset(self::$instance) ? self::$instance = new loginDAO() : self::$instance;
            
        }
        public function cadastrarLogin($objetoLogin){
            $dataBase = DataBase::getInstance();
            $querySQL = "INSERT INTO login (ds_login, ds_senha) VALUES (:ds_login, :ds_senha)";
            $comandoSQL =  $dataBase->prepare($querySQL);
            $comandoSQL -> bindParam(':ds_login', $objetoLogin->getLogin());
            $comandoSQL -> bindParam(':ds_senha', $objetoLogin->getSenha());
            $comandoSQL->execute();
            return $dataBase->lastInsertId();
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
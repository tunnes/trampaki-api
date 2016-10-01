<?php
    require_once('configuration/dataBase.php');
    class loginDAO{
        private static $instance;
        public static function getInstance(){
            return !isset(self::$instance) ? self::$instance = new loginDAO() : self::$instance;
            
        }
        public function novoLogin($objetoLogin){
            $dataBase = DataBase::getInstance();
            $querySQL = "INSERT INTO login (ds_login, ds_senha) VALUES (:ds_login, :ds_senha)";
            $comandoSQL =  $dataBase->prepare($querySQL);
            $comandoSQL -> bindParam(':ds_login', $objetoLogin->getLogin());
            $comandoSQL -> bindParam(':ds_senha', $objetoLogin->getSenha());
            $comandoSQL->execute();
            return $dataBase->lastInsertId();
        }
    }
?>
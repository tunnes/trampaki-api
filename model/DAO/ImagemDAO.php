<?php
    require_once 'configuration/autoload-geral.php';
    
    class ImagemDAO{
        private static $instance;
        public static function getInstance(){
            return !isset(self::$instance) ? self::$instance = new ImagemDAO() : self::$instance;
        }
        public function cadastrar($imagem){
            $DB = DataBase::getInstance();
            $comandoSQL =  $DB->prepare('INSERT INTO imagem (ds_imagem) VALUES (:ds_imagem)');
        	$comandoSQL->bindParam(':ds_imagem', $imagem, PDO::PARAM_LOB);
            $comandoSQL->execute();
            return  $DB->lastInsertId();
        }
        public function consultar($cd_imagem){
            $dataBase = DataBase::getInstance();
            $querySQL = "SELECT * FROM imagem WHERE cd_imagem = :cd_imagem";
            $comandoSQL =  $dataBase->prepare($querySQL);
        	$comandoSQL -> bindParam(':cd_imagem', $cd_imagem);
            $comandoSQL->execute();
            return $comandoSQL->fetch(PDO::FETCH_OBJ)->ds_imagem;
        }
    }
?>
<?php
    require_once('configuration/dataBase.php');
    class enderecoDAO{
        private static $instance;
        public static function getInstance(){
        #   Existe uma instância feita..
            return !isset(self::$instance) ? self::$instance = new enderecoDAO() : self::$instance;
        }
        public function cadastrarEndereco($objetoEndereco){
            $bancoDeDados = Database::getInstance();
            $querySQL = "INSERT INTO endereco (cd_numeroResidencia, cd_cep, nm_cidade, sg_estado, cd_longitude, cd_latitude) 
                         VALUES (:cd_numResiden, :cd_cep, :nm_cidade, :sg_estado, :cd_lon, :cd_lat)";
            $comandoSQL =  $bancoDeDados->prepare($querySQL);
            $comandoSQL -> bindParam(':cd_numResiden', $objetoEndereco->getNumeroResidencia());
            $comandoSQL -> bindParam(':cd_cep', $objetoEndereco->getCEP());
            $comandoSQL -> bindParam(':nm_cidade', $objetoEndereco->getCidade());
            $comandoSQL -> bindParam(':sg_estado', $objetoEndereco->getEstado());
            $comandoSQL -> bindParam(':cd_lon', $objetoEndereco->getLongitude());
            $comandoSQL -> bindParam(':cd_lat', $objetoEndereco->getLatitude());
            $comandoSQL->execute();
            return $bancoDeDados->lastInsertId();
        }
    }
?>
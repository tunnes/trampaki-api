<?php
    require_once('configuration/dataBase.php');
    require_once('model/BPO/enderecoBPO.php');
    
    class EnderecoDAO{
        private static $instance;
        public static function getInstance(){
        #   Existe uma instância feita..
            return !isset(self::$instance) ? self::$instance = new enderecoDAO() : self::$instance;
        }
        public function cadastrarEndereco(EnderecoBPO $enderecoBPO){
            $bancoDeDados = Database::getInstance();
            $querySQL = "INSERT INTO endereco (cd_numeroResidencia, cd_cep, nm_cidade, sg_estado, cd_longitude, cd_latitude) 
                         VALUES (:cd_numResiden, :cd_cep, :nm_cidade, :sg_estado, :cd_lon, :cd_lat)";
            $comandoSQL =  $bancoDeDados->prepare($querySQL);
            $comandoSQL -> bindParam(':cd_numResiden',  $enderecoBPO->getNumeroResidencia());
            $comandoSQL -> bindParam(':cd_cep',         $enderecoBPO->getCEP());
            $comandoSQL -> bindParam(':nm_cidade',      $enderecoBPO->getCidade());
            $comandoSQL -> bindParam(':sg_estado',      $enderecoBPO->getEstado());
            $comandoSQL -> bindParam(':cd_lon',         $enderecoBPO->getLongitude());
            $comandoSQL -> bindParam(':cd_lat',         $enderecoBPO->getLatitude());
            $comandoSQL->execute();
            $enderecoBPO = new EnderecoBPO(
                $bancoDeDados->lastInsertId(), 
                $enderecoBPO->getEstado(), 
                $enderecoBPO->getCidade(), 
                $enderecoBPO->getCEP(), 
                $enderecoBPO->getNumeroResidencia(), 
                $enderecoBPO->getLongitude(), 
                $enderecoBPO->getLatitude()
            );
            return $enderecoBPO;
        }
        public function consultarEndereco($codigoEndereco){
            $dataBase = DataBase::getInstance();
            $querySQL = "SELECT * FROM endereco WHERE (cd_endereco = :cd_endereco)";
            $comandoSQL =  $dataBase->prepare($querySQL);
            $comandoSQL -> bindParam(':cd_endereco', $codigoEndereco);
            $comandoSQL->execute();
            $co = $comandoSQL->fetch(PDO::FETCH_OBJ);
            return new EnderecoBPO($co->cd_endereco, $co->nm_estado, $co->nm_cidade, $co->cd_CEP, $co->ds_rumRes, $co->cd_long, $co->cd_lati);   
        }
    }
?>
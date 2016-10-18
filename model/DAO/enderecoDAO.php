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
        public function editarEndereco(EnderecoBPO $enderecoBPO){
            $bancoDeDados = Database::getInstance();
            $querySQL = "UPDATE endereco 
                SET cd_numeroResidencia = :cd_numResiden, cd_cep = :cd_cep, nm_cidade = :nm_cidade, sg_estado = :sg_estado, cd_longitude = :cd_lon, cd_latitude = :cd_lat 
                WHERE cd_endereco = :cd_endereco";
            $comandoSQL =  $bancoDeDados->prepare($querySQL);
            $comandoSQL -> bindParam(':cd_endereco',    $enderecoBPO->getCodigoEndereco());
            $comandoSQL -> bindParam(':cd_numResiden',  $enderecoBPO->getNumeroResidencia());
            $comandoSQL -> bindParam(':cd_cep',         $enderecoBPO->getCEP());
            $comandoSQL -> bindParam(':nm_cidade',      $enderecoBPO->getCidade());
            $comandoSQL -> bindParam(':sg_estado',      $enderecoBPO->getEstado());
            $comandoSQL -> bindParam(':cd_lon',         $enderecoBPO->getLongitude());
            $comandoSQL -> bindParam(':cd_lat',         $enderecoBPO->getLatitude());
            $comandoSQL->execute();
        }
        public function consultarEndereco($codigoEndereco){
            $dataBase = DataBase::getInstance();
            $querySQL = "SELECT * FROM endereco WHERE (cd_endereco = :cd_endereco)";
            $comandoSQL =  $dataBase->prepare($querySQL);
            $comandoSQL -> bindParam(':cd_endereco', $codigoEndereco);
            $comandoSQL->execute();
            $consulta = $comandoSQL->fetch(PDO::FETCH_OBJ);
            return new EnderecoBPO(
                $consulta->cd_endereco, 
                $consulta->sg_estado, 
                $consulta->nm_cidade,
                $consulta->cd_cep,
                $consulta->cd_numeroResidencia, 
                $consulta->cd_longitude, 
                $consulta->cd_latitude
            );   
        }
    }
?>
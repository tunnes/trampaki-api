<?php
#   Classe 'Endereco':

#   Na classe 'Endereco' estão contidas informações relativas ao endereço as instancias de usuários
#   conceitualmente o endereço possui relação de composição a classe 'Usuario' pois para a existêcia
#   logica de um usuário no sistema é imprescindível que o mesmo possua um endereço contendo suas 
#   respectivas cordenadas.

    require_once 'configuration/dataBase.php';
    
    class EnderecoBPO{
        private $codigoEndereco;
        private $estado;         
        private $cidade;
        private $CEP;
        private $numeroResidencia;
        private $longitude;
        private $latitude;
        
        public function  __construct($codigoEndereco, $estado, $cidade, $CEP, $numeroResidencia, $longitude, $latitude){
            $this->codigoEndereco = $codigoEndereco;
            $this->estado = $estado;
            $this->cidade = $cidade;
            $this->CEP = $CEP;
            $this->numeroResidencia = $numeroResidencia;
            $this->longitude = $longitude;
            $this->latitude = $latitude;
        }
       
        public function  verEndereco($codigoEndereco){
            $querySQL = "SELECT * FROM ENDERECO WHERE cd_endereco = :cd_endereco";
            $comandoSQL = dataBase::prepare($querySQL);
            $comandoSQL->bindParam(':cd_endereco',$codigoEndereco);
            $comandoSQL->execute();
            return $comandoSQL->fetchAll();
        }
        public function  editarEndereco($codigoEndereco){
            $querySQL = "UPDATE ENDERECO SET (cd_numeroResidencia = :cd_numeroResidencia, cd_cep = :cd_cep, nm_cidade = :nm_cidade, sg_estado = :sg_estado, cd_logitude = :cd_logitude, cd_latitude = :cd_latitude) 
                         WHERE cd_endereco = :cd_endereco";
            $comandoSQL = dataBase::prepare($querySQL);
            $comandoSQL->bindParam(':cd_numeroResidencia', $this->numeroResidencia);
            $comandoSQL->bindParam(':cd_logitude', $this->longitude);
            $comandoSQL->bindParam(':cd_latitude', $this->latitude);
            $comandoSQL->bindParam(':nm_cidade', $this->cidade);
            $comandoSQL->bindParam(':sg_estado', $this->estado);
            $comandoSQL->bindParam(':cd_cep', $this->CEP);
            $comandoSQL->bindParam(':cd_endereco', $codigoEndereco);
            return $comandoSQL->execute();
        }
        public function getCodigoEndereco(){
            return $this->codigoEndereco;
        }
        public function getEstado(){
            return $this->estado;
        }
        public function getCidade(){
            return $this->cidade;
        }
        public function getCEP(){
            return $this->CEP;
        }
        public function getNumeroResidencia(){
            return $this->numeroResidencia;
        }
        public function getLongitude(){
            return $this->longitude;
        }
        public function getLatitude(){
            return $this->latitude;
        }
        
    }
?>
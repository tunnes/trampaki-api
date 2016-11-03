<?php
#   Classe 'Endereco':

#   Na classe 'Endereco' estão contidas informações relativas ao endereço as instancias de usuários
#   conceitualmente o endereço possui relação de composição a classe 'Usuario' pois para a existêcia
#   logica de um usuário no sistema é imprescindível que o mesmo possua um endereço contendo suas 
#   respectivas cordenadas.
    
    class EnderecoBPO implements JsonSerializable{
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
        public function jsonSerialize() {
        #   SE EU TIVESSE QUE ME APAIXONAR POR ALGO
        #   SERIA POR ESSA FUNÇÃO E COMO AMANTE EU TERIA ESSA INTERFACE (JsonSerializable)
            return get_object_vars($this);
        }  
    }
?>
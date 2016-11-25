<?php                        
    class Conexao implements JsonSerealizable{
        private $codigoAnuncio;
        private $codigoPrestadorDeServico;
        
        public function __construct($codigoAnuncio, $codigoPrestadorDeServico){
            $this->codigoAnuncio;
            $this->codigoPrestadorDeServico;
        }
        public function jsonSerialize() {
        #   SE EU TIVESSE QUE ME APAIXONAR POR ALGO
        #   SERIA POR ESSA FUNÇÃO E COMO AMANTE EU TERIA ESSA INTERFACE (JsonSerializable)
            return get_object_vars($this);
        }   
    }

?>
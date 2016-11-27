<?php                        
    class ConexaoBPO implements JsonSerializable{
        private $cuu;
        private $ciu;
        private $nuu;
        
        public function __construct($cuu, $ciu, $nuu){
            $this->cuu = $cuu;
            $this->ciu = $ciu;
            $this->nuu = $nuu;
        }
        public function jsonSerialize() {
        #   SE EU TIVESSE QUE ME APAIXONAR POR ALGO
        #   SERIA POR ESSA FUNÇÃO E COMO AMANTE EU TERIA ESSA INTERFACE (JsonSerializable)
            return get_object_vars($this);
        }   
    }

?>
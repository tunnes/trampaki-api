<?php                        
    class ConexaoBPO implements JsonSerealizable{
        private $cuu;
        private $ciu;
        private $nuu;
        private $cud;
        private $cid;
        private $nud;
        
        public function __construct($cuu, $ciu, $nuu, $cud, $cid, $nud){
            $this->cuu = $cuu;
            $this->ciu = $ciu;
            $this->nuu = $nuu;
            $this->cud = $cud;
            $this->cid = $cid;
            $this->nud = $nud;
        }
        public function jsonSerialize() {
        #   SE EU TIVESSE QUE ME APAIXONAR POR ALGO
        #   SERIA POR ESSA FUNÇÃO E COMO AMANTE EU TERIA ESSA INTERFACE (JsonSerializable)
            return get_object_vars($this);
        }   
    }

?>
<?php
    class AnuncioBPO implements JsonSerializable{
        private $codigoAnuncio, $codigoAnunciante, $titulo, $descricao, $areaAlcance, $categorias, $codigoStatus;
        
        public function __construct($codigoAnuncio, $codigoAnunciante, $titulo, $descricao, $areaAlcance, $categorias = null, $codigoStatus){
            $this->codigoAnuncio    = $codigoAnuncio;
            $this->codigoAnunciante = $codigoAnunciante;
            $this->titulo           = $titulo;
            $this->descricao        = $descricao;
            $this->areaAlcance      = $areaAlcance;
            $this->categorias       = $categorias;
            $this->codigoStatus     = $codigoStatus;
        }
    #   Getters..
        public function getCodigoAnuncio(){
            return $this->codigoAnuncio;
        }
        public function getCodigoAnunciante(){
            return $this->codigoAnunciante;
        }
        public function getTitulo(){
            return $this->titulo;
        }
        public function getDescricao(){
            return $this->descricao;
        }
        public function getAreaAlcance(){
            return $this->areaAlcance;
        }
        public function getCategorias(){
            return $this->categorias;
        }
        public function getCodigoStatus(){
            return $this->codigoStatus;
        }
                
        public function jsonSerialize() {
        #   SE EU TIVESSE QUE ME APAIXONAR POR ALGO
        #   SERIA POR ESSA FUNÇÃO E COMO AMANTE EU TERIA ESSA INTERFACE (JsonSerializable)
            return get_object_vars($this);
        }   
    }

?>
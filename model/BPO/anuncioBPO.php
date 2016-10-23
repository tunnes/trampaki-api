<?php
    class AnuncioBPO{
        private $codigoAnuncio, $codigoAnunciante, $titulo, $descricao, $areaAlcance, $codigoCat01, $codigoCat02, $codigoCat03, $codigoStatus;
        
        
        public function __construct($codigoAnuncio, $codigoAnunciante, $titulo, $descricao, $areaAlcance, $codigoCat01, $codigoCat02, $codigoCat03, $codigoStatus){
            $this->codigoAnuncio    = $codigoAnuncio;
            $this->codigoAnunciante = $codigoAnunciante;
            $this->titulo           = $titulo;
            $this->descricao        = $descricao;
            $this->areaAlcance      = $areaAlcance;
            $this->codigoCat01      = $codigoCat01;
            $this->codigoCat02      = $codigoCat02;
            $this->codigoCat03      = $codigoCat03;
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
        public function getCodigoCat01(){
            return $this->codigoCat01;
        }
        public function getCodigoCat02(){
            return $this->codigoCat02;
        }        
        public function getCodigoCat03(){
            return $this->codigoCat03;
        }
        public function getCodigoStatus(){
            return $this->codigoStatus;
        }
    }

?>
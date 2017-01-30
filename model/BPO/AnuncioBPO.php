<?php
    class AnuncioBPO implements JsonSerializable{
        private $codigoAnuncio, $codigoAnunciante, $titulo, $descricao, $areaAlcance, $categorias, $codigoStatus, $cd_imagem_01, $cd_imagem_02, $cd_imagem_03;
        
        public function __construct($codigoAnuncio, $codigoAnunciante, $titulo, $descricao, $areaAlcance, $categorias = null, $codigoStatus, $cd_imagem_01, $cd_imagem_02, $cd_imagem_03){
            $this->codigoAnuncio    = $codigoAnuncio;
            $this->codigoAnunciante = $codigoAnunciante;
            $this->titulo           = $titulo;
            $this->descricao        = $descricao;
            $this->areaAlcance      = $areaAlcance;
            $this->categorias       = $categorias;
            $this->codigoStatus     = $codigoStatus;
            $this->cd_imagem_01     = $cd_imagem_01;
            $this->cd_imagem_02     = $cd_imagem_02;
            $this->cd_imagem_03     = $cd_imagem_03;
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
        
        public function getImagem01(){
            return $this->cd_imagem_01;
        }
        public function getImagem02(){
            return $this->cd_imagem_02;
        }
        public function getImagem03(){
            return $this->cd_imagem_03;
        }
        
                
        public function jsonSerialize() {
            return get_object_vars($this);
        }   
    }

?>
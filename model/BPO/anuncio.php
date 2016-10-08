<?php
    class Anuncio{
        private $codigoAnuncio, $codigoAnunciante, $titulo, $descricao, $areaAlcance, $codigoCat01, $codigoCat02, $codigoCat03;
        
        
        public function __construct($codigoAnuncio, $codigoAnunciante, $titulo, $descricao, $areaAlcance, $codigoCat01, $codigoCat02, $codigoCat03){
            $this->titulo           = $titulo;
            $this->descricao        = $descricao;
            $this->areaAlcance      = $areaAlcance;
            $this->codigoCat01      = $codigoCat01;
            $this->codigoCat02      = $codigoCat02;
            $this->codigoCat03      = $codigoCat03;
            $this->codigoAnuncio    = $codigoAnuncio;
            $this->codigoAnunciante = $codigoAnunciante;
        }
        public function selecionarCategoria($codeCategoria){
            $bancoDeDados = Database::getInstance();
            $querySQL = "INSERT INTO categoriaAnuncio (cd_anuncio, cd_categoria) VALUES (:cd_anuncio, :cd_categoria)";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
            $comandoSQL->bindParam(':cd_anuncio', $this->codigoAnucio);
            $comandoSQL->bindParam(':cd_categoria', $codeCategoria);
            $comandoSQL->execute();
        }
    }

?>
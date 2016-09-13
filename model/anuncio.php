<?php
    class Anuncio{
        private $titulo;
        private $descricao;
        private $areaDeAlcance;
        private $codigoAnucio;
        
        public function __construct($titulo, $descricao, $areaDeAlcance){
            $this->titulo = $titulo;
            $this->descricao = $descricao;
            $this->areaDeAlcance = $areaDeAlcance;
        }
        public function novoAnuncio($codigoAnunciante){
            $bancoDeDados = Database::getInstance();
            $querySQL   = "INSERT INTO anuncio (cd_anunciante, nm_titulo, ds_anuncio, qt_areaAlcance, cd_status) 
                           VALUES (:cd_anunciante, :nm_titulo, :ds_anuncio, :qt_areaAlcance, 'Aberto')";
            $comandoSQL =  $bancoDeDados->prepare($querySQL);
            $comandoSQL -> bindParam(':cd_anunciante', $codigoAnunciante);
            $comandoSQL -> bindParam(':nm_titulo', $this->titulo);
            $comandoSQL -> bindParam(':ds_anuncio', $this->descricao);
            $comandoSQL -> bindParam(':qt_areaAlcance', $this->areaDeAlcance);
            $comandoSQL->execute();
            $this->codigoAnucio = $bancoDeDados->lastInsertId();      

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
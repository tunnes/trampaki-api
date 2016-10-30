<?php
    class AnuncioDAO{
    #   Padrão de Projeto Singleton ---------------------------------------------------------------------------------------------------------    
        private static $instance;
        public function getInstance(){
            return !isset(self::$instance) ? self::$instance = new AnuncioDAO() : self::$instance;
        }
    
    #   Funções de acesso ao banco ----------------------------------------------------------------------------------------------------------
        public function cadastrarAnuncio(AnuncioBPO $anuncioBPO, $categorias){
            $bancoDeDados = Database::getInstance();
            $querySQL   = "INSERT INTO anuncio (cd_usuario, nm_titulo, ds_anuncio, qt_areaAlcance, cd_status) 
                                        VALUES (:cd_usuario, :nm_titulo, :ds_anuncio, :qt_areaAlcance, '0')";
            $comandoSQL =  $bancoDeDados->prepare($querySQL);
            $comandoSQL -> bindParam(':cd_usuario',     $anuncioBPO->getCodigoAnunciante());
            $comandoSQL -> bindParam(':nm_titulo',      $anuncioBPO->getTitulo());
            $comandoSQL -> bindParam(':ds_anuncio',     $anuncioBPO->getDescricao());
            $comandoSQL -> bindParam(':qt_areaAlcance', $anuncioBPO->getAreaAlcance());
            $comandoSQL->execute();
            
            $codigoAnuncio = $bancoDeDados->lastInsertId();
            
            $categoriasBPO = array();
            foreach($categorias as $categoria){ 
                $this->selecionarCategoria($codigoAnuncio, $categoria);
            }
        }
        public function listarAnuncios(){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT A.cd_usuario, AN.cd_anuncio, AN.nm_titulo, AN.ds_anuncio, E.cd_latitude, E.cd_longitude 
                                                    FROM usuario as U 
                                                    INNER JOIN endereco as E ON U.cd_endereco = E.cd_endereco
                                                    INNER JOIN anunciante as A ON U.cd_usuario = A.cd_usuario
                                                    INNER JOIN anuncio as AN ON A.cd_usuario = AN.cd_usuario");
            $comandoSQL->execute();
            return $comandoSQL->fetchAll(PDO::FETCH_ASSOC);
        }
        public function listarMeusAnuncios($codigoAnunciante){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM anuncio WHERE cd_usuario = :cd_usuario");
            $comandoSQL->bindParam(':cd_usuario', $codigoAnunciante);
            $comandoSQL->execute();
            $rows = $comandoSQL->fetchAll(PDO::FETCH_OBJ);
           
            $xs = array();
            foreach($rows as $row){
                $categoriasBPO = CategoriaDAO::getInstance()->consultarAnunCate($row->cd_anuncio);
                $an = new AnuncioBPO($row->cd_anuncio, $row->cd_usuario, $row->nm_titulo, $row->ds_anuncio, $row->qt_areaAlcance, $categoriasBPO, $row->cd_status);
                array_push($xs, $an); 
            }
            return $xs;     
        }
        public function consultarAnuncio($codigoAnuncio){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM anuncio WHERE cd_anuncio = :cd_anuncio");
            $comandoSQL->bindParam(':cd_anuncio', $codigoAnuncio);
            $comandoSQL->execute();
            return $comandoSQL->fetch(PDO::FETCH_OBJ);
        }
        public function editarAnuncio($codigoAnuncio, $titulo, $descricao, $areaAlcance, $status){
            $bancoDeDados = Database::getInstance();
            $querySQL   = "UPDATE anuncio SET 
                           nm_titulo = :nm_titulo, ds_anuncio = :ds_anuncio, qt_areaAlcance = :qt_areaAlcance, cd_status = :cd_status  
                           WHERE cd_anuncio = :cd_anuncio";
            $comandoSQL =  $bancoDeDados->prepare($querySQL);
            $comandoSQL -> bindParam(':nm_titulo',      $titulo);
            $comandoSQL -> bindParam(':ds_anuncio',     $descricao);
            $comandoSQL -> bindParam(':qt_areaAlcance', $areaAlcance);
            $comandoSQL -> bindParam(':cd_status',      $status);
            $comandoSQL -> bindParam(':cd_anuncio',     $codigoAnuncio);
            $comandoSQL->execute();
        }
        public function selecionarCategoria($codigoAnuncio, $codigoCategoria){
            $bancoDeDados = Database::getInstance();
            $querySQL = "INSERT INTO categoriaAnuncio (cd_anuncio, cd_categoria) VALUES (:cd_anuncio, :cd_categoria)";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
            $comandoSQL->bindParam(':cd_anuncio', $codigoAnuncio);
            $comandoSQL->bindParam(':cd_categoria', $codigoCategoria);
            $comandoSQL->execute();
        }
        public function novaConexao(){
            
        }
    }
?>
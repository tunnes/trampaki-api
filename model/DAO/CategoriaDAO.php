<?php
    require_once 'configuration/autoload-geral.php';

    class CategoriaDAO{
    #   Padrão de Projeto Singleton ---------------------------------------------------------------------------------------------------------    
        private static $instance;
        public function getInstance(){
            return !isset(self::$instance) ? self::$instance = new CategoriaDAO() : self::$instance;
        }
    
    #   Funções de acesso ao banco ----------------------------------------------------------------------------------------------------------
        public function novaCategoria(categoriaBPO $categoriaBPO){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare('INSERT INTO categoria (nm_categoria, ds_categoria) VALUES (:nm_categoria, :ds_categoria)');
            $comandoSQL->bindParam(':nm_categoria', $categoriaBPO->getNome());
            $comandoSQL->bindParam(':ds_categoria', $categoriaBPO->getDescricao());
            $comandoSQL->execute();
        }
        public function consultar($codigo){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare('SELECT * FROM categoria WHERE cd_categoria = :cd_categoria');
            $comandoSQL->bindParam(':cd_categoria', $codigo);
            $comandoSQL->execute();
            $row = $comandoSQL->fetch(PDO::FETCH_OBJ);
            $categoriaBPO = new CategoriaBPO($row->cd_categoria, $row->nm_categoria, $row->ds_categoria);
            return $categoriaBPO;
        }
        public function consultarPresCate($codigoUsuario){
        #   DEVE RETORNAR UM ARRAY DE OBJETOS CATEGORIA RELACIONADAS AO PRESTADOR.    
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare('SELECT C.cd_categoria, C.nm_categoria, C.ds_categoria FROM 
                                                    categoriaPrestador AS CP INNER JOIN categoria AS C ON C.cd_categoria = CP.cd_categoria   
                                                    WHERE CP.cd_usuario = :cd_usuario');
            $comandoSQL->bindParam(':cd_usuario', $codigoUsuario);
            $comandoSQL->execute();
            $rows = $comandoSQL->fetchAll(PDO::FETCH_OBJ);
            $categoriasBPO = array();
            foreach($rows as $row){ 
                $categoriaBPO = new CategoriaBPO($row->cd_categoria, $row->nm_categoria, $row->ds_categoria);
                array_push($categoriasBPO, $categoriaBPO);
            }
            return $categoriasBPO;            
        }
        public function consultarAnunCate($codigoAnuncio){
        #   DEVE RETORNAR UM ARRAY DE OBJETOS/JSON CATEGORIA RELACIONADAS AO PRESTADOR.    
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare('SELECT C.cd_categoria, C.nm_categoria, C.ds_categoria FROM 
                                                    categoriaAnuncio AS CA INNER JOIN categoria AS C ON C.cd_categoria = CA.cd_categoria   
                                                    WHERE CA.cd_anuncio = :cd_anuncio');
            $comandoSQL->bindParam(':cd_anuncio', $codigoAnuncio);
            $comandoSQL->execute();
            $rows = $comandoSQL->fetchAll(PDO::FETCH_OBJ);
            $categoriasBPO = array();
            foreach($rows as $row){ 
                $categoriaBPO = new CategoriaBPO($row->cd_categoria, $row->nm_categoria, $row->ds_categoria);
                array_push($categoriasBPO, $categoriaBPO);
            }
            return $categoriasBPO;            
        }
        
    }
?>
<?php
    require_once 'configuration/autoload-geral.php';
    
    class AnuncioDAO{
    #   Padrão de Projeto Singleton ---------------------------------------------------------------------------------------------------------    
        private static $instance;
        public function getInstance(){
            return !isset(self::$instance) ? self::$instance = new AnuncioDAO() : self::$instance;
        }
    
    #   Funções de acesso ao banco ----------------------------------------------------------------------------------------------------------
        public function cadastrar(AnuncioBPO $anuncioBPO, $categorias){
            $bancoDeDados = Database::getInstance();
            $querySQL   = "INSERT INTO anuncio (cd_usuario, nm_titulo, ds_anuncio, qt_areaAlcance, cd_status, cd_imagem01, cd_imagem02, cd_imagem03) 
                           VALUES (:cd_usuario, :nm_titulo, :ds_anuncio, :qt_areaAlcance, '0', :cd_imagem01, :cd_imagem02, :cd_imagem03)";
            $comandoSQL =  $bancoDeDados->prepare($querySQL);
            $comandoSQL -> bindParam(':cd_usuario',     $anuncioBPO->getCodigoAnunciante());
            $comandoSQL -> bindParam(':nm_titulo',      $anuncioBPO->getTitulo());
            $comandoSQL -> bindParam(':ds_anuncio',     $anuncioBPO->getDescricao());
            $comandoSQL -> bindParam(':qt_areaAlcance', $anuncioBPO->getAreaAlcance());
            $comandoSQL -> bindParam(':cd_imagem01',    $anuncioBPO->getImagem01());
            $comandoSQL -> bindParam(':cd_imagem02',    $anuncioBPO->getImagem02());
            $comandoSQL -> bindParam(':cd_imagem03',    $anuncioBPO->getImagem03());
            $comandoSQL->execute();
            
            $codigoAnuncio = $bancoDeDados->lastInsertId();
            
            $categoriasBPO = array();
            foreach($categorias as $categoria){ 
                $this->selecionarCategoria($codigoAnuncio, $categoria);
            }
        }
        public function consultarTodos(){
            $bancoDeDados = DataBase::getInstance();
            $querySQL ="SELECT U.cd_usuario, U.cd_imagem, U.nm_usuario, AN.cd_anuncio, AN.nm_titulo, AN.ds_anuncio, AN.cd_imagem01, E.cd_latitude, E.cd_longitude FROM usuario as U 
                            INNER JOIN endereco   as E  ON U.cd_endereco =  E.cd_endereco
                            INNER JOIN anunciante as A  ON U.cd_usuario  =  A.cd_usuario
                            INNER JOIN anuncio    as AN ON A.cd_usuario  = AN.cd_usuario";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
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
                $an = new AnuncioBPO(
                    $row->cd_anuncio, 
                    $row->cd_usuario, 
                    $row->nm_titulo, 
                    $row->ds_anuncio, 
                    $row->qt_areaAlcance, 
                    $categoriasBPO, 
                    $row->cd_status, 
                    $row->cd_imagem01, 
                    $row->cd_imagem02, 
                    $row->cd_imagem03
                );
                array_push($xs, $an); 
            }
            return $xs;     
        }
        public function consultarAnuncio($codigoAnuncio){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM anuncio WHERE cd_anuncio = :cd_anuncio");
            $comandoSQL->bindParam(':cd_anuncio', $codigoAnuncio);
            $comandoSQL->execute();
            $row = $comandoSQL->fetch(PDO::FETCH_OBJ);
            return $anuncioBPO = new AnuncioBPO(
                $row->cd_anuncio, 
                $row->cd_usuario, 
                $row->nm_titulo, 
                $row->ds_anuncio, 
                $row->qt_areaAlcance, 
                CategoriaDAO::getInstance()->consultarAnunCate($row->cd_anuncio),
                $row->cd_status, 
                $row->cd_imagem01,
                $row->cd_imagem02,
                $row->cd_imagem03
            );
            
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
        public function carregarEnvolvidos($codigoAnuncio){
            $bancoDeDados = DataBase::getInstance();
            $querySQL = "SELECT C.cd_conexao, U.cd_imagem, U.cd_usuario, U.nm_usuario, A.cd_anuncio
                            FROM conexao AS C 
                                INNER JOIN anuncio AS A ON C.cd_anuncio = A.cd_anuncio 
                                INNER JOIN usuario AS U ON C.cd_usuario = U.cd_usuario
                            WHERE A.cd_anuncio = :cd_anuncio and C.cd_status = '1'";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
            $comandoSQL->bindParam(':cd_anuncio', $codigoAnuncio);  
            $bancoDeDados->prepare($querySQL);               
            $comandoSQL->execute();
            return $comandoSQL->fetchAll(PDO::FETCH_OBJ);                      
        }
        
        public function anunciosAceitos($usuario, $codigoAnuncio){
            $bancoDeDados = DataBase::getInstance();
            $querySQL = "SELECT u.nm_usuario AS prestador , u.cd_imagem AS imagem, a.nm_titulo AS titulo, 
                         c.cd_anuncio as codigo FROM conexao AS c INNER JOIN anuncio a ON c.cd_anuncio = a.cd_anuncio
		                 INNER JOIN usuario AS u ON c.cd_usuario = u.cd_usuario 
		              	 WHERE a.cd_usuario = :cd_usuario AND c.cd_status = '1' AND c.cd_anuncio > :cd_anuncio";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
            $comandoSQL->bindParam('cd_usuario', $usuario);
            $comandoSQL->bindParam(':cd_anuncio', $codigoAnuncio);
            $comandoSQL->execute();
            return $comandoSQL->fetchAll(PDO::FETCH_OBJ);
        }
        
        public function getTituloPorConexao($conexao){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT nm_titulo FROM anuncio WHERE cd_anuncio
                                                        IN (SELECT cd_anuncio FROM conexao WHERE cd_conexao = :cd_conexao)");
            $comandoSQL->bindParam(':cd_conexao', $conexao);
            $comandoSQL->execute();
            return $comandoSQL->fetch(PDO::FETCH_OBJ)->nm_titulo;
        }
        
        public function getCodigoAnunciante($conexao){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT cd_usuario FROM anuncio WHERE cd_anuncio
                                                        IN (SELECT cd_anuncio FROM conexao WHERE cd_conexao = :cd_conexao)");
            $comandoSQL->bindParam(':cd_conexao', $conexao);
            $comandoSQL->execute();
            return $comandoSQL->fetch(PDO::FETCH_OBJ)->cd_usuario;
        }
        
        public function getCodigoPrestador($conexao){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT cd_usuario FROM conexao WHERE cd_conexao = :cd_conexao");
            $comandoSQL->bindParam(':cd_conexao', $conexao);
            $comandoSQL->execute();
            return $comandoSQL->fetch(PDO::FETCH_OBJ)->cd_usuario;
        }
    }
?>
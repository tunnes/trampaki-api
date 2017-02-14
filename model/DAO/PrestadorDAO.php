<?php
    require_once 'configuration/autoload-geral.php';

    class PrestadorDAO extends UsuarioDAO{
    #   Padrão de Projeto Singleton ------------------------------------------------------------------------------------------------------------------------    
        private static $instance;
        public function getInstance(){
            return !isset(self::$instance) ? self::$instance = new PrestadorDAO() : self::$instance;
        }
        
    #   Funções de acesso ao banco -------------------------------------------------------------------------------------------------------------------------
        public function cadastrarPrestador(PrestadorBPO $prestadorBPO, $categorias){
            $bancoDeDados = Database::getInstance();
            $loginDAO     = LoginDAO::getInstance();
            $enderecoDAO  = EnderecoDAO::getInstance();
            
            $loginBPO      = $loginDAO->cadastrarLogin($prestadorBPO->getLogin());
            $enderecoBPO   = $enderecoDAO->cadastrarEndereco($prestadorBPO->getEndereco());
            $codigoUsuario = $this->cadastrarUsuario(
                $prestadorBPO->getNome(), 
                $prestadorBPO->getEmail(), 
                $loginBPO->getCodigoLogin(), 
                $enderecoBPO->getCodigoEndereco(), 
                $prestadorBPO->getTelefone(),
                '1',
                $prestadorBPO->getCodigoImagem()
            );
            
            $querySQL = "INSERT INTO prestador (cd_usuario, ds_perfilProfissional, qt_areaAlcance) 
                                VALUES (:cd_usuario, :ds_perfilProfissional, :qt_areaAlcance)";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
            $comandoSQL -> bindParam(':cd_usuario',             $codigoUsuario);
            $comandoSQL -> bindParam(':ds_perfilProfissional',  $prestadorBPO->getDescricao());
            $comandoSQL -> bindParam(':qt_areaAlcance',         $prestadorBPO->getAreaAlcance());            
            $comandoSQL->execute();
            
            $categoriasBPO = array();
            foreach($categorias as $categoria){ 
                $this->selecionarCategoria($codigoUsuario, $categoria);
                $categoriaBPO = CategoriaDAO::getInstance()->consultar($categoria);
                array_push($categoriasBPO, $categoriaBPO);
            }
            
            $prestadorBPO = new PrestadorBPO(
                $codigoUsuario, 
                $prestadorBPO->getNome(),
                $prestadorBPO->getEmail(), 
                $prestadorBPO->getTelefone(), 
                $loginBPO, 
                $enderecoBPO, 
                $prestadorBPO->getDescricao(), 
                $prestadorBPO->getAreaAlcance(),
                $categoriasBPO,
                $prestadorBPO->getCodigoImagem()
            );
            return $prestadorBPO;
        }
        public function editarPrestador(PrestadorBPO $prestadorBPO, $categorias){
            $bancoDeDados  = Database::getInstance();
            $loginDAO      = LoginDAO::getInstance();
            $enderecoDAO   = EnderecoDAO::getInstance();
            
            $loginBPO      = $loginDAO->editarLogin($prestadorBPO->getLogin());
            $enderecoBPO   = $enderecoDAO->editarEndereco($prestadorBPO->getEndereco());
            
            $this->editarUsuario($prestadorBPO);
            $querySQL = "UPDATE prestador SET ds_perfilProfissional = :ds_perfilProfissional, qt_areaAlcance = :qt_areaAlcance  
                         WHERE cd_usuario = :cd_usuario";
            $comandoSQL =  $bancoDeDados->prepare($querySQL);
            $comandoSQL -> bindParam(':ds_perfilProfissional',  $prestadorBPO->getDescricao());
            $comandoSQL -> bindParam(':qt_areaAlcance',         $prestadorBPO->getAreaAlcance());
            $comandoSQL -> bindParam(':cd_usuario',             $prestadorBPO->getCodigoUsuario());
            $comandoSQL->execute();
            $this->excluirVinculoCategoria($prestadorBPO->getCodigoUsuario());
            foreach($categorias as $categoria){ $this->selecionarCategoria($prestadorBPO->getCodigoUsuario(), $categoria);}

        }
        public function selecionarCategoria($codigoUsuario, $codigoCategoria){
            $bancoDeDados = Database::getInstance();
            $querySQL = "INSERT INTO categoriaPrestador (cd_usuario, cd_categoria) VALUES (:cd_usuario, :cd_categoria)";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
                    $comandoSQL->bindParam(':cd_usuario', $codigoUsuario);
                    $comandoSQL->bindParam(':cd_categoria', $codigoCategoria);
                    $comandoSQL->execute();
        }
        public function carregarSolicitacoes(PrestadorBPO $prestadorBPO){
            $bancoDeDados = DataBase::getInstance();
            $querySQL = "SELECT U.nm_usuario, C.cd_conexao, C.cd_anuncio, A.cd_imagem01, A.nm_titulo, E.nm_cidade, E.sg_estado, C.cd_status, C.cd_solicitante 
                        	FROM conexao AS C 
                        	INNER JOIN anuncio AS A ON C.cd_anuncio = A.cd_anuncio
                        	INNER JOIN usuario AS U ON A.cd_usuario = U.cd_usuario
                        	INNER JOIN endereco AS E ON U.cd_endereco = E.cd_endereco
                         WHERE C.cd_usuario = :cd_usuario and C.cd_status = '0'";
                         
            $comandoSQL   = $bancoDeDados->prepare($querySQL);
            $comandoSQL->bindParam(':cd_usuario', $prestadorBPO->getCodigoUsuario());
            $comandoSQL->execute();
            return $comandoSQL->fetchAll(PDO::FETCH_OBJ);
        }
        public function meusServicos(PrestadorBPO $prestadorBPO){
            $bancoDeDados = Database::getInstance();
            $querySQL = "SELECT A.cd_imagem01, C.cd_anuncio, A.cd_status, A.nm_titulo, E.sg_estado, E.nm_cidade  
                         FROM conexao as C 
                         INNER JOIN anuncio as A ON A.cd_anuncio = C.cd_anuncio 
                         INNER JOIN usuario as U ON U.cd_usuario = A.cd_usuario 
                         INNER JOIN endereco as E ON E.cd_endereco = U.cd_endereco 
                         WHERE C.cd_usuario = :cd_usuario AND C.cd_status = '1'";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
            $comandoSQL->bindParam(':cd_usuario',  $prestadorBPO->getCodigoUsuario());
            $comandoSQL->execute();
            return $comandoSQL->fetchAll(PDO::FETCH_OBJ);     
        }
        public function consultarPerfil($codigoPrestador){
        #   Esta função esta muito quebrada, fiz isso meio que sem pensar ai é foda..    
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT P.*, U.*, E.* FROM usuario as U 
                                                    	INNER JOIN endereco as E ON U.cd_endereco = E.cd_endereco
                                                    	INNER JOIN prestador as P ON U.cd_usuario = P.cd_usuario
                                                    WHERE P.cd_usuario = :cd_usuario");
            $comandoSQL->bindParam(':cd_usuario', $codigoPrestador);
            $comandoSQL->execute();
            $objeto = $comandoSQL->fetch(PDO::FETCH_OBJ);
            $objeto->categorias = CategoriaDAO::getInstance()->consultarPresCate($objeto->cd_usuario);
            return $objeto;                        
        }
        public function excluirVinculoCategoria($codigo){
            $bancoDeDados  = Database::getInstance();
            $querySQL = "DELETE FROM categoriaPrestador WHERE cd_usuario = :cd_usuario";
            $comandoSQL =  $bancoDeDados->prepare($querySQL);
            $comandoSQL->bindParam(':cd_usuario', $codigo);
            $comandoSQL->execute();
        }
        public function consultar($codigoPrestador){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT P.*, U.*, E.*, L.* FROM usuario as U 
                                                    	INNER JOIN endereco as E ON U.cd_endereco = E.cd_endereco
                                                    	INNER JOIN login as L ON U.cd_login = L.cd_login
                                                    	INNER JOIN prestador as P ON U.cd_usuario = P.cd_usuario
                                                    WHERE P.cd_usuario = :cd_usuario");
            $comandoSQL->bindParam(':cd_usuario', $codigoPrestador);
            $comandoSQL->execute();
            $row = $comandoSQL->fetch(PDO::FETCH_OBJ);
            $categoriasBPO = CategoriaDAO::getInstance()->consultarPresCate($row->cd_usuario);
            $enderecoBPO = new EnderecoBPO(
                $row->cd_endereco, 
                $row->sg_estado, 
                $row->nm_cidade, 
                $row->cd_cep, 
                $row->cd_numeroResidencia, 
                $row->cd_longitude, 
                $row->cd_latitude
            );
            $loginBPO = new LoginBPO(
                $row->cd_login, 
                $row->ds_login, 
                $row->ds_senha,
                $row->cd_token
            );
            $prestadorBPO = new PrestadorBPO(
                $row->cd_usuario, 
                $row->nm_usuario, 
                $row->ds_email, 
                $row->ds_telefone, 
                $loginBPO, 
                $enderecoBPO, 
                $row->ds_perfilProfissional, 
                $row->qt_areaAlcance,
                $categoriasBPO,
                $row->cd_imagem
            );
                
            return $prestadorBPO;                         
        }
        public function listarPrestadores(){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT P.cd_usuario, P.ds_perfilProfissional, U.cd_imagem, U.nm_usuario, E.cd_longitude, E.cd_latitude 
	                                                    FROM usuario as U 
	                                                    INNER JOIN endereco as E ON U.cd_endereco = E.cd_endereco
	                                                    INNER JOIN prestador as P ON U.cd_usuario = P.cd_usuario");
            $comandoSQL->execute();
            return $comandoSQL->fetchAll(PDO::FETCH_ASSOC);     
        }

    }
?>
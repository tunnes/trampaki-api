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
        public function excluirVinculoCategoria($codigo){
            $bancoDeDados  = Database::getInstance();
            $querySQL = "DELETE FROM categoriaPrestador WHERE cd_usuario = :cd_usuario";
            $comandoSQL =  $bancoDeDados->prepare($querySQL);
            $comandoSQL->bindParam(':cd_usuario', $codigo);
            $comandoSQL->execute();
        }
        private function selecionarCategoria($codigoUsuario, $codigoCategoria){
            $bancoDeDados = Database::getInstance();
            $querySQL = "INSERT INTO categoriaPrestador (cd_usuario, cd_categoria) VALUES (:cd_usuario, :cd_categoria)";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
                    $comandoSQL->bindParam(':cd_usuario', $codigoUsuario);
                    $comandoSQL->bindParam(':cd_categoria', $codigoCategoria);
                    $comandoSQL->execute();
        }
        public function listarPrestadores(){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT P.cd_usuario, U.nm_usuario, E.cd_longitude, E.cd_latitude 
	                                                    FROM usuario as U 
	                                                    INNER JOIN endereco as E ON U.cd_endereco = E.cd_endereco
	                                                    INNER JOIN prestador as P ON U.cd_usuario = P.cd_usuario");
            $comandoSQL->execute();
            return $comandoSQL->fetchAll(PDO::FETCH_ASSOC);     
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
        public function carregarSolicitacoes(PrestadorBPO $prestadorBPO){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM conexao WHERE cd_usuario = :cd_usuario");
            $comandoSQL->bindParam(':cd_usuario', $prestadorBPO->getCodigoUsuario());
            $comandoSQL->execute();
            return $comandoSQL->fetchAll(PDO::FETCH_OBJ);              
        }
        public function novaConexao(PrestadorBPO $prestadorBPO, $codigoAnuncio){
            $bancoDeDados = Database::getInstance();
            $querySQL = "INSERT INTO conexao (cd_usuario, cd_anuncio) VALUES (:cd_usuario, :cd_anuncio)";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
            $comandoSQL->bindParam(':cd_usuario', $prestadorBPO->getCodigoUsuario());
            $comandoSQL->bindParam(':cd_anuncio', $codigoAnuncio);            
            $comandoSQL->execute();
        }
        private function consultarCategoria($codigoCategoria){
            
        }
    }
?>
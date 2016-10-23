<?php
    require_once('model/DAO/usuarioDAO.php');
    require_once('model/BPO/prestadorBPO.php');

    class PrestadorDAO extends UsuarioDAO{
    #   Padrão de Projeto Singleton ------------------------------------------------------------------------------------------------------------------------    
        private static $instance;
        public function getInstance(){
            return !isset(self::$instance) ? self::$instance = new prestadorDAO() : self::$instance;
        }
        
    #   Funções de acesso ao banco -------------------------------------------------------------------------------------------------------------------------
        public function cadastrarPrestador(PrestadorBPO $prestadorBPO){
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
                '1'
            );
            
            $querySQL = "INSERT INTO prestador (cd_usuario, ds_perfilProfissional, qt_areaAlcance) 
                                VALUES (:cd_usuario, :ds_perfilProfissional, :qt_areaAlcance)";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
            $comandoSQL -> bindParam(':cd_usuario',             $codigoUsuario);
            $comandoSQL -> bindParam(':ds_perfilProfissional',  $prestadorBPO->getDescricao());
            $comandoSQL -> bindParam(':qt_areaAlcance',         $prestadorBPO->getAreaAlcance());            
            $comandoSQL->execute();
            
            $prestadorBPO = new PrestadorBPO(
                $codigoUsuario, 
                $prestadorBPO->getNome(),
                $prestadorBPO->getEmail(), 
                $prestadorBPO->getTelefone(), 
                $loginBPO, 
                $enderecoBPO, 
                $prestadorBPO->getDescricao(), 
                $prestadorBPO->getAreaAlcance()
            );
            return $prestadorBPO;
        }
        public function editarPrestador(PrestadorBPO $prestadorBPO){
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
            $comandoSQL -> bindParam(':qt_areaAlcance',    $prestadorBPO->getAreaAlcance());
            $comandoSQL -> bindParam(':cd_usuario', $prestadorBPO->getCodigoUsuario());
            $comandoSQL->execute();
        }
        public function editarCategoria(PrestadorBPO $prestadorBPO, $codigoCategoria){
            $bancoDeDados  = Database::getInstance();
            $querySQL = "UPDATE categoriaPrestador SET cd_categoria = :cd_categoria  
                         WHERE cd_usuario = :cd_usuario";
            $comandoSQL =  $bancoDeDados->prepare($querySQL);
            $comandoSQL -> bindParam(':cd_categoria', $codigoCategoria);
            $comandoSQL -> bindParam(':cd_usuario',   $prestadorBPO->getCodigoUsuario());
            $comandoSQL->execute();
        }
        public function selecionarCategoria($codigoUsuario, $codigoCategoria){
            $bancoDeDados = Database::getInstance();
            $querySQL = "INSERT INTO categoriaPrestador (cd_usuario, cd_categoria) VALUES (:cd_usuario, :cd_categoria)";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
                    $comandoSQL->bindParam(':cd_usuario', $codigoUsuario);
                    $comandoSQL->bindParam(':cd_categoria', $codigoCategoria);
                    $comandoSQL->execute();
        }
        public function consultarPrestador($codigoUsuario){
            $bancoDeDados = Database::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM prestador as P INNER JOIN usuario as U ON U.cd_usuario = P.cd_usuario WHERE U.cd_usuario = :cd_usuario");
            $comandoSQL->bindParam(':cd_usuario', $codigoUsuario);
            $comandoSQL->execute();
            $co = $comandoSQL->fetch(PDO::FETCH_OBJ);
            $loginDAO      = LoginDAO::getInstance();
            $enderecoDAO   = EnderecoDAO::getInstance();
            
            $loginBPO      = $loginDAO->consultarLogin($co->cd_login);
            $enderecoBPO   = $enderecoDAO->consultarEndereco($co->cd_endereco);
            $prestadorBPO = new PrestadorBPO($co->cd_usuario, $co->nm_prestador, $co->ds_email, $co->ds_telefone, $loginBPO, $enderecoBPO, $co->ds_profissional, $co->qt_areaAlcance);
            return $prestadorBPO; 
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
        public function carregarPerfil($codigoPrestador){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT P.*, U.*, E.*, L.* 
                                                    	FROM usuario as U 
                                                    	INNER JOIN endereco as E ON U.cd_endereco = E.cd_endereco
                                                    	INNER JOIN login as L ON U.cd_login = L.cd_login
                                                    	INNER JOIN prestador as P ON U.cd_usuario = P.cd_usuario
                                                        WHERE P.cd_usuario = :cd_usuario");
            $comandoSQL->bindParam(':cd_usuario', $codigoPrestador);
            $comandoSQL->execute();
            return $comandoSQL->fetch(PDO::FETCH_OBJ);       
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
    }
?>
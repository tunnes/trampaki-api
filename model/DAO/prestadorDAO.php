<?php
    require_once('model/DAO/usuarioDAO.php');
    require_once('model/BPO/prestador.php');

    class PrestadorDAO extends UsuarioDAO{
    #   Padrão de Projeto Singleton ------------------------------------------------------------------------------------------------------------------------    
        private static $instance;
        public function getInstance(){
            return !isset(self::$instance) ? self::$instance = new prestadorDAO() : self::$instance;
        }
        
    #   Funções de acesso ao banco -------------------------------------------------------------------------------------------------------------------------
        public function cadastrarPrestador($nome, $email, $tel, $login, $senha, $estado, $cidade, $CEP, $numRes, $long, $lati, $desProf, $qntAlc){
            $loginDAO     = LoginDAO::getInstance();
            $enderecoDAO  = EnderecoDAO::getInstance();
            $bancoDeDados = Database::getInstance();
        
            $loginBPO      = $loginDAO->cadastrarLogin($login, $senha);
            $enderecoBPO   = $enderecoDAO->cadastrarEndereco($estado, $cidade, $CEP, $numRes, $long, $lati);
            $codigoUsuario = $this->cadastrarUsuario($nome, $email, $loginBPO->getCodigoLogin(), $enderecoBPO->getCodigoEndereco(), $tel, '1');
            
            
            $querySQL = "INSERT INTO prestador (cd_usuario, ds_perfilProfissional, qt_areaAlcance) 
                                VALUES ('".$codigoUsuario."', '".$desProf."', '".$qntAlc."')";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
            $comandoSQL->execute();
            $prestadorBPO = new PrestadorBPO($bancoDeDados->lastInsertId(), $nome, $email, $tel, $loginBPO, $enderecoBPO, $desProf, $qntAlc);
            return $prestadorBPO;
        }
        public function selecionarCategoria($codigoPrestador, $codigoCategoria){
            $bancoDeDados = Database::getInstance();
            $querySQL = "INSERT INTO categoriaPrestador (cd_prestador, cd_categoria) VALUES (:cd_prestador, :cd_categoria)";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
            $comandoSQL->bindParam(':cd_prestador', $codigoPrestador);
            $comandoSQL->bindParam(':cd_categoria', $codigoCategoria);
            $comandoSQL->execute();
        }
        public function consultarPrestador($codigoPrestador){
            $bancoDeDados = Database::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM prestador as P INNER JOIN usuario as U ON U.cd_usuario = P.cd_usuario WHERE U.cd_usuario = :cd_usuario");
            $comandoSQL->bindParam(':cd_usuario', $codigoPrestador);
            $comandoSQL->execute();
            $co = $comandoSQL->fetch(PDO::FETCH_OBJ);
            $loginDAO      = LoginDAO::getInstance();
            $enderecoDAO   = EnderecoDAO::getInstance();
            
            $loginBPO      = $loginDAO->consultarLogin($co->cd_login);
            $enderecoBPO   = $enderecoDAO->consultarEndereco($co->cd_endereco);
            $prestadorBPO = new PrestadorBPO($co->cd_prestador, $co->nm_prestador, $co->ds_email, $co->ds_telefone, $loginBPO, $enderecoBPO, $co->ds_profissional, $co->qt_areaAlcance);
            return $prestadorBPO; 
        }
        public function listarPrestadores(){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT P.cd_prestador, U.nm_usuario, E.cd_longitude, E.cd_latitude 
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
                                                        WHERE P.cd_prestador = :cd_prestador");
            $comandoSQL->bindParam(':cd_prestador', $codigoPrestador);
            $comandoSQL->execute();
            return $comandoSQL->fetch(PDO::FETCH_OBJ);       
        }
    }
?>
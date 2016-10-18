<?php
    require_once('model/DAO/usuarioDAO.php');
    require_once('model/DAO/enderecoDAO.php');
    require_once('model/DAO/loginDAO.php');
    require_once('model/BPO/anuncianteBPO.php');
    
    class AnuncianteDAO extends UsuarioDAO{
    #   Padrão de Projeto Singleton ---------------------------------------------------------------------------------------------------------    
        private static $instance;
        public function getInstance(){
            return !isset(self::$instance) ? self::$instance = new anuncianteDAO() : self::$instance;
        }
        
    #   Funções de acesso ao banco ----------------------------------------------------------------------------------------------------------
    
        public function cadastrarAnunciante(AnuncianteBPO $anuncianteBPO){
        #   Cadastrando um endereco e um login, recebendo assim seus atributos, identificadores do banco de dados tornar fisica o conceito 
        #   de presente no projeto agregação:
            $loginDAO      = LoginDAO::getInstance();
            $loginBPO      = $loginDAO->cadastrarLogin($anuncianteBPO->getLogin());
            
            $enderecoDAO   = EnderecoDAO::getInstance();
            $enderecoBPO   = $enderecoDAO->cadastrarEndereco($anuncianteBPO->getEndereco());
            
            $codigoUsuario = $this->cadastrarUsuario(
                $anuncianteBPO->getNome(), 
                $anuncianteBPO->getEmail(), 
                $loginBPO->getCodigoLogin(), 
                $enderecoBPO->getCodigoEndereco(), 
                $anuncianteBPO->getTelefone(), 
                '0'
            );
            $bancoDeDados  = Database::getInstance();
            $comandoSQL = $bancoDeDados->prepare();
            $comandoSQL->bindParam(':cd_usuario', $codigoUsuario);
            $comandoSQL->execute();
            return $anuncianteBPO = new AnuncianteBPO(
                $codigoUsuario, 
                $anuncianteBPO->getNome(), 
                $anuncianteBPO->getEmail(), 
                $anuncianteBPO->getTelefone(), 
                $enderecoBPO, 
                $loginBPO
            );
        }
        public function editarAnunciante(AnuncianteBPO $anuncianteBPO){
            $bancoDeDados  = Database::getInstance();
            $loginDAO      = LoginDAO::getInstance();
            $enderecoDAO   = EnderecoDAO::getInstance();
            
            $loginBPO      = $loginDAO->editarLogin($anuncianteBPO->getLogin());
            $enderecoBPO   = $enderecoDAO->editarEndereco($anuncianteBPO->getEndereco());
            
            $this->editarUsuario($anuncianteBPO);
        }
        public function carregarSolicitacoes(AnuncianteBPO $anuncianteBPO){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM conexao AS c INNER JOIN anuncio AS a ON c.cd_anuncio = a.cd_anuncio 
                                                    WHERE a.cd_usuario = :cd_usuario");
            $comandoSQL->bindParam(':cd_usuario', $anuncianteBPO->getCodigoUsuario());                                                    
            $comandoSQL->execute();
            return $comandoSQL->fetchAll(PDO::FETCH_OBJ);              
        }
        public function consultarAnunciante($codigoUsuario){
            $bancoDeDados = Database::getInstance();
            $comandoSQL = $bancoDeDados->prepare("SELECT * FROM anunciante as A INNER JOIN usuario as U ON U.cd_usuario = A.cd_usuario WHERE U.cd_usuario = :cd_usuario"); 
            $comandoSQL->bindParam(':cd_usuario', $codigoUsuario);
            $comandoSQL->execute();
            
            $consulta = $comandoSQL->fetch(PDO::FETCH_OBJ);
            
            $loginDAO = LoginDAO::getInstance();
            $loginBPO = $loginDAO->consultarLogin($consulta->cd_login);
            
            $enderecoDAO = EnderecoDAO::getInstance();
            $enderecoBPO = $enderecoDAO->consultarEndereco($consulta->cd_endereco);
            
            return $anuncianteBPO = new AnuncianteBPO(
                $consulta->cd_usuario,
                $consulta->nm_usuario, 
                $consulta->ds_email, 
                $consulta->ds_telefone, 
                $enderecoBPO, 
                $loginBPO
            );
            
        }
        public function carregarPerfil($codigoUsuario){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT A.*, U.*, E.*, L.* 
                                                    	FROM usuario as U 
                                                    	INNER JOIN endereco as E ON U.cd_endereco = E.cd_endereco
                                                    	INNER JOIN login as L ON U.cd_login = L.cd_login
                                                    	INNER JOIN anunciante as A ON U.cd_usuario = A.cd_usuario
                                                        WHERE A.cd_usuario = :cd_usuario");
            $comandoSQL->bindParam(':cd_usuario', $codigoUsuario);
            $comandoSQL->execute();
            return $comandoSQL->fetch(PDO::FETCH_OBJ);       
        }
    }
?>
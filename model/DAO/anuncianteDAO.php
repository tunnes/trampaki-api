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
        #   Cadastrando um endereco e um login, recebendo assim seus atributos
        #   identificadores do banco de dados tornar fisica o conceito de presente
        #   no projeto agregação:
            $bancoDeDados  = Database::getInstance();
            $loginDAO      = LoginDAO::getInstance();
            $enderecoDAO   = EnderecoDAO::getInstance();
            
            $loginBPO      = $loginDAO->cadastrarLogin($anuncianteBPO->getLogin());
            $enderecoBPO   = $enderecoDAO->cadastrarEndereco($anuncianteBPO->getEndereco());
            $codigoUsuario = $this->cadastrarUsuario(
                $anuncianteBPO->getNome(), 
                $anuncianteBPO->getEmail(), 
                $loginBPO->getCodigoLogin(), 
                $enderecoBPO->getCodigoEndereco(), 
                $anuncianteBPO->getTelefone(), 
                '0'
            );
            $comandoSQL = $bancoDeDados->prepare("INSERT INTO anunciante (cd_usuario) VALUES (:cd_usuario)");
            $comandoSQL->bindParam(':cd_usuario', $codigoUsuario);
            $comandoSQL->execute();
            $anuncianteBPO = new AnuncianteBPO(
                $bancoDeDados->lastInsertId(), 
                $anuncianteBPO->getNome(), 
                $anuncianteBPO->getEmail(), 
                $anuncianteBPO->getTelefone(), 
                $enderecoBPO, 
                $loginBPO
            ); 
            return $anuncianteBPO; 
            
        }
        public function consultarAnunciante($codigoAnunciante){
            $bancoDeDados = Database::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM anunciante as A INNER JOIN usuario as U ON U.cd_usuario = A.cd_usuario WHERE U.cd_usuario = :cd_usuario"); 
            $comandoSQL->bindParam(':cd_usuario', $codigoAnunciante);
            $comandoSQL->execute();
            $co = $comandoSQL->fetch(PDO::FETCH_OBJ);
            $loginDAO      = LoginDAO::getInstance();
            $enderecoDAO   = EnderecoDAO::getInstance();
            
            $loginBPO      = $loginDAO->consultarLogin($co->cd_login);
            $enderecoBPO   = $enderecoDAO->consultarEndereco($co->cd_endereco);
            $anuncianteBPO = new AnuncianteBPO($co->cd_anunciante, $co->nm_anunciante, $co->ds_email, $co->ds_telefone, $enderecoBPO, $loginBPO);
            return $anuncianteBPO; 
        }
        public function carregarPerfil($codigoAnunciante){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT A.*, U.*, E.*, L.* 
                                                    	FROM usuario as U 
                                                    	INNER JOIN endereco as E ON U.cd_endereco = E.cd_endereco
                                                    	INNER JOIN login as L ON U.cd_login = L.cd_login
                                                    	INNER JOIN anunciante as A ON U.cd_usuario = A.cd_usuario
                                                        WHERE A.cd_anunciante = :cd_anunciante");
            $comandoSQL->bindParam(':cd_anunciante', $codigoAnunciante);
            $comandoSQL->execute();
            return $comandoSQL->fetch(PDO::FETCH_OBJ);       
        }
    }
?>
<?php
    require_once('model/DAO/usuarioDAO.php');
    require_once('model/DAO/enderecoDAO.php');
    require_once('model/DAO/loginDAO.php');
    
    class AnuncianteDAO extends UsuarioDAO{
    #   Padrão de Projeto Singleton ---------------------------------------------------------------------------------------------------------    
        private static $instance;
        public function getInstance(){
            return !isset(self::$instance) ? self::$instance = new anuncianteDAO() : self::$instance;
        }
        
    #   Funções de acesso ao banco ----------------------------------------------------------------------------------------------------------
        public function cadastrarAnunciante($nome, $email, $tel, $login, $senha, $estado, $cidade, $CEP, $numRes, $long, $lati){
        #   Cadastrando um endereco e um login, recebendo assim seus atributos
        #   identificadores do banco de dados tornar fisica o conceito de presente
        #   no projeto agregação:
            $bancoDeDados  = Database::getInstance();
            $loginDAO      = LoginDAO::getInstance();
            $enderecoDAO   = EnderecoDAO::getInstance();
            
            $loginBPO      = $loginDAO->cadastrarLogin($login, $senha);
            $enderecoBPO   = $enderecoDAO->cadastrarEndereco($estado, $cidade, $CEP, $numRes, $long, $lati);
            $codigoUsuario = $this->cadastrarUsuario($nome, $email, $loginBPO->getCodigoLogin(), $enderecoBPO->getCodigoEndereco(), $tel, '0');

            $comandoSQL = $bancoDeDados->prepare("INSERT INTO anunciante (cd_usuario) VALUES (:cd_usuario)");
            $comandoSQL->bindParam(':cd_usuario', $codigoUsuario);
            $comandoSQL->execute();
            $codigoAnunciante = $bancoDeDados->lastInsertId();
            $anuncianteBPO = new AnuncianteBPO($codigoAnunciante, $nome, $email, $tel, $enderecoBPO, $loginBPO); 
            return $anuncianteBPO; 
            
        }
    }
?>
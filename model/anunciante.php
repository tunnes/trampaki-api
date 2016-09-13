<?php
#   Classe 'Anunciante'
    class Anunciante extends UsuarioGenerico{
        
        private $codigoAnunciante;
        
        public function __construct($nome, $email, $telefone, Endereco $endereco, Login $login){
            parent::__construct($nome, $email, $telefone, $endereco, $login);
        }
        public function novoAnunciante(){
        #   Cadastrando um usuário genérico e recebendo seu 
        #   atributo identificador do banco de dados:    
            $codUsuario = $this->novoCadastro();
            
            $bancoDeDados = Database::getInstance();
            $querySQL = "INSERT INTO anunciante (cd_usuario) VALUES (:cd_usuario)";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
            $comandoSQL->bindParam(':cd_usuario', $codUsuario);
            $comandoSQL->execute();
            $this->codigoAnunciante = $bancoDeDados->lastInsertId();            
        }
    }
?>
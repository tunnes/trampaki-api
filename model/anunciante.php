<?php
#   Classe 'Anunciante'
    class Anunciante extends Usuario{
        
        public $codigoAnunciante;
        
        public function __construct($nome, $email, $telefone, Endereco $endereco, Login $login){
            parent::__construct($nome, $email, $telefone,$endereco,$login);
        }
        public function novoAnunciante(){
            $bancoDeDados = Database::getInstance();
            $querySQL = "INSERT INTO anunciante (cd_usuario) VALUES (:cd_usuario)";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
            $comandoSQL->bindParam(':cd_usuario', $this->getCodigoUsuario());
            $comandoSQL->execute();
            $this->codigoAnunciante = $bancoDeDados->lastInsertId();            
        }
        public function getCodigoAnunciante(){
            return $this->codigoAnunciante;
        }
    }
?>
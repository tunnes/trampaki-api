<?php
#   Classe 'Anunciante'
    class Anunciante extends Usuario{
        
        // private $codigoAnunciante;
        
        public function __construct($nome, $email, $telefone, Endereco $endereco, Login $login){
            parent::__construct($nome, $email, $telefone, $endereco, $login);
        }
    }
?>
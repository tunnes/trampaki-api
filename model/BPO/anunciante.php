<?php
#   Classe 'Anunciante'
    class AnuncianteBPO extends Usuario{
        
        private $codigoAnunciante;
        
        public function __construct($codigoAnunciante, $nome, $email, $telefone, EnderecoBPO $endereco, LoginBPO $login){
            parent::__construct($nome, $email, $telefone, $endereco, $login);
            $this->codigoAnunciante = $codigoAnunciante;
        }
    }
?>
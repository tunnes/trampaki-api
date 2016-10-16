<?php
    require_once ('model/BPO/usuarioBPO.php');
    class AnuncianteBPO extends Usuario{
        
        private $codigoAnunciante;
        
        public function __construct($codigoAnunciante, $nome, $email, $telefone, EnderecoBPO $endereco, LoginBPO $login){
            parent::__construct($nome, $email, $telefone, $endereco, $login);
            $this->codigoAnunciante = $codigoAnunciante;
        }
        public function getCodigoAnunciante(){
            return $this->codigoAnunciante;
        }
    }
?>
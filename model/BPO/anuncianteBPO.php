<?php
    require_once ('model/BPO/usuarioBPO.php');
    class AnuncianteBPO extends Usuario{
        public function __construct($codigoUsuario, $nome, $email, $telefone, EnderecoBPO $endereco, LoginBPO $login){
            parent::__construct($codigoUsuario, $nome, $email, $telefone, $endereco, $login);
        }
    }
?>
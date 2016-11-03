<?php
    require_once 'configuration/autoload-geral.php';
    class AnuncianteBPO extends UsuarioBPO implements JsonSerializable{
        public function __construct($codigoUsuario, $nome, $email, $telefone, EnderecoBPO $endereco, LoginBPO $login, $codigoImagem){
            parent::__construct($codigoUsuario, $nome, $email, $telefone, $endereco, $login, $codigoImagem);
        }
        public function jsonSerialize() {
        #   SE EU TIVESSE QUE ME APAIXONAR POR ALGO
        #   SERIA POR ESSA FUNÇÃO E COMO AMANTE EU TERIA ESSA INTERFACE (JsonSerializable)
            return get_object_vars($this);
        }   
    }
?>
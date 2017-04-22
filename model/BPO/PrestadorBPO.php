<?php
    require_once 'configuration/autoload-geral.php';

    class PrestadorBPO extends UsuarioBPO implements JsonSerializable{
        private $dsProfissional;
        private $categorias;

        public function __construct($codigoUsuario, $nome, $email, $telefone, LoginBPO $login, EnderecoBPO $endereco, $dsProfissional, $categorias = null, $codigoImagem, $tokenFcm){
            parent::__construct($codigoUsuario, $nome, $email, $telefone, $endereco, $login, $codigoImagem, $tokenFcm);
            $this->dsProfissional = $dsProfissional;
            $this->categorias = $categorias;
        }
        public function getDescricao(){
            return $this->dsProfissional; 
        }

        public function abrirChat(AnuncianteBPO $u) {
            return Chat::aceitarChat(Chat::abrirChat($this, $u));
        }

        public function getCategorias(){
            return $this->categorias;
        }
        public function jsonSerialize() {
            return get_object_vars($this);
        }  
    }
?>

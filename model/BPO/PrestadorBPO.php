<?php
    require_once 'configuration/autoload-geral.php';

    class PrestadorBPO extends UsuarioBPO implements JsonSerializable{
        private $dsProfissional;
        private $qtAreaDeAlcance;
        private $categorias;

        public function __construct($codigoUsuario, $nome, $email, $telefone, LoginBPO $login, EnderecoBPO $endereco, $dsProfissional, $qtAreaDeAlcance, $categorias = null){
            parent::__construct($codigoUsuario, $nome, $email, $telefone,$endereco,$login);
            $this->dsProfissional = $dsProfissional;
            $this->qtAreaDeAlcance = $qtAreaDeAlcance;
            $this->categorias = $categorias;
            
        }
        public function getDescricao(){
            return $this->dsProfissional; 
        }
        public function getAreaAlcance(){
            return $this->qtAreaDeAlcance;
        }
        public function getCategorias(){
            return $this->categorias;
        }
        public function jsonSerialize() {
        #   SE EU TIVESSE QUE ME APAIXONAR POR ALGO
        #   SERIA POR ESSA FUNÇÃO E COMO AMANTE EU TERIA ESSA INTERFACE (JsonSerializable)
            return get_object_vars($this);
        }  
    }
?>
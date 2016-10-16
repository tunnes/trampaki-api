<?php
    require_once 'usuarioBPO.php';

    class PrestadorBPO extends Usuario{
        private $codigoPrestador;
        private $dsProfissional;
        private $qtAreaDeAlcance;
        
        public function __construct($codigoPrestador, $nome, $email, $telefone, LoginBPO $login, EnderecoBPO $endereco, $dsProfissional, $qtAreaDeAlcance){
            parent::__construct($nome, $email, $telefone,$endereco,$login);
            $this->codigoPrestador = $codigoPrestador;
            $this->dsProfissional = $dsProfissional;
            $this->qtAreaDeAlcance = $qtAreaDeAlcance;
        }
        public function getCodigoPrestador(){
            return $this->codigoPrestador;
        }
        public function getDescricao(){
            return $this->dsProfissional; 
        }
        public function getAreaAlcance(){
            return $this->qtAreaDeAlcance;
        }

    }
?>
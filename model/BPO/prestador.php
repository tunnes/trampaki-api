<?php
    require_once 'usuario.php';

    class Prestador extends Usuario{
        private $dsProfissional;
        private $qtAreaDeAlcance;
        
        public function __construct($nome, $email, $telefone, Endereco $endereco, Login $login, $dsProfissional, $qtAreaDeAlcance){
            parent::__construct($nome, $email, $telefone,$endereco,$login);
            $this->dsProfissional = $dsProfissional;
            $this->qtAreaDeAlcance = $qtAreaDeAlcance;
        }
        public function getDescricaoProfissional(){
            return $this->dsProfissional; 
        }
        public function getAreaAlcance(){
            return $this->qtAreaDeAlcance;
        }

    }
?>
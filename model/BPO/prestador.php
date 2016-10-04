<?php
    require_once 'usuario.php';

    class PrestadorBPO extends Usuario{
        private $dsProfissional;
        private $qtAreaDeAlcance;
        
        public function __construct($nome, $email, $telefone, LoginBPO $login, EnderecoBPO $endereco, $dsProfissional, $qtAreaDeAlcance){
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
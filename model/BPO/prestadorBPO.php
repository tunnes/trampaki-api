<?php
    require_once 'usuarioBPO.php';

    class PrestadorBPO extends Usuario{
        private $dsProfissional;
        private $qtAreaDeAlcance;
        
        public function __construct($codigoUsuario, $nome, $email, $telefone, LoginBPO $login, EnderecoBPO $endereco, $dsProfissional, $qtAreaDeAlcance){
            parent::__construct($codigoUsuario, $nome, $email, $telefone,$endereco,$login);
            $this->dsProfissional = $dsProfissional;
            $this->qtAreaDeAlcance = $qtAreaDeAlcance;
        }
        public function getDescricao(){
            return $this->dsProfissional; 
        }
        public function getAreaAlcance(){
            return $this->qtAreaDeAlcance;
        }

        public function solicitarConexao(AnuncianteBPO $u) {
            return Chat::abrirChat($u);
        }
        public function aceitarConexao(Chat $c) {
            return Chat::aceitarChat($c);
        }

    }
?>

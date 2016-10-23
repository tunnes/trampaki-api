<?php
    require_once ('model/BPO/usuario.php');
    class AnuncianteBPO extends Usuario{
        
        private $codigoAnunciante;
        
        public function __construct($codigoAnunciante, $nome, $email, $telefone, EnderecoBPO $endereco, LoginBPO $login){
            parent::__construct($nome, $email, $telefone, $endereco, $login);
            $this->codigoAnunciante = $codigoAnunciante;
        }

        public function solicitarConexao(PrestadorBPO $u) {
            return Chat::abrirChat($u);
        }
        public function aceitarConexao(Chat $c) {
            return Chat::aceitarChat($c);
        }
    }
?>

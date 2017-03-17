<?php
    require_once 'configuration/autoload-geral.php';
    class AnuncianteBPO extends UsuarioBPO implements JsonSerializable{
        
        private $cd_anuncio_selecionado;
        
        public function __construct($codigoUsuario, $nome, $email, $telefone, EnderecoBPO $endereco, LoginBPO $login, $codigoImagem, $cd_anuncio_selecionado, $tokenFcm){
            parent::__construct($codigoUsuario, $nome, $email, $telefone, $endereco, $login, $codigoImagem, $tokenFcm);
            $this->cd_anuncio_selecionado = $cd_anuncio_selecionado;
        }
        
        public function getCodigoAnuncioSelecionado(){
            return $this->cd_anuncio_selecionado;
        }
        public function jsonSerialize() {
            return get_object_vars($this);
        }
        
        public function abrirChat(PrestadorBPO $u) {
            return Chat::aceitarChat(Chat::abrirChat($this, $u));
        }
    }
?>

<?php
    require_once 'configuration/autoload-geral.php';
    
    class NotificaAnuncioAceito implements Notificacao{
        private $index = 'anuncios_aceitos';

        public function notificar($id, $ultimoAcesso){
            $aceitos = AnuncioDAO::getInstance()->anunciosAceitos($id,$ultimoAcesso);
            if ($aceitos != null){
                return $aceitos;
            }
            return null;
        }
        public function index(){
            return $this->index;
        }
    }
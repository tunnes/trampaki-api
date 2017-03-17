<?php
    require_once 'configuration/autoload-geral.php';
    
    class NotificaAnuncioNegado implements Notificacao{
        private $index= "anuncios_negados";
        public function notificar($id, $info){
            return null;
        }
        public function index(){
            return $this->index;
        }
    }
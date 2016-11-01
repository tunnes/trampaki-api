<?php
    require_once 'configuration/autoload-geral.php';
    
    class CarregarImagem{
        public function __construct(){
            $_GET['param'] != null ? $this->responseImage(strip_tags($_GET['param'])) : null;
        }
        private function responseImage($cd_imagem){
           header( "Content-type: image/gif");
           $response = ImagemDAO::getInstance()->consultar($cd_imagem);
           echo $response;
        }
    }
?>
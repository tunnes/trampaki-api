<?php
    require_once 'configuration/autoload-geral.php';
    
    class CarregarAnuncio{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'GET'? $this->validarGET() : null;
        }
        private function validarGET(){
            $IO = ValidacaoIO::getInstance();
            $es = array();
            $es = $IO->validarAnuncio($es, $_GET["param"]);
            $es ? $IO->retornar400($es) : $this->retornar200();
        }

        private function retornar200(){
            header('HTTP/1.1 200 OK');
            header('Content-type: application/json');
            $response = AnuncioDAO::getInstance()->consultarAnuncio($_GET["param"]);
            echo json_encode($response);    
        }
    }
?>

<?php
    require_once 'configuration/autoload-geral.php';
    
    class CarregarAnuncio{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'GET'? $this->validarToken() : null;
        }
        private function validarToken(){
            $prestadorBPO = LoginDAO::getInstance()->gerarAutenticacao(apache_request_headers()['authorization']);
            $prestadorBPO instanceof PrestadorBPO ? $this->validarGET() : header('HTTP/1.1 401 Unauthorized');
        }
        private function validarGET(){
            $IO = ValidacaoIO::getInstance();
            $es = array();
            $es = $IO->validarPrestador($es, $_GET["param"]);
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

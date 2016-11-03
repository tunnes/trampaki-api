<?php
    require_once 'configuration/autoload-geral.php';
    
    class CarregarMeusServicos{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'GET'? $this->validarToken() : header('HTTP/1.1 400 Bad Request');
        }
        private function validarToken(){
            $prestadorBPO = LoginDAO::getInstance()->gerarAutenticacao(apache_request_headers()['authorization']);
            $prestadorBPO instanceof PrestadorBPO ? $this->retornar200($prestadorBPO) : header('HTTP/1.1 401 Unauthorized');
        }
        private function retornar200($prestadorBPO){
            header('HTTP/1.1 200 OK');
            header('Content-type: application/json');
            $response = PrestadorDAO::getInstance()->meusServicos($prestadorBPO);
            echo json_encode($response);    
        }
    }
?>

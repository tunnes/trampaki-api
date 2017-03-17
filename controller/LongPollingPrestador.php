<?php
    require_once 'configuration/autoload-geral.php';
    
    class LongPollingPrestador{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'GET' ? $this->validarToken() : null; 
        }
        private function validarToken(){
            $PrestadorBPO = LoginDAO::getInstance()->gerarAutenticacao(apache_request_headers()['authorization']);
            $PrestadorBPO instanceof PrestadorBPO ? $this->validarPOST($PrestadorBPO) : header('HTTP/1.1 401 Unauthorized');
        }
        
        private function retornar200($prestadorBPO){
            header('HTTP/1.1 200 OK');
            header('Content-type: application/json');
            $response = PrestadorDAO::getInstance()->meusServicos($prestadorBPO);
            echo json_encode($response);    
        }
    } 
    
?>
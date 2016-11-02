<?php
    require_once 'configuration/autoload-geral.php';

    class CarregarDadosPrestador{
        public function __construct(){
        #   Verificação de metodo da requisição:
            $_SERVER['REQUEST_METHOD'] == 'GET'? $this->validarToken() : header('HTTP/1.1 400 Bad Request');
        }
        private function validarToken(){
            $prestadorBPO = LoginDAO::getInstance()->gerarAutenticacao(apache_request_headers()['authorization']);
            $prestadorBPO instanceof PrestadorBPO ? $this->retornar200($prestadorBPO) : header('HTTP/1.1 401 Unauthorized');
        }
        private function retornar200($prestadorBPO){
            $prestadorBPO  = PrestadorDAO::getInstance()->consultar($prestadorBPO->getCodigoUsuario());
            header('HTTP/1.1 200 OK');
            header('Content-type: application/json');
            echo json_encode($prestadorBPO);
        }
    }
?>

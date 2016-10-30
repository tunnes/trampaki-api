<?php
    require_once 'configuration/autoload-geral.php';

    class CarregarAnuncios{
        public function __construct(){
        #   Verificação de metodo da requisição:
            $_SERVER['REQUEST_METHOD'] == 'GET' ? $this->validarToken() : null;
        }
        private function validarToken(){
            $this->retornar200();
            // $prestadorBPO = LoginDAO::getInstance()->gerarAutenticacao(apache_request_headers()['authorization']);
            // $prestadorBPO instanceof PrestadorBPO ? $this->retornar200() : header('HTTP/1.1 401 Unauthorized');
        }
        private function retornar200(){
            header('HTTP/1.1 200 OK');
            header('Content-type: application/json');
            $response = AnuncioDAO::getInstance()->consultarTodos();
            echo json_encode($response);
        }
    }
?>

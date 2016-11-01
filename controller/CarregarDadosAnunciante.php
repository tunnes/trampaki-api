<?php
    require_once 'configuration/autoload-geral.php';

    # Este controller tem como função quado o proprio usuario for consultar informações relativas ao mesmo.
    class CarregarDadosAnunciante{
        public function __construct(){
        #   Verificação de metodo da requisição:
            $_SERVER['REQUEST_METHOD'] == 'GET' ? $this->validarToken() : header('HTTP/1.1 400 Bad Request');
        }
        private function validarToken(){
            $anuncianteBPO = LoginDAO::getInstance()->gerarAutenticacao(apache_request_headers()['authorization']);
            $anuncianteBPO instanceof AnuncianteBPO ? $this->retornar200($anuncianteBPO) : header('HTTP/1.1 401 Unauthorized');
        }
        private function retornar200($anuncianteBPO){
            header('HTTP/1.1 200 OK');
            header('Content-type: application/json');
            echo json_encode($anuncianteBPO);
        }
    }
?>

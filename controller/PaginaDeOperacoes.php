<?php
    class PaginaDeOperacoes{
        public function __construct(){
        #   Verificação de metodo da requisição:
            //$_SERVER['REQUEST_METHOD'] == 'GET' ? $this->validarToken() : null;
            readfile('view/painel-prestador.html');
        }
        private function validarToken(){
            $prestadorBPO = LoginDAO::getInstance()->gerarAutenticacao(apache_request_headers()['authorization']);
            // echo apache_request_headers()['authorization'];
            $prestadorBPO instanceof PrestadorBPO ? $this->retornar200() : header('HTTP/1.1 401 Unauthorized');
        }
        private function retornar200(){
            header('HTTP/1.1 200 OK');
            include('/view/painel-prestador.html');
        }
    }
?>
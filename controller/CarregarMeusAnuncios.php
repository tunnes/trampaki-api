<?php
    require_once 'configuration/autoload-geral.php';

    class CarregarMeusAnuncios{
        public function __construct(){
        #   Verificação de metodo da requisição:
            $_SERVER['REQUEST_METHOD'] == 'GET' ? $this->validarToken() : null;
        }
        private function validarToken(){
            $anuncianteBPO = LoginDAO::getInstance()->gerarAutenticacao(apache_request_headers()['authorization']);
            $anuncianteBPO instanceof AnuncianteBPO ? $this->carregarMeusAnuncios($anuncianteBPO) : header('HTTP/1.1 401 Unauthorized');
        }
        private function carregarMeusAnuncios($anuncianteBPO){
            header('HTTP/1.1 200 OK');
            header('Content-type: application/json');
            echo json_encode(AnuncioDAO::getInstance()->listarMeusAnuncios($anuncianteBPO->getCodigoUsuario()));
        }
    }
?>

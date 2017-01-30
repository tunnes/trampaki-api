<?php
    require_once 'configuration/autoload-geral.php';

    class CarregarDadosPrestador{
        public function __construct(){
        #   Verificação de metodo da requisição:
            $_SERVER['REQUEST_METHOD'] == 'GET' ? $this->validarToken() : null;
        }
        private function validarToken(){
            $prestadorBPO = LoginDAO::getInstance()->gerarAutenticacao(apache_request_headers()['authorization']);
            // echo json_encode($prestadorBPO);
            $prestadorBPO instanceof AnuncianteBPO ? print('sim') : print('nao'); 
            // $prestadorBPO instanceof PrestadorBPO ? $this->retornar200($prestadorBPO) : header('HTTP/1.1 401 Unauthorized');
        }
        private function retornar200($prestadorBPO){
            $prestadorBPO  = PrestadorDAO::getInstance()->consultar($prestadorBPO->getCodigoUsuario());
            header('HTTP/1.1 200 OK');
            header('Content-type: application/json');
            echo json_encode($prestadorBPO);
        }
    }
?>

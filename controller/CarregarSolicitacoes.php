<?php
    require_once 'configuration/autoload-geral.php';
    
    class CarregarSolicitacoes{
        public function __construct(){
            
            $_SERVER['REQUEST_METHOD'] == 'GET' ? $this->validarHeader() : null; 
        }
        private function validarHeader(){
            switch (apache_request_headers()['trampakiuser']) {
                case 0:
                    $anuncianteBPO = LoginDAO::getInstance()->gerarAutenticacao(apache_request_headers()['authorization']);
                    $anuncianteBPO instanceof AnuncianteBPO ? $this->responseAnunciante($anuncianteBPO) : header('HTTP/1.1 401 Unauthorized');
                    break;
                case 1:
                    
                    $prestadorBPO = LoginDAO::getInstance()->gerarAutenticacao(apache_request_headers()['authorization']);
                    $prestadorBPO instanceof PrestadorBPO ? $this->responsePrestador($prestadorBPO) : header('HTTP/1.1 401 Unauthorized');
                    break;
                
                default:
                    header('HTTP/1.1 400 Bad Request');
                    break;
            }
        }

        private function responseAnunciante($anuncianteBPO){
            $anuncianteDAO = AnuncianteDAO::getInstance();
            $response = $anuncianteDAO->carregarSolicitacoes($anuncianteBPO);
            echo json_encode($response); 
        }
        private function responsePrestador($prestadorBPO){
            $prestadorDAO = PrestadorDAO::getInstance();
            $response = $prestadorDAO->carregarSolicitacoes($prestadorBPO);
            echo json_encode($response);
        }
    }
?>
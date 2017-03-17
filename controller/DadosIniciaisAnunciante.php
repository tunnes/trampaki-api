<?php
    require_once 'configuration/autoload-geral.php';
    
    class DadosIniciaisAnunciante{
        
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'GET' ? $this->validarToken() : null; 
        }
        
        private function validarToken(){
            $anuncianteBPO = LoginDAO::getInstance()->gerarAutenticacao(apache_request_headers()['authorization']);
            $anuncianteBPO instanceof AnuncianteBPO ? $this->carregarDados($anuncianteBPO->getCodigoUsuario()) : header('HTTP/1.1 401 Unauthorized');
        }
        
        private function carregarDados($id){
            $anunciante = AnuncianteDAO::getInstance();
            echo json_encode(array(
                'ultimo_anuncio_aceito' => $anunciante->ultimoAnuncioAceito($id)
            ));
            header('HTTP/1.1 200 OK');
            header('Content-type: application/json');
        }
    }
?>
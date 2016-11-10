<?php
    require_once 'configuration/autoload-geral.php';
    
    class NovaConexaoPrestador{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->validarToken() : null; 
        }
        private function validarToken(){
            $prestadorBPO = LoginDAO::getInstance()->gerarAutenticacao(apache_request_headers()['authorization']);
            $prestadorBPO instanceof PrestadorBPO ? $this->validarPOST($prestadorBPO) : header('HTTP/1.1 401 Unauthorized');
        }
        private function validarPOST($prestadorBPO){
            $IO = ValidacaoIO::getInstance();
            $es = array();
            
            $es = $IO->validarConsisten($es, $_POST["codigo_anuncio"]);
            $es = $IO->validarAnuncio($es, $_POST["codigo_anuncio"]);
            $es ? $IO->retornar400($es) : $this->retornar201($prestadorBPO);
        }
        private function retornar201($prestadorBPO){
            $prestadorDAO = PrestadorDAO::getInstance();
            $prestadorDAO->novaConexao($prestadorBPO, $_POST['codigo_anuncio']);
            header('HTTP/1.1 201 Created');          
        }
    }
?>    
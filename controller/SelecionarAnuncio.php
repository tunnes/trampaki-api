<?php
    require_once 'configuration/autoload-geral.php';
    
    class SelecionarAnuncio{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->validarToken() : null; 
        }
        private function validarToken(){
            $anuncianteBPO = LoginDAO::getInstance()->gerarAutenticacao(apache_request_headers()['authorization']);
            $anuncianteBPO instanceof AnuncianteBPO ? $this->validarPOST($anuncianteBPO) : header('HTTP/1.1 401 Unauthorized');
        }
        private function validarPOST($anuncianteBPO){
        #   Variável que conterá informações relativas ao erro de validação:
            $IO = ValidacaoIO::getInstance();
            $es = array();
            $ps = $_POST;

        #   Validar codigo de anuncio fornecido:                    
            $es = $IO->validarDonoAnuncio($es, $anuncianteBPO->getCodigoUsuario(), $ps['codigoAnuncio']);
        
        #   Se existir algum erro, mostra o erro
            $es ? $IO->retornar400($es) : $this->retornar200($anuncianteBPO, $ps);
        }
        private function retornar200($anuncianteBPO, $ps){
            $anuncianteDAO = AnuncianteDAO::getInstance();
            $anuncianteDAO->selecionarAnuncio($anuncianteBPO->getCodigoUsuario(), $ps['codigoAnuncio']);
            header('HTTP/1.1 200 OK');         
        }
    }
?>
<?php
    require_once 'configuration/autoload-geral.php';
    
    class CarregarEnvolvidos{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'GET' ? $this->validarToken() : null; 
        }
        private function validarToken(){
            $anuncianteBPO = LoginDAO::getInstance()->gerarAutenticacao(apache_request_headers()['authorization']);
            $anuncianteBPO instanceof AnuncianteBPO ? $this->validarPOST($anuncianteBPO) : header('HTTP/1.1 401 Unauthorized');
        }
        private function validarPOST($anuncianteBPO){
        #   Variável que conterá informações relativas ao erro de validação:
            $IO = ValidacaoIO::getInstance();
            $es = array();
        
        #   Validar codigo de anuncio fornecido:                    
            $es = $IO->validarAnuncio($es, $_GET["param"]);          
            $es = $IO->validarDonoAnuncio($es, $anuncianteBPO->getCodigoUsuario(), $_GET["param"]);
                            
        #   Se existir algum erro, mostra o erro
            $es ? $IO->retornar400($es) : $this->retornar200();

        }
        private function retornar200(){
            $AnuncioDAO = AnuncioDAO::getInstance();
            echo json_encode($AnuncioDAO->carregarEnvolvidos($_GET["param"]));
            header('Content-type: application/json');
            header('HTTP/1.1 200 OK');       
        }
    }
?>
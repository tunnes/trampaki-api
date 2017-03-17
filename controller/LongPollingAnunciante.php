<?php
    require_once 'configuration/autoload-geral.php';
    
    class LongPollingAnunciante extends LongPolling{
        
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'GET' ? $this->validarToken() : null; 
        }
        
        private function validarToken(){
            $anuncianteBPO = LoginDAO::getInstance()->gerarAutenticacao(apache_request_headers()['authorization']);
            $anuncianteBPO instanceof AnuncianteBPO ? $this->polling($anuncianteBPO->getCodigoUsuario()) : header('HTTP/1.1 401 Unauthorized');
        }
        
        public function polling($id){
            $ultimoAcesso = apache_request_headers()['ultimo_anuncio_aceito'];
            $this->addCanal(new Canal($id, $ultimoAcesso, array(new NotificaAnuncioAceito,
                                                                new NotificaAnuncioNegado)));
            while($this->canal()->estaAusente()){
                $this->canal()->ouvir();
                sleep($this->descanso());
            }

            $this->retornar200();
        }
        
        private function retornar200(){
            header('HTTP/1.1 200 OK');
            echo $this->canal()->retorno();    
        }
    } 
    
?>
<?php
    require_once 'configuration/autoload-geral.php';
    
    class NovaConexaoAnunciante{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->validarToken() : null; 
        }
        private function validarToken(){
            $anuncianteBPO = LoginDAO::getInstance()->gerarAutenticacao(apache_request_headers()['authorization']);
            $anuncianteBPO instanceof AnuncianteBPO ? $this->validarPOST($anuncianteBPO): header('HTTP/1.1 401 Unauthorized');
        }
        private function validarPOST($anuncianteBPO){
        #   Variável que conterá informações relativas ao erro de validação:
            $IO = ValidacaoIO::getInstance();
            $es = array();
            $ps = $_POST;
        
        #   Validar codigo de anuncio fornecido:                    
            $es = $IO->validarPrestador($es, $ps['codigo_prestador']);   
            
            $es = $IO->validarDonoAnuncio($es, $anuncianteBPO->getCodigoUsuario(), $ps['codigo_anuncio']);
            $es = $IO->redundanciaConexao($es, $ps['codigo_prestador'], $_POST["codigo_anuncio"]);                      
        
        #   Se existir algum erro, mostra o erro
            $es ? $IO->retornar400($es) : $this->retornar200($ps, $anuncianteBPO);

        }
        private function retornar200($ps, $anuncianteBPO){
            $anuncianteDAO = AnuncianteDAO::getInstance();
            $anuncianteDAO->novaConexao($ps['codigo_prestador'], $ps['codigo_anuncio'], '0');
            $anuncio = AnuncioDAO::getInstance()->consultarAnuncio($ps['codigo_anuncio']);
            $tokenFcm = $anuncianteDAO->getTokenFcm($ps['codigo_prestador']);
            $notificacao = new NotificacaoFirebase($tokenFcm, "Nova conexão - ". $anuncio->getTitulo(),
                                                   $anuncianteBPO->getNome() . " solicitou uma conexão para o seu anuncio.", 
                                                   $anuncianteBPO->getCodigoImagem(), "/painel-prestador", "default",
                                                   $anuncianteBPO->getCodigoUsuario());
            $notificacao->enviar();
            header('HTTP/1.1 201 Created');
        }
    }
?>
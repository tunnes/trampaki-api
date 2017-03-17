<?php
    require_once 'configuration/autoload-geral.php';
    
    class AceitarConexao{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'PUT' ? $this->validarHeader() : null; 
        }
        private function validarHeader(){
            $usuario = LoginDAO::getInstance()->gerarAutenticacao(apache_request_headers()['authorization']);
            $usuario instanceof PrestadorBPO || $usuario instanceof AnuncianteBPO ? $this->validarResquest($usuario) : header('HTTP/1.1 401 Unauthorized');
            
        }
        private function validarResquest($usuario){
            $IO = ValidacaoIO::getInstance();
            $es = array();
            parse_str(file_get_contents("php://input"),$post_vars);
            $ps = $post_vars;
            $es = $IO->conexaoExistente($es, $ps['cd_conexao']);
            $codigoSolicitante = AnuncianteDAO::getInstance()->consultarConexao($ps['cd_conexao'])->cd_solicitante;
            if(($usuario instanceof PrestadorBPO && $codigoSolicitante == 0) || ($usuario instanceof AnuncianteBPO && $codigoSolicitante == 1)){
                $es ? $IO->retornar400($es) : $this->response200($ps['cd_conexao'], $usuario);
            }else{
                header('HTTP/1.1 401 Unauthorized');
            }
        }
        private function response200($conexao, $usuario){
            PrestadorDAO::getInstance()->aceitarConexao($conexao);
            $anuncio =  AnuncioDAO::getInstance();
            if($usuario instanceof PrestadorBPO){
                $cdUsuario = $anuncio->getCodigoAnunciante($conexao);
                $caminho   = "/painel-anunciante";
            }else{
                $cdUsuario = $anuncio->getCodigoPrestador($conexao);
                $caminho   = "/painel-prestador";
            }
            
            $tokenFcm = PrestadorDAO::getInstance()->getTokenFcm($cdUsuario);
            $notificacao = new NotificacaoFirebase($tokenFcm, "Conexão aceita - ". $anuncio->getTituloPorConexao($conexao),
                                                   $usuario->getNome() . " aceitou a sua conexão.", 
                                                   $usuario->getCodigoImagem(), $caminho, "default",
                                                   $usuario->getCodigoUsuario());
            $notificacao->enviar();
            header('HTTP/1.1 200 Ok');
        }
    }
?>

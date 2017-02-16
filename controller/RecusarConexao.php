<?php
    require_once 'configuration/autoload-geral.php';
    
    class RecusarConexao{
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
            if($usuario instanceof PrestadorBPO && $codigoSolicitante == 0){
                $es ? $IO->retornar400($es) : $this->response200($ps['cd_conexao']);
                
            }elseif($usuario instanceof AnuncianteBPO && $codigoSolicitante == 1){
                $es ? $IO->retornar400($es) : $this->response200($ps['cd_conexao']);
                
            }else{
                header('HTTP/1.1 401 Unauthorized');
            }
        }
        private function response200($codigoConexao){
            PrestadorDAO::getInstance()->recusarConexao($codigoConexao);
            header('HTTP/1.1 200 Ok');
        }
    }
?>
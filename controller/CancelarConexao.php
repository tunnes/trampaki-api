<?php
    require_once 'configuration/autoload-geral.php';
    
    class CancelarConexao{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'DELETE' ? $this->validarHeader() : null; 
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
            $conexao = AnuncianteDAO::getInstance()->consultarConexao($ps['cd_conexao']);
            
            if($usuario instanceof PrestadorBPO && $conexao->cd_solicitante == 1 && $conexao->cd_status == 0){
                $IO->conexaoPrestador($es, $ps['cd_conexao'], $usuario->getCodigoUsuario());
                $es ? $IO->retornar400($es) : $this->response200($ps['cd_conexao']);
                
            }elseif($usuario instanceof AnuncianteBPO && $conexao->cd_solicitante == 0 && $conexao->cd_status == 0){
                $IO->conexaoAnunciante($es, $ps['cd_conexao'], $usuario->getCodigoUsuario());
                $es ? $IO->retornar400($es) : $this->response200($ps['cd_conexao']);
                
            }else{
                header('HTTP/1.1 401 Unauthorized');
            }
        }
        private function response200($codigoConexao){
            PrestadorDAO::getInstance()->excluirConexao($codigoConexao);
            header('HTTP/1.1 205 Reset Content');
        }
    }
?>
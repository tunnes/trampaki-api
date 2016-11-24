<?php
    require_once 'configuration/autoload-geral.php';
    
    class AceitarConexao{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'PUT' ? $this->validarHeader() : null; 
        }
        private function validarHeader(){
        #   Necessario analisar solucções para o possivel problema de segurança neste controller.
            $usuario = LoginDAO::getInstance()->gerarAutenticacao(apache_request_headers()['authorization']);
            $usuario instanceof PrestadorBPO || $usuario instanceof AnuncianteBPO ? $this->response200() : header('HTTP/1.1 401 Unauthorized');   
        }
        private function response200(){
            parse_str(file_get_contents("php://input"),$post_vars);
            $codigoConexao = $post_vars['cd_conexao'];
            PrestadorDAO::getInstance()->aceitarConexao($codigoConexao);
        }
    }
?>
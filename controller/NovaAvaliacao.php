<?php
    require_once 'configuration/autoload-geral.php';
    
    class NovaAvaliacao{
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
            $es = $IO->validarPrestador($es, $ps['codigo_prestador']);
            $es = $IO->conexaoExistente($es, $ps['codigo_conexao']);
            $es = $IO->validarNota($es, $ps['nota_servico']);
            $es = $IO->validarNota($es, $ps['nota_valor']);
            
        #   Se existir algum erro, mostra o erro
            $es ? $IO->retornar400($es) : $this->retornar200($ps);
        }
        private function retornar200($ps){
            $anuncianteDAO = AnuncianteDAO::getInstance();
            $anuncianteDAO->novaAvaliacao($ps['codigo_prestador'], $ps['codigo_conexao'], $ps['nota_servico'], $ps['nota_valor'], $ps['descricao']);
            header('HTTP/1.1 201 Created');
        }
    }
?>
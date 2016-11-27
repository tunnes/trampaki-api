<?php
#   Classe para gerenciamento de rotas da aplicação.
    class Router{
        
        private $arrayRotas = array();
        private $arrayAcoes = array();
        
        public function novaRota($url, $acao){
            $this->arrayRotas[] = trim($url,'/');
            $this->arrayAcoes[] = $acao;
        }
        public function rotear(){
            $url = $_GET['url'];
            $chave = array_search($url, $this->arrayRotas);
            $chave === false ? header('HTTP/1.1 404 Not Found') : new  $this->arrayAcoes[$chave]();
        }
    }
?>

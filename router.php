<?php
#   Classe para gerenciamento de rotas da aplicação.
    class Router{
        
        private $arrayRotas = array();
        private $arrayAcoes = array();
        
        public function novaRota($url, $acao){
            $this -> arrayRotas[] = trim($url,'/');
            $this -> arrayAcoes[] = $acao;
        }
        public function mapa(){
            echo '<pre>';
            print_r($this->arrayRotas);
            print_r($this->arrayAcoes);
        }
        
        public function rotear(){
            $url = $_GET['url'];
        #   echo 'URL: ' .$url;
            
            $chave = array_search($url, $this->arrayRotas);
            if($chave === false){
                echo '<hr> Rota não encontrada';
            }else{
            #   echo $this -> arrayAcoes[$chave];
                new  $this -> arrayAcoes[$chave]();
                
            }
        }
    }
?>
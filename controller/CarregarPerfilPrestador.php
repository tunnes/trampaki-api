<?php
    require_once 'configuration/autoload-geral.php';

    class CarregarPerfilPrestador{
        public  function __construct(){
        #   Verificação de metodo da requisição:
            $_SERVER['REQUEST_METHOD'] == 'GET' ? $this->validarGET() : null;
        }
        private function validarGET(){
            $IO = ValidacaoIO::getInstance();
            $es = array();
            $es = $IO->validarPrestador($es, $_GET["param"]);
            $es ? $IO->retornar400($es) : $this->retornar200();
        }        
        private function retornar200(){
            $prestadorDAO = PrestadorDAO::getInstance();
            header('HTTP/1.1 200 OK');
            header('Content-type: application/json');
            echo json_encode($prestadorDAO->consultarPerfil($_GET["param"]));
        }
    }
?>

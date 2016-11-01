<?php
    require_once 'configuration/autoload-geral.php';

    class CarregarCategorias{
        public function __construct(){
        #   Verificação de metodo da requisição:
            $_SERVER['REQUEST_METHOD'] == 'GET' ? $this->response200() : header('HTTP/1.1 400 Bad Request');
        }
        private function response200(){
            header('HTTP/1.1 200 OK');
            header('Content-type: application/json');
            $response = CategoriaDAO::getInstace()->consultarTodas();
            echo json_encode($response);
        }
    }
?>

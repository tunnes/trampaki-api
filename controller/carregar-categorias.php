<?php
    session_start();
    require_once("model/DAO/anuncioDAO.php");

    class CarregarCategorias{
        public function __construct(){
        #   Verificação de metodo da requisição:
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->responsePOST() : $this->responseGET();
        }
        private function responsePOST(){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL = $bancoDeDados->prepare("select * from categoria");
            $comandoSQL->execute();
            $janta = $comandoSQL->fetchAll(PDO::FETCH_ASSOC);
            header('Content-type: application/json');
            echo json_encode($janta);
        }
        private function responseGET(){
            include('view/pagina-404.html');
        }
    }
?>

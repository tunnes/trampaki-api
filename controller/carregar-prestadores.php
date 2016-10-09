<?php
    session_start();
    require_once("model/DAO/prestadorDAO.php");

    class CarregarPrestadores{
        public  function __construct(){
        #   Verificação de metodo da requisição:
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->responsePOST() :  include('view/pagina-404.html');
        }
        private function responsePOST(){
            switch ($_SESSION['tipoUsuario']){
                    case 0:
                    case 2:
                        $this->carregarPrestadores();
                        break;
                    case 1:
                        echo 'voce não possui privilegio para isto malandrãoo!';
                        break;
                    default:
                        header('Location: login');  
                        break;
            }
        }
        private function carregarPrestadores(){
            $prestadorDAO = PrestadorDAO::getInstance();
            $response = json_encode($prestadorDAO->listarPrestadores());
            header('Content-type: application/json');
            echo $response;
        }
    }
?>

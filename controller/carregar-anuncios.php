<?php
    session_start();
    require_once("model/DAO/anuncioDAO.php");

    class CarregarAnuncios{
        public function __construct(){
        #   Verificação de metodo da requisição:
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->responsePOST() : $this->responseGET();
        }
        private function carregarAnuncios(){
            $anuncioDAO = AnuncioDAO::getInstance();
            $response = $anuncioDAO->listarAnuncios();
            header('Content-type: application/json');
            echo json_encode($response);
        }
        private function responsePOST(){
            switch ($_SESSION['tipoUsuario']){
                    case 1:
                    case 2:
                        $this->carregarAnuncios(); break;
                        break;
                    case 0:
                        echo 'voce não possui privilegio para isto malandrãoo!';
                        break;
                    default:
                        header('Location: login');  
                        break;
            }
        }
        private function responseGET(){
            include('view/pagina-404.html');
        }
    }
?>

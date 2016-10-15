<?php
    session_start();
    require_once("model/DAO/anuncioDAO.php");

    class CarregarAnuncio{
        public function __construct(){
        #   Verificação de metodo da requisição:
            $_SERVER['REQUEST_METHOD'] == 'GET' && is_numeric($_POST["codigoAnuncio"]) ? $this->validarSessao() : include('view/pagina-404.html');
        }
        private function validarSessao(){
            switch ($_SESSION['tipoUsuario']){
                    case '1' || '2':
                        $this->carregarAnuncio($_POST["codigoAnuncio"]);
                        break;
                    case '0':
                        echo 'voce não possui privilegio para isto malandrãoo!';
                        break;
                    default:
                        header('Location: login');  
                        break;
            }
        }
        private function carregarAnuncio($codigoAnuncio){
            $anuncioDAO = AnuncioDAO::getInstance();
            $response = $anuncioDAO->consultarAnuncio($codigoAnuncio);
            header('Content-type: application/json');
            echo json_encode($response);
        }
    }
?>

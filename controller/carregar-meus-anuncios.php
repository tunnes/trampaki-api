<?php
    session_start();
    require_once("model/BPO/anuncianteBPO.php");
    require_once("model/DAO/anuncianteDAO.php");

    class CarregarMeusAnuncios{
        public function __construct(){
        #   Verificação de metodo da requisição:
            $_SERVER['REQUEST_METHOD'] == 'GET' ? $this->validarSessao() : include('view/pagina-404.html');
        }
        private function validarSessao(){
            switch ($_SESSION['tipoUsuario']){
                    case 0:
                    case 2:
                        $this->carregarMeusAnuncios();
                        break;
                    case 1:
                        echo 'voce não possui privilegio para isto malandrãoo!';
                        break;
                    default:
                        header('Location: login');  
                        break;
            }
        }
        private function carregarMeusAnuncios(){
            $anuncianteBPO = unserialize($_SESSION['objetoUsuario']);
            $anuncioDAO = AnuncioDAO::getInstance();
            $response = $anuncioDAO->listarMeusAnuncios($anuncianteBPO->getCodigoUsuario());
            header('Content-type: application/json');    
            echo json_encode($response);
        }
    }
?>

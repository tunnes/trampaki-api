<?php
    session_start();
    require_once("model/BPO/anunciante.php");
    require_once("model/DAO/anuncianteDAO.php");

    class CarregarDadosAnunciante{
        public function __construct(){
        #   Verificação de metodo da requisição:
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->validarSessao() : include('view/pagina-404.html');
        }
        private function validarSessao(){
            switch ($_SESSION['tipoUsuario']){
                    case 0:
                    case 2:
                        $this->carregarDadosAnunciante();
                        break;
                    case 1:
                        echo 'voce não possui privilegio para isto malandrãoo!';
                        break;
                    default:
                        header('Location: login');  
                        break;
            }
        }
        private function carregarDadosAnunciante(){
            $anuncianteBPO = unserialize($_SESSION['objetoUsuario']);
            $anuncianteDAO = AnuncianteDAO::getInstance();
            $response = $anuncianteDAO->carregarPerfil($anuncianteBPO->getCodigoAnunciante());
            header('Content-type: application/json');    
            echo json_encode($response);
        }
    }
?>

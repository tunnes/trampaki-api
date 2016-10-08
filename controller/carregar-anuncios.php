<?php
    session_start();
    require_once("model/BPO/prestador.php");
    require_once("model/DAO/anuncioDAO.php");

    class CarregarAnuncios{
        public function __construct(){
            #   Verificação do tipo de usuario:
            switch ($_SESSION['tipoUsuario']){
                case '1' || '2':
                    $this->semIdeiaProNome(); break;
                case '0':
                    echo 'voce não possui privilegio para isto malandrãoo!';
                    break;
                default:
                    header('Location: login');    
                    break;
            }
        }
        private function semIdeiaProNome(){
            $anuncioDAO = AnuncioDAO::getInstance();
            $response = $anuncioDAO->listarAnuncios();
            header('Content-type: application/json');
            echo json_encode($response);
        }
    }
?>

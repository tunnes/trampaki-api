<?php
    session_start();
    require_once("model/BPO/prestador.php");
    require_once("model/DAO/prestadorDAO.php");

    class CarregarDadosPrestador{
        public function __construct(){
        #   Verificação de metodo da requisição:
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->validarSessao() : include('view/pagina-404.html');
        }
        private function validarSessao(){
            switch ($_SESSION['tipoUsuario']){
                    case '1' || '2':
                        $this->carregarDadosPrestador();
                        break;
                    case '0':
                        echo 'voce não possui privilegio para isto malandrãoo!';
                        break;
                    default:
                        header('Location: login');  
                        break;
            }
        }
        private function carregarDadosPrestador(){
            $prestadorBPO = unserialize($_SESSION['objetoUsuario']);
            $prestadorDAO = PrestadorDAO::getInstance();
            $response = $prestadorDAO->carregarPerfil($prestadorBPO->getCodigoPrestador());
            header('Content-type: application/json');    
            echo json_encode($response);
        }
    }
?>

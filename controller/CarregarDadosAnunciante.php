<?php
    session_start();
    require_once 'configuration/autoload-geral.php';

    # Este controller tem como função quado o proprio usuario for consultar informações relativas ao mesmo.
    class CarregarDadosAnunciante{
        public function __construct(){
        #   Verificação de metodo da requisição:
            $_SERVER['REQUEST_METHOD'] == 'GET' ? $this->validarSessao() : include('view/pagina-404.html');
        }
        private function validarSessao(){
            switch ($_SESSION['tipoUsuario']){
                    case 0:
                    case 2:
                        $this->retornar200();
                        break;
                    case 1:
                        echo 'voce não possui privilegio para isto malandrãoo!';
                        break;
                    default:
                        header('Location: login');  
                        break;
            }
        }
        private function retornar200(){
            $anuncianteBPO  = unserialize($_SESSION['objetoUsuario']);
            $anuncianteBPO  = AnuncianteDAO::getInstance()->consultar($anuncianteBPO->getCodigoUsuario(), 'perfil');
            header('HTTP/1.1 200 OK');
            header('Content-type: application/json');
            echo json_encode($anuncianteBPO);
        }
    }
?>

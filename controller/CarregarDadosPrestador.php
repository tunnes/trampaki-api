<?php
    session_start();
    require_once 'configuration/autoload-geral.php';

    class CarregarDadosPrestador{
        public function __construct(){
        #   Verificação de metodo da requisição:
            $_SERVER['REQUEST_METHOD'] == 'GET' ? $this->validarSessao() : include('view/pagina-404.html');
        }
        private function validarSessao(){
            switch ($_SESSION['tipoUsuario']){
                    case 2:
                    case 1:
                        $this->retornar200();
                        break;
                    case 0:
                        echo 'voce não possui privilegio para isto malandrãoo!';
                        break;
                    default:
                        header('Location: login');  
                        break;
            }
        }
        private function retornar200(){
            $prestadorBPO  = unserialize($_SESSION['objetoUsuario']); 
            $prestadorBPO  = PrestadorDAO::getInstance()->consultar($prestadorBPO->getCodigoUsuario());
            header('HTTP/1.1 200 OK');
            header('Content-type: application/json');
            echo json_encode($prestadorBPO);
        }
    }
?>

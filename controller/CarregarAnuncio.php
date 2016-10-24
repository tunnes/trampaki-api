<?php
    session_start();
    require_once 'configuration/autoload-geral.php';
    
    class CarregarAnuncio{
        public function __construct(){
        #   Verificação de metodo da requisição:
            echo "AKI";
            $_SERVER['REQUEST_METHOD'] == 'POST'? $this->validarSessao() : include('view/pagina-404.html');
        }
        private function validarSessao(){
            switch ($_SESSION['tipoUsuario']){
                    case 1:
                    case 2:
                        $this->validarPOST();
                        break;
                    case 0:
                        echo 'voce não possui privilegio para isto malandrãoo!';
                        break;
                    default:
                        header('Location: login');  
                        break;
            }
        }
        private function validarPOST(){
            $IO = ValidacaoIO::getInstance();
            $erro = array();
            $erro = $IO->validarConsisten($erro, $_POST["codigoAnuncio"]);
            $erro = $IO->validarAnuncio($erro, $_POST["codigoAnuncio"]);
            $erro ? $IO->retornar400($erro) : $this->retornar200(); 
        }
        private function retornar200(){
            $anuncioDAO = AnuncioDAO::getInstance();
            header('HTTP/1.1 200 OK');
            header('Content-type: application/json');
            $response = $anuncioDAO->consultarAnuncio($_POST["codigoAnuncio"]);
            echo json_encode($response);    
        }
    }
?>

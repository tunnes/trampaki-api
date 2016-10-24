<?php
    require_once 'configuration/autoload-geral.php';
    class NovaConexaoPrestador{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->validarSessao() : null; 
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
            $prestadorBPO = unserialize($_SESSION['objetoUsuario']);
            $prestadorDAO = PrestadorDAO::getInstance();
            $prestadorDAO->novaConexao($prestadorBPO, $_POST['codigoAnuncio']);
            header('HTTP/1.1 200 OK');            
        }
    }
?>    
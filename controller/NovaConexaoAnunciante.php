<?php
    require_once 'configuration/autoload-geral.php';
    
    class NovaConexaoAnunciante{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->validarSessao() : null; 
        }
        private function validarSessao(){
            switch ($_SESSION['tipoUsuario']){
                    case 0:
                    case 2:
                        $this->validarPOST();
                        break;
                    case 1:
                        echo 'voce não possui privilegio para isto malandrãoo!';
                        break;
                    default:
                        header('Location: login');  
                        break;
            }
        }
        private function validarPOST(){
        #   Variável que conterá informações relativas ao erro de validação:
            $IO = ValidacaoIO::getInstance();
            $es = array();
            $ps = $_POST;
        #   Verificando se longitude, latitude são numeros:
            $es = $IO->validarPrestador($es, $ps['codigoPrestador']);            
        
        #   Validar codigo de anuncio fornecido:
            $prestadorBPO = unserialize($_SESSION['objetoUsuario']);                    
            $es = $IO->validarDonoAnuncio($es, $prestadorBPO->getCodigoUsuario(), $ps['codigoAnuncio']);
        
        #   Se existir algum erro, mostra o erro
            $es ? $IO->retornar400($es) : $this->retornar200($ps);
        
        }
        private function retornar200($ps){
            $anuncianteDAO = AnuncioDAO::getInstance();
            $anuncianteDAO->novaConexao($ps['codigoPrestador'], $ps['codigoAnuncio']);
            header('HTTP/1.1 200 OK');         
        }
    }
?>
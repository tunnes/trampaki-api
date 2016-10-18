<?php
    require_once('model/DAO/prestadorDAO.php');
    require_once('model/BPO/prestadorBPO.php');
    
    class NovaConexaoPrestador{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->responsePOST() : null; 
        }
        private function validarSessao(){
            switch ($_SESSION['tipoUsuario']){
                    case 1:
                    case 2:
                        $this->response();
                        break;
                    case 0:
                        echo 'voce não possui privilegio para isto malandrãoo!';
                        break;
                    default:
                        header('Location: login');  
                        break;
            }
        }
        private function response(){
        #   Variável que conterá informações 
        #   relativas ao erro de validação:
            $erro = false;
            
        #   Verificação de quantidade de parametros fornecidos no request:
            count($_POST) != 1 ? $erro = "Quantidade de parametros invalida." : null;
        
        #   Falta verificar se o codigoDo anuncio é valido.
        #   ...
        
        #   Se existir algum erro, mostra o erro   
            if($erro){
                echo $erro;
            }else{
                $usuarioBPO = unserialize($_SESSION['objetoUsuario']);
                $anuncianteDAO = AnuncianteDAO::getInstance();
                $anuncianteDAO->novaConexao($anuncianteBPO->getCodigoUsuario(), $codigoAnuncio);
                echo "SOLICITAÇÃO EFETUADA COM ÊXITO.";
            }

        }
    }
?>
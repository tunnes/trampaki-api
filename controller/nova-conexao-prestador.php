<?php
    require_once('model/DAO/prestadorDAO.php');
    require_once('model/BPO/prestadorBPO.php');
    
    class NovaConexaoPrestador{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->validarSessao() : null; 
        }
        private function validarSessao(){
            switch ($_SESSION['tipoUsuario']){
                    case 1:
                    case 2:
                        $this->responsePOST();
                        break;
                    case 0:
                        echo 'voce não possui privilegio para isto malandrãoo!';
                        break;
                    default:
                        header('Location: login');  
                        break;
            }
        }
        
        private function responsePOST(){
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
                $prestadorBPO = unserialize($_SESSION['objetoUsuario']);
                $prestadorDAO = PrestadorDAO::getInstance();
                $prestadorDAO->novaConexao($prestadorBPO, $_POST['codigoAnuncio']);
                echo "SOLICITACAO EFETUADA COM EXITO...";
            }

        }
    }
?>
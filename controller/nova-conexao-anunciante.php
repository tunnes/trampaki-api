<?php
    require_once('model/DAO/anuncioDAO.php');
    require_once('model/BPO/anuncioBPO.php');
    
    class NovaConexaoAnunciante{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->responsePOST() : null; 
        }
        private function validarSessao(){
            switch ($_SESSION['tipoUsuario']){
                    case 0:
                    case 2:
                        $this->responsePOST();
                        break;
                    case 1:
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
            count($_POST) != 2 ? $erro = "Quantidade de parametros invalida." : null;
        
        #   Falta verificar se o codigoDo anuncio é valido. e do prestador.
        #   ...
        
        #   Se existir algum erro, mostra o erro   
            if($erro){
                echo $erro;
            }else{
                $anuncianteDAO = AnuncioDAO::getInstance();
                $anuncianteDAO->novaConexao($codigoPrestador, $codigoAnuncio);
                echo "SOLICITAÇÃO EFETUADA COM ÊXITO.";
            }

        }
    }
?>
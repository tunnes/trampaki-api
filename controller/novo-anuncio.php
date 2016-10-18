<?php
    session_start();
    require_once('model/DAO/anuncioDAO.php');
    require_once('model/BPO/anuncioBPO.php');
    require_once('model/BPO/anuncianteBPO.php');
    
    class novoAnuncio{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->validarSessao() : null; 
        }
        private function validarSessao(){
            switch ($_SESSION['tipoUsuario']){
                    case 0:
                    case 2:
                        $this->cadastrarAnuncio();
                        break;
                    case 1:
                        echo 'voce não possui privilegio para isto malandrãoo!';
                        break;
                    default:
                        header('Location: login');  
                        break;
            }
        }
        private function cadastrarAnuncio(){
        #   Variável que conterá informações 
        #   relativas ao erro de validação:
            $erro = false;
            
        #   Verificação de quantidade de parametros fornecidos no request:
            count($_POST) != 6 ? $erro = "Quantidade de parametros invalida." : null;
        
        #   Criando variáveis dinamicamente, e removendo possiveis tags HTML, espaços em branco e valores nulos:
            foreach ( $_POST as $atributo => $valor ){
    	        $$atributo = trim(strip_tags($valor));
            	empty($valor) ? $erro = "Existem campos em branco." : null;
            }
            
        #   Verificando se longitude, latitude são numeros:
            !is_numeric($_POST['areaAlcance']) ? $erro = 'A latitude deve ser de valor númererico.' : null;
        
        #   Se existir algum erro, mostra o erro   
            if($erro){
                echo $erro;
            }else{
             $anuncianteBPO = unserialize($_SESSION['objetoUsuario']);    
                $anuncioBPO = new AnuncioBPO(null, $anuncianteBPO->getCodigoUsuario(), $titulo, $descricao, $areaAlcance, $cat01, $cat02, $cat03, '0');
                
                 $anuncioDAO = AnuncioDAO::getInstance();
                 $anuncioDAO->cadastrarAnuncio($anuncioBPO);
                 echo "Cadastrado com sucesso.";
            }

        }
    }
?>
<?php
    session_start();
    require_once("model/DAO/anuncioDAO.php");

    class EditarAnuncio{
        public function __construct(){
        #   Verificação de metodo da requisição:
           $_SERVER['REQUEST_METHOD'] == 'PUT' ? $this->validarSessao() : include('view/pagina-404.html');
            
        }
        private function validarSessao(){
            switch ($_SESSION['tipoUsuario']){
                    case 0:
                    case 2:
                        $this->atualizarAnuncio();
                        break;
                    case 1:
                        echo 'voce não possui privilegio para isto malandrãoo!';
                        break;
                    default:
                        header('Location: login');  
                        break;
            }
        }
        private function atualizarAnuncio(){
        #   Converte em variaveis do tipo String o que foi enviado via 'PUT'    
            parse_str(file_get_contents("php://input"),$post_vars);
        
        #   Variável que conterá informações relativas ao erro de validação:
            $erro = false;
            
        #   Verificação de quantidade de parametros fornecidos no request:
            count($post_vars) != 5 ? $erro = "Quantidade de parametros invalida." : null;
        
        #   Criando variáveis dinamicamente, e removendo possiveis 
        #   tags HTML, espaços em branco e valores nulos:
            foreach ( $post_vars as $atributo => $valor ){
    	        $$atributo = trim(strip_tags($valor));
            	empty($valor) ? $erro = " O atributo ".$atributo. " esta em branco.": null;
            	
            }
            
        #   Verificando se longitude, latitude são numeros:
            !is_numeric($areaAlcance) ? $erro = 'Quantidade alcance deve ser de valor númererico.' : null;
            
        #   Se existir algum erro, mostra o erro   
            if($erro){
                echo $erro;
            }else{
                $anuncioDAO = AnuncioDAO::getInstance();
                $anuncioBPO = $anuncioDAO->editarAnuncio($codigoAnuncio, $titulo, $descricao, $areaAlcance, $status);
                echo "EditadoComSucesso";
            }
        }
    }
?>

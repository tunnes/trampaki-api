<?php
    session_start();
    require_once 'configuration/autoload-geral.php';
    
    class NovoAnuncio{
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
        #   Variável que conterá informações relativas ao es de validação:
            $IO = ValidacaoIO::getInstance();
            $es = array();
            $ps = array();
            $cs = array();

        #   Criando variáveis dinamicamente, e removendo possiveis tags HTML, espaços em branco e valores nulos:
            foreach ($_POST as $atributo => $valor){
    	        $ps[$atributo] = trim(strip_tags($valor));
            	$es = $IO->validarConsisten($es, $valor);
            }
            
        #   Verificando se longitude, latitude são numeros:
            $es = $IO->validarAlcance($es, $ps['areaAlcance']);

        #   Verificando se a categoria fornecida existe.
            isset($ps['cat01']) ? array_push($cs, $ps['cat01']) : null;
            isset($ps['cat02']) ? array_push($cs, $ps['cat02']) : null;
            isset($ps['cat03']) ? array_push($cs, $ps['cat03']) : null;
            foreach($cs as $c){ $es = $IO->validarCategoria($es, $c);}
            
        #   Verificando redundancia de entre categorias.    
            $es = $IO->redundanciaCategorias($es, $ps['cat01'], $ps['cat02'], $ps['cat03']);
            
        #   Se existir algum es, mostra o es
            $es ? $IO->retornar400($es) : $this->retornar200($ps, $cs);
            
        }
        private function retornar200($ps, $cs){
            
            $anuncianteBPO = unserialize($_SESSION['objetoUsuario']);
            $anuncioBPO = new AnuncioBPO(null, $anuncianteBPO->getCodigoUsuario(), $ps['titulo'], $ps['descricao'], $ps['areaAlcance'], null, '0');
            $anuncioDAO = AnuncioDAO::getInstance();
            $anuncioDAO->cadastrarAnuncio($anuncioBPO, $cs);
            header('HTTP/1.1 200 OK');
        }
    }
?>
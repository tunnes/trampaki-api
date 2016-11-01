<?php
    session_start();
    require_once 'configuration/autoload-geral.php';
    
    class EditarAnuncio{
        public function __construct(){
        #   Verificação de metodo da requisição:
           $_SERVER['REQUEST_METHOD'] == 'PUT' ? $this->validarSessao() : include('view/pagina-404.html');
            
        }
        private function validarSessao(){
            switch ($_SESSION['tipoUsuario']){
                    case 0:
                    case 2:
                        $this->validarPUT();
                        break;
                    case 1:
                        echo 'voce não possui privilegio para isto malandrãoo!';
                        break;
                    default:
                        header('Location: login');  
                        break;
            }
        }
        private function validarPUT(){
        #   Converte em variaveis do tipo String o que foi enviado via 'PUT'    
            parse_str(file_get_contents("php://input"),$post_vars);
            $IO = ValidacaoIO::getInstance();
            $es = array();
            $ps = array();
            
        #   Criando variáveis dinamicamente, e removendo possiveis tags HTML, espaços em branco e valores nulos:
            foreach ($post_vars as $atributo => $valor){
    	        $ps[$atributo] = trim(strip_tags($valor));
            	$es = $IO->validarConsisten($es, $valor);
            }
            
        #   Verificando a quantidade de parametros enviados:
            $es = $IO->validarQuantParam($es, $ps, 5);
            
        #   Verificando se longitude, latitude são numeros:
            $es = $IO->validarAlcance($es, $ps['areaAlcance']);
        
        #   Validar status fornecido:
            $es = $IO->validarStatusAnuncio($es, $ps['status']);
            
        #   Validar codigo de anuncio fornecido:
            $prestadorBPO = unserialize($_SESSION['objetoUsuario']);
        //  $es = $IO->validarAnuncio($es, $ps['codigoAnuncio']);
            
            $es = $IO->validarDonoAnuncio($es, $prestadorBPO->getCodigoUsuario(), $ps['codigoAnuncio']);
            
        #   Se existir algum erro, mostra o erro
            $es ? $IO->retornar400($es) : $this->retornar200($ps);
        }
        private function retornar200($ps){
            $anuncioDAO = AnuncioDAO::getInstance();
            $anuncioBPO = $anuncioDAO->editarAnuncio($ps['codigoAnuncio'], $ps['titulo'], $ps['descricao'], $ps['areaAlcance'], $ps['status']);
            header('HTTP/1.1 200 OK');
        }
    }
?>

<?php
    session_start();
    require_once 'configuration/autoload-geral.php';

    class EditarPrestador{
        public function __construct(){
        #   Verificação de metodo da requisição:
           $_SERVER['REQUEST_METHOD'] == 'PUT' ? $this->validarSessao() : include('view/pagina-404.html');
            
        }
        private function validarSessao(){
            switch ($_SESSION['tipoUsuario']){
                    case 1:
                    case 2:
                        $this->validarPUT();
                        break;
                    case 2:
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
            $prestadorBPO  = unserialize($_SESSION['objetoUsuario']);
            $IO = ValidacaoIO::getInstance();
            $es = array();
            $ps = array();
            $categorias = array();
            
        #   Criando variáveis dinamicamente, e removendo possiveis tags HTML, espaços em branco e valores nulos:
            foreach ($post_vars as $atributo => $valor){
    	        $ps[$atributo] = trim(strip_tags($valor));
            	$es = $IO->validarConsisten($es, $valor);
            }
            
        #   Verificando a quantidade de parametros enviados:
        #    $es = $IO->validarQuantParam($es, $ps, 16);
        
        #   Verificando se longitude, latitude e area de alcance são numeros:
            $es = $IO->validarCoordenada($es, $ps['latitude']);
            $es = $IO->validarCoordenada($es, $ps['longitude']);
            
        #   Verificando se o email ou login já foram cadastrados.
            $es = $IO->redundanciaUpdateEmail($es, $ps['email'], $prestadorBPO->getCodigoUsuario());
            $es = $IO->redundanciaUpdateLogin($es, $ps['login'], $prestadorBPO->getLogin()->getCodigoLogin());
            
        #   Verificando a formatação do campo de email e possivel duplicidade:
            $es = $IO->validarEmail($es, $ps['email']);
            
        #   Verificando se a categoria fornecida existe.
            isset($ps['cat01']) ? array_push($categorias, $ps['cat01']) : null;
            isset($ps['cat02']) ? array_push($categorias, $ps['cat02']) : null;
            isset($ps['cat03']) ? array_push($categorias, $ps['cat03']) : null;
            foreach($categorias as $categoria){ 
                $es = $IO->validarCategoria($es, $categoria);
                $es = $IO->redundanciaUpdateCategoria($es, $categoria, $prestadorBPO->getCodigoUsuario());
            }
            
        #   Verificando redundancia de entre categorias.    
        #   Atenção: Devo implementar a redundancia inteligente de categorias.     
        #   $es = $IO->redundanciaCategorias($es, $ps['cat01'], $ps['cat02'], $ps['cat03']);
         
        #   Se existir algum erro, mostra o erro
            $es ? $IO->retornar400($es) : $this->retornar200($ps, $prestadorBPO, $categorias);
        }
        private function retornar200($ps, $prestadorBPO, $categorias){
            $enderecoBPO    = new EnderecoBPO($prestadorBPO->getEndereco()->getCodigoEndereco(), $ps['estado'], $ps['cidade'], $ps['CEP'], $ps['numRes'], $ps['longitude'], $ps['latitude']);
            $loginBPO       = new LoginBPO($prestadorBPO->getLogin()->getCodigoLogin(), $ps['login'], $ps['senha']);                    
            $prestadorBPO   = new PrestadorBPO($prestadorBPO->getCodigoUsuario(), $ps['nome'], $ps['email'], $ps['tel'], $loginBPO, $enderecoBPO, $ps['desProf'], $ps['qntAlc'], $prestadorBPO->getCategorias(), null);
                
            $prestadorDAO  = PrestadorDAO::getInstance();
            $prestadorDAO->editarPrestador($prestadorBPO, $categorias);
           # $prestadorBPO = $prestadorDAO->editarCategoria($prestadorBPO, $categorias);
            
            // $prestadorDAO->editarCategoria($prestadorBPO, $ps['cat01'], $prestadorBPO->getCategoria01());
            // $prestadorDAO->editarCategoria($prestadorBPO, $ps['cat02'], $prestadorBPO->getCategoria02());
            // $prestadorDAO->editarCategoria($prestadorBPO, $ps['cat03'], $prestadorBPO->getCategoria03());            
            header('HTTP/1.1 200 OK');
        }
    }
?>

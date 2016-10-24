<?php
    session_start();
    require_once 'configuration/autoload-geral.php';

    class EditarAnunciante extends NovoUsuario{
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
            $anuncianteBPO  = unserialize($_SESSION['objetoUsuario']);
            $IO = ValidacaoIO::getInstance();
            $es = array();
            $ps = array();
            
        #   Criando variáveis dinamicamente, e removendo possiveis tags HTML, espaços em branco e valores nulos:
            foreach ($post_vars as $atributo => $valor){
    	        $ps[$atributo] = trim(strip_tags($valor));
            	$es = $IO->validarConsisten($es, $valor);
            }
            
        #   Verificando a quantidade de parametros enviados:
            $es = $IO->validarQuantParam($es, $ps, 11);
        
        #   Verificando se longitude, latitude e area de alcance são numeros:
            $es = $IO->validarCoordenada($es, $ps['latitude']);
            $es = $IO->validarCoordenada($es, $ps['longitude']);
            
        #   Verificando se o email ou login já foram cadastrados.
        #   SELECT * FROM `login` WHERE ds_login = 'love' and cd_login <> 2
            $es = $IO->redundanciaUpdateEmail($es, $ps['email'], $anuncianteBPO->getCodigoUsuario());
            $es = $IO->redundanciaUpdateLogin($es, $ps['login'], $anuncianteBPO->getLogin()->getCodigoLogin());
            
        #   Verificando a formatação do campo de email e possivel duplicidade:
            $es = $IO->validarEmail($es, $ps['email']);

        #   Se existir algum erro, mostra o erro
            $es ? $IO->retornar400($es) : $this->retornar200($ps, $anuncianteBPO);
        }
        private function retornar200($ps, $anuncianteBPO){
            $enderecoBPO    = new EnderecoBPO($anuncianteBPO->getEndereco()->getCodigoEndereco(), $ps['estado'], $ps['cidade'], $ps['CEP'], $ps['numRes'], $ps['longitude'], $ps['longitude']);
            $loginBPO       = new LoginBPO($anuncianteBPO->getLogin()->getCodigoLogin(), $ps['login'], $ps['senha']);
            $anuncianteBPO  = new AnuncianteBPO($anuncianteBPO->getCodigoUsuario(), $ps['nome'], $ps['email'], $ps['tel'], $enderecoBPO, $loginBPO);
            AnuncianteDAO::getInstance()->editarAnunciante($anuncianteBPO);
            header('HTTP/1.1 200 OK');
        }
    }
?>

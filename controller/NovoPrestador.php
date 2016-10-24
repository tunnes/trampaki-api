<?php
    require_once 'configuration/autoload-geral.php';
    
    class NovoPrestador{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->validarPOST() : include('view/novo-prestador.html');
        }
        private function validarPOST(){
        #   Variável '$es' conterá informações relativas ao es de validação '$IO' é a instância de ValidaoIO:
            $IO = ValidacaoIO::getInstance();
            $es = array();
            $ps = array();
            $categorias = array();
            
        #   Verificação de quantidade de parametros fornecidos no request:
        #   $es = $IO->validarQuantParam($es, $_POST, 16);
            
        #   Criando variáveis dinamicamente, e removendo possiveis tags HTML, espaços em branco e valores nulos:
            foreach ( $_POST as $atributo => $valor ){
    	        $ps[$atributo] = trim(strip_tags($valor));
            	$es = $IO->validarConsisten($es, $valor);
            }
            
        #   Verificando se longitude, latitude e area de alcance são numeros:
            $es = $IO->validarCoordenada($es, $ps['latitude']);
            $es = $IO->validarCoordenada($es, $ps['longitude']);
            $es = $IO->validarAlcance($es, $ps['qntAlc']);    
            
        #   Verificando se o email ou login já foram cadastrados.    
            $es = $IO->redundanciaEmail($es, $ps['email']);
            $es = $IO->redundanciaLogin($es, $ps['login']);
            
        #   Verificando se a categoria fornecida existe.
            isset($ps['cat01']) ? array_push($categorias, $ps['cat01']) : null;
            isset($ps['cat02']) ? array_push($categorias, $ps['cat02']) : null;
            isset($ps['cat03']) ? array_push($categorias, $ps['cat03']) : null;
            foreach($categorias as $categoria){ $es = $IO->validarCategoria($es, $categoria);}
            
        #   Verificando redundancia de entre categorias.    
        #   Atenção: Devo implementar a redundancia inteligente de categorias.     
        #   $es = $IO->redundanciaCategorias($es, $ps['cat01'], $ps['cat02'], $ps['cat03']);
            
            
        #   Verificando a formatação do campo de email e possivel duplicidade:
            $es = $IO->validarEmail($es, $ps['email']);

        #   Se existir algum es, mostra o es
            $es ? $IO->retornar400($es) : $this->retornar200($ps, $categorias);
            
        }
        private function retornar200($ps){
            $loginBPO       = new LoginBPO(null, $ps['login'], $ps['senha']);
            $enderecoBPO    = new EnderecoBPO(null, $ps['estado'], $ps['cidade'], $ps['CEP'], $ps['numRes'], $ps['longitude'], $ps['latitude']);
            $prestadorBPO   = new PrestadorBPO(null, $ps['nome'], $ps['email'], $ps['tel'], $loginBPO, $enderecoBPO, $ps['desProf'], $ps['qntAlc'], null);
            $prestadorDAO = PrestadorDAO::getInstance();

            $prestadorBPO = $prestadorDAO->cadastrarPrestador($prestadorBPO, $categorias);
            header('HTTP/1.1 200 OK');
        }
    }
?>
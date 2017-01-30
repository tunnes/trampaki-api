<?php
    require_once 'configuration/autoload-geral.php';
    
    class NovoPrestador{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->validarPOST() : null;
        }
        private function validarPOST(){
        #   Variável '$es' conterá informações relativas ao es de validação '$IO' é a instância de ValidaoIO:
            $IO = ValidacaoIO::getInstance();
            $es = array();
            $ps = array();
            $cs = array();
            
        #   Criando variáveis dinamicamente, e removendo possiveis tags HTML, espaços em branco e valores nulos:
            foreach ( $_POST as $atributo => $valor ){
    	        $ps[$atributo] = trim(strip_tags($valor));
            	$es = $IO->validarConsisten($es, $atributo, $valor);
            }
            
            $ps['codigo_postal'] != null ? $ps = $this->pegarCoordenadas($ps) : null;
            
        
            
            $es = $IO->validarAlcance($es, $ps['distancia_alcance']);    
            
        #   Verificando se o email ou login já foram cadastrados.    
            $es = $IO->redundanciaEmail($es, $ps['usuario_email']);
            $es = $IO->redundanciaLogin($es, $ps['usuario_login']);
        #   Verificando a formatação do campo de email e possivel duplicidade:
            $es = $IO->validarEmail($es, $ps['usuario_email']);
            
        #   Manipulando funcionalmente as categorias -------------------------------------------------------------------------------
        #   https://secure.php.net/manual/pt_BR/function.array-diff.php
        #   http://php.net/manual/pt_BR/function.array-unique.php
        
            array_push($cs, $ps['codigo_categoria_01'], $ps['codigo_categoria_02'], $ps['codigo_categoria_03']);
            
        #   Remover valores nullos:    
            $cs = array_filter($cs);
        #   Remover valores repetidos:    
            $cs = array_unique($cs);
            
            $es = $IO->validarCategorias($es, $cs);
            
        #   -------------------------------------------------------------------------------------------------------------------------
        
        #   Validação da imagem de perfil ------------------------------------------------------
            if(isset($_FILES['imagem_perfil']) && $_FILES['imagem_perfil']['size'] > 0){ 
                $tmpName  = $_FILES['imagem_perfil']['tmp_name'];  
                $ps['imagem_perfil'] = fopen($tmpName, 'rb');
            }else{
                $es = $IO->addERRO($es, 821 , 'Imagem invalida');
            } 
        #   ------------------------------------------------------------------------------------            

        #   Se existir algum es, mostra o es
            $es ? $IO->retornar400($es) : $this->retornar201($ps, $cs);
            
        }
        private function retornar201($ps, $cs){
        #   Cadastrando a imagem de perfil:    
            $codigoImagem = ImagemDAO::getInstance()->cadastrar($ps['imagem_perfil']);
            
            $loginBPO = new LoginBPO(
                null, 
                $ps['usuario_login'], 
                $ps['usuario_senha'],
                null
            );
            $enderecoBPO = new EnderecoBPO(
                null, 
                $ps['sigla_estado'], 
                $ps['nome_cidade'], 
                $ps['codigo_postal'],
                $ps['numero_residencial'], 
                $ps['longitude'], 
                $ps['latitude']
            );
            $prestadorBPO = new PrestadorBPO(
                null, 
                $ps['usuario_nome'], 
                $ps['usuario_email'],
                $ps['usuario_telefone'], 
                $loginBPO, 
                $enderecoBPO, 
                $ps['descricao_profissional'], 
                $ps['distancia_alcance'], 
                null,
                $codigoImagem
            );
            echo "aqui filho da puta ".$ps['longitude'] . " - " .$ps['latitude'];
            $prestadorBPO = PrestadorDAO::getInstance()->cadastrarPrestador($prestadorBPO, $cs);
            
            header('HTTP/1.1 201 Created');
        #   Uma solução MVP para o problema de pegar o Authorization via Js:
            header("Access-Control-Expose-Headers: Authorization, Trampaki-ID, trampaki_user");
            header("Authorization: ".$prestadorBPO->getLogin()->getToken()."");
            header("Trampaki-ID: ".$prestadorBPO->getCodigoUsuario());            
            header("trampaki_user: 1");
        }
        private function pegarCoordenadas($ps){
        #   Conseguindo longitude e latitude do endereco ------------------------
            $coordenadas = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$ps['codigo_postal'].'-'.'&sensor=false');
        
            $ps['latitude'] = json_decode($coordenadas)->results[0]->geometry->location->lat;
            $ps['longitude']  = json_decode($coordenadas)->results[0]->geometry->location->lng;
           
            return $ps;
        }
        
    }
?>
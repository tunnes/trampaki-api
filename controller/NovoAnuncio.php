<?php
    require_once 'configuration/autoload-geral.php';
    
    class NovoAnuncio{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->validarToken() : null; 
        }
        private function validarToken(){
            $anuncianteBPO = LoginDAO::getInstance()->gerarAutenticacao(apache_request_headers()['authorization']);
            $anuncianteBPO instanceof AnuncianteBPO ? $this->validarPOST($anuncianteBPO) : header('HTTP/1.1 401 Unauthorized');
        }
        private function validarPOST($anuncianteBPO){
        #   Variável que conterá informações relativas ao es de validação:
            $IO = ValidacaoIO::getInstance();
            $es = array();
            $ps = array();
            $cs = array();
            $is = array();
            
        #   Criando variáveis dinamicamente, e removendo possiveis tags HTML, espaços em branco e valores nulos:
            foreach ($_POST as $atributo => $valor){
    	        $ps[$atributo] = trim(strip_tags($valor));
            	$es = $IO->validarConsisten($es, $atributo, $valor);
            }        
        
        #   Validação da imagem de perfil -----------------------------------------------------------------------------------------
            array_push($is, $_FILES["imagem_anuncio_01"], $_FILES["imagem_anuncio_02"], $_FILES["imagem_anuncio_03"]);
            $blob = array();
            
            foreach($is as $imagem){ 
                isset($imagem) && $imagem['size'] > 0 ? array_push($blob, (fopen(($imagem['tmp_name']), 'rb'))) : null; 
            };
            
        #   -----------------------------------------------------------------------------------------------------------------------    
            
        #   Manipulando funcionalmente as categorias -------------------------------------------------------------------------------
        #   https://secure.php.net/manual/pt_BR/function.array-diff.php
        #   http://php.net/manual/pt_BR/function.array-unique.php
        
            array_push($cs, $ps['codigo_categoria_01'], $ps['codigo_categoria_02'], $ps['codigo_categoria_03']);
            
        #   Remover valores nullos:    
            $cs = array_filter($cs);
            
        #   Remover valores repetidos:    
            $cs = array_unique($cs);
            
            $es = $IO->validarCategorias($es, $cs);

        #   Se existir algum es, mostra o es
            $es ? $IO->retornar400($es) : $this->retornar201($ps, $cs, $blob, $anuncianteBPO);
            
        }
        private function retornar201($ps, $cs, $blob, $anuncianteBPO){
            $codigoImagens = array();
            foreach($blob as $image){ array_push($codigoImagens, ImagemDAO::getInstance()->cadastrar($image));}
            
            $anuncioBPO = new AnuncioBPO(
                null,                                   //  Código do anuncio. 
                $anuncianteBPO->getCodigoUsuario(),     //  Código do anunciante.
                $ps['titulo_anuncio'],                  //  Titulo do anuncio.
                $ps['descricao_anuncio'],               //  Descrição do anuncio.
                null,                                   //  Array de CategoriaBPO do anuncio.
                '0',                                    //  Status do anuncio.
                $codigoImagens[0],
                $codigoImagens[1],
                $codigoImagens[2]
            );
            $anuncioDAO = AnuncioDAO::getInstance();
            $anuncioDAO->cadastrar($anuncioBPO, $cs);
            header('HTTP/1.1 201 Created');
        }
    }
?>
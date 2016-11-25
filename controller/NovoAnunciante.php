<?php
    require_once 'configuration/autoload-geral.php';
    
    class NovoAnunciante{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->responsePOST() : include('view/novo-anunciante.html'); 
        }
        
        private function responsePOST(){
        #   Variável que conterá informações, relativas ao erro de validação:
            $IO = ValidacaoIO::getInstance();
            $es = array();
            $ps = array();
            
            foreach ( $_POST as $atributo => $valor ){ $ps[$atributo] = $valor; }
            
            $es = $IO->validarConsisten($es, $ps['codigo_postal']);
            
        #   Conseguindo longitude e latitude do endereco ------------------------
            $ps['codigo_postal'] != null ? $ps = $this->pegarCoordenadas($ps) : null;
            
            
        #   Verificando se o email ou login já foram cadastrados.    
            $es = $IO->redundanciaEmail($es, $ps['usuario_email']);
            $es = $IO->redundanciaLogin($es, $ps['usuario_login']);
        
        #   Verificando a formatação do campo de email e possivel duplicidade:
            $es = $IO->validarEmail($es, $ps['usuario_email']);    
        
        #   Validação da imagem de perfil ------------------------------------------------------
            if(isset($_FILES['imagem_perfil']) && $_FILES['imagem_perfil']['size'] > 0){ 
                $tmpName  = $_FILES['imagem_perfil']['tmp_name'];  
                $ps['imagem_perfil'] = fopen($tmpName, 'rb');
            }else{
                $es = $IO->addERRO($es, 821 , 'Imagem invalida');
            } 
        #   ------------------------------------------------------------------------------------
        
        #   Se existir algum es, mostra o es
            $es ? $IO->retornar400($es) : $this->retornar201($ps);
        }
        public function retornar201($ps){
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
            $anuncianteBPO = new AnuncianteBPO(
                null, 
                $ps['usuario_nome'], 
                $ps['usuario_email'], 
                $ps['usuario_telefone'], 
                $enderecoBPO, 
                $loginBPO, 
                $codigoImagem
            );
            
            $anuncianteDAO = AnuncianteDAO::getInstance();
            $anuncianteBPO = $anuncianteDAO->cadastrarAnunciante($anuncianteBPO);
            
            header('HTTP/1.1 201 Created');
            header("Access-Control-Expose-Headers: Authorization, Trampaki-ID, Trampaki-user");
            header("Authorization: ".$anuncianteBPO->getLogin()->getToken()."");
            header("Trampaki-ID: ".$anuncianteBPO->getCodigoUsuario());            
            header("Trampaki-user: 1");
        }
        private function pegarCoordenadas($ps){
        #   Conseguindo longitude e latitude do endereco ------------------------
            $coordenadas = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$ps['codigo_postal'].'&sensor=false');
        
            $ps['latitude'] = json_decode($coordenadas)->results[0]->geometry->location->lat;
            $ps['longitude']  = json_decode($coordenadas)->results[0]->geometry->location->lng;
           
            return $ps;
        }
    }
?>
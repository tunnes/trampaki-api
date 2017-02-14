<?php
    require_once 'configuration/autoload-geral.php';
    
    class PaginaAutenticacao{
        public function __construct(){
        #   Verificação de metodo da requisição:
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->efetuarAutenticacao() : null;
        }
        
        private function efetuarAutenticacao(){
        #   A função PHP 'filter_input()' tem como finalidade obter a variavel especifica do formulario.
        #   O 'FILTER_SANITIZE_MAGIC_QUOTES' retorna uma barra invertida na frente das aspas simples, neste
        #   passo estou recuperando os dados inseridos no formulario de login e verificando se são validos:
        
            $dadosLogin  = filter_input(INPUT_POST, "login", FILTER_SANITIZE_MAGIC_QUOTES);
            $dadosSenha  = filter_input(INPUT_POST, "senha", FILTER_SANITIZE_MAGIC_QUOTES);
            
            $token = base64_encode($dadosLogin. ':' .$dadosSenha);    
            $loginDAO = LoginDAO::getInstance();
            $usuario = $loginDAO->gerarAutenticacao($token);
            $this->enviar200($usuario);

        }
        private function enviar200($usuario){
            if($usuario instanceof AnuncianteBPO){
                header("Access-Control-Expose-Headers: Authorization, Trampaki-ID, trampaki_user, anuncio_selecionado");
                header('HTTP/1.1 200 OK');
                header("Authorization: ".$usuario->getLogin()->getToken()."");
                header("Trampaki-ID: ".$usuario->getCodigoUsuario());
                header("trampaki_user: 0");
                header("anuncio_selecionado: ".$usuario->getCodigoAnuncioSelecionado());
            }
            elseif($usuario instanceof PrestadorBPO){
                header("Access-Control-Expose-Headers: Authorization, Trampaki-ID, trampaki_user");
                header('HTTP/1.1 200 OK');
                header("Authorization: ".$usuario->getLogin()->getToken()."");
                header("Trampaki-ID: ".$usuario->getCodigoUsuario());                
                header("trampaki_user: 1");
            }
            else{
                header('HTTP/1.1 401 Unauthorized');
            }
            
        }
    }
?>

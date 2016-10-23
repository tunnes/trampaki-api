<?php
    session_start();
    require_once("model/DAO/prestadorDAO.php");
    require_once("model/BPO/prestadorBPO.php");

    class EditarPrestador{
        public function __construct(){
        #   Verificação de metodo da requisição:
           $_SERVER['REQUEST_METHOD'] == 'PUT' ? $this->validarSessao() : include('view/pagina-404.html');
            
        }
        private function validarSessao(){
            switch ($_SESSION['tipoUsuario']){
                    case 1:
                    case 2:
                        $this->responsePOST();
                        break;
                    case 2:
                        echo 'voce não possui privilegio para isto malandrãoo!';
                        break;
                    default:
                        header('Location: login');  
                        break;
            }
        }
        private function responsePOST(){
        #   Converte em variaveis do tipo String o que foi enviado via 'PUT'    
            parse_str(file_get_contents("php://input"),$post_vars);
            
        #   Variável que conterá informações 
        #   relativas ao erro de validação:
            $erro = false;
            
        #   Verificação de quantidade de parametros fornecidos no request:
            count($post_vars) != 16 ? $erro = "Quantidade de parametros invalida." : null;
        
        #   Criando variáveis dinamicamente, e removendo possiveis 
        #   tags HTML, espaços em branco e valores nulos:
            foreach ( $post_vars as $atributo => $valor ){
    	        $$atributo = trim(strip_tags($valor));
            	empty($valor) ? $erro = "Existem campos em branco." : null;
            }
            
        #   Verificando se longitude, latitude são numeros:
            !is_numeric($lati)      ? $erro = 'A latitude deve ser de valor númererico.' : null;
            !is_numeric($long)      ? $erro = 'A longitude deve ser de valor númererico.' : null;
            !is_numeric($qntAlc) ? $erro = 'A Area de alcance deve ser de valor númererico.' : null;
    
        
        #   Se existir algum erro, mostra o erro
            if($erro){
                echo $erro;
            }else{
                $prestadorBPO  = unserialize($_SESSION['objetoUsuario']);
                
                $enderecoBPO    = $prestadorBPO->getEndereco();
                $enderecoBPO    = new EnderecoBPO($enderecoBPO->getCodigoEndereco(), $estado, $cidade, $CEP, $numRes, $long, $lati);
                $loginBPO       = $prestadorBPO->getLogin();
                $loginBPO       = new LoginBPO($loginBPO->getCodigoLogin(), $login, $senha);                    
                $prestadorBPO   = new PrestadorBPO($prestadorBPO->getCodigoUsuario(), $nome, $email, $tel, $loginBPO, $enderecoBPO, $desProf, $qntAlc);
                
                $prestadorDAO  = PrestadorDAO::getInstance();
                $prestadorBPO  = $prestadorDAO->editarPrestador($prestadorBPO);
                // $prestadorDAO->editarCategoria($prestadorBPO, $cat01);
                // $prestadorDAO->editarCategoria($prestadorBPO, $cat02);
                // $prestadorDAO->editarCategoria($prestadorBPO, $cat03);
                echo "Dados EDITADOS COM SUCESSO!";
            }

        }
    }
?>

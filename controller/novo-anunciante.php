<?php
    require_once('model/login.php');
    require_once('model/endereco.php');
    require_once('model/anunciante.php');
    
    class novoAnunciante{
        public function __construct(){
            switch ($_POST['acao']) {
                case 'cadastrar':
                    $response = $this->validarDados();
                    header('Content-type: text/html');
                    echo $response;
                    break;
                default:
                    include('view/novo-anunciante.html');
                    break;
            }
        }
        private function validarDados(){
        #   Variável que conterá informações 
        #   relativas ao erro de validação:
            $erro = false;
            
        #   Verificação de quantidade de parametros fornecidos no request:
            count($_POST) != 13 ? $erro = "Quantidade de parametros invalida." : null;
        
        #   Criando variáveis dinamicamente, e removendo possiveis 
        #   tags HTML, espaços em branco e valores nulos:
            foreach ( $_POST as $atributo => $valor ){
    	        $$atributo = trim(strip_tags($valor));
            	empty($valor) ? $erro = "Existem campos em branco." : null;
            }
            
        #   Verificando se longitude, latitude e area de alcance são numeros:
            !is_numeric($latitude)  ? $erro = 'A latitude deve ser de valor númererico.' : null;
            !is_numeric($longitude) ? $erro = 'A longitude deve ser de valor númererico.' : null;
            !is_numeric($qntAlc)    ? $erro = 'A Area de alcance deve ser de valor númererico.' : null;
            
        #   Verificando a formatação do campo de email e possivel duplicidade:
            !filter_var($email, FILTER_VALIDATE_EMAIL) ? $erro = 'Envie um email válido.' : null;
            !$this->duplicidadeEmail($email)           ? $erro = "Email já cadastrado." : null;
            
        #   Verificando se o login já foi cadastrado:
            !$this->duplicidadeLogin($login) ? $erro = "Login já cadastrado." : null;
        
        #   Se existir algum erro, mostra o erro   
            if($erro){
            	return $erro;
            }else{
                $objetoEndereco  = new Endereco($estado, $cidade, $CEP, $numeroResidencia, $longitude, $latitude);
                $objetoLogin     = new Login($login, $senha);
                $prestador       = new Anunciante($nome, $email, $tel, $objetoEndereco, $objetoLogin);
                $prestador->novoAnunciante();
                return "Cadastrado com sucesso.";
            }

        }
        private function duplicidadeLogin($login){
            $bancoDeDados = Database::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM login WHERE ds_login = :ds_login");
            $comandoSQL->bindParam(':ds_login', $login);
            $comandoSQL->execute();
            return $comandoSQL->rowCount() == 0 ? true : false;        
        }
        private function duplicidadeEmail($email){
            $bancoDeDados = Database::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM usuario WHERE ds_email = :ds_email");
            $comandoSQL->bindParam(':ds_email', $email);
            $comandoSQL->execute();
            return $comandoSQL->rowCount() == 0 ? true : false;        
        }
    }
?>
<?php
    require_once('model/BPO/login.php');
    require_once('model/BPO/endereco.php');
    require_once('model/BPO/prestador.php');
    require_once('model/BPO/prestador.php');
    require_once('controller/novo-usuario.php');
    
    
    class novoPrestador extends novoUsuario{
        public function __construct(){
            switch ($_POST["acao"]) {
                case 'cadastrar':
                    $response = $this->validarDados();
                    header('Content-type: text/html');
                    echo $response;
                    break;
                case 'listarCategorias':
                    $response = $this->listarCategorias();
                    header('Content-type: application/json');
                    echo $response;
                    break;
                default:
                    include('view/novo-prestador.html');
                    break;
            }
        }
        private function validarDados(){
        #   Variável que conterá informações 
        #   relativas ao erro de validação:
            $erro = false;
            
        #   Verificação de quantidade de parametros fornecidos no request:
            count($_POST) != 17 ? $erro = "Quantidade de parametros invalida." : null;
        
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
                $objetoPrestador = new PrestadoDeServico($nome, $email, $tel, $objetoEndereco, $objetoLogin, $desProf, $qntAlc);
                
                $prestadorDAO = PrestadorDAO::getInstance();
                $codigoPrestador = $prestadorDAO->novoPrestador($objetoPrestador);
                $prestadorDAO->selecionarCategoria($codigoPrestador, $cat01);
                $prestadorDAO->selecionarCategoria($codigoPrestador, $cat02);
                $prestadorDAO->selecionarCategoria($codigoPrestador, $cat02);
                return "Cadastrado com sucesso.";
            }

        }

    }
?>
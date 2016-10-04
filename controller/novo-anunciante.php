<?php
    require_once('model/BPO/login.php');
    require_once('model/BPO/endereco.php');
    require_once('model/BPO/anunciante.php');
    require_once('model/DAO/anuncianteDAO.php');
    require_once('configuration/dataBase.php');
    
    require_once('controller/novo-usuario.php');
    class novoAnunciante extends novoUsuario{
        public function __construct(){
            switch ($_POST["acao"]) {
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
            !is_numeric($lati)      ? $erro = 'A latitude deve ser de valor númererico.' : null;
            !is_numeric($long)      ? $erro = 'A longitude deve ser de valor númererico.' : null;
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
                $anuncianteDAO    = AnuncianteDAO::getInstance();
                
                $anuncianteBPO = $anuncianteDAO->cadastrarAnunciante($nome, $email, $tel, $login, $senha, $estado, $cidade, $CEP, $numRes, $long, $lati);
                $login = $anuncianteBPO->getLogin();
                $login->iniciarSessao($anuncianteBPO, '0');
                return "Cadastrado com sucesso.";
            }

        }
    }
?>
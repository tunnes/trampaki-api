<?php
    require_once('controller/novo-usuario.php');
    require_once('model/DAO/anuncianteDAO.php');
    
    class novoAnunciante extends novoUsuario{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->responsePOST() : include('view/novo-anunciante.html'); 
        }
        
        private function responsePOST(){
        #   Variável que conterá informações 
        #   relativas ao erro de validação:
            $erro = false;
            
        #   Verificação de quantidade de parametros fornecidos no request:
            count($_POST) != 11 ? $erro = "Quantidade de parametros invalida." : null;
        
        #   Criando variáveis dinamicamente, e removendo possiveis 
        #   tags HTML, espaços em branco e valores nulos:
            foreach ( $_POST as $atributo => $valor ){
    	        $$atributo = trim(strip_tags($valor));
            	empty($valor) ? $erro = "Existem campos em branco." : null;
            }
            
        #   Verificando se longitude, latitude são numeros:
            !is_numeric($lati)      ? $erro = 'A latitude deve ser de valor númererico.' : null;
            !is_numeric($long)      ? $erro = 'A longitude deve ser de valor númererico.' : null;
            
        #   Verificando a formatação do campo de email e possivel duplicidade:
            !filter_var($email, FILTER_VALIDATE_EMAIL) ? $erro = 'Envie um email válido.' : null;
            !$this->duplicidadeEmail($email)           ? $erro = "Email já cadastrado." : null;
            
        #   Verificando se o login já foi cadastrado:
            !$this->duplicidadeLogin($login) ? $erro = "Login já cadastrado." : null;
        
        #   Se existir algum erro, mostra o erro   
            if($erro){
                echo $erro;
            }else{
                $anuncianteDAO    = AnuncianteDAO::getInstance();
                $anuncianteBPO    = $anuncianteDAO->cadastrarAnunciante($nome, $email, $tel, $login, $senha, $estado, $cidade, $CEP, $numRes, $long, $lati);
                $login = $anuncianteBPO->getLogin();
                $login->iniciarSessao($anuncianteBPO, '0');
                echo "Cadastrado com sucesso.";
            }

        }
    }
?>
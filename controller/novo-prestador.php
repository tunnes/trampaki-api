<?php
    require_once('controller/validacao-entradas.php');
    require_once('model/DAO/prestadorDAO.php');
    
    class NovoPrestador{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->validarPOST() : include('view/novo-prestador.html');
        }
        private function validarPOST(){
        #   Variável '$erro' conterá informações relativas ao erro de validação '$IO' é a instância de ValidaoIO:
            $IO = ValidacaoIO::getInstance();
            $erro = array();
            
        #   Verificação de quantidade de parametros fornecidos no request:
            count($_POST) != 16 ? $erro = $IO->addERRO($erro, 800 , 'Quantidade de parametros invalida') : null;
        
        #   Criando variáveis dinamicamente, e removendo possiveis tags HTML, espaços em branco e valores nulos:
            foreach ( $_POST as $atributo => $valor ){
    	        $$atributo = trim(strip_tags($valor));
            	empty($valor) ? $erro = $IO->addERRO($erro, 801 , 'Existem campos em branco') : null;
            }
            
        #   Verificando se longitude, latitude e area de alcance são numeros:
            !is_numeric($lati)   ? $erro = $IO->addERRO($erro, 802 , 'A latitude deve ser de valor númererico') : null;
            !is_numeric($long)   ? $erro = $IO->addERRO($erro, 803 , 'A longitude deve ser de valor númererico') : null;
            !is_numeric($qntAlc) ? $erro = $IO->addERRO($erro, 804 , 'O alcance deve ser de valor númererico') : null;
            
        #   Verificando a formatação do campo de email e possivel duplicidade:
            !filter_var($email, FILTER_VALIDATE_EMAIL) ? $erro = $this->addERRO($erro, 805 , 'Email invalido')  : null;
            
            $IO->validarEmail($email)     ? null : $erro = $IO->addERRO($erro, 806 , 'Email já cadastrado.');
            $IO->validarEmail($login)     ? null : $erro = $IO->addERRO($erro, 807 , 'Login já cadastrado.');
            $IO->validarCategoria($cat01) ? null : $erro = $IO->addERRO($erro, 808 , 'Categoria: '.$cat01.' não existe.');
            $IO->validarCategoria($cat02) ? null : $erro = $IO->addERRO($erro, 809 , 'Categoria: '.$cat02.' não existe.');
            $IO->validarCategoria($cat03) ? null : $erro = $IO->addERRO($erro, 810 , 'Categoria: '.$cat03.' não existe.');
            
        #   Verificando redundancia de entre categorias.    
            echo $cat1 == $cat2 && $cat1 == $cat3 ? $erro = $IO->addERRO($erro, 811 , 'Categoria redundante.') : null;

        #   Se existir algum erro, mostra o erro
            $erro ? $this->retornar400($erro) : $this->retornar200();
            
        }
        private function retornar200(){
            foreach ($_POST as $atributo => $valor){
                $$atributo = trim(strip_tags($valor));
            }
            
            $loginBPO       = new LoginBPO(null, $login, $senha);
            $enderecoBPO    = new EnderecoBPO(null, $estado, $cidade, $CEP, $numRes, $long, $lati);
            $prestadorBPO   = new PrestadorBPO(null, $nome, $email, $tel, $loginBPO, $enderecoBPO, $desProf, $qntAlc);
                
            $prestadorDAO = PrestadorDAO::getInstance();
            $prestadorBPO = $prestadorDAO->cadastrarPrestador($prestadorBPO);
            $prestadorDAO->selecionarCategoria($prestadorBPO->getCodigoUsuario(), $cat01);
            $prestadorDAO->selecionarCategoria($prestadorBPO->getCodigoUsuario(), $cat02);
            $prestadorDAO->selecionarCategoria($prestadorBPO->getCodigoUsuario(), $cat03);
            header('HTTP/1.1 200 OK');
        }
        private function retornar400($erro){
            header('HTTP/1.1 400 Bad Request');
            header('Content-type: application/json');
            echo json_encode($erro);
        }
    }
?>
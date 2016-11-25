<?php
    require_once 'configuration/autoload-geral.php';
    
    #   Descrição:
    #   A classe 'ValidacaoIO' como o prório nome já diz, ela tem a finalidade de validar as entradas impuras e ocasionalemente algumas  
    #   saidas sejam elas do processadas pelo servidor ou simplesmente vindas do banco de dados, por uma questão de conveniência foi 
    #   utilizado também o padrão Singleton, sendo que todas as funções possuem o retorno boleano 'True' para dado validado e 'False'
    #   para dados não validos.
    
    
    class ValidacaoIO{
    #   Padrão de Projeto Singleton ---------------------------------------------------------------------------------------------------------    
        private static $instance;
        public function getInstance(){
            return !isset(self::$instance) ? self::$instance = new ValidacaoIO() : self::$instance;
        }
        
    #   Funções de validação ----------------------------------------------------------------------------------------------------------------
        public function validarCategoria($array, $dadoImpuro){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM categoria WHERE cd_categoria = :cd_categoria");
            $comandoSQL->bindParam(':cd_categoria', $dadoImpuro);
            $comandoSQL->execute();
            
            return $comandoSQL->rowCount() == 0 ? $array = $this->addERRO($array, 808 , 'Categoria: '.$dadoImpuro.' não existe.') : $array;   
        }
        public function validarCategorias($arrayErro, $arrayCate){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT cd_categoria FROM categoria");
            $comandoSQL->execute();
            $arrayDoBanco = $comandoSQL->fetchAll(PDO::FETCH_OBJ);
            
            $arrayLImpo = array();
            foreach ($arrayDoBanco as $row => $valor){ array_push( $arrayLImpo, $valor->cd_categoria); }            
            $result = array_diff($arrayCate, $arrayLImpo);
            
            if(count($result) > 0){
                foreach($result as $categoria){ $arrayErro = $this->addERRO($arrayErro, 808 , 'Categoria: '.$categoria.' não existe.'); }
            }
            return $arrayErro;
        }
        public function validarCoordenada($array, $dadoImpuro){
            return !is_numeric($dadoImpuro) ? $array = $this->addERRO($array, 802 , 'A coordenada deve ser númererica') : $array;
        }
        public function validarAnuncio($array, $dadoImpuro){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM anuncio WHERE cd_anuncio = :cd_anuncio");
            $comandoSQL->bindParam(':cd_anuncio', $dadoImpuro);
            $comandoSQL->execute();
            return $comandoSQL->rowCount() == 0 ? $array = $this->addERRO($array, 812 , 'Anuncio: '.$dadoImpuro.' não existe.') : $array;               
        }
        public function validarEmail($array, $dadoImpuro){
            return !filter_var($dadoImpuro, FILTER_VALIDATE_EMAIL) ? $array = $this->addERRO($array, 805 , 'Email invalido')  : $array;
        }
        public function validarAlcance($array, $dadoImpuro){
            return !is_numeric($dadoImpuro) ? $array = $this->addERRO($array, 804 , 'O alcance deve ser de valor númererico') : $array;
        }
        public function validarQuantParam($array, $param, $tamanho){
            return count($param) != $tamanho ? $array = $this->addERRO($array, 800, 'Quantidade de parametros invalida') : $array;
        }
        public function validarConsisten($array, $dadoImpuro){
            return empty($dadoImpuro) ? $array = $this->addERRO($array, 801 , 'Existencia de parâmetros em branco') : $array;
        }
        public function validarStatusAnuncio($xs, $x){
            return $x == '0' || $x == '1' || $x == '2' ? $xs : $xs = $this->addERRO($xs, 823, 'Status anuncio invalido');  
        }
        public function redundanciaLogin($array, $dadoImpuro){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM login WHERE ds_login = :ds_login");
            $comandoSQL->bindParam(':ds_login', $dadoImpuro);
            $comandoSQL->execute();
            return $comandoSQL->rowCount() == 0 ? $array : $array =  $this->addERRO($array, 807, 'Login já cadastrado.');    
        }
        public function redundanciaEmail($array, $dadoImpuro){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM usuario WHERE ds_email = :ds_email");
            $comandoSQL->bindParam(':ds_email', $dadoImpuro);
            $comandoSQL->execute();
            return $comandoSQL->rowCount() == 0 ? $array : $array = $this->addERRO($array, 806, 'Email já cadastrado.');
        }
        public function redundanciaUpdateEmail($array, $dadoImpuro, $codigoUsuario){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM usuario WHERE ds_email = :ds_email and cd_usuario <> :cd_usuario");
            $comandoSQL->bindParam(':ds_email', $dadoImpuro);
            $comandoSQL->bindParam(':cd_usuario', $codigoUsuario);
            $comandoSQL->execute();
            return $comandoSQL->rowCount() == 0 ? $array : $array = $this->addERRO($array, 806, 'Email já cadastrado.');
        }
        public function redundanciaUpdateLogin($array, $dadoImpuro, $codigoLogin){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM login WHERE ds_login = :ds_login and cd_login <> :cd_login");
            $comandoSQL->bindParam(':ds_login', $dadoImpuro);
            $comandoSQL->bindParam(':cd_login', $codigoLogin);
            $comandoSQL->execute();
            return $comandoSQL->rowCount() == 0 ? $array : $array = $this->addERRO($array, 806, 'Login já cadastrado.');
        }
        public function redundanciaCategorias($xs, $a, $b, $c){
            return $a == $b || $a == $c || $b == $c ? $xs = $this->addERRO($xs, 811 , 'Categoria redundante.') : $xs;
        }
        public function validarDonoAnuncio($xs, $x, $y){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM anuncio WHERE cd_usuario = :cd_usuario and cd_anuncio = :cd_anuncio");
            $comandoSQL->bindParam(':cd_usuario', $x);
            $comandoSQL->bindParam(':cd_anuncio', $y);
            $comandoSQL->execute();
            return $comandoSQL->rowCount() == 0 ? $xs = $this->addERRO($xs, 811 , 'Anuncio: '.$y.' inválido.') : $xs;
        }
        public function validarPrestador($xs, $x){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM prestador WHERE cd_usuario = :cd_usuario");
            $comandoSQL->bindParam(':cd_usuario', $x);
            $comandoSQL->execute();
            return $comandoSQL->rowCount() == 0 ? $xs = $this->addERRO($xs, 811 , 'Codigo de Prestador: '.$y.' inválido.') : $xs;
        }
        public function addERRO($array, $codigo , $descricao){
            array_push($array, array('codigo' => $codigo,'descricao' => $descricao));
            return $array;
        }
        public function retornar400($erro){
            header('HTTP/1.1 400 Bad Request');
            header('Content-type: application/json');
            echo json_encode($erro);
        }
        public function redundanciaUpdateCategoria($xs, $x, $y){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM categoriaPrestador WHERE cd_usuario = :cd_usuario and cd_categoria = :cd_categoria");
            $comandoSQL->bindParam(':cd_usuario', $y);
            $comandoSQL->bindParam(':cd_categoria', $x);
            $comandoSQL->execute();
            return $comandoSQL->rowCount() != 0 ? $xs = $this->addERRO($xs, 811 , 'Categoria '.$x.' já vinculada o prestador') : $xs;
        }
        
    }
?>
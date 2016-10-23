<?php
    
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
        public function validarCategoria($dadoImpuro){
            $bancoDeDados = Database::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM categoria WHERE cd_categoria = :cd_categoria");
            $comandoSQL->bindParam(':cd_categoria', $dadoImpuro);
            $comandoSQL->execute();
            return $comandoSQL->rowCount() == 0 ? false : true;   
        }
        public function validarLogin($dadoImpuro){
            $bancoDeDados = Database::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM login WHERE ds_login = :ds_login");
            $comandoSQL->bindParam(':ds_login', $dadoImpuro);
            $comandoSQL->execute();
            return $comandoSQL->rowCount() == 0 ? true : false;        
        }
        public function validarEmail($dadoImpuro){
            $bancoDeDados = Database::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM usuario WHERE ds_email = :ds_email");
            $comandoSQL->bindParam(':ds_email', $dadoImpuro);
            $comandoSQL->execute();
            return $comandoSQL->rowCount() == 0 ? true : false;
        }
        public function addERRO($array, $codigo , $descricao){
            array_push($array, array('codigo' => $codigo,'descricao' => $descricao));
            return $array;
        }
    }
?>
<?php
    #   Conexão no padrão 'singleton' que garante a existencia de apenas uma instância
    #   da classe dataBase.php
    
    require_once 'config.php';
    
    class DataBase{
        
        private static $instance;
        
        public static function getInstance(){
        #   Verificando se o atributo '$instance' já foi alocado. 
        #   o 'self::' invez do $this-> é que seriam para varias instâncias..
            if(!isset(self::$instance)){
                try {
                #   Relativo ao PDO, seus recursos e funções devo aprofundar mais meus conhecimentos 
                #   relacionados para isso: https://www.youtube.com/watch?v=etcYlWwHAn0
                    
                    self::$instance = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
                    self::$instance->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    self::$instance -> setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                } catch (PDOException $erroDeConexao) {
                    echo $erroDeConexao -> getMenssage();
                }
            }
            return self::$instance;
        }
        
        public static function prepare($sql){
            return self::getInstance() -> prepare($sql);
        }
        
    }
        

?>
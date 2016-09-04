<?php
    require_once 'dataBase.php';

    class Categoria{
        public $codigoCategoria;
        public $nomeCategoria;
        public $descricaoCategoria;
            
    /*    public function __construct($codigoCategoria, $nomeCategoria, $descricaoCategoria){
            $this->codigoCategoria    = $codigoCategoria; 
            $this->nomeCategoria      = $nomeCategoria; 
            $this->descricaoCategoria = $descricaoCategoria;
        }
    */
    #   public function novaCategoria()...
        public function carregarCategorias(){
            $bancoDeDados = DataBase::getInstance(); 
            $querySQL = "SELECT * FROM categoria";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
            $comandoSQL->execute();
            return $comandoSQL->fetchAll(PDO::FETCH_ASSOC);    
        }
        
    }

?>
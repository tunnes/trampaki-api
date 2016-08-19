<?php

#   Neste arquivo o 'abstractOperations.php' deixei as operações genéricas relativas ao 
#   banco, isso tambem torna mais robusta a segurança já que com esse recurso implementado 
#   poucas classes teram acesso ao arquivo 'dataBase.php'.

    require_once 'dataBase.php';
    
    abstract class AbstractOperations extends dataBase{
        
    #   Para que as classes filhas, acessem os valores dos atributos da classe abstractOperations
    #   eu usei o protected.
    
        protected $table;
    
        abstract public function insert();
        abstract public function update($id);
        
        public function select($id){
            $querySQL = "SELECT * FROM $this->table WHERE cd_usuario = :cd_usuario";
            $stmt = dataBase::prepare($querySQL);
            $stmt->bindParam(':cd_usuario', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        }
        public function selectAll(){
            $querySQL = "SELECT * FROM $this->table ";
            $stmt = dataBase::prepare($querySQL);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        public function remove($id){
            $querySQL = "DELETE FROM $this->table WHERE cd_usuario = :cd_usuario";
            $stmt = dataBase::prepare($querySQL);
            $stmt->bindParam(':cd_usuario', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
?>
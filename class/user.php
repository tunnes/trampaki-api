<?php
#   O arquivo 'user.php' como o próprio nome já acusa, tem como finalidade 
#   as interações genéricas dos usuários do sistema.

    require_once 'abstractOperations.php';
    
    class User extends AbstractOperations{
        
        protected $table = 'usuario';
        private   $nome;
        private   $email;
    
        public function setNome($nome){
            $this->nome = $nome;
        }
        public function getNome(){
            return $this->nome;
        }
        public function insert(){
            $querySQL = "INSERT INTO $this->table (nm_usuario) VALUES (:nm_usuario)";
            $stmt =  dataBase::prepare($querySQL);
            $stmt -> bindParam(':nm_usuario', $this->nome);
            return $stmt->execute();
        }
        public function update($id){
            $querySQL = "UPDATE $this->table SET nome = :nome WHERE cd_usuario = :cd_usuario";
            $stmt = dataBase::prepare($querySQL);
            $stmt = bindParam(':nome',$this->nome);
            $stmt = bindParam(':id',$id);
            return $stmt->execute($querySQL);
        }
    }
?>
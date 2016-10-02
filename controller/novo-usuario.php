<?php
    abstract class novoUsuario{
        public function listarCategorias(){
            $bancoDeDados = DataBase::getInstance(); 
            $querySQL = "SELECT * FROM categoria";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
            $comandoSQL->execute();
            $categorias = json_encode($comandoSQL->fetchAll(PDO::FETCH_ASSOC));
            return $categorias;
        }
        public function duplicidadeLogin($login){
            $bancoDeDados = Database::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM login WHERE ds_login = :ds_login");
            $comandoSQL->bindParam(':ds_login', $login);
            $comandoSQL->execute();
            return $comandoSQL->rowCount() == 0 ? true : false;        
        }
        public function duplicidadeEmail($email){
            $bancoDeDados = Database::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM usuario WHERE ds_email = :ds_email");
            $comandoSQL->bindParam(':ds_email', $email);
            $comandoSQL->execute();
            return $comandoSQL->rowCount() == 0 ? true : false;        
        }
    }
?>
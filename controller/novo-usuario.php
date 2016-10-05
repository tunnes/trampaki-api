<?php

    abstract class NovoUsuario{
        public function listarCategorias(){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL = $bancoDeDados->prepare("select * from categoria");
            $comandoSQL->execute();
            $janta = $comandoSQL->fetchAll(PDO::FETCH_ASSOC);
            echo "Categorias Disponiveis: <br>";
            foreach ($janta as $row) {
                print "Codigo: ".$row["cd_categoria"] . " | Nome: " . $row["nm_categoria"] . " | Descrição: " . $row["ds_categoria"] ."<br/>";
                } 
            return $janta;
            
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
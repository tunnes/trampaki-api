<?php
    require_once('configuration/dataBase.php');
    
    abstract class UsuarioDAO{
        protected function cadastrarUsuario($nome, $email, $codigoLogin, $codigoEndereco, $tel, $tipoUsuario){
            $bancoDeDados = DataBase::getInstance();
            $querySQL = "INSERT INTO usuario (nm_usuario, ds_email, cd_login, cd_endereco, ds_telefone, cd_tipo) 
                                VALUES (:nm_usuario, :ds_email, :cd_login, :cd_endereco, :ds_telefone, :cd_tipo)";
                         
            $comandoSQL   = $bancoDeDados->prepare($querySQL);
            $comandoSQL -> bindParam(':ds_email', $email);
            $comandoSQL -> bindParam(':nm_usuario', $nome);
            $comandoSQL -> bindParam(':ds_telefone', $tel);
            $comandoSQL -> bindParam(':cd_login', $codigoLogin);
            $comandoSQL -> bindParam(':cd_endereco', $codigoEndereco);
            $comandoSQL -> bindParam(':cd_tipo', $tipoUsuario);
            $comandoSQL->execute();
            return $bancoDeDados->lastInsertId();
        }
        public function editarUsuario($codigoUsuario, $nome, $email, $tel){
            $bancoDeDados = Database::getInstance();
            $querySQL   = "UPDATE usuario SET 
                           nm_usuario = :nm_usuario, ds_email = :ds_email, ds_telefone = :ds_telefone  
                           WHERE cd_usuario = :cd_usuario";
            $comandoSQL =  $bancoDeDados->prepare($querySQL);
            $comandoSQL -> bindParam(':nm_usuario',  $nome);
            $comandoSQL -> bindParam(':ds_email',    $email);
            $comandoSQL -> bindParam(':ds_telefone', $tel);
            $comandoSQL -> bindParam(':cd_usuario',  $codigoUsuario);
            $comandoSQL->execute();
        }
    }
?>
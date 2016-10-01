<?php
    require_once('configuration/dataBase.php');
    require_once('model/DAO/loginDAO.php');
    require_once('model/DAO/enderecoDAO.php');
    
    abstract class UsuarioDAO{
        protected function novoUsuario($objetoUsuario){
        #   Cadastrando um endereco e um login, recebendo assim seus atributos
        #   identificadores do banco de dados tornar fisica o conceito de 
        #   presente no projeto agregação:
            $loginDAO = LoginDAO::getInstance();
            $codLogin = $loginDAO->novoLogin($objetoUsuario->getLogin());
        
            $enderecoDAO = EnderecoDAO::getInstance();
            $codEndereco = $enderecoDAO->novoEndereco($objetoUsuario->getEndereco());

            $querySQL = "INSERT INTO usuario (nm_usuario, ds_email, cd_login, cd_endereco, ds_telefone) 
                                VALUES (:nm_usuario, :ds_email, :cd_login, :cd_endereco, :ds_telefone)";
            $bancoDeDados = DataBase::getInstance();             
            $comandoSQL   = $bancoDeDados->prepare($querySQL);
            $comandoSQL -> bindParam(':ds_email', $objetoUsuario->getEmail());
            $comandoSQL -> bindParam(':nm_usuario', $objetoUsuario->getNome());
            $comandoSQL -> bindParam(':ds_telefone', $objetoUsuario->getTelefone());
            $comandoSQL -> bindParam(':cd_login', $codLogin);
            $comandoSQL -> bindParam(':cd_endereco', $codEndereco);
            $comandoSQL->execute();
            $codigoUsuario = $bancoDeDados->lastInsertId();
            
            $objetoLogin = $objetoUsuario->getLogin();
            $objetoLogin->iniciarSessao($codigoUsuario);
            
            return $codigoUsuario;
        }
    }
?>
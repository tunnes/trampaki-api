<?php
#   O arquivo 'user.php' como o próprio nome já acusa, tem como finalidade 
#   as interações genéricas dos usuários do sistema.

    require_once('configuration/dataBase.php');
    
    abstract class Usuario{
        protected $nome, $email, $telefone, $endereco, $login;
        protected function __construct($nome, $email, $telefone, Endereco $endereco, Login $login){
            $this->nome     = $nome;
            $this->email    = $email;
            $this->telefone = $telefone;
            $this->endereco = $endereco;
            $this->login    = $login;
        }
        protected function novoUsuario(){
        #   Cadastrando um endereco e um login, recebendo assim seus atributos
        #   identificadores do banco de dados tornar fisica o conceito de 
        #   presente no projeto agregação:
            $codLogin = $this->login->novoLogin();
            $codEndereco = $this->endereco->novoEndereco();
            
            $querySQL = "INSERT INTO usuario (nm_usuario, ds_email, cd_login, cd_endereco, ds_telefone) 
                                VALUES (:nm_usuario, :ds_email, :cd_login, :cd_endereco, :ds_telefone)";
            $bancoDeDados = DataBase::getInstance();             
            $comandoSQL   = $bancoDeDados->prepare($querySQL);
            $comandoSQL -> bindParam(':ds_email', $this->email);
            $comandoSQL -> bindParam(':nm_usuario', $this->nome);
            $comandoSQL -> bindParam(':ds_telefone', $this->telefone);
            $comandoSQL -> bindParam(':cd_login', $codLogin);
            $comandoSQL -> bindParam(':cd_endereco', $codEndereco);
            $comandoSQL->execute();
            $codigoUsuario = $bancoDeDados->lastInsertId();
            
            $this->login->iniciarSessao($codigoUsuario);
            return $codigoUsuario;
        }
    }
?>
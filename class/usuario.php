<?php
#   O arquivo 'user.php' como o próprio nome já acusa, tem como finalidade 
#   as interações genéricas dos usuários do sistema.

    require_once 'abstractOperations.php';
    
    class Usuario{
        public $nome, $email, $telefone, $endereco, $login;
        
        public function __construct($nome, $email, $telefone, Endereco $endereco, Login $login){
            $this->nome = $nome;
            $this->email = $email;
            $this->telefone = $telefone;
            $this->endereco = $endereco;
            $this->login = $login;
        }
        public function novoCadastro(){
            $querySQL = "INSERT INTO usuario (nm_usuario, nm_email, cd_senha, cd_login, cd_endereco, cd_telefone) 
                         VALUES (:nm_usuario, :nm_email, :cd_senha, :cd_login, :cd_endereco, :cd_telefone)";
            $comandoSQL =  dataBase::prepare($querySQL);
            $comandoSQL -> bindParam(':nm_email', $this->email);
            $comandoSQL -> bindParam(':nm_usuario', $this->nome);
            $comandoSQL -> bindParam(':cd_telefone', $this->telefone);
            $comandoSQL -> bindParam(':cd_senha', $this->login->getSenha());
            $comandoSQL -> bindParam(':cd_login', $this->login->getLogin());
            echo"aqui!!". $this->endereco->getCodigoEndereco();
            $comandoSQL -> bindParam(':cd_endereco', $this->endereco->getCodigoEndereco());
            return $comandoSQL->execute();
        }
    }
?>
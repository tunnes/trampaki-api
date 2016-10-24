<?php
#   O arquivo 'user.php' como o próprio nome já acusa, tem como finalidade 
#   as interações genéricas dos usuários do sistema.

    abstract class UsuarioBPO implements JsonSerializable{
        protected $codigoUsuario, $nome, $email, $telefone, $endereco, $login;
        protected function __construct($codigoUsuario, $nome, $email, $telefone, EnderecoBPO $endereco, LoginBPO $login){
            $this->codigoUsuario = $codigoUsuario;
            $this->nome     = $nome;
            $this->email    = $email;
            $this->telefone = $telefone;
            $this->endereco = $endereco;
            $this->login    = $login;
        }
        public function getCodigoUsuario(){
            return $this->codigoUsuario;
        }
        public function getNome(){
            return $this->nome;
        }
        public function getEmail(){
            return $this->email;
        }
        public function getTelefone(){
            return $this->telefone;
        }
        public function getEndereco(){
            return $this->endereco;
        }
        public function getLogin(){
            return $this->login;
        }
        public function jsonSerialize() {
        #   SE EU TIVESSE QUE ME APAIXONAR POR ALGO
        #   SERIA POR ESSA FUNÇÃO E COMO AMANTE EU TERIA ESSA INTERFACE (JsonSerializable)
            return get_object_vars($this);
        }  
    }
?>
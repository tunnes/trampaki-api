<?php
    class PrestadoDeServico extends Usuario{
        public $codePrestador;
        public $dsProfissional;
        public $qtAreaDeAlcance;
        
        public function __construct($nome, $email, $telefone, Endereco $endereco, Login $login, $dsProfissional, $qtAreaDeAlcance){
            parent::__construct($nome, $email, $telefone,$endereco,$login);
            $this->dsProfissional = $dsProfissional;
            $this->qtAreaDeAlcance = $qtAreaDeAlcance;
        }
        
        public function getCodigoPrestador(){
            return $this->codePrestador; 
        }
        
        public function novoPrestador(){
            $bancoDeDados = Database::getInstance();
            $querySQL = "INSERT INTO prestadorDeServico (cd_usuario, ds_perfilProfissional, qt_areaAlcance) VALUES (:cd_usuario, :ds_perfilProfissional, :qt_areaAlcance)";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
            $comandoSQL->bindParam(':cd_usuario', $this->getCodigoUsuario());
            $comandoSQL->bindParam(':ds_perfilProfissional', $this->dsProfissional);
            $comandoSQL->bindParam(':qt_areaAlcance', $this->qtAreaDeAlcance);
            $comandoSQL->execute();
            $this->codePrestador = $bancoDeDados->lastInsertId();            
        }
        public function selecionarCategoria($codeCategoria){
            $bancoDeDados = Database::getInstance();
            $querySQL = "INSERT INTO categoriaPrestador (cd_prestadorDeServico, cd_categoria) VALUES (:cd_prestadorDeServico, :cd_categoria)";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
            $comandoSQL->bindParam(':cd_prestadorDeServico', $this->codePrestador);
            $comandoSQL->bindParam(':cd_categoria', $codeCategoria);
            $comandoSQL->execute();
        }
    }
?>
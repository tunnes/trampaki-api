<?php
    class prestadorDAO{
    #   Padrão de Projeto Singleton -----------------------------------------------------------------------------    
        private static $instance;
        public function getInstance(){
            return !isset(self::$instance) ? self::$instance = new prestadorDAO() : self::$instance;
        }
        
    #   Funções de acesso ao banco ------------------------------------------------------------------------------
        public function cadastrarPrestador($objetoPrestador){
        #   Cadastrando um usuário genérico e recebendo seu 
        #   atributo identificador do banco de dados:    
            $codUsuario = $this->cadastrarUsuario($objetoPrestador, '1');
            
            $bancoDeDados = Database::getInstance();
            $querySQL = "INSERT INTO prestadorDeServico (cd_usuario, ds_perfilProfissional, qt_areaAlcance) 
                         VALUES (:cd_usuario, :ds_perfilProfissional, :qt_areaAlcance)";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
            $comandoSQL->bindParam(':cd_usuario', $codUsuario);
            $comandoSQL->bindParam(':ds_perfilProfissional', $objetoPrestador->getDescricaoProfissional());
            $comandoSQL->bindParam(':qt_areaAlcance', $objetoPrestador->getAreaAlcance());
            $comandoSQL->execute();
            
            return $bancoDeDados->lastInsertId();            
        }
        public function selecionarCategoria($codigoPrestador, $codigoCategoria){
            $bancoDeDados = Database::getInstance();
            $querySQL = "INSERT INTO categoriaPrestador (cd_prestadorDeServico, cd_categoria) VALUES (:cd_prestadorDeServico, :cd_categoria)";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
            $comandoSQL->bindParam(':cd_prestadorDeServico', $codigoPrestador);
            $comandoSQL->bindParam(':cd_categoria', $codigoCategoria);
            $comandoSQL->execute();
        }

    }
?>
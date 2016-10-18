<?php
    require_once ('model/BPO/categoriaBPO.php');

    class CategoriaDAO{
    #   Padrão de Projeto Singleton ---------------------------------------------------------------------------------------------------------    
        private static $instance;
        public function getInstance(){
            return !isset(self::$instance) ? self::$instance = new CategoriaDAO() : self::$instance;
        }
    
    #   Funções de acesso ao banco ----------------------------------------------------------------------------------------------------------
        public function novaCategoria(categoriaBPO $categoriaBPO){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare('INSERT INTO categoria (nm_categoria, ds_categoria) VALUES (:nm_categoria, :ds_categoria)');
            $comandoSQL->bindParam(':nm_categoria', $categoriaBPO->getNome());
            $comandoSQL->bindParam(':ds_categoria', $categoriaBPO->getDescricao());
            $comandoSQL->execute();
        }
        
    }
?>
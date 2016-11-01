<?php
    require_once 'configuration/autoload-geral.php';
    
    class NovaCategoria{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->cadastrarCategoria() : null; 
        }
        private function cadastrarCategoria(){
            $categoriaBPO = new CategoriaBPO(null, $_POST['nome'], $_POST['descricao']);
            $categoriaDAO = CategoriaDAO::getInstance();
            $categoriaDAO->novaCategoria($categoriaBPO);              
            echo "Cadastrado com sucesso.";
        }
    }
?>
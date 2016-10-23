<?php
    require_once ('model/BPO/categoriaBPO.php');
    require_once ('model/DAO/categoriaDAO.php');
    
    class novaCategoria{
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
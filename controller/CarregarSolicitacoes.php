<?php
    class CarregarSolicitacoes{
        public function __construct(){
            $_SERVER['REQUEST_METHOD'] == 'GET' && $_SESSION['logado'] ? $this->verificarUsuaurio() : null; 
        }
        private function verificarUsuaurio(){
            $_SESSION['tipoUsuario'] == 1 ? $this->responsePrestador() : $this->responseAnunciante();         
        }
        private function responseAnunciante(){
            $anuncianteBPO = unserialize($_SESSION['objetoUsuario']);
            $anuncianteDAO = AnuncianteDAO::getInstance();
            $response = $anuncianteDAO->carregarSolicitacoes($anuncianteBPO);
            echo json_encode($response); 
        }
        private function responsePrestador(){
            $prestadorBPO = unserialize($_SESSION['objetoUsuario']);
            $prestadorDAO = PrestadorDAO::getInstance();
            $response = $prestadorDAO->carregarSolicitacoes($prestadorBPO);
            
        }
    }
?>
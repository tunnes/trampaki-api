<?php
    session_start();
    class PainelDeOperacoes{
        public function __construct(){
        #   Verificação de metodo da requisição:
            $_SERVER['REQUEST_METHOD'] == 'GET' && $_SESSION['logado'] != null ? include('view/pagina-operacoes.html') : include('view/pagina-autenticacao.html');
        }
    }
?>
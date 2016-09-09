<?php
    include ('router.php');
    include ('controller/pagina-autenticacao.php');
    include ('controller/pagina-operacoes.php');
    include ('controller/pagina-principal.php');
    include ('controller/novo-prestador.php');
    include ('controller/novo-anunciante.php');
    
    $roteador =  new Router();
    $roteador -> novaRota('/','PaginaPrincipal');
    $roteador -> novaRota('/login','PaginaAutenticacao');
    $roteador -> novaRota('/novo-anunciante','novoAnunciante');
    $roteador -> novaRota('/novo-prestador','novoPrestador');
    $roteador -> novaRota('/painel-de-operacoes','painelDeOperacoes');
    $roteador -> rotear();
?>
<?php
    
    include ('formLogin.php');
    include ('router.php');
    include ('primeira.php');
    include ('controller/paginaInicial.php');
    include ('controller/formCadastro.php');
    include ('controller/painelDeOperacoes.php');
    $roteador =  new Router();
    
    $roteador -> novaRota('/','paginaInicial');
    $roteador -> novaRota('/cadastro','formCadastro');
    $roteador -> novaRota('/primeira','primeira');
    $roteador -> novaRota('/login','formLogin');
    $roteador -> novaRota('/painel-de-operacoes','painelDeOperacoes');
    #$roteador -> mapa();
    $roteador -> rotear();
    
?>
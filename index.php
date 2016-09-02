<hr>
<?php
    
    include ('formLogin.php');
    include ('router.php');
    include ('primeira.php');
    include ('controller/paginaInicial.php');
    include ('controller/formCadastro.php');
    $roteador =  new Router();
    
    $roteador -> novaRota('/','paginaInicial');
    $roteador -> novaRota('/cadastro','formCadastro');
    $roteador -> novaRota('/primeira','primeira');
    $roteador -> novaRota('/register','register');
    $roteador -> novaRota('/login','formLogin');
    #$roteador -> mapa();
    $roteador -> rotear();
    
?>
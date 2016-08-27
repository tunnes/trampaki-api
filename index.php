<hr>
<?php
    
    include ('formLogin.php');
    include ('router.php');
    include ('primeira.php');
    include ('home.php');
    
    $roteador =  new Router();
    
    $roteador -> novaRota('/','home');
    $roteador -> novaRota('/primeira','primeira');
    $roteador -> novaRota('/register','register');
    $roteador -> novaRota('/login','formLogin');
    #$roteador -> mapa();
    $roteador -> rotear();
    
?>
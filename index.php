<hr>
<?php
    # https://www.youtube.com/watch?v=_TjHKWdOF94 CRUD
    
    # Entendo melhor o PDO (Curso completo)
    # https://www.youtube.com/watch?v=etRFu_eJ3vU&list=PLbXvLovKLUIkE78UNFE8UpfOtoDlrpQec
    
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
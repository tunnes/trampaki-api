<?php
    session_start();
    if(isset($_GET['logout'])){
        if($_GET['logout'] == 'ok'){
            Login::efetuarDeslog();
        }
    }
    
    if(isset($_SESSION['logado'])){
        echo "Bem vindo... Eu sei seu nome!".$_SESSION['nome'];
        echo '<br>';
        echo "<a href='logado.php?logout=ok'>Logout</a>";
        
    }else{
        echo "Você nao tem permissão de acesso";
    }
    
    include('view/testeLogin.html');
?>
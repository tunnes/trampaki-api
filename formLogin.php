<?php
    if(isset($_POST['enviar'])){
    #   A função PHP 'filter_input()' tem como finalidade obter a variavel especifica do formulario.
    #   O 'FILTER_SANITIZE_MAGIC_QUOTES' retorna uma barra invertida na frente das aspas simples.
    
        echo $login = filter_input(INPUT_POST, "login", FILTER_SANITIZE_MAGIC_QUOTES);
        echo $senha = filter_input(INPUT_POST, "senha", FILTER_SANITIZE_MAGIC_QUOTES);
        
    }
?>
<!DOCTYPE html>
<html>
    <head>
        
    </head>   
    <body>
        <div id='login'>
            <form action='' method='POST' id='form_login'>
                <label for="login">Login:
                    <input type="text" name="login" class="input" id="input_login"/>
                </label>
                <label for="senha">Senha:
                    <input type="psw" name="senha" class="input" id="input_senha"/>
                </label>
                <label for="submit">
                    <input name="enviar" type="submit"  value="Submit"/>    
                </label>
            </form>
        </div>
    </body>
</html>
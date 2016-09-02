<?php
    require_once('class/usuario.php');
    require_once('class/login.php');
    require_once('class/endereco.php');
    
    class FormCadastro{
        
        public function __construct(){
            if(isset($_POST['cadastrar'])){
            #   Passo 01 ----------------------------------------------------------------------------------------
            #   Neste passo estou verificando se o usuario efetudou o request POST pelo sumit 'cadastrar' que se 
            #   encontra na camada view, como tenho injeção de dependencia com usuario, login e endereco ambos 
            #   tem que serem declarados na seguinte sequencia:
            
                $formLogin = filter_input(INPUT_POST, "login", FILTER_SANITIZE_MAGIC_QUOTES);
                $formSenha = filter_input(INPUT_POST, "senha", FILTER_SANITIZE_MAGIC_QUOTES);
                $login = new Login($formLogin, $formSenha);
                
                $formNumeroResidencia = filter_input(INPUT_POST, "cd_numeroResidencia", FILTER_SANITIZE_MAGIC_QUOTES);
                $formCEP = filter_input(INPUT_POST, "cd_cep", FILTER_SANITIZE_MAGIC_QUOTES);
                $formCidade = filter_input(INPUT_POST, "nm_cidade", FILTER_SANITIZE_MAGIC_QUOTES);
                $formEstado = filter_input(INPUT_POST, "sg_estado", FILTER_SANITIZE_MAGIC_QUOTES);
                $formLongitude = filter_input(INPUT_POST, "cd_longitude", FILTER_SANITIZE_MAGIC_QUOTES);
                $formLatitude = filter_input(INPUT_POST, "cd_latitude", FILTER_SANITIZE_MAGIC_QUOTES);
                $endereco = new Endereco($formEstado, $formCidade, $formCEP, $formNumeroResidencia, $formLongitude, $formLatitude);
                
                $formNomeUsuario = filter_input(INPUT_POST, "nm_usuario", FILTER_SANITIZE_MAGIC_QUOTES);
                $formEmail = filter_input(INPUT_POST, "nm_email", FILTER_SANITIZE_MAGIC_QUOTES);
                $formTelefone = filter_input(INPUT_POST, "cd_telefone", FILTER_SANITIZE_MAGIC_QUOTES);
                $endereco->novoEndereco();
                $usuario = new Usuario($formNomeUsuario, $formEmail, $formTelefone, $endereco, $login);
                
                
                
            #   --------------------------------------------------------------------------------------------------
            
            #   Passo 02 -----------------------------------------------------------------------------------------
            #   A função 'header()' recebe como parametro um arquivo PHP e direciona o usuario até o endereço, 
            #   inserido na String de seu parametro, neste passo verifico se o método 'efetuarLogin()' 
            #   retornou verdadeiro caso sim, o usuario sera direcionado para a tela principal:
            
                $usuario->novoCadastro() ? print("<hr> Cadastro realizado com sucesso.") : print('Erro ao logar');
            #   --------------------------------------------------------------------------------------------------
            }
            include('view/pageCadastro.html');
        }
    }
?>

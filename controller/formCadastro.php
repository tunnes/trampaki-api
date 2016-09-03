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
                
                $valoresFormulario = array("login"=>"cd_login","senha"=>"cd_senha","nmResidencia"=>"cd_numeroResidencia","cep"=>"cd_cep",
                                           "cidade"=>"nm_cidade","estado"=>"sg_estado","long"=>"cd_longitude","lati"=>"cd_latitude",
                                           "nomeUser"=>"nm_usuario","email"=>"nm_email","tel"=>"cd_telefone");
                                           
                foreach($valoresFormulario as $campo => $valor) {
                    $valoresFormulario[$campo] = filter_input(INPUT_POST, $valoresFormulario[$campo], FILTER_SANITIZE_MAGIC_QUOTES);
                }
                $login = new Login($valoresFormulario[login], $valoresFormulario[senha]);
                $endereco = new Endereco($valoresFormulario[estado], $valoresFormulario[cidade], $valoresFormulario[cep], $valoresFormulario[nmResidencia], $valoresFormulario[long], $valoresFormulario[lati]);
            
                $endereco->novoEndereco();
                $usuario = new Usuario($valoresFormulario[nomeUser], $valoresFormulario[email], $valoresFormulario[tel], $endereco, $login);
                
                
                
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

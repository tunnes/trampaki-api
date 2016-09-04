<?php
    require_once('model/usuario.php');
    require_once('model/anunciante.php');
    require_once('model/categoria.php');
    require_once('model/prestadorDeServico.php');
    require_once('model/login.php');
    require_once('model/endereco.php');
    require_once('model/anuncio.php');
    require_once('model/conexao.php');
    
    class FormCadastro{
        
        public function __construct(){
            $catest = new Categoria();
            $disponiveis = $catest->carregarCategorias();
            echo "Categorias Disponiveis: <br>";
            foreach ($disponiveis as $row) {
                print "Codigo: ".$row["cd_categoria"] . " | Nome: " . $row["nm_categoria"] . " | Descrição: " . $row["ds_categoria"] ."<br/>";
            }  
            
            if(isset($_POST['cadastrar'])){
            #   Passo 01 ----------------------------------------------------------------------------------------
            #   Neste passo estou verificando se o usuario efetudou o request POST pelo sumit 'cadastrar' que se 
            #   encontra na camada view, como tenho injeção de dependencia com usuario, login e endereco ambos 
            #   tem que serem declarados na seguinte sequencia, foi utlizado:
            #       ->  Para redução de código um array associativo contendo todos os valores 
            #           dos campos do formulario.
            #       ->  Para validação de campos e remoção de possiveis elementos nocivos ao 
            #           sistema a função 'filter_input()'     
            
                
                $valoresFormulario = array( "login"=>"cd_login",
                                            "senha"=>"cd_senha",
                                            "nmResidencia"=>"cd_numeroResidencia",
                                            "cep"=>"cd_cep",
                                            "cidade"=>"nm_cidade",
                                            "estado"=>"sg_estado",
                                            "long"=>"cd_longitude",
                                            "lati"=>"cd_latitude",
                                            "nomeUser"=>"nm_usuario",
                                            "email"=>"nm_email",
                                            "tel"=>"cd_telefone",
                                            "tipo"=>"tipo", 
                                            "ds_cat01"=>"ds_cat01",
                                            "ds_cat02"=>"ds_cat02",
                                            "ds_cat03"=>"ds_cat03",
                                            "qt_areaDeAlcance"=>"qt_areaDeAlcance",
                                            "ds_profissao"=>"ds_profissao"
                                            );
                                           
                foreach($valoresFormulario as $campo => $valor) {
                    $valoresFormulario[$campo] = filter_input(INPUT_POST, $valoresFormulario[$campo], FILTER_SANITIZE_MAGIC_QUOTES);
                }
                $login = new Login($valoresFormulario[login], $valoresFormulario[senha]);
                $endereco = new Endereco($valoresFormulario[estado], $valoresFormulario[cidade], $valoresFormulario[cep], $valoresFormulario[nmResidencia], $valoresFormulario[long], $valoresFormulario[lati]);
            
                $endereco->novoEndereco();
                //$usuario = new Usuario($valoresFormulario[nomeUser], $valoresFormulario[email], $valoresFormulario[tel], $endereco, $login);
                
                if($valoresFormulario[tipo] == 'aunciante'){
                    echo "<br> Eh um anunciante mother focker";
                    $anunciante = new Anunciante($valoresFormulario[nomeUser], $valoresFormulario[email], $valoresFormulario[tel], $endereco, $login);
                    $anunciante->novoCadastro();
                    $anunciante->novoAnunciante();
                    
                #   Cadastrando um anuncio:
                    $anuncio = new Anuncio("AnuncioTeste", "DescriçãoDoAnuncioTeste", "10000KM");
                    
                    $anuncio->novoAnuncio($anunciante->getCodigoAnunciante());
                    
                #   Cadastrando uma categoria ao anuncio:
                    $anuncio->selecionarCategoria(1);
                }else{
                    echo "<br> Eh um prestador focker mother";
                    $prestador = new PrestadoDeServico($valoresFormulario[nomeUser], $valoresFormulario[email], $valoresFormulario[tel], $endereco, $login, $valoresFormulario[ds_profissao], $valoresFormulario[qt_areaDeAlcance]);
                    $prestador->novoCadastro();
                    $prestador->novoPrestador();
                    $prestador->selecionarCategoria(1);
                    
                #   Conexão Prestador De Serviço -> Anuncio;
                    $conexao = new Conexao();
                    $conexao->novaConexao(1, $prestador->getCodigoPrestador());
                    
                }
                
                
            #   --------------------------------------------------------------------------------------------------
            
            #   Passo 02 -----------------------------------------------------------------------------------------
            #   A função 'header()' recebe como parametro um arquivo PHP e direciona o usuario até o endereço, 
            #   inserido na String de seu parametro, neste passo verifico se o método 'efetuarLogin()' 
            #   retornou verdadeiro caso sim, o usuario sera direcionado para a tela principal:
            
                //usuario->novoCadastro() ? print("<hr> Cadastro realizado com sucesso.") : print('Erro ao logar');
            #   --------------------------------------------------------------------------------------------------
            }
            include('view/pageCadastro.html');
        }
    }
?>

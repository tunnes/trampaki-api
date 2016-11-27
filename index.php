<?php
#   Configuração de requisições ao servidor:
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Authorization, Trampaki-ID, trampaki_user");
    header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
    
    require_once 'router.php';
    require_once 'configuration/autoload-geral.php';

    $roteador =  new Router();
    $rotas = array(
        '/login'                     =>'PaginaAutenticacao',
        '/novo-anuncio'              =>'NovoAnuncio',
        '/novo-prestador'            =>'NovoPrestador',
        '/novo-anunciante'           =>'NovoAnunciante',
        '/nova-conexao-prestador'    =>'NovaConexaoPrestador',
        '/nova-conexao-anunciante'   =>'NovaConexaoAnunciante',
        '/nova-categoria'            =>'NovaCategoria',
        '/editar-anuncio'            =>'EditarAnuncio',
        '/editar-anunciante'         =>'EditarAnunciante',
        '/editar-prestador'          =>'EditarPrestador',
        '/carregar-anuncio'          =>'CarregarAnuncio',
        '/carregar-anuncios'         =>'CarregarAnuncios',
        '/carregar-imagem'           =>'CarregarImagem',
        '/carregar-perfil-prestador' =>'CarregarPerfilPrestador',
        '/aceitar-conexao'           =>'AceitarConexao',
        '/carregar-categorias'       =>'CarregarCategorias',
        '/carregar-prestadores'      =>'CarregarPrestadores',
        '/meus-servicos'             =>'CarregarMeusServicos',
        '/carregar-solicitacoes'     =>'CarregarSolicitacoes',
        '/carregar-meus-anuncios'    =>'CarregarMeusAnuncios',
        '/carregar-dados-prestador'  =>'CarregarDadosPrestador',
        '/carregar-dados-anunciante' =>'CarregarDadosAnunciante',
        '/chat'                      =>'Chat'
    );
    // echo "param" .$_GET['param'] . "<hr>";
        
    // echo "param2" .$_GET['param2'] . "<hr>";
    // echo "param3" .$_GET['param3'] . "<hr>";
        
    foreach ($rotas as $URL => $CLASS) { $roteador -> novaRota($URL, $CLASS); }
    $roteador -> rotear();
?>

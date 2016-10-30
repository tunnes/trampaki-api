<?php
    require_once 'router.php';
    require_once 'configuration/autoload-geral.php';

    $roteador =  new Router();
    $rotas = array(
        '/'                         =>'PaginaPrincipal',
        '/login'                    =>'PaginaAutenticacao',
        '/painel-de-operacoes'      =>'PaginaDeOperacoes',
        '/novo-anuncio'             =>'NovoAnuncio',
        '/novo-prestador'           =>'NovoPrestador',
        '/novo-anunciante'          =>'NovoAnunciante',
        '/nova-conexao-prestador'   =>'NovaConexaoPrestador',
        '/nova-conexao-anunciante'  =>'NovaConexaoAnunciante',
        '/nova-categoria'           =>'NovaCategoria',
        '/editar-anuncio'           =>'EditarAnuncio',
        '/editar-anunciante'        =>'EditarAnunciante',
        '/editar-prestador'         =>'EditarPrestador',
        '/carregar-anuncio'         =>'CarregarAnuncio',
        '/carregar-anuncios'        =>'CarregarAnuncios',
        '/carregar-categorias'      =>'CarregarCategorias',
        '/carregar-prestadores'     =>'CarregarPrestadores',
        '/carregar-solicitacoes'    =>'CarregarSolicitacoes',
        '/carregar-meus-anuncios'   =>'CarregarMeusAnuncios',
        '/carregar-dados-prestador' =>'CarregarDadosPrestador',
        '/carregar-dados-anunciante'=>'CarregarDadosAnunciante',
    );
    
    foreach ($rotas as $URL => $CLASS) { $roteador -> novaRota($URL, $CLASS); }
    $roteador -> rotear();
?>
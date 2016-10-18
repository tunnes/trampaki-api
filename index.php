<?php
    include ('router.php');
    include ('controller/pagina-autenticacao.php');
    include ('controller/pagina-operacoes.php');
    include ('controller/pagina-principal.php');
    
    
    
    include ('controller/carregar-anuncio.php');
    include ('controller/carregar-anuncios.php');
    include ('controller/carregar-categorias.php');
    include ('controller/carregar-dados-prestador.php');
    include ('controller/carregar-dados-anunciante.php');
    include ('controller/carregar-prestadores.php');
    include ('controller/carregar-meus-anuncios.php');
    
    include ('controller/editar-dados-anuncio.php');
    include ('controller/editar-dados-anunciante.php');
    include ('controller/editar-dados-prestador.php');
    
    include ('controller/novo-prestador.php');
    include ('controller/novo-anunciante.php');
    include ('controller/nova-categoria.php');
    include ('controller/novo-anuncio.php');
    include ('controller/nova-conexao-anunciante.php');
    include ('controller/nova-conexao-prestador.php');
    
    
    include ('controller/carregar-solicitacoes.php');
        
    $roteador =  new Router();
    $roteador -> novaRota('/','PaginaPrincipal');
    $roteador -> novaRota('/login','PaginaAutenticacao');

    $roteador -> novaRota('/novo-anuncio'             ,'NovoAnuncio');
    $roteador -> novaRota('/novo-prestador'           ,'NovoPrestador');
    $roteador -> novaRota('/novo-anunciante'          ,'NovoAnunciante');
    $roteador -> novaRota('/nova-conexao-prestador'   ,'NovaConexaoPrestador');
    $roteador -> novaRota('/nova-conexao-anunciante'  ,'NovaConexaoAnunciante');
    
    $roteador -> novaRota('/painel-de-operacoes','painelDeOperacoes');
    
    $roteador -> novaRota('/carregar-anuncio','CarregarAnuncio');
    $roteador -> novaRota('/carregar-anuncios','CarregarAnuncios');
    $roteador -> novaRota('/carregar-categorias','CarregarCategorias');
    $roteador -> novaRota('/carregar-dados-prestador','CarregarDadosPrestador');
    $roteador -> novaRota('/carregar-dados-anunciante','CarregarDadosAnunciante');
    $roteador -> novaRota('/carregar-prestadores','CarregarPrestadores');
    $roteador -> novaRota('/carregar-meus-anuncios','CarregarMeusAnuncios');
    
    $roteador -> novaRota('/editar-anuncio','EditarAnuncio');
    $roteador -> novaRota('/editar-anunciante','EditarAnunciante');
    $roteador -> novaRota('/editar-prestador','EditarPrestador');
    
    $roteador -> novaRota('/cadastrar-categoria','novaCategoria');
    
    
    $roteador -> novaRota('/carregar-solicitacoes','CarregarSolicitacoes');
    
    $roteador -> rotear();

?>
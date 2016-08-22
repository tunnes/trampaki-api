/*global google*/

function mapEngine(){
    var mapa;
    
    function inicializar(){
    //  O objeto 'posicaoAtual' recebe como parametro as cordenadas relativas ao ponto onde o usuario se encontra
    //  tornando este o centro da tela será necessário tornar isto dinâmico, com um request Ajax.
        var posicaoAtual = new google.maps.LatLng(-18.8800397, -47.05878999999999);
        
    //  O JSON 'configuracoes' carrega as informações para renderização do mapa, sendo 'zoom' em relação ao nivel inicial
    //  o 'center' para o centro da tela, e o 'mapTypeId' para o tipo de visão do mapa.
        var configuracoes = {
            zoom: 5,
            center: posicaoAtual,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        
        mapa = new google.maps.Map(document.getElementById("mapa"), configuracoes);
    }
    
    inicializar();
    
    function carregarMarcadores(){
    //  O
        var configuracoesMarcador01 = {
            position: new google.maps.LatLng(-19.212355602107472, -44.20234468749999),
            title: "Marcador 01",
            map: mapa
        };
        var marcador01 = new google.maps.Marker(configuracoesMarcador01);
        
        var configuracoesMarcador02 = {
            position: new google.maps.LatLng(-19.212355602107472, -44.20234468749999),
            title: "Marcador 02",
            map: mapa
        };
        var marcador02 = new google.maps.Marker(configuracoesMarcador02);
    }
    
    carregarMarcadores();
}


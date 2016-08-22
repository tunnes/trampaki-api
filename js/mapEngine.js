/*global google*/

function mapEngine(){
    var mapa;
    
//  GERAR MAPA --------------------------------------------------------------------------------------------------------------

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
    
//  GERAR MARCADORES --------------------------------------------------------------------------------------------------------    
    function carregarMarcadores(){
        
    //  O objeto 'configuracoesMarcador01' contem as informações de localização, 
    //  titulo e mapa onde seram exibidos.
        var configuracoesMarcador01 = {
            position: new google.maps.LatLng(-22.57825604463875, -48.68476656249999),
            title: "Marcador 01",
            map: mapa,
            icon: "../img/blackHoleSun.png"
        };
        
    //  Na instancia 'marcador01' passei como parâmetro o objeto cirado 'configuraçõesMarcador01'.
        var marcador01 = new google.maps.Marker(configuracoesMarcador01);
        
        var configuracoesMarcador02 = {
            position: new google.maps.LatLng(-22.618827234831404, -42.57636812499999),
            title: "Marcador 02",
            map: mapa
        };
        var marcador02 = new google.maps.Marker(configuracoesMarcador02);
    }
    
    carregarMarcadores();
}


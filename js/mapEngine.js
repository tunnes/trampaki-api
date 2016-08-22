/*global google*/

function mapEngine(){
    var mapa;
    
//  GERAR MAPA --------------------------------------------------------------------------------------------------------------
    function inicializar(){
    //  O objeto 'posicaoAtual' recebe como parametro as cordenadas relativas ao ponto onde o usuario se encontra
    //  tornando este o centro da tela será necessário tornar isto dinâmico, com um request Ajax.
        var posicaoAtual = new google.maps.LatLng(-23.96425614, -46.38520819);
        
    //  O JSON 'configuracoes' carrega as informações para renderização do mapa, sendo 'zoom' em relação ao nivel inicial
    //  o 'center' para o centro da tela, e o 'mapTypeId' para o tipo de visão do mapa, e o 'disableDefaultUI' desativa
    //  os controles padrões que o Google oferece em seus mapas.
    
        var configuracoes = {
            zoom: 13,
            center: posicaoAtual,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true,
            mapTypeControlOptions: {
                mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
            }
        };
        
    //  Estilizando o mapa
        function estiloDoMapa(){
            var styles = [
                {stylers: [{hue: "#FF8C1F"}, {saturation: 60}, { lightness: -20 }, { gamma: 1.51 }]},
                {featureType: "road", elementType: "geometry", stylers: [{lightness: 100}, {visibility: "simplified"}]},
                {featureType: "road", elementType: "labels"}
            ];
            var styledMap = new google.maps.StyledMapType(styles, {name: "Mapa Style"});
            
            mapa.mapTypes.set('map_style', styledMap);
            mapa.setMapTypeId('map_style');
        }
        
    
    //
        
        mapa = new google.maps.Map(document.getElementById("mapa"), configuracoes);
        estiloDoMapa();
    }
    
//  GERAR MARCADORES --------------------------------------------------------------------------------------------------------    
    function carregarMarcadores(){
        
    //  O objeto 'configuracoesMarcador01' contem as informações de localização, 
    //  titulo e mapa onde seram exibidos. Caras, se virem com isso que ta meio 
    //  na cara o que cada atributo faz.. --'
        var configuracoesMarcador01 = {
            position: new google.maps.LatLng(-23.98154798, -46.45915708),
            title: "M-01 Casa",
            map: mapa,
            icon: "../img/blackHoleSun.png",
            animation: google.maps.Animation.DROP
        };
        
    //  Na instancia 'marcador01' passei como parâmetro o objeto cirado 'configuraçõesMarcador01'.
        var marcador01 = new google.maps.Marker(configuracoesMarcador01);
        
        var configuracoesMarcador02 = {
            position: new google.maps.LatLng(-23.97915736, -46.31190878),
            title: "M02 - Fatec",
            map: mapa,
            icon: "../img/blackHoleSun.png",
            animation: google.maps.Animation.BOUNCE
        };
        var marcador02 = new google.maps.Marker(configuracoesMarcador02);
        
        function carregarInfoWindown(){
              var contentString = 
                   '<h2>Minha Casa</h2>'
                  +'<p>O marcador lindo do Ayrton *-*.</p>'
                  +'<img src="../img/eu.jpg" alt="Um cara normal" height="100" width="100">'
                  +'<br>'
                  +'<a href="https://github.com/tunnes" target="_blank">GitHub</a>';
              var infowindow = new google.maps.InfoWindow({
                  content: contentString,
                  maxWidth: 200
              });
               google.maps.event.addListener(marcador01, 'click', function() {
                infowindow.open(mapa,marcador01);
              });
              
        }
        carregarInfoWindown();
        
    }

//  CHAMADA DE FUNCOES ------------------------------------------------------------------------------------------------------
    inicializar();
    carregarMarcadores();
}


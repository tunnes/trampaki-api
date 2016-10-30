/*global google*/
function mapEngine(){
    var mapa;
    var ultimo = new google.maps.Marker();
    
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
        
    //  Estilizando o mapa --------------------------------------------------------------------------------------------------
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
        
    //  Alcance do Usuario --------------------------------------------------------------------------------------------------        
        function circuloCentral(){
            var circulo = new google.maps.Circle({
                strokeColor: '#FF3110',
                strokeOpacity: 0.35,
                strokeWeight: 1,
                fillColor: '#FF3110',
                fillOpacity: 0.01,
                map: mapa,
                center: {lat: -23.96425614, lng: -46.38520819},
                radius:  3000
            });
            var teste = 0.01;

            setInterval(function() {
                if(circulo.getRadius() >= 5000){
                   circulo.setRadius(500);
                   teste = 0.80
                }else{
                    circulo.setRadius(circulo.getRadius() + 40);
                    teste > 0.006 ? teste = teste - 0.006 : teste = 0.006;
                }
                
                circulo.setOptions({fillOpacity: teste });
                }, 80);
        }
        
        mapa = new google.maps.Map(document.getElementById("mapa"), configuracoes);
        estiloDoMapa();
        //circuloCentral();
    }
    
//  CARREGAR MARCADORES -----------------------------------------------------------------------------------------------------    
    function marcadores(){
            $.ajax({
                type: "GET",
                url:  "http://trampaki-tunnes.c9users.io/carregar-anuncios",
                headers:{
                    "Authorization":"QXlydG9uVGVzdGU6MTIz"
                },
                complete: function(data){   
                    carregarMarcadores(data.responseText);
                }
            });
        function carregarMarcadores(data){
                data = JSON.parse(data);
            var arrayResponse = [].slice.call(data);

                arrayResponse.forEach(function(anuncio){
                    
                    var marcador = new google.maps.Marker({
                        position: new google.maps.LatLng(anuncio.cd_longitude, anuncio.cd_latitude),
                        title: anuncio.titulo,
                        icon: "../trampaki/img/blackHoleSun.png",
                        map: mapa,
                        animation: google.maps.Animation.DROP,
                        imagem: 'https://trampaki-tunnes.c9users.io/carregar-imagem/'+anuncio.cd_imagem01+'',
                        descricaoSimples: anuncio.ds_anuncio,
                        estrelas: anuncio.estrelas,
                        titulo: anuncio.nm_titulo,
                        codigo: anuncio.nm_anuncio
                    });
                    marcador.addListener('click', function(){
                        ultimo.getAnimation() != null ? ultimo.setAnimation(null) : null;
                        carregarVisualizacao(marcador);
                    });
                });
        }
        
        
        function carregarVisualizacao(marcador){
            document.getElementById('titulo').textContent = marcador.titulo;
            document.getElementById('info-moldura').style.opacity = 1;
            document.getElementById('info-moldura').style.height = "auto";
            document.getElementById('info-fundo-imagem').style.backgroundImage = "url(" +marcador.imagem+")";
            document.getElementById('descricao').textContent = marcador.descricaoSimples;
            marcador.setAnimation(google.maps.Animation.BOUNCE);
            ultimo = marcador;
            mapa.addListener('click', function(){
                document.getElementById('info-moldura').style.opacity = 0;
                document.getElementById('info-moldura').style.height = 1;
                ultimo.setAnimation(null);
            });
        }
    }

//  CHAMADA DE FUNCOES ------------------------------------------------------------------------------------------------------
    inicializar();
    marcadores();
}



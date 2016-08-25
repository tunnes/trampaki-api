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
            var c1 = new google.maps.Circle({
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
                if(c1.getRadius() >= 5000){
                   c1.setRadius(500);
                   teste = 0.80
                }else{
                    c1.setRadius(c1.getRadius() + 40);
                    teste > 0.006 ? teste = teste - 0.006 : teste = 0.006;
                }
                
                c1.setOptions({fillOpacity: teste });
                }, 50);

        }
        
        mapa = new google.maps.Map(document.getElementById("mapa"), configuracoes);
        estiloDoMapa();
        circuloCentral();
    }
    
//  CARREGAR MARCADORES -----------------------------------------------------------------------------------------------------    
    function marcadores(){
        function loadJSON(path, success, error){
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function(){
                if (xhr.readyState === XMLHttpRequest.DONE){
                    if (xhr.status === 200) {
                        if (success)
                            success(JSON.parse(xhr.responseText));
                    } else {
                        if (error)
                            error(xhr);
                    }
                }
            };
            xhr.open("GET", path, true);
            xhr.send();
        }
        loadJSON('js/responseMark.json', function(data){ carregarMarcadores(data);}, function(xhr){console.error(xhr);});
        function carregarMarcadores(data){
            var arrayResponse = [].slice.call(data)
                arrayResponse.forEach(function(objeto){
                    var marcador = new google.maps.Marker({
                        position: new google.maps.LatLng(objeto.latitude, objeto.longitude),
                        title: objeto.titulo,
                        icon: "../img/blackHoleSun.png",
                        map: mapa,
                        animation: google.maps.Animation.DROP,
                        imagem: objeto.imagem,
                        descricaoSimples: objeto.descricaoSimples,
                        estrelas: objeto.estrelas
                    });
                    marcador.addListener('click', function() {
                        document.getElementById('notification').style.display = "block";
                    });
                });
        }
    }

//  CHAMADA DE FUNCOES ------------------------------------------------------------------------------------------------------
    inicializar();
    marcadores();
}



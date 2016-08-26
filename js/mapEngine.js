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
                        estrelas: objeto.estrelas,
                        titulo: objeto.titulo
                    });
                    marcador.addListener('click', function(){
                        ultimo.getAnimation() != null ? ultimo.setAnimation(null) : carregarVisualizacao(marcador);
                    });
                });
        }
        function carregarVisualizacao(marcador){
            document.getElementById('titulo').textContent = marcador.titulo;
            document.getElementById('notification').style.display = "block";
            document.getElementById('trojkat_bg').style.backgroundImage = "url(" +marcador.imagem+")";
            document.getElementById('descricao').textContent = marcador.descricaoSimples;
            marcador.setAnimation(google.maps.Animation.BOUNCE);
            ultimo = marcador;
            mapa.addListener('click', function(){
                document.getElementById('notification').style.display = "none";
                ultimo.setAnimation(null);
            });
        }
    }

//  CHAMADA DE FUNCOES ------------------------------------------------------------------------------------------------------
    inicializar();
    marcadores();
}



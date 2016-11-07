/*global $*/ 
function novaJanela(caminho){
    $("#janela").load(caminho);
	$("#janela").show();
	$("#mapa").hide();
}

function retornar(){
	$("#janela").hide();
	$("#mapa").show();
}


function visualizaAnuncio(codigoAnuncio){
    novaJanela("view/ajax/prestador-anuncio.html");
	
	$.ajax({
        type:"GET",
        url:"https://trampaki-tunnes.c9users.io/carregar-anuncio/" + codigoAnuncio,
        headers:{
            "Authorization": sessionStorage.getItem("authorization")
        },
        complete: function(data){
            data = JSON.parse(data.responseText);
            
            var categoriasDOM = document.getElementById('categorias');
                categoriasDOM.innerHTML = '';
            [].slice.call(data.categorias).forEach(function(categoria){
	            categoriasDOM.innerHTML = categoriasDOM.innerHTML + "<div class='categoria'>" + categoria.nome + "</div>";	
            });
            
            var descricaoDOM = document.getElementById('longHistorio');
                descricaoDOM.innerHTML = data.descricao;
			
			var tituloAnuncioDOM = document.getElementById('tituloAnuncioDOM');
                tituloAnuncioDOM.innerHTML = data.titulo;
                
            var caminhoImagem = "url(https://trampaki-tunnes.c9users.io/carregar-imagem/"   
                
            var imagem01 = document.getElementById('imagem01');
                data.cd_imagem_01 != null ? imagem01.style.backgroundImage = caminhoImagem + data.cd_imagem_01 : null;
            
            var imagem02 = document.getElementById('imagem02');
        	    data.cd_imagem_02 != null ? imagem02.style.backgroundImage = caminhoImagem + data.cd_imagem_02 : null;
            
            var imagem03 = document.getElementById('imagem03');
                data.cd_imagem_03 != null ? imagem03.style.backgroundImage = caminhoImagem + data.cd_imagem_03 : null;
                		
            $('#conectar').click(function(){
                enviarSolicitacao(codigoAnuncio);
            });
        }
    });
	
	
	
}
	
function enviarSolicitacao(codigoAnuncio){
	$.ajax({
        type:"POST",
        url:"https://trampaki-tunnes.c9users.io/nova-conexao-prestador",
        headers:{
            "Authorization": sessionStorage.getItem("authorization")
        },
        data:{
        	codigo_anuncio: codigoAnuncio
        },
    	statusCode:{
    		201: function(){
    			alert('Solicitacao enviada');
    		}
    	}
    });
}


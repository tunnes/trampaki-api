function visualizaAnuncio(codigoAnuncio){
	
//	CARREGANDO PÁGINA VIA AJAX ------------------------------------
	console.log(codigoAnuncio + 'fatorial');  
	$("#janela").load("view/ajax/prestador-anuncio.html");
	$("#janela").show();
	$("#mapa").hide();
	//$("#info-moldura").hide();
	$.ajax({
        type: "GET",
        url:  "https://trampaki-tunnes.c9users.io/carregar-anuncio/"+codigoAnuncio,
        headers:{
            "Authorization": sessionStorage.getItem("authorization")
                },
                complete: function(data){
                	
                	data = JSON.parse(data.responseText);
                	var categoriasDOM = document.getElementById('categorias');
                		categoriasDOM.innerHTML = '';
                	[].slice.call(data.categorias).forEach(function(categoria){
                		var div = document.createElement('DIV');
                			div.innerHTML = categoria.nome;
                			div.className = 'categoria';
                		
	                		categoriasDOM.appendChild(div);	
                	});
                	var descricaoDOM = document.getElementById('longHistorio');
                		// console.log(descricaoDOM);
                		descricaoDOM.innerHTML = data.descricao;
					var tituloAnuncioDOM = document.getElementById('tituloAnuncioDOM');
                		// console.log(descricaoDOM);
                		tituloAnuncioDOM.innerHTML = data.titulo;


                	var imagem01 = document.getElementById('imagem01');
                		data.cd_imagem_01 != null ? imagem01.style.backgroundImage = "url(https://trampaki-tunnes.c9users.io/carregar-imagem/" +data.cd_imagem_01+")" : null;
                	var imagem02 = document.getElementById('imagem02');
        	       		data.cd_imagem_02 != null ? imagem02.style.backgroundImage = "url(https://trampaki-tunnes.c9users.io/carregar-imagem/" +data.cd_imagem_02+")" : null;
                	var imagem03 = document.getElementById('imagem03');
                		data.cd_imagem_03 != null ? imagem03.style.backgroundImage = "url(https://trampaki-tunnes.c9users.io/carregar-imagem/" +data.cd_imagem_03+")" : null;
                		
                }
    });
	
	
	
}
	function retornar(){
		$("#janela").hide();
		$("#mapa").show();
		// $("#info-moldura").show();
	}
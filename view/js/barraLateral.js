function barraLateralMensagens(){
    document.getElementById('configuracaoAjax').style.borderBottom = '2px solid white';
    document.getElementById('menu-painel').style.borderBottom = 'none';
    document.getElementById('chat').style.display = 'block';
    document.getElementById('painel').style.display = 'none';
    
    // Future code here....  
}
function barraLateralPainel(){
    document.getElementById('configuracaoAjax').style.borderBottom = 'none';
    document.getElementById('menu-painel').style.borderBottom ='2px solid white';
    document.getElementById('chat').style.display = 'none';
    document.getElementById('painel').style.display = 'block';
    // Future code here....
}

//  VISUALIZAR-PERFIL -------------------------------------------------------------------------------------
    function meuPerfil(){
        document.getElementById('info-moldura').style.opacity = 0;
        document.getElementById('info-moldura').style.height = 1;
        $("#janela").load("https://trampaki-tunnes.c9users.io/view/meu-perfil-prestador.html");
	    $("#janela").show();
	    $("#mapa").hide();
	
        $.ajax({
        type: "GET",
        url:  "https://trampaki-tunnes.c9users.io/carregar-dados-prestador",
        headers:{
            "Authorization":"QXlydG9uVGVzdGU6MTIz"
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
//  -------------------------------------------------------------------------------------------------------
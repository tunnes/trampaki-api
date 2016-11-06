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

//  MEU-PERFIL ------------------------------------------------------------------------------------------------
    function meuPerfil(){
        document.getElementById('info-moldura').style.opacity = 0;
        document.getElementById('info-moldura').style.height = 1;
        $("#janela").load("/view/ajax/prestador-perfil.html");
	    $("#janela").show();
	    $("#mapa").hide();
	    
        $.ajax({
        type: "GET",
        url:  "https://trampaki-tunnes.c9users.io/carregar-dados-prestador",
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
                	var imagem = document.getElementById('imagem_header');
                	    data.codigoImagem != null ? imagem.style.backgroundImage = "url(https://trampaki-tunnes.c9users.io/carregar-imagem/" +data.codigoImagem+")" : null;
                	
                	var nome = document.getElementById('nm_prestador');
                	    nome.innerHTML = data.nome;
                	    nome = document.getElementById('nome');
                	    nome.innerHTML = data.nome;
                	
                	var descricaoDOM = document.getElementById('ds_profissional');
                        descricaoDOM.innerHTML = data.dsProfissional;
                        
                    var email = document.getElementById('ds_email');
                		email.innerHTML = data.email;
                		
                    var cd_telefone = document.getElementById('cd_telefone');
                		cd_telefone.innerHTML = data.telefone;
                		
                	var sg_estado = document.getElementById('sg_estado1');
                	    sg_estado.innerHTML = data.endereco.estado;
                	
                	var header_estado = document.getElementById('header_estado');
                	    header_estado.innerHTML = data.endereco.estado;
                	    
                	
                	var cidade = document.getElementById('cidade');
                	    cidade.innerHTML = data.endereco.cidade;
                	    
                	var cidade = document.getElementById('header_cidade');
                	    cidade.innerHTML = data.endereco.cidade;                	  
                	   
                	var cep = document.getElementById('cep');
                	    cep.innerHTML = data.endereco.CEP;
                	    
                	var numResiden = document.getElementById('numResiden');
                	    numResiden.innerHTML = data.endereco.numeroResidencia;
                	    
                	var login = document.getElementById('login');
                	    login.innerHTML = data.login.login;  

                	var senha = document.getElementById('senha');
                	    senha.innerHTML = data.login.senha;
                	    
                	var token = document.getElementById('token');
                	    token.innerHTML = data.login.token;                	    
                	 
                	 
                		
                }
    });
    }
    
//  MEUS-SERVICOS ---------------------------------------------------------------------------------------------
    function meusServicos(){
        // alert(sessionStorage.getItem("authorization"));
        document.getElementById('info-moldura').style.opacity = 0;
        document.getElementById('info-moldura').style.height = 1;
        $("#janela").load("/view/ajax/prestador-servicos.html");
	    $("#janela").show().hide().fadeIn('slow');;
	    $("#mapa").hide();
	    
	            $.ajax({
        type: "GET",
        url:  "https://trampaki-tunnes.c9users.io/meus-servicos",
        headers:{
            "Authorization": sessionStorage.getItem("authorization")
                },
                complete: function(data){
                	data = JSON.parse(data.responseText);
                	var servicos = document.getElementById('servicos');
                	[].slice.call(data).forEach(function(servico){
                        var item_servico = document.createElement("div");
                        var imagem_servico = document.createElement("div");
                        servico.cd_imagem01 != null ? imagem_servico.style.backgroundImage = "url(https://trampaki-tunnes.c9users.io/carregar-imagem/" +servico.cd_imagem01+")" : null;
                        var info_servico = document.createElement("div");
                        var titulo = document.createElement("strong");
                            titulo.innerHTML = servico.nm_titulo;
                        var cidade = document.createElement("p");
                            cidade.innerHTML = servico.nm_cidade;
                        // var estado = document.createElement('P').innerHTML = servico.sg_estado;
                        var status = document.createElement("p");
                            status.innerHTML = servico.cd_status;
                        
                        item_servico.onclick=function(){
                            visualizaAnuncio(servico.cd_anuncio);
                        };
                        item_servico.className = 'item_servico';
                        imagem_servico.className = 'imagem_servico';
                        info_servico.className = 'info_servico';
                        console.log(item_servico);
                        
                        info_servico.appendChild(titulo);
                        info_servico.appendChild(cidade);
                        info_servico.appendChild(cidade); 
                        // info_servico.appendChild(estado);
                        info_servico.appendChild(status);
                        
                        
                        item_servico.appendChild(imagem_servico);
                        item_servico.appendChild(info_servico);
                        servicos.appendChild(item_servico);
                        console.log(servico);	
                	});
                		
                }
    });
	    
    }

//  SOLICITAÇÕES ----------------------------------------------------------------------------------------------
    function solicitacoes(){
        document.getElementById('info-moldura').style.opacity = 0;
        document.getElementById('info-moldura').style.height = 1;
        $("#janela").load("/view/ajax/prestador-solicitacoes.html");
	    $("#janela").show().hide().fadeIn('slow');;
	    $("#mapa").hide();
    }
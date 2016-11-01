function visualizaAnuncio(codigoAnuncio, janela){
//	CARREGANDO PÁGINA VIA AJAX ------------------------------------
	console.log(codigoAnuncio + 'fatorial');  
	$("#janela").load("view/js/teste.html");
	$("#janela").show();
	$("#mapa").hide();
	$("#info-moldura").hide();
// 	$.ajax({
//         type: "GET",
//         url:  "https://trampaki-tunnes.c9users.io/carregar-anuncio/"+codigoAnuncio,
//         headers:{
//             "Authorization":"QXlydG9uVGVzdGU6MTIz"
//                 },
//                 complete: function(data){   
//                 	document.getElementById('janela').textContent = (data.responseText);
//                 }
//     });
	
	
	
}

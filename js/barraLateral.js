function barraLateralMensagens(){
    document.getElementById('configuracaoAjax').style.borderBottom = '2px solid white';
    document.getElementById('painelAjax').style.borderBottom = 'none';
    document.getElementById('chat').style.display = 'block';
    document.getElementById('painel').style.display = 'none';
    
    // Future code here....  
}
function barraLateralPainel(){
    document.getElementById('configuracaoAjax').style.borderBottom = 'none';
    document.getElementById('painelAjax').style.borderBottom ='2px solid white';
    document.getElementById('chat').style.display = 'none';
    document.getElementById('painel').style.display = 'block';
    // Future code here....
}
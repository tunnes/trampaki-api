<?php
    class Conexao{
        public $status;
        
    public function novaConexao($codigoAnuncio, $codigoPrestadorDeServico){
        $bancoDeDados = Database::getInstance();
        $querySQL = "INSERT INTO conexao (cd_anuncio, cd_PrestadorDeServico, cd_status) VALUES (:cd_anuncio, :cd_PrestadorDeServico, 'Ativa')";
        $comandoSQL = $bancoDeDados->prepare($querySQL);
        $comandoSQL->bindParam(':cd_anuncio', $codigoAnuncio);
        $comandoSQL->bindParam(':cd_PrestadorDeServico', $codigoPrestadorDeServico);
        $comandoSQL->execute();
    }    
        
    }

?>
<?php
    session_start();
    class PainelDeOperacoes{
        public function __construct(){
            header('Content-Type: text/html; charset=utf-8');
        #   Abaixo verifico se o usuário esta logado, tendo como base no valor boleano 
        #   do campo 'logado' da 'SESSION' gerada, apartir de um possivel login ou cadastro.
            $_SESSION['logado'] ? $this->logado() : $this->deslogado();
        }

        public function visualizarAnuncios(){
            $bancoDeDados = Database::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT A.cd_anunciante, AN.cd_anuncio, AN.nm_titulo, AN.ds_anuncio, E.cd_latitude, E.cd_longitude 
                                                    FROM usuario as U 
                                                    INNER JOIN endereco as E ON U.cd_endereco = E.cd_endereco
                                                    INNER JOIN anunciante as A ON U.cd_usuario = A.cd_usuario
                                                    INNER JOIN anuncio as AN ON A.cd_anunciante = AN.cd_anunciante");
            $comandoSQL->execute();
            $anuncios = $comandoSQL->fetchAll(PDO::FETCH_ASSOC);
            return $anuncios;
        }
        
        
        public function logado(){
        #   Consulta muito feia aqui -----------------------------------------------------------------------------------------------------------------
            $bancoDeDados = Database::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT usuario.*, endereco.* FROM usuario INNER JOIN endereco 
                                                    ON usuario.cd_endereco = endereco.cd_endereco WHERE usuario.cd_usuario = :cd_usuario");
            $comandoSQL->bindParam(':cd_usuario', $_SESSION['cd_usuario']);
            $comandoSQL->execute();
            $objeto = $comandoSQL->fetch(PDO::FETCH_OBJ);
        #   ------------------------------------------------------------------------------------------------------------------------------------------    
            
            echo "<h1>Painel de Operacoes</h1>";
            echo "<hr>";
        #   --------------------------------------------------------------------------------
            echo "<h2 style='color:gray;'>Informacoes Pessoais:</h2>";
            echo "<p>Nome: ".$objeto->nm_usuario."</p>";
            echo "<p>Email: ".$objeto->nm_email." </p>";
            echo "<p>Telefone: ".$objeto->cd_telefone." </p>";
            echo "<p>Numero Residencial: ".$objeto->cd_numeroResidencia." </p>";
            echo "<p>CEP: ".$objeto->cd_cep." </p>";
            echo "<p>Cidade: ".$objeto->nm_cidade." </p>";
            echo "<p>Estado: ".$objeto->sg_estado." </p>";
            echo "<p>Longitude: ".$objeto->cd_longitude." </p>";
            echo "<p>Latitude: ".$objeto->cd_latitude." </p>";
            echo "<hr>";
        #   --------------------------------------------------------------------------------    
            echo "<h2>Anucios por perto:</h2>";
            echo "<hr>";
            $anuncios = $this->visualizarAnuncios();
            foreach ($anuncios as $row) {
                print "Codigo: "    .$row["cd_anuncio"]. " |  
                       Titulo: "    .$row["nm_titulo"].  " | 
                       Descrição: " .$row["ds_anuncio"]. " |  
                       Longitude: " .$row["cd_latitude"]." |  
                       Latitude: "  .$row["cd_longitude"]."<br/>";
            }
            
        }
        public function deslogado(){
            echo "Voce nao esta logado.";
        }
    }
?>
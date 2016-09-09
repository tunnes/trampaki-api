<?php
    session_start();
    class PainelDeOperacoes{
        
        public function __construct(){
        #   Verificação de sessão:
            $_SESSION['logado'] ? $this->usuarioLogado() : print("N logado");
        }
    
        private function usuarioLogado(){
            $tipo = $this->verificaTipo();
            switch ($_POST['acao']) {
                case 'carregarAnuncios':
                    $response = $this->carregarAnuncios();
                    header('Content-type: application/json');
                    echo $response;
                    break;
                case 'carregarPrestadores':
                    $response = $this->carregarPrestadores();
                    header('Content-type: application/json');
                    echo $response;
                    break;
                case 'dadosPrestador':
                    $response = $this->dadosPrestador();
                    header('Content-type: application/json');
                    echo $response;
                    break;
                default:
                    include('view/pagina-operacoes.html');
                    break;
            }
        }
        private function dadosPrestador(){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT P.*, U.*, E.*, L.* 
                                                    	FROM usuario as U 
                                                    	INNER JOIN endereco as E ON U.cd_endereco = E.cd_endereco
                                                    	INNER JOIN login as L ON U.cd_login = L.cd_login
                                                    	INNER JOIN prestadorDeServico as P ON U.cd_usuario = P.cd_usuario
                                                        WHERE U.cd_usuario = :cd_usuario");
            $comandoSQL->bindParam(':cd_usuario', $_SESSION['codigoUsuario']);
            $comandoSQL->execute();
            $dadosPrestador = json_encode($comandoSQL->fetchAll(PDO::FETCH_ASSOC));
            return $dadosPrestador;       
        }
        private function carregarPrestadores(){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT P.cd_prestadorDeServico, U.nm_usuario, E.cd_lat, E.cd_lon 
	                                                    FROM usuario as U 
	                                                    INNER JOIN endereco as E ON U.cd_endereco = E.cd_endereco
	                                                    INNER JOIN prestadorDeServico as P ON U.cd_usuario = P.cd_usuario");
            $comandoSQL->execute();
            $prestadores = json_encode($comandoSQL->fetchAll(PDO::FETCH_ASSOC));
            return $prestadores;            
        }
        private function carregarAnuncios(){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT A.cd_anunciante, AN.cd_anuncio, AN.nm_titulo, AN.ds_anuncio, E.cd_lat, E.cd_lon 
                                                    FROM usuario as U 
                                                    INNER JOIN endereco as E ON U.cd_endereco = E.cd_endereco
                                                    INNER JOIN anunciante as A ON U.cd_usuario = A.cd_usuario
                                                    INNER JOIN anuncio as AN ON A.cd_anunciante = AN.cd_anunciante");
            $comandoSQL->execute();
            $anuncios = json_encode($comandoSQL->fetchAll(PDO::FETCH_ASSOC));
            return $anuncios;
        }
        private function verificaTipo(){
            $this->ehPrestador()  && !$this->ehAnunciante() ? $tipo = "prestador"  : null;
            $this->ehAnunciante() && !$this->ehPrestador()  ? $tipo = "anunciante" : null;
            $this->ehPrestador()  && $this->ehAnunciante()  ? $tipo = "hibrido"    : null;
            return $tipo;            
        } 
        private function ehAnunciante(){
            $bancoDeDados = Database::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM anunciante WHERE cd_usuario = :cd_usuario");
            $comandoSQL->bindParam(':cd_usuario', $_SESSION['codigoUsuario']);
            $comandoSQL->execute();
            return $comandoSQL->rowCount() == 1 ? true : false;
        }
        private function ehPrestador(){
            $bancoDeDados = Database::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT * FROM prestadorDeServico WHERE cd_usuario = :cd_usuario");
            $comandoSQL->bindParam(':cd_usuario', $_SESSION['codigoUsuario']);
            $comandoSQL->execute();
            return $comandoSQL->rowCount() == 1 ? true : false;
        }
    }
?>
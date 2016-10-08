<?php
    require_once("model/BPO/prestador.php");
    require_once("model/DAO/anuncioDAO.php");
    
    session_start();
    class PainelDeOperacoes{
        
        public function __construct(){
        #   Verificação de sessão:
            $_SESSION['logado'] ? $this->usuarioLogado() : header('Location: login');
        }
        private function usuarioLogado(){
            switch ($_POST["acao"]) {
                case 'carregarMeusAnuncios':
                    break;
                case 'carregarAnuncios':
                #   Criar uma validação pra saber se o cara é prestador ou hibrido..
                    $response = $this->carregarAnuncios();
                    break;
                case 'carregarPrestadores':
                #   Criar uma validação pra saber se o cara é anunciante ou hibrido..
                    $response = $this->carregarPrestadores();
                    header('Content-type: application/json');
                    echo $response;
                    break;
                case 'dadosPrestador':
                #   Criar uma validação pra saber se o cara é prestador mesmo ou hibrido..
                    $response = $this->dadosPrestador();
                    header('Content-type: application/json');
                    echo $response;
                    break;
                case 'dadosAnunciante':
                #   Criar uma validação pra saber se o cara é prestador mesmo ou hibrido..
                    $response = $this->dadosAnunciante();
                    header('Content-type: application/json');
                    echo $response;                    
                    break;
                case 'dadosAnuncio':
                    $response = $this->dadosAnuncio();
                    header('Content-type: application/json');
                    echo $response; 
                    break;
                case 'editarPrestador':
                #   Basta executar um UPDATE..
                    break;
                case 'editarAnunciante':
                #   Basta executar um UPDATE..
                    break;
                case 'editarAnuncio':
                #   Basta executar um UPDATE..    
                    break;
                default:
                    include('view/pagina-operacoes.html');
                    break;
            }
        }
        private function dadosPrestador(){
            $obj = unserialize($_SESSION['objetoUsuario']);
            $prestadorDAO = PrestadorDAO::getInstance();
            $response = $prestadorDAO->carregarPerfil($obj->getCodigoPrestador());
            echo json_encode($response);
        }
        private function dadosAnunciante(){
            $obj = unserialize($_SESSION['objetoUsuario']);
            $anuncianteDAO = AnuncianteDAO::getInstance();
            $response = $anuncianteDAO->carregarPerfil($obj->getCodigoAnunciante());
            echo json_encode($response);
        }
        private function dadosAnuncio(){
            $anuncioDAO = AnuncioDAO::getInstance();
            $response = $anuncioDAO->consultarAnuncio($codigoAnuncio);
            return json_encode($response);
        }
        private function carregarPrestadores(){
           $prestadorDAO = PrestadorDAO::getInstance();
           $response = json_encode($prestadorDAO->listarPrestadores());
           return $response; 
        }
        private function carregarAnuncios(){
            $anuncioDAO = AnuncioDAO::getInstance();
            $response = $anuncioDAO->listarAnuncios();
            header('Content-type: application/json');
            echo json_encode($response);
        }
        private function cadastrarAnuncio(){
        #   Criar uma validação dos dados a serem inseridos..
            $anuncioDAO = AnuncioDAO::getInstance();
            $anuncioDAO->cadastrarAnuncio($codigoAnunciante, $titulo, $descricao, $areaAlcance);
        }
        private function editarPrestador(){}
        private function editarAnunciante(){}
        private function editarAnuncio(){}
    }
?>
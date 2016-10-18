<?php
    session_start();
    require_once("model/BPO/anuncianteBPO.php");
    require_once("model/DAO/anuncianteDAO.php");

    class CarregarDadosAnunciante{
        public function __construct(){
        #   Verificação de metodo da requisição:
            $_SERVER['REQUEST_METHOD'] == 'POST' ? $this->validarSessao() : include('view/pagina-404.html');
        }
        private function validarSessao(){
            switch ($_SESSION['tipoUsuario']){
                    case 0:
                    case 2:
                        $this->carregarDadosAnunciante();
                        break;
                    case 1:
                        echo 'voce não possui privilegio para isto malandrãoo!';
                        break;
                    default:
                        header('Location: login');  
                        break;
            }
        }
        private function carregarDadosAnunciante(){
            $anuncianteBPO  = unserialize($_SESSION['objetoUsuario']);
            header('Content-type: application/json');
            echo json_encode(array(
                'cd_usuario' => $anuncianteBPO->getCodigoUsuario(),
                'nm_usuario' => $anuncianteBPO->getNome(),
                'ds_email' => $anuncianteBPO->getEmail(),
                'ds_telefone' => $anuncianteBPO->getTelefone(),
                'cd_tipo' => 0,
                'cd_login' => $anuncianteBPO->getLogin()->getLogin(),
                'ds_login' => $anuncianteBPO->getLogin()->getLogin(),
                'ds_senha' => $anuncianteBPO->getLogin()->getSenha(),
                'cd_endereco' => $anuncianteBPO->getEndereco()->getCodigoEndereco(),
                'sg_estado' => $anuncianteBPO->getEndereco()->getEstado(),
                'nm_cidade' => $anuncianteBPO->getEndereco()->getCidade(),
                'cd_cep' => $anuncianteBPO->getEndereco()->getCEP(),
                'cd_numeroResidencia' => $anuncianteBPO->getEndereco()->getNumeroResidencia(),
                'cd_logitude' => $anuncianteBPO->getEndereco()->getLongitude(),
                'cd_latitude' => $anuncianteBPO->getEndereco()->getLatitude(),
            ));
        }
    }
?>

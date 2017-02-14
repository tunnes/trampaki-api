<?php
    require_once 'configuration/autoload-geral.php';
    
    class AnuncianteDAO extends UsuarioDAO{
    #   Padrão de Projeto Singleton ---------------------------------------------------------------------------------------------------------    
        private static $instance;
        public function getInstance(){
            return !isset(self::$instance) ? self::$instance = new anuncianteDAO() : self::$instance;
        }
        
    #   Funções de acesso ao banco ----------------------------------------------------------------------------------------------------------
        public function cadastrarAnunciante(AnuncianteBPO $anuncianteBPO){
        #   Cadastrando um endereco e um login, recebendo assim seus atributos, identificadores do banco de dados tornar fisica o conceito 
        #   de presente no projeto agregação:
            $loginDAO      = LoginDAO::getInstance();
            $loginBPO      = $loginDAO->cadastrarLogin($anuncianteBPO->getLogin());
            
            $enderecoDAO   = EnderecoDAO::getInstance();
            $enderecoBPO   = $enderecoDAO->cadastrarEndereco($anuncianteBPO->getEndereco());
            
            $codigoUsuario = $this->cadastrarUsuario(
                $anuncianteBPO->getNome(), 
                $anuncianteBPO->getEmail(), 
                $loginBPO->getCodigoLogin(), 
                $enderecoBPO->getCodigoEndereco(), 
                $anuncianteBPO->getTelefone(), 
                '0',
                $anuncianteBPO->getCodigoImagem()
            );
            $bancoDeDados  = Database::getInstance();
            $comandoSQL = $bancoDeDados->prepare('INSERT INTO anunciante (cd_usuario) VALUES (:cd_usuario)');
            $comandoSQL->bindParam(':cd_usuario', $codigoUsuario);
            $comandoSQL->execute();
            return $anuncianteBPO = new AnuncianteBPO(
                $codigoUsuario, 
                $anuncianteBPO->getNome(), 
                $anuncianteBPO->getEmail(), 
                $anuncianteBPO->getTelefone(), 
                $enderecoBPO, 
                $loginBPO,
                $anuncianteBPO->getCodigoImagem()
            );
        }
        public function editarAnunciante(AnuncianteBPO $anuncianteBPO){
            $bancoDeDados  = Database::getInstance();
            $loginDAO      = LoginDAO::getInstance();
            $enderecoDAO   = EnderecoDAO::getInstance();
            
            $loginBPO      = $loginDAO->editarLogin($anuncianteBPO->getLogin());
            $enderecoBPO   = $enderecoDAO->editarEndereco($anuncianteBPO->getEndereco());
            
            $this->editarUsuario($anuncianteBPO);
        }
        public function carregarSolicitacoes(AnuncianteBPO $anuncianteBPO){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT C.cd_conexao, C.cd_solicitante, C.cd_status, U.cd_imagem, U.cd_usuario, U.nm_usuario, A.cd_anuncio, A.nm_titulo 
                                                    FROM conexao AS C 
                                                        INNER JOIN anuncio AS A ON C.cd_anuncio = A.cd_anuncio 
                                                        INNER JOIN usuario AS U ON C.cd_usuario = U.cd_usuario
                                                    WHERE A.cd_usuario = :cd_usuario");
            $comandoSQL->bindParam(':cd_usuario', $anuncianteBPO->getCodigoUsuario());               
            $comandoSQL->execute();
            return $comandoSQL->fetchAll(PDO::FETCH_OBJ);              
        }
        public function consultar($codigoUsuario, $opcao = null){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT A.*, U.*, E.*, L.* 
                                                    	FROM usuario as U 
                                                    	INNER JOIN endereco as E ON U.cd_endereco = E.cd_endereco
                                                    	INNER JOIN login as L ON U.cd_login = L.cd_login
                                                    	INNER JOIN anunciante as A ON U.cd_usuario = A.cd_usuario
                                                        WHERE A.cd_usuario = :cd_usuario");
            $comandoSQL->bindParam(':cd_usuario', $codigoUsuario);
            $comandoSQL->execute();
            $row = $comandoSQL->fetch(PDO::FETCH_OBJ);
            if($opcao == 'perfil'){
                return $row;
            }else{
                $enderecoBPO = new EnderecoBPO(
                    $row->cd_endereco, 
                    $row->sg_estado, 
                    $row->nm_cidade, 
                    $row->cd_cep, 
                    $row->cd_numeroResidencia, 
                    $row->cd_longitude, 
                    $row->cd_latitude
                );
                $loginBPO = new LoginBPO(
                    $row->cd_login, 
                    $row->ds_login, 
                    $row->ds_senha, 
                    $row->cd_token
                );
                $anuncianteBPO = new AnuncianteBPO(
                    $row->cd_usuario, 
                    $row->nm_usuario, 
                    $row->ds_email, 
                    $row->ds_telefone, 
                    $enderecoBPO, 
                    $loginBPO, 
                    $row->cd_imagem,
                    $row->cd_anuncioSelecionado
                );
                return $anuncianteBPO;
            }
            
                   
        }
        public function selecionarAnuncio($cd_anunciante, $cd_anuncioSelecionado){
            $dataBase = DataBase::getInstance();
            $querySQL = "UPDATE anunciante SET cd_anuncioSelecionado = :cd_anuncioSelecionado WHERE cd_usuario = :cd_usuario";
            $comandoSQL =  $dataBase->prepare($querySQL);
            $comandoSQL -> bindParam(':cd_usuario', $cd_anunciante);
            $comandoSQL -> bindParam(':cd_anuncioSelecionado', $cd_anuncioSelecionado);
            $comandoSQL->execute();             
        }
    }
?>
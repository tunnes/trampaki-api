<?php
    require_once 'configuration/autoload-geral.php';
    
    abstract class UsuarioDAO{
        protected function cadastrarUsuario($nome, $email, $codigoLogin, $codigoEndereco, $tel, $tipoUsuario, $codigoImagem){
            $bancoDeDados = DataBase::getInstance();
            $querySQL = "INSERT INTO usuario (nm_usuario, ds_email, cd_login, cd_endereco, ds_telefone, cd_tipo, cd_imagem) 
                                VALUES (:nm_usuario, :ds_email, :cd_login, :cd_endereco, :ds_telefone, :cd_tipo, :cd_imagem)";
                         
            $comandoSQL   = $bancoDeDados->prepare($querySQL);
            $comandoSQL -> bindParam(':ds_email', $email);
            $comandoSQL -> bindParam(':nm_usuario', $nome);
            $comandoSQL -> bindParam(':ds_telefone', $tel);
            $comandoSQL -> bindParam(':cd_login', $codigoLogin);
            $comandoSQL -> bindParam(':cd_endereco', $codigoEndereco);
            $comandoSQL -> bindParam(':cd_tipo', $tipoUsuario);
            $comandoSQL -> bindParam(':cd_imagem', $codigoImagem);
            $comandoSQL->execute();
            return $bancoDeDados->lastInsertId();
        }
        protected function editarUsuario($objetoUsuario){
            $bancoDeDados = Database::getInstance();
            $querySQL   = "UPDATE usuario SET 
                           nm_usuario = :nm_usuario, ds_email = :ds_email, ds_telefone = :ds_telefone  
                           WHERE cd_usuario = :cd_usuario";
            $comandoSQL =  $bancoDeDados->prepare($querySQL);
            $comandoSQL -> bindParam(':nm_usuario',  $objetoUsuario->getNome());
            $comandoSQL -> bindParam(':ds_email',    $objetoUsuario->getEmail());
            $comandoSQL -> bindParam(':ds_telefone', $objetoUsuario->getTelefone());
            $comandoSQL -> bindParam(':cd_usuario',  $objetoUsuario->getCodigoUsuario());
            $comandoSQL->execute();
        }
        public function novaConexao($codigoPrestador, $codigoAnuncio, $enumSolicitante){
            echo $enumSolicitante;
            $bancoDeDados = Database::getInstance();
            $querySQL = "INSERT INTO conexao (cd_usuario, cd_anuncio, cd_status, cd_solicitante) 
                                VALUES (".$codigoPrestador.", ".$codigoAnuncio.", '0', '".$enumSolicitante."')";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
            $comandoSQL->execute();
        }
        public function aceitarConexao($codigoConexao){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL = $bancoDeDados->prepare("UPDATE conexao SET cd_status = '1' WHERE cd_conexao = :cd_conexao");
            $comandoSQL->bindParam(':cd_conexao', $codigoConexao);
            $comandoSQL->execute();
        }
        public function recusarConexao($codigoConexao){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL = $bancoDeDados->prepare("UPDATE conexao SET cd_status = '2' WHERE cd_conexao = :cd_conexao");
            $comandoSQL->bindParam(':cd_conexao', $codigoConexao);
            $comandoSQL->execute();       
        } 
        public function excluirConexao($codigoConexao){
            $bancoDeDados = DataBase::getInstance();
            $comandoSQL = $bancoDeDados->prepare("DELETE FROM conexao WHERE cd_conexao = :cd_conexao");
            $comandoSQL->bindParam(':cd_conexao', $codigoConexao);
            $comandoSQL->execute();       
        }         
        public function consultarConexao($codigoConexao){
            $comandoSQL =  DataBase::getInstance()->prepare('SELECT * FROM conexao WHERE cd_conexao = :cd_conexao');
        	$comandoSQL->bindParam(':cd_conexao', $codigoConexao);
            $comandoSQL->execute();
            return  $comandoSQL->fetch(PDO::FETCH_OBJ);
        }        
        public function getNome($id) {
            $cmd = DataBase::getInstance()->prepare("SELECT nm_usuario FROM usuario WHERE cd_usuario = :id");
            $cmd->bindParam(":id", $id);
            $cmd->execute();
            return $cmd->fetch(PDO::FETCH_OBJ)->nm_usuario;
        }
        public function getTokenFcm($usuario){
            $cmd = DataBase::getInstance()->prepare("SELECT cd_tokenFcm FROM usuario WHERE cd_usuario = :cd_usuario");
            $cmd->bindParam(":cd_usuario", $usuario);
            $cmd->execute();
            return $cmd->fetch(PDO::FETCH_OBJ)->cd_tokenFcm;
        }
    }
?>

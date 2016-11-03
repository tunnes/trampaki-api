<?php
    require_once 'configuration/autoload-geral.php';
    
    class LoginDAO{
        private static $instance;
        public static function getInstance(){
            return !isset(self::$instance) ? self::$instance = new loginDAO() : self::$instance;
            
        }
        public function gerarAutenticacao($token){
            $bancoDeDados = DataBase::getInstance();
            $query = 'SELECT U.cd_usuario, U.cd_tipo FROM login as L INNER JOIN usuario AS U ON U.cd_login = L.cd_login WHERE L.cd_token = :cd_token';
            $comandoSQL   = $bancoDeDados->prepare($query);
            $comandoSQL->bindParam(':cd_token', $token, PDO::PARAM_STR);
            $comandoSQL->execute();
                
            if($comandoSQL->rowCount() == 1){
                $comandoSQL = $comandoSQL->fetch(PDO::FETCH_OBJ);
                switch ($comandoSQL->cd_tipo){
                    case 0:
                        return AnuncianteDAO::getInstance()->consultar($comandoSQL->cd_usuario);
                        break;
                    case 1:
                        return PrestadorDAO::getInstance()->consultar($comandoSQL->cd_usuario);
                        break;
                }
            }
        }
        public function cadastrarLogin(LoginBPO $loginBPO){
        #   Implementação de 'Token' ------------------------------------------------------------------------------
        #   Para começar a utilizar a 'md5' verificar com o Garcia...
            $token = $loginBPO->getLogin(). ':' .$loginBPO->getSenha();
            $token = base64_encode($token);
        
        #   -------------------------------------------------------------------------------------------------------
            
            $dataBase = DataBase::getInstance();
            $querySQL = "INSERT INTO login (ds_login, ds_senha, cd_token) VALUES (:ds_login, :ds_senha, :cd_token)";
            $comandoSQL =  $dataBase->prepare($querySQL);
            $comandoSQL -> bindParam(':ds_login', $loginBPO->getLogin());
            $comandoSQL -> bindParam(':ds_senha', $loginBPO->getSenha());
            $comandoSQL -> bindParam(':cd_token', $token);    
            $comandoSQL->execute();
            $loginBPO = new LoginBPO($dataBase->lastInsertId(), $loginBPO->getLogin(), $loginBPO->getSenha(), $token); 
            return $loginBPO; 
        }
        public function editarLogin(LoginBPO $loginBPO){
            $dataBase = DataBase::getInstance();
            $querySQL = "UPDATE login SET ds_login = :ds_login, ds_senha = :ds_senha WHERE cd_login = :cd_login";
            $comandoSQL =  $dataBase->prepare($querySQL);
            $comandoSQL -> bindParam(':cd_login', $loginBPO->getCodigoLogin());
            $comandoSQL -> bindParam(':ds_login', $loginBPO->getLogin());
            $comandoSQL -> bindParam(':ds_senha', $loginBPO->getSenha());
            $comandoSQL->execute(); 
        }
        public function consultarLogin($codigoLogin){
            $dataBase = DataBase::getInstance();
            $querySQL = "SELECT * FROM login WHERE (cd_login = :cd_login)";
            $comandoSQL =  $dataBase->prepare($querySQL);
            $comandoSQL -> bindParam(':cd_login', $codigoLogin);
            $comandoSQL->execute();
            $consulta = $comandoSQL->fetch(PDO::FETCH_OBJ);
            return new LoginBPO($consulta->cd_login, $consulta->ds_login, $consulta->ds_senha);   
        }
        
    }
?>
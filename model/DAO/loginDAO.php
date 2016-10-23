<?php
    require_once('configuration/dataBase.php');
    require_once('model/BPO/loginBPO.php');
    
    class LoginDAO{
        private static $instance;
        public static function getInstance(){
            return !isset(self::$instance) ? self::$instance = new loginDAO() : self::$instance;
            
        }
        public function gerarAutenticacao($login, $senha){
            $bancoDeDados = Database::getInstance();
            $comandoSQL   = $bancoDeDados->prepare("SELECT usuario.*, login.* FROM login  
                                                        INNER JOIN usuario ON usuario.cd_login = login.cd_login
                                                        WHERE login.ds_login = :ds_login AND login.ds_senha = :ds_senha");
            $comandoSQL->bindParam(':ds_login', $login, PDO::PARAM_STR);
            $comandoSQL->bindParam(':ds_senha', $senha, PDO::PARAM_STR);
            $comandoSQL->execute();
                
            if($comandoSQL->rowCount() == 1){
                $comandoSQL = $comandoSQL->fetch(PDO::FETCH_OBJ);
                switch ($comandoSQL->cd_tipo){
                    case 0:
                        $anuncianteDAO = AnuncianteDAO::getInstance();
                        $anuncianteBPO = $anuncianteDAO->consultarAnunciante($comandoSQL->cd_usuario);
                        $anuncianteBPO->getLogin()->iniciarSessao($anuncianteBPO, '0');
                        break;
                    case 1:
                        $prestadorDAO = PrestadorDAO::getInstance();
                        $prestadorBPO = $prestadorDAO->consultarPrestador($comandoSQL->cd_usuario);
                        $prestadorBPO->getLogin()->iniciarSessao($prestadorBPO, '1');
                        break;
                }
                
                return true;
            }else{
                return false;
            }
        }
        public function cadastrarLogin(LoginBPO $loginBPO){
            $dataBase = DataBase::getInstance();
            $querySQL = "INSERT INTO login (ds_login, ds_senha) VALUES (:ds_login, :ds_senha)";
            $comandoSQL =  $dataBase->prepare($querySQL);
            $comandoSQL -> bindParam(':ds_login', $loginBPO->getLogin());
            $comandoSQL -> bindParam(':ds_senha', $loginBPO->getSenha());
            $comandoSQL->execute();
            $loginBPO = new LoginBPO($dataBase->lastInsertId(), $loginBPO->getLogin(), $loginBPO->getSenha()); 
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
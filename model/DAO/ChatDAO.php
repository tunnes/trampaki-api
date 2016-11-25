<?php
require_once 'configuration/autoload-geral.php';

class ChatDAO {
    private static $instance;

    public function getInstance() {
        return !isset(self::$instance) ? self::$instance = new ChatDAO() : self::$instance;
    }

    public function novoChat($uu, $ud) {
        $cmd = Database::getInstance()->prepare("INSERT INTO chat (cd_usuario_um, cd_usuario_dois) VALUES (:um, :dois)");
        $cmd->bindParam(":um",   $uu);
        $cmd->bindParam(":dois", $ud);
        $cmd->execute();
    }
    
    public function checarChat($uu, $ud) {
        $cmd = Database::getInstance()->prepare("SELECT * FROM chat WHERE cd_usuario_um = least(:um, :dois) AND cd_usuario_dois = greatest(:um, :dois)");
        $cmd->bindParam(":um",   $uu);
        $cmd->bindParam(":dois", $ud);
        $cmd->execute();
        $res = $cmd->fetch(PDO::FETCH_OBJ);
        return isset($res) ? new ChatBPO($res->cd_usuario_um, $res->cd_usuario_dois) : $res;
    }
    
    public function listarContatos($uu) {
        $cmd = Database::getInstance()->prepare(
            "SELECT * FROM conexao
                INNER JOIN anuncio ON conexao.cd_anuncio = anuncio.cd_anuncio
                INNER JOIN usuario ON anuncio.cd_usuario = usuario.cd_usuario
                    WHERE conexao.cd_usuario = :usuario AND conexao.cd_status = 1");
        $cmd->bindParam(":usuario", $uu);
        $cmd->execute();
        $res = $cmd->fetchAll(PDO::FETCH_OBJ);
        
    }
}

?>

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
    
    public function listarContatos($uu, $ty) {
        $cmd = Database::getInstance()->prepare(
            "SELECT u1.cd_usuario AS cuu, u1.nm_usuario AS nuu, u1.cd_imagem AS ciu,
                    u2.cd_usuario AS cud, u2.nm_usuario AS nud, u2.cd_imagem AS cid
                      FROM usuario AS u1
                INNER JOIN conexao AS c  ON u1.cd_usuario =  c.cd_usuario
                INNER JOIN anuncio AS a  ON  c.cd_anuncio =  a.cd_anuncio
                INNER JOIN usuario AS u2 ON  a.cd_usuario = u2.cd_usuario
                    WHERE " . $ty . ".cd_usuario = :usuario AND c.cd_status = 1");
        $cmd->bindParam(":usuario", $uu);
        $cmd->execute();
        $res = $cmd->fetchAll(PDO::FETCH_OBJ);
        return isset($res) ? array_map(function($con) {
            return new ConexaoBPO(
                $con->cuu, $con->ciu, $con->nuu,
                $con->cud, $con->cid, $con->nud
            );
        }, $res) : $res;
    }
}

?>

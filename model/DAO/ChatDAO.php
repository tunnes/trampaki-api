<?php
require_once 'configuration/autoload-geral.php';

public class ChatDAO {
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
}

?>

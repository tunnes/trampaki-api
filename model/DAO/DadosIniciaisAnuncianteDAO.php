<?php
require_once 'configuration/autoload-geral.php';

class DadosIniciaisAnuncianteDAO{

    public function carregarDados($id) {
        $cmd = DataBase::getInstance()->prepare("SELECT MAX(c.cd_anuncio) FROM conexao AS c INNER JOIN anuncio AS a ON c.cd_anuncio = a.cd_anuncio WHERE a.cd_usuario = 1 AND c.cd_status = '1'");
        $cmd->bindParam(":um",   $uu);
        $cmd->bindParam(":dois", $ud);
        $cmd->execute();
        return $this->checarChat($uu, $ud);
    }
    
    private function ultimoAnuncioAceito($uu, $ud) {
        $cmd = DataBase::getInstance()->prepare("SELECT * FROM chat WHERE cd_usuario_um = least(:um, :dois) AND cd_usuario_dois = greatest(:um, :dois)");
        $cmd->bindParam(":um",   $uu);
        $cmd->bindParam(":dois", $ud);
        $cmd->execute();
        $res = $cmd->fetch(PDO::FETCH_OBJ);
        return $res != null ? new ChatBPO($res->cd_usuario_um, $res->cd_usuario_dois) : $res;
    }
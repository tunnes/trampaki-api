<?php

class ChatBPO implements JsonSerializable {
    private $usuarioUm;
    private $usuarioDois;
    
    public function __construct($usuarioUm, $usuarioDois) {
        $this->usuarioUm = $usuarioUm;
        $this->usuarioDois = $usuarioDois;
    }
    
    public function __toString() {
        return $usuarioUm . $usuarioDois;
    }
    
    public function getUsuarioUm() {
        return $this->usuarioUm;
    }
    public function getUsuarioDois() {
        return $this->usuarioDois;
    }
    
    public function jsonSerialize() {
        return get_object_vars($this);
    }
}

?>
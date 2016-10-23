<?php

class Chat {
    private $host = "";
    private $usuario;
    private $socket;

    private function getErr() {
        return socket_last_error() . ": " .  socket_strerror(socket_last_error());
    }

    private function __construct(Usuario $u) {
        $this->usuario = $u;
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die(getErr());
                        socket_bind  ($this->socket, "localhost")    or die(getErr());
                        socket_listen($this->socket)                 or die(getErr());
    }

    public static function abrirChat(Usuario $u) {
        return new Chat($u);
    }

    public static function aceitarChat(Chat $chat) {
        $chat->socket = socket_accept($chat->socket) or die(getErr());
        return $chat;
    }
}

?>

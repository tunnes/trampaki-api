<?php

class Chat {
    private $host = "";
    private $self;
    private $user;
    private $socket;

    private static final $history = "/"

    private function getErr() {
        return socket_last_error() . ": " . socket_strerror(socket_last_error());
    }

    private function __construct(Usuario $s, Usuario $u) {
        $this->self = $s;
        $this->user = $u;
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die(getErr());
                        socket_bind  ($this->socket, "localhost")    or die(getErr());
                        socket_listen($this->socket)                 or die(getErr());
    }

    public static function abrirChat(Usuario $s, Usuario $u) {
        return new Chat($s, $u);
    }

    public static function aceitarChat(Chat $chat) {
        $chat->socket = socket_accept($chat->socket) or die(getErr());
        $ms = $history . $chat->self->getCodigoUsuario() . "/" . $chat->user->getCodigoUsuario();
        if (file_exists($ms)) {
            foreach(file($ms) as $m) {
                socket_write($chat->socket, $m) or die(getErr());
            }
        }
        return $chat;
    }
}

?>

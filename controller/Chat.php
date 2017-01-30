<?php

require_once 'configuration/autoload-geral.php';

class Chat {
    private $chatFolder;
    
    public function __construct() {
        $this->chatFolder = __DIR__ . '/../chat/';
        set_time_limit(0);
        header('Content-type: application/json');
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                
                if ($_GET['param2'] != null) {
                    $this->checkMessages($_GET['param'], $_GET['param2'], $_GET['param3']);
                }
                else {
                    apache_request_headers()['trampaki_user'] == 1 ? $this->listContacts($_GET['param'], "c", "u2") : $this->listContacts($_GET['param'], "a", "u1");
                }
                break;
            case 'POST':
                $this->writeMessage($_GET['param'], $_GET['param2']);
                break;
        }
    }
    
    private function checkMessages($uu, $ud, $ts) {
        while(true) {
            $lastCall     = $ts != "" ? (int) $ts : null;
            $maybeMessage = ChatDAO::getInstance()->checarChat($uu, $ud);
            
            if ($maybeMessage == null) {
                $maybeMessage = ChatDAO::getInstance()->novoChat($uu, $ud);
            }
            
            clearstatcache();
            $chatFile = $this->chatFolder . (string) $maybeMessage;
            if (!file_exists($chatFile)) {
                touch($chatFile);
            }
            
            $lastChange = filemtime($chatFile);
            if ($lastCall == null || $lastChange > $lastCall) {
                $history = array_map(function($arg) {
                    $args = explode(':', $arg, 2);
                    if ($args[0] != "") {
                        return '<span class="namebox">' . PrestadorDAO::getInstance()->getNome($args[0]) . ':</span> ' . $args[1];
                    }
                }, explode("\n", file_get_contents($chatFile)));
                echo json_encode(array(
                    'history'   => $history,
                    'timestamp' => $lastChange
                ));
                break;
            }
            else {
                sleep(1);
                continue;
            }
        }
    }
    private function writeMessage($uu, $ud) {
        $maybeChat = ChatDAO::getInstance()->checarChat($uu, $ud);
        echo ((string) $maybeChat);
        if ($maybeChat != null) {
            file_put_contents($this->chatFolder . (string) $maybeChat, "\n$uu:" . file_get_contents('php://input'), FILE_APPEND);
            header('HTTP/1.1 200 OK');
        }
    }
    private function listContacts($uu, $ty, $ud) {
        echo json_encode(ChatDAO::getInstance()->listarContatos($uu, $ty, $ud));
    }
}

?>

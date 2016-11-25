<?php

class Chat {
    private static $chatFolder = '../chat/';
    
    public function __construct() {
        set_time_limit(0);
        header('Content-type: application/json');
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                if (isset($_GET['param2'])) {
                    $this->checkMessages($_GET['param'], $_GET['param2']);
                }
                else {
                    apache_request_headers(['trampakiuser']) ? $this->listContacts($_GET['param'], "a") : $this->listContacts($_GET['param'], "c");
                }
                break;
            case 'POST':
                $this->writeMessage($_GET['param'], $_GET['param2']);
                break;
        }
    }
    
    private function checkMessages($uu, $ud) {
        while(true) {
            $lastCall     = isset($_GET['timestamp']) ? (int) $_GET['timestamp'] : null;
            $maybeMessage = ChatDAO::getInstance()->checarChat($uu, $ud);
            if (isset($maybeMessage)) {
                clearstatcache();
                $chatFile   = $chatFolder . (string) $maybeMessage;
                $lastChange = filemtime($chatFile);
                if ($lastCall == null || $lastChange > $lastCall) {
                    echo json_encode(array(
                        'history'   => file_get_contents($chatFile),
                        'timestamp' => $lastChange
                    ));
                    break;
                }
                else {
                    sleep(1);
                    continue;
                }
            }
            else {
                header('HTTP/1.1 400 Bad Request');
                echo json_encode('{err: chat não existe}');
                break;
            }
        }
    }
    private function writeMessage($uu, $ud) {
        $maybeChat = ChatDAO::getInstance()->checarChat($uu, $ud);
        if (isset($maybeChat)) {
            file_put_contents($chatFolder . (string) $maybeChat, file_get_contents('php://input'), FILE_APPEND);
            header('HTTP/1.1 200 OK');
        }
    }
    private function listContacts($uu) {
        echo json_encode(ChatDAO::getInstance()->listarContatos($uu));
    }
}

?>

<?php

class Chat {
    public static $chatFolder = '../chat/';
    
    public function __construct() {
        set_time_limit(0);
        header('Content-type: application/json');
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $this->checkMessages();
            case 'POST':
                $this->writeMessage();
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
}

?>

<?php
    class NotificacaoFirebase{
        private $tokenFcm, $texto, $titulo, $icone, $href, $som, $remetente;
        
        public function __construct($tokenFcm, $titulo, $texto, $icone, $href, $som, $remetente){
            $this->tokenFcm  = $tokenFcm;
            $this->titulo    = $titulo;
            $this->texto     = $texto;
            $this->icone     = "https://trampaki-api-gmoraiz.c9users.io/carregar-imagem/" . $icone;
            $this->href      = $href;
            $this->som       = $som;
            $this->remetente = $remetente;
        }
        
        public function enviar(){
            $data = array(
                "title"     => $this->titulo,
                "body"      => $this->texto,
                "icon"      => $this->icone,
                "sound"     => $this->som,
                "href"      => $this->href,
                "remetente" => $this->remetente
            );
            $fields = array(
            	'to' 	       => $this->tokenFcm,
            	'notification' => $data
            );
            $headers = array(
            	'Authorization: key=AAAAAQZrz4s:APA91bHWNQWE4t52lgcamCSQL__sMM9k-LbwGUYvSNG6KPwKtpBQbGYIyPKHOFUASk-ozWSe1diJH2Jyd7eWR8tVul90XTDchQyuFW7-xu03IGclYHEtM6ivaSAvXDqOto4z9iSN_DIk',
            	'Content-Type: application/json'
            );
            echo json_encode($fields);
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
            curl_setopt($ch,CURLOPT_POST, true );
            curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch );
            curl_close( $ch );
            echo "resultado: " + $result;
        }
    }
    
#curl -X POST -H 'authorization: key=AAAAAQZrz4s:APA91bHWNQWE4t52lgcamCSQL__sMM9k-LbwGUYvSNG6KPwKtpBQbGYIyPKHOFUASk-ozWSe1diJH2Jyd7eWR8tVul90XTDchQyuFW7-xu03IGclYHEtM6ivaSAvXDqOto4z9iSN_DIk' -H 'Content-Type:application/json' -d '{"notification":{"title": "PIROCASSAURO","text": "SAUROPIRROCA", "icon":"https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/e0/e0bfeb199a4d69de60cec131156ce4a75048f9c7_full.jpg"},"to" : "crqnnnu6ZoM:APA91bExu_wmINHGaPhdaup2CveSWPOTSDhAcWtRdRPSi7q_5GkJA0YucMClRHSofGU9jeQs4Aeaevoq0_5ab7OT6PR3tCf8v-KX6GIFlfD_oIkgiJTU82BH1Wjm5jWqXiZE7K_A49S1"}' https://android.googleapis.com/gcm/send 
?>
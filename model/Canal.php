<?php
    require_once 'configuration/autoload-geral.php';
    
    class Canal{
        private $ausente, $idUsuario, $ultimoAcesso, $notificacao, $resposta;
        
        public function __construct($idUsuario, $ultimoAcesso, $notificacao){
            $this->idUsuario = $idUsuario;
            $this->ausente = true;
            $this->notificacao = $notificacao;
            $this->ultimoAcesso = $ultimoAcesso;
            $this->resposta = array();
        }
        
        public function estaAusente(){
            return $this->ausente;
        }
        
        public function ouvir(){
            foreach($this->notificacao as $n){
                $notificado = $n->notificar($this->idUsuario, $this->ultimoAcesso);
                if($notificado != null){
                    $this->ausente = false;
                    $this->resposta[$n->index()] = $notificado;
                }
            }
        }
        
        public function retorno(){
            return json_encode($this->resposta, JSON_NUMERIC_CHECK);
        }
    }

?>
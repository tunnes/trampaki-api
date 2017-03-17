<?php
    abstract class LongPolling{
        private $DESCANSO = 3;
        private $canal;
        
        abstract public  function polling($id);
        
        public function addCanal(Canal $canal){
            $this->canal = $canal;
        }
        
        public function canal(){
            return $this->canal;
        }
        
        public function descanso(){
            return $this->DESCANSO;
        }
    }
?>
    
    
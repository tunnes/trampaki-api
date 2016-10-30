<?php                        
    class Conexao implements JsonSerealizable{
        private $codigoAnuncio;
        private $codigoPrestadorDeServico;
        
        public function __construct($codigoAnuncio, $codigoPrestadorDeServico){
            $this->codigoAnuncio;
            $this->codigoPrestadorDeServico;
        }
        public function novaConexao(){
            $bancoDeDados = Database::getInstance();
            $querySQL = "INSERT INTO conexao (cd_anuncio, cd_PrestadorDeServico, cd_status) VALUES (:cd_anuncio, :cd_PrestadorDeServico, 'Ativa')";
            $comandoSQL = $bancoDeDados->prepare($querySQL);
            $comandoSQL->bindParam(':cd_anuncio', $this->codigoAnuncio);
            $comandoSQL->bindParam(':cd_PrestadorDeServico', $this->codigoPrestadorDeServico);
            $comandoSQL->execute();
        }
        public function jsonSerialize() {
        #   SE EU TIVESSE QUE ME APAIXONAR POR ALGO
        #   SERIA POR ESSA FUNÇÃO E COMO AMANTE EU TERIA ESSA INTERFACE (JsonSerializable)
            return get_object_vars($this);
        }   
    }

?>
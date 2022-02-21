<?php
    class bancoDados{
        private $host;
        private $baseDados;
        private $usuario;
        private $senha;
        private $conectado;
        private $connection;
        
        function __construct($h, $db, $user, $pass){
            $this->set_host($h);
            $this->set_database($db);
            $this->set_usuario($user);
            $this->set_senha($pass);
            $this->conectar();
        }
        
        public function get_host(){
            return $this->host;
        }
        public function get_database(){
            return $this->baseDados;
        }
        public function get_usuario(){
            return $this->usuario;
        }
        public function get_senha(){
            return $this->senha;
        }
        public function get_conectado(){
            return $this->conectado;
        }
        public function get_connection(){
            return $this->connection;
        }
        
        public function set_host($h){
            $this->host = $h;
        }
        public function set_database($db){
            $this->baseDados = $db;
        }
        public function set_usuario($user){
            $this->usuario = $user;
        }
        public function set_senha($pass){
            $this->senha = $pass;
        }
        public function set_conectado($c){
            $this->conectado = $c;
        }
        public function set_connection($cn){
            $this->connection = $cn;
        }
        
        private function conectar(){
            try{
                $c = new PDO("mysql:host=".$this->get_host().";dbname=".$this->get_database(), $this->get_usuario(), $this->get_senha());                
                $this->set_connection($c);
                $this->set_conectado('Conectado');
                
            }
            catch (PDOException $e){                
                $this->set_conectado('Falha Conexão');
                
            }
        }
        public function criarTabela($nome, $chave, $conteudo){
            /*
                1 = nome da tabela
                2 = se é AI
                3 = conteudo
                    Array multidimensional ex: $dados = array (
                                                    array("Nome",tipo,tamanho),
                                                    array("Nome",tipo,tamanho),
                                                    array("Nome",tipo,tamanho),
                                                    array("Nome",tipo,tamanho)
                                                );
                    tipos: 1 = int / 2 = vachar / 3 = text / 4 = date
            */
            $tabela = "CREATE TABLE IF NOT EXISTS ".$nome." (";
            $linha = 0;
            $cvP = '';
            foreach($conteudo as $l){
                $linha ++;
                switch($l[1]){
                    case 1:
                        $tp = 'int';
                        break;
                    case 2:
                        $tp = 'varchar';
                        break;
                    case 3:
                        $tp = 'text';
                        break;
                    case 4:
                        $tp = 'date';
                }
                if($linha == 1){
                    if($chave == true){
                        $tabela = $tabela."".$l[0]." int(".$l[2].") UNSIGNED AUTO_INCREMENT PRIMARY KEY";
                        $cvP = $l[0];
                    }else{
                        $tabela = $tabela."".$l[0]." ".$tp."(".$l[2].") NOT NULL, ";
                        $cvP = '';
                    }
                    
                }else{
                    $tabela = $tabela.", ".$l[0]." ".$tp."(".$l[2].") NOT NULL";
                }
            }
            $tabela = $tabela.")";            
            $this->get_connection()->exec($tabela);
        }
        
    }

$cn = new bancoDados('localhost', 'base1', 'root', ''); 
    
$tabelas = array (
    array("id",1,2),
    array("nome",2,30)
        
);
    
$cn->criarTabela('Nomes', true, $tabelas);
    
?>

<?php
    class DataBase{
        private $host;
        private $baseDados;
        private $user;
        private $password;
        private $connected;
        private $connection;
        
        function __construct($h, $db, $user, $pass){
            $this->set_host($h);
            $this->set_database($db);
            $this->set_user($user);
            $this->set_password($pass);
            $this->connect();
        }
        
        public function get_host(){
            return $this->host;
        }
        public function get_database(){
            return $this->baseDados;
        }
        public function get_user(){
            return $this->usuario;
        }
        public function get_password(){
            return $this->senha;
        }
        public function get_connected(){
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
        public function set_user($user){
            $this->usuario = $user;
        }
        public function set_password($pass){
            $this->senha = $pass;
        }
        public function set_connected($c){
            $this->conectado = $c;
        }
        public function set_connection($cn){
            $this->connection = $cn;
        }
        
        private function connect(){
            try{
                $c = new PDO("mysql:host=".$this->get_host().";dbname=".$this->get_database(), $this->get_user(), $this->get_password());                
                $this->set_connection($c);
                $this->set_connected('Connected');
                
            }
            catch (PDOException $e){                
                $this->set_connected('Connection failure');
                
            }
        }
        public function createTable($name, $auto, $content){
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
            $table = "CREATE TABLE IF NOT EXISTS ".$name." (";
            $line = 0;
            $cvP = '';
            foreach($content as $l){
                $line ++;
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
                if($line == 1){
                    if($auto == true){
                        $table .= "".$l[0]." int(".$l[2].") UNSIGNED AUTO_INCREMENT PRIMARY KEY";
                        $cvP = $l[0];
                    }else{
                        $table .= "".$l[0]." ".$tp."(".$l[2].") NOT NULL, ";
                        $cvP = '';
                    }
                    
                }else{
                    $table .= ", ".$l[0]." ".$tp."(".$l[2].") NOT NULL";
                }
            }
            $table .= ")";            
            $this->get_connection()->exec($table);
        }
        
        public function insertTable($nt, $columns, $values){
            /* parametros:
             *  1°
             *  True para dados simples
             *  False para array
             *  2°
             *  Nome tabela
             *  3°
             *  Nome coluna ou array com colunas
             *  4°
             *  Valor da coluna ou valores das colunas
            */
                        
            $sql = "INSERT INTO ".$nt." (";
            
            $c = 0;
            while($c < count($columns)){
                if($c == count($columns) - 1){
                    $sql .= $columns[$c].")";
                }else{
                    $sql .= $columns[$c].", ";
                }
                $c ++;
            }
            
            $sql .= " values (";
            
            $c = 0;
            while($c < count($columns)){
                if($c == count($columns) - 1){
                    $sql .="?)";
                }else{
                    $sql .= "?, ";
                }
                $c ++;
            }
            
            $stmt = $this->get_connection()->prepare($sql);                             
            
            foreach($values as $vl){
                $c = 1;
                $c2 = 0;
                    
                while($c2 < count($vl)){
                    $stmt->bindParam($c, $vl[$c2]);
                    $c ++;
                    $c2 ++;
                }
                $stmt->execute();
            }
        }
        
        

    }
    
    
?>

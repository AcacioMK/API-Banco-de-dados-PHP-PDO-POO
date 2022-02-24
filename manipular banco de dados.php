<?php
/*
* By Acacio MK
* https://github.com/AcacioMK
* acacio89oliveira@gmail.com
* please don't delete these comments
*/
    class DataBase{
        private $host;
        private $baseDados;
        private $user;
        private $password;
        private $connected;
        private $connection;
        
        function __construct($h, $db, $user, $pass){ // host, database, user and password
            $this->set_host($h);
            $this->set_database($db);
            $this->set_user($user);
            $this->set_password($pass);
            $this->connect();
        }
        
        private function get_host(){
            return $this->host;
        }
        private function get_database(){
            return $this->baseDados;
        }
        private function get_user(){
            return $this->usuario;
        }
        private function get_password(){
            return $this->senha;
        }
        public function get_connected(){
            return $this->conectado;
        }
        public function get_connection(){
            return $this->connection;
        }
        
        private function set_host($h){
            $this->host = $h;
        }
        private function set_database($db){
            $this->baseDados = $db;
        }
        private function set_user($user){
            $this->usuario = $user;
        }
        private function set_password($pass){
            $this->senha = $pass;
        }
        private function set_connected($c){
            $this->conectado = $c;
        }
        private function set_connection($cn){
            $this->connection = $cn;
        }
        
        private function connect(){ // connecting database
            try{
                $c = new PDO("mysql:host=".$this->get_host().";dbname=".$this->get_database(), $this->get_user(), $this->get_password());                
                $this->set_connection($c);
                $this->set_connected('Connected'); // return 
                
            }
            catch (PDOException $e){                
                $this->set_connected('Connection failure'); // return 
                
            }
        }
        public function createTable($name, $auto, $content){ // create a table by inserting the name, if auto-increment and the content in array multidimensional
            /*                
                    Array: $dados = array (
                        array("name",type, size),
                        array("name",type, size),
                        array("name",type, size),
                        array("name",type, size)
                    );
                    type: 1 = int / 2 = vachar / 3 = text / 4 = date
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
        
        public function insertData($nt, $columns, $values){
            /* parameters:             
             *  1°
             *  name table
             *  2°
             *  name column or array with columns
             *  3°
             *  column value or array with columns values
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
        
        public function consultSimpleTable($columnsConsult, $vlTable, $table, $columns){ // consult simple / 1° = name table consult / 2° value table consult / 3° table consult / 4° returned columns
            $arrayReturn = array();
            $rs = $this->get_connection()->prepare("SELECT * FROM $table WHERE $columnsConsult = ?");
            $rs->bindParam(1, $vlTable);
            if($rs->execute()){
                while($registro = $rs->fetch(PDO::FETCH_OBJ)){
                    foreach($columns as $vl){
                        $i = $registro->$vl;
                        array_push($arrayReturn, $i);                        
                    }
                }
            }            
            return $arrayReturn;
        }
        
        public function consultTableFull($table, $orderColumns, $sort, $columns){ // consult all data the table / 1° table / 2° table sort / 3° sort column / 4° returned columns
            //$sort: 0 = none / 1 = ascending and 3 = descending
            $arrayReturn = array();
            
            switch($sort){
                case(0):                    
                    $sql = "SELECT * FROM $table";
                    break;
                case(1):
                    $sql = "SELECT * FROM $table ORDER BY $table . $orderColumns ASC";
                    break;
                case(2):
                    $sql = "SELECT * FROM $table ORDER BY $table . $orderColumns DESC";
                break;
            }
            
            $rs = $this->get_connection()->prepare($sql);
            if($rs->execute()){
                while($registro = $rs->fetch(PDO::FETCH_OBJ)){
                    $i2 = array();
                    foreach($columns as $vl){
                        $i1 = $registro->$vl;
                        array_push($i2, $i1);
                    }                                
                    array_push($arrayReturn, $i2);
                }
            }
            
            return $arrayReturn;
        }
        
        /*
        class sendEmail{
            
            
            
        }
        */
        
        // to be continued

    }
    
    
?>

<?php
    class conexao{
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
        
        private function get_host(){
            return $this->host;
        }
        private function get_database(){
            return $this->baseDados;
        }
        private function get_usuario(){
            return $this->usuario;
        }
        private function get_senha(){
            return $this->senha;
        }
        public function get_conectado(){
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
        private function set_usuario($user){
            $this->usuario = $user;
        }
        private function set_senha($pass){
            $this->senha = $pass;
        }
        private function set_conectado($c){
            $this->conectado = $c;
        }
        private function set_connection($cn){
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
        
    }
    
    class criarTabela{
        private $nomeTabela;        
        private $comChaveAuto;
        private $conteudoTabela;
        
        function __construct($nome, $chave, $conteudo, $cn){
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
            
            $this->set_nome($nome);            
            $this->set_chave($chave);
            $this->set_conteudo($conteudo);
            $this->construir($cn);
        }
        
        private function get_nome(){
            return $this->nomeTabela;
        }
        private function get_chave(){
            return $this->comChaveAuto;
        }
        private function get_conteudo(){
            return $this->conteudoTabela;
        }
        
        private function set_nome($nome){
            $this->nomeTabela = $nome;
        }        
        private function set_chave($chave){
            $this->comChaveAuto = $chave;
        }
        private function set_conteudo($conteudo){
            $this->conteudoTabela = $conteudo;
        }
        
        private function construir($cn){
            
            $tabela = "CREATE TABLE IF NOT EXISTS ".$this->get_nome()." (";
            
            $linha = 0;
            $cvP = '';
            foreach($this->get_conteudo() as $l){
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
                    if($this->get_chave() == true){
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
            print($tabela);
            $cn->get_connection()->exec($tabela);
            
        }
        
    }
    
    $cn = new conexao('localhost', 'base1', 'root', '');
    
    $tabelas = array (
        array("id",1,6),
        array("nome",2,2),
        array("endereco",2,2),        
        array("telefone",2,2)
    );
    
    new criarTabela('tabela', true, $tabelas, $cn);
    
    
?>

<?php

    include 'mk.php';
    
    $cn = new DataBase('localhost', 'base1', 'root', ''); 
    
    $tabelas = array (
        array("id",1,2),
        array("nome",2,30),
        array("idade",1,2)
        
    );
    
    $cn->createTable('Nomes', true, $tabelas);
    
    $colunas = array("nome", "idade");
    
    $valores = array(
        array("Ana", 23),
        array("Joao", 32),
    );
    
    $cn->insertData('Nomes', $colunas, $valores);
    
    
    
    $return = $cn->consultSimpleTable('idade', '230', 'Nomes', $colunas);
    
    $return2 = $cn->consultTableFull('Nomes', 'nome', 1, $colunas);
    
    var_dump($return);
    
    
?>

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
        array("AcacioMK", 23),
        array("Maria", 32),
    );
    
    $cn->insertData('Nomes', $colunas, $valores);
    
    $colunasConsultarID = array('nome', 'idade');
    
    $return = $cn->consultTableID(67, 'Nomes', $colunasConsultarID);
    
    var_dump($return);
?>

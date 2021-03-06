<?php
/*
    @autor: Giorgio Mecca
    Matricola : 880847
*/
/*
    funzione php per ottenere i dati di un venditore di un prodotto
*/
if (!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"] != "GET") {
	header("HTTP/1.1 400 Invalid Request");
	die("ERROR 400: Invalid request.");
}
if (!isset($_GET["ID_Seller"])) {
	header("HTTP/1.1 400 Invalid Data");
	die("ERROR 400: Invalid data.");
}


try{
    include("connectionDB.php");
    $db =connect();
    
    $rows = array();

    if(isset($_GET["ID_Seller"])){

        $ID_Seller= $db->quote($_GET["ID_Seller"]);

        $result= $db->query("SELECT EMail, Name, Surname
                             FROM Users
                             WHERE ID = $ID_Seller");
        $rows= $result->fetchAll(PDO::FETCH_ASSOC);

        if($rows != FALSE){
            $result1= $db->query("SELECT Name, Description, Link, PhoneN
                                    FROM Business
                                    WHERE ID = $ID_Seller");
            $result1= $result1->fetchAll(PDO::FETCH_ASSOC);
            unset($result1["ID"]);
            if($result1 != FALSE){
                $rows= array_merge($rows,$result1); 
            }
                              
        }
        
    }
    print json_encode($rows);
    
}catch(PDOException $ex){
    die('Could not connect: ' . $ex->getMessage());
}

?>
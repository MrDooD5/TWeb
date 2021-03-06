<?php
/*
    @autor: Giorgio Mecca
    Matricola : 880847
*/
/*
    funzione php per restituire tutti gli Address associatu all'utente della sessione
    in modalità JSON
*/
session_start();

if (session_id() == '' || !isset($_SESSION) || !isset($_SESSION["ID"])) {
    header('Location: ../../html/login.html');
    exit;
}

try{
    include("connectionDB.php");
    $db =connect();

    $id= $_SESSION["ID"];
    $result= $db->query("SELECT *
                            FROM Addresses
                            WHERE ID_User = $id");
    $result= $result->fetchAll(PDO::FETCH_ASSOC);
    
    print json_encode($result);
    
}catch(PDOException $ex){
    die('Could not connect: ' . $ex->getMessage());
}

?>
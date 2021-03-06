<?php
/*
    @autor: Giorgio Mecca
    Matricola : 880847
*/
/*
    funzione php per restituirei dati dell'utente della sessione
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
    print "{\n \"Type\": ";
    if($_SESSION["UserBusiness"] == FALSE){
        print "\"User\",\n";
        $result= $db->query("SELECT *
                            FROM Users
                            WHERE ID = $id");
        $result= $result->fetch(PDO::FETCH_ASSOC);
    }else{
        print "\"Business\",\n";
        $resultU= $db->query("SELECT EMail
                            FROM Users
                            WHERE ID = $id");
        $resultB= $db->query("SELECT *
                            FROM Business
                            WHERE ID = $id");
        $result = array_merge($resultU->fetch(PDO::FETCH_ASSOC), $resultB->fetch(PDO::FETCH_ASSOC));
    }
    print "\"Data\": ";
    if($result!=FALSE){
        
        
        print json_encode($result);
    }else{
        die("Invalid Session");
    }
    print "}";
}catch(PDOException $ex){
    die('Could not connect: ' . $ex->getMessage());
}

?>
<?php 
/*
    @autor: Giorgio Mecca
    Matricola : 880847
*/
/*
    funzione php per registrare un nuovo utente
    se possibile crea la sessione e setta l'ID
*/
if (!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"] != "POST") {
	header("HTTP/1.1 400 Invalid Request");
	die("ERROR 400: Invalid request - This service accepts only POST requests.");
}

try {
    include("connectionDB.php");
    $db =connect();
    
    $mail= $db->quote(htmlspecialchars($_POST["EMail"]));
    $psw= $db->quote(htmlspecialchars($_POST["Psw"]));

    $id_user= $db->query("SELECT ID FROM Users WHERE EMail LIKE $mail");
    $result= $id_user->fetch();
    if($result==FALSE){
        if(strcmp($_POST["Type"],"User") == 0){
            $name= isset($_POST["Name"]) ? $db->quote(htmlspecialchars($_POST["Name"])) : "NULL";
            $surname= isset($_POST["Surname"]) ? $db->quote(htmlspecialchars($_POST["Surname"])) : "NULL";
            $db->query("INSERT INTO Users (EMail,Psw,Name,Surname) VALUES ($mail,$psw,$name,$surname);");
            
            $id_user= $db->query("SELECT ID FROM Users WHERE EMail LIKE $mail");
            $id_user= $id_user->fetch()["ID"];
        }else{
            //Creo l'utente
            $db->query("INSERT INTO Users (EMail,Psw) VALUES ($mail,$psw);");
            //ricerco il suo ID
            $id_user= $db->query("SELECT ID FROM Users WHERE EMail LIKE $mail");
            $id_user= $id_user->fetch()["ID"];

            $id= $db->quote($id_user);

            $name= isset($_POST["Name"]) ? htmlspecialchars($db->quote($_POST["Name"])) : "NULL";
            $desc= isset($_POST["Desc"]) ? htmlspecialchars($db->quote($_POST["Desc"])) : "NULL";
            $link= isset($_POST["Link"]) ? htmlspecialchars($db->quote($_POST["Link"])) : "NULL";
            $tel= isset($_POST["Tel"]) ? htmlspecialchars($db->quote($_POST["Tel"])) : "NULL";
    
            
            $db->query("INSERT INTO Business VALUES ($name,$desc,$link,$tel,$id);");
        }

        print "{\n \"result\": ";
        print "\"TRUE\", \n";
        print " \"StrErr\": \"\" ";
        print "}";

        session_start();
        $_SESSION["ID"]= $id_user;
        if(strcmp($_POST["Type"],"User") != 0) $_SESSION["UserBusiness"]= TRUE;
        else $_SESSION["UserBusiness"]= FALSE;

    }else{
        print "{\n \"result\": ";
        print "\"FALSE\", \n";
        print " \"StrErr\": \"Mail gia' registrata\" ";
        print "}";
    }
} catch(PDOException $ex){
    die('Could not connect: ' . $ex->getMessage());
}

?>
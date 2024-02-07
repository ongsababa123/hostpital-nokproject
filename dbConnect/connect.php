<?php
    $dbHost = "localhost";
    $dbUser = "root";
    $dbPass = "";
    $dbName = "hostpital";

    $conn = new mysqli ($dbHost,$dbUser,$dbPass,$dbName);
    if($conn -> connect_error){
        die("ติดต่อฐานข้อมูลไม่สำเร็จ:".$conn->connect_error);
    }
    $conn -> query("set names utf8");
     
    $complete = json_encode(Array('status' => true ));
    $invalid = json_encode(Array('status' => false ));
    
?>
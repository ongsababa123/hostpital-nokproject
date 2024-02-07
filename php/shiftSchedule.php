<?php

    require_once("../dbConnect/connect.php");

    $act = isset($_POST['act']) ? $_POST['act'] : '';

    $cookie = $_COOKIE['hostpital'];
    $cookie = json_decode(base64_decode($cookie));
    $Personal_ID = base64_decode($cookie->Personal_ID);

    $month = isset($_POST['month']) ? $_POST['month'] : '';
    $year = isset($_POST['year']) ? $_POST['year'] : '';


    if($act == "scheduleList"){
        $sql = "SELECT * FROM `shift_schedule` WHERE `Personal_ID` = '$Personal_ID' AND YEAR(`shift_date`) = $year AND MONTH(`shift_date`) = $month AND `shift_status` = '1'";
        $result = $conn->query($sql);
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    }
?>
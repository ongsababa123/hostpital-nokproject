<?php

    require_once("../dbConnect/connect.php");
    $conn->autocommit(false);

    $act = isset($_POST['act']) ? $_POST['act'] : "";

    $special_personal = isset($_POST['special_personal']) ? $_POST['special_personal'] : "";
    $special_date = isset($_POST['special_date']) ? $_POST['special_date'] : "";
    $special_time = isset($_POST['special_time']) ? $_POST['special_time'] : "";
    $special_patient = isset($_POST['special_patient']) ? $_POST['special_patient'] : "";
    $special_payment = isset($_POST['special_payment']) ? $_POST['special_payment'] : "";
    $special_detail = isset($_POST['special_detail']) ? $_POST['special_detail'] : "";

    $special_edit_id = isset($_POST['special_edit_id']) ? $_POST['special_edit_id'] : "";
    $shift_del_id = isset($_POST['shift_del_id']) ? $_POST['shift_del_id'] : "";

    $cookie = json_decode(base64_decode($_COOKIE['hostpital']));
    $updated_by = base64_decode($cookie->Personal_ID);


    if($act == "add"){
        $sql = "INSERT INTO `shift_special`(
            `Personal_ID`,
            `special_date`,
            `special_time`,
            `special_patient`,
            `special_payment`,
            `special_detail`,
            `sepcial_status`,
            `created_by`,
            `created_at`,
            `updated_by`,
            `updated_at`
        ) VALUES (
            '$special_personal',
            '$special_date',
            '$special_time',
            '$special_patient',
            '$special_payment',
            '$special_detail',
            '1',
            '$updated_by',
            now(),
            '$updated_by',
            now()
        )";
        if($conn->query($sql)){
            $conn->commit();
            echo $complete;
        }else {
            echo $invalid;
        }
    }

    if($act == "edit"){
        $sql = "UPDATE `shift_special` SET
                    `Personal_ID` = '$special_personal',
                    `special_date` = '$special_date',
                    `special_time` = '$special_time',
                    `special_patient` = '$special_patient',
                    `special_payment` = '$special_payment',
                    `special_detail` = '$special_detail',
                    `updated_by` = '$updated_by',
                    `updated_at` = now()
                WHERE
                    `id` = '$special_edit_id'
                ";
        if($conn->query($sql)){
            $conn->commit();
            echo $complete;
        }else {
            echo $invalid;
        }
    }

    if($act == "del"){
        $sql = "UPDATE `shift_special` SET
                    `sepcial_status` = '0',
                    `updated_by` = '$updated_by',
                    `updated_at` = now()
                WHERE
                    `id` = '$shift_del_id'
                ";
        if($conn->query($sql)){
            $conn->commit();
            echo $complete;
        }else {
            echo $invalid;
        }
    }

    if($act == "read"){
        $sql = "SELECT 
                    a.*,
                    b.`Fname`,
                    b.`Lname`,
                    c.`prefixName`
                FROM 
                    `shift_special` a
                    LEFT JOIN `personal` b ON a.`Personal_ID` = b.`Personal_ID`
                    LEFT JOIN `prefix` c ON b.`prefixCode` = c.`prefixCode`
                WHERE
                    a.`sepcial_status` = '1'
                ORDER BY
                    a.`id` DESC
                ";
        $result = $conn->query($sql);
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    }
?>
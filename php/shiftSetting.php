<?php

    require_once('../dbConnect/connect.php');

    $conn->autocommit(false);

    $act = isset($_POST['act'])?$_POST['act'] : "";
    $position_id = isset($_POST['position_id'])? json_decode($_POST['position_id']) : [];
    $type_shift = isset($_POST['type_shift'])? json_decode($_POST['type_shift']) : [];
    $morning_shift_count = isset($_POST['morning_shift_count'])? json_decode($_POST['morning_shift_count']) : [];
    $afternoon_shift_count = isset($_POST['afternoon_shift_count'])? json_decode($_POST['afternoon_shift_count']) : [];
    $night_shift_count = isset($_POST['night_shift_count'])? json_decode($_POST['night_shift_count']) : [];
    $shift_count = isset($_POST['shift_count'])? json_decode($_POST['shift_count']) : [];
    $all_department = isset($_POST['all_department'])? json_decode($_POST['all_department']) : [];
    $detailCount = isset($_POST['detailCount'])? $_POST['detailCount'] : "";
    if($act == "getDepartmentList"){
        $sql = "SELECT * FROM department";
        $result = $conn->query($sql);
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    }

    if($act == "getPositionList"){
        $sql = "SELECT * FROM position ORDER BY positName ASC";
        $result = $conn->query($sql);
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    }

    if($act == "add"){
        $state = true;
        for ($i=0; $i < intval($detailCount); $i++) { 
            $sql = "INSERT INTO shift_setting (
                        `position_code`,
                        `is_shift`,
                        `moring_shift_count`,
                        `afternoon_shift_count`,
                        `night_shift_count`,
                        `all_department`
                    ) VALUES (
                        '".$conn->real_escape_string($position_id[$i])."',
                        '".$conn->real_escape_string($type_shift[$i])."',
                        '".$conn->real_escape_string($type_shift[$i] == "1" ? $morning_shift_count[$i] : $shift_count[$i])."',
                        ".($type_shift[$i] == "1" ? "'".$conn->real_escape_string($afternoon_shift_count[$i])."'" : "NULL").",
                        ".($type_shift[$i] == "1" ? "'".$conn->real_escape_string($night_shift_count[$i])."'" : "NULL").",
                        '".$all_department[$i]."'
                    )ON DUPLICATE KEY UPDATE 
                        `is_shift` = '".$conn->real_escape_string($type_shift[$i])."',
                        `moring_shift_count` = '".$conn->real_escape_string($type_shift[$i] == "1" ? $morning_shift_count[$i] : $shift_count[$i])."',
                        `afternoon_shift_count` = ".($type_shift[$i] == "1" ? "'".$conn->real_escape_string($afternoon_shift_count[$i])."'" : "NULL").",
                        `night_shift_count` = ".($type_shift[$i] == "1" ? "'".$conn->real_escape_string($night_shift_count[$i])."'" : "NULL").",
                        `all_department` = '".$all_department[$i]."'
                    ;";
            if(!$conn->query($sql))$state = false;
        }
        if($state){
            $sql = "DELETE FROM shift_setting WHERE `position_code` NOT IN (".implode(",",$position_id).")";
            if(!$conn->query($sql))$state = false;
        }

        if($state){
            $conn->commit();
            echo json_encode(array("status" => true));
        }else{
            echo json_encode(array("status" => false));
        }
    }

    if($act == "getSettingShift"){
        $sql = "SELECT * FROM shift_setting";
        $result = $conn->query($sql);
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    }
?>
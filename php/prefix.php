<?php
    $msg ="('status':'fail')";
    require_once('../dbConnect/connect.php');

    $act = isset($_POST['act'])?$_POST['act'] : "";
    $prefixCode = isset($_POST['prefixCode'])?$_POST['prefixCode'] : "";
    $prefixName = isset($_POST['prefixName'])?$_POST['prefixName'] : "";

    if($act == 'create'){
        $sql = "insert into prefix (prefixCode,prefixName) values ('$prefixCode','$prefixName')";
        if($conn -> query($sql)){
            $msg = "<div style='color:green';>บันทึกรายการสำเร็จ</div>";
        }else{
            $msg = "<div style='color:red'>บันทึกรายการไม่สำเร็จ</div>";
        }
        echo $msg;
    }

    if($act == 'read'){
        $sql = 'select * from prefix WHERE prefixIsDelete = "0"';
        $result = $conn->query($sql);
        echo json_encode($result -> fetch_all(MYSQLI_ASSOC));
    }

    if($act == 'update'){
        $sql = "update prefix set prefixName = '{$conn->real_escape_string($prefixName)}' where prefixCode = '{$conn->real_escape_string($prefixCode)}'";
        if($conn -> query($sql)){
            $msg = "<div style='color:green';>แก้ไขรายการสำเร็จ</div>";
        }else{
            $msg = "<div style='color:red'>แก้ไขรายการไม่สำเร็จ</div>";
        }
        echo $msg;
    }

    if($act == 'delete'){
        $sql = "UPDATE `prefix` SET prefixIsDelete = '1'  where prefixCode ='{$conn->real_escape_string($prefixCode)}'";
        // $sql = "delete from prefix where prefixCode ='{$conn->real_escape_string($prefixCode)}'";
        if($conn -> query($sql)){
            $msg = "ลบข้อมูลสำเร็จ";
        }else{
            $msg ="ลบข้อมูลไม่สำเร็จ";
        }
        echo $msg;
    }
?>
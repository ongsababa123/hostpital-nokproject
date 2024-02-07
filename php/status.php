<?php
    $msg ="('status':'fail')";
    require_once('../dbConnect/connect.php');

    $act = isset($_POST['act'])?$_POST['act'] : "";
    $staCode = isset($_POST['staCode'])?$_POST['staCode'] : "";
    $staName = isset($_POST['staName'])?$_POST['staName'] : "";

    if($act == 'create'){
        $sql = "insert into `status` (staCode,staName) values ('{$conn->real_escape_string($staCode)}','{$conn->real_escape_string($staName)}')";
        if($conn -> query($sql)){
            $msg = "<div style='color:green';>บันทึกรายการสำเร็จ</div>";
        }else{
            $msg = "<div style='color:red'>บันทึกรายการไม่สำเร็จ</div>";
        }
        echo $msg;
    }

    if($act == 'read'){
        $sql = 'select * from `status` WHERE staIsDelete = "0"';
        $result = $conn->query($sql);
        echo json_encode($result -> fetch_all(MYSQLI_ASSOC));
    }

    if($act == 'update'){
        $sql = "update `status` set staName = '{$conn->real_escape_string($staName)}' where staCode = '{$conn->real_escape_string($staCode)}'";
        if($conn -> query($sql)){
            $msg = "<div style='color:green';>แก้ไขรายการสำเร็จ</div>";
        }else{
            $msg = "<div style='color:red'>แก้ไขรายการไม่สำเร็จ</div>";
        }
        echo $msg;
    }

    if($act == 'delete'){
        $sql = "UPDATE `status` SET staIsDelete = '1' where staCode ='{$conn->real_escape_string($staCode)}'";
        // $sql = "delete from status where staCode ='{$conn->real_escape_string($staCode)}'";
        if($conn -> query($sql)){
            $msg = "ลบข้อมูลสำเร็จ";
        }else{
            $msg ="ลบข้อมูลไม่สำเร็จ";
        }
        echo $msg;
    }
?>
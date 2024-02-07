<?php
    $msg ="('status':'fail')";
    require_once('../dbConnect/connect.php');

    $act = isset($_POST['act'])?$_POST['act'] : "";
    $positCode = isset($_POST['positCode'])?$_POST['positCode'] : "";
    $positName = isset($_POST['positName'])?$_POST['positName'] : "";

    if($act == 'create'){
        $sql = "insert into position (positCode,positName) values ('$positCode','$positName')";
        if($conn -> query($sql)){
            $msg = "<div style='color:green';>บันทึกรายการสำเร็จ</div>";
        }else{
            $msg = "<div style='color:red'>บันทึกรายการไม่สำเร็จ</div>";
        }
        echo $msg;
    }

    if($act == 'read'){
        $sql = 'select * from position where positIsDelete = "0"';
        $result = $conn->query($sql);
        echo json_encode($result -> fetch_all(MYSQLI_ASSOC));
    }

    if($act == 'update'){
        $sql = "update position set positName = '{$conn->real_escape_string($positName)}' where positCode = '{$conn->real_escape_string($positCode)}'";
        if($conn -> query($sql)){
            $msg = "<div style='color:green';>แก้ไขรายการสำเร็จ</div>";
        }else{
            $msg = "<div style='color:red'>แก้ไขรายการไม่สำเร็จ</div>";
        }
        echo $msg;
    }

    if($act == 'delete'){
        $sql = "UPDATE `position` SET positIsDelete = '1' WHERE positCode ='{$conn->real_escape_string($positCode)}'";
        // $sql = "delete from position where positCode ='$positCode'";
        if($conn -> query($sql)){
            $msg = "ลบข้อมูลสำเร็จ";
        }else{
            $msg ="ลบข้อมูลไม่สำเร็จ";
        }
        echo $msg;
    }
?>
<?php
    $msg ="('status':'fail')";
    require_once('../dbConnect/connect.php');

    $act = isset($_POST['act'])?$_POST['act'] : "";
    $accessCode = isset($_POST['accessCode'])?$_POST['accessCode'] : "";
    $accessName = isset($_POST['accessName'])?$_POST['accessName'] : "";

    if($act == 'create'){
        $sql = "insert into access (accessCode,accessName) values ('{$conn->real_escape_string($accessCode)}','{$conn->real_escape_string($accessName)}')";
        if($conn -> query($sql)){
            $msg = "<div style='color:green';>บันทึกรายการสำเร็จ</div>";
        }else{
            $msg = "<div style='color:red'>บันทึกรายการไม่สำเร็จ</div>";
        }
        echo $msg;
    }

    if($act == 'read'){
        $sql = 'select * from access where accessIsDelete = "0"';
        $result = $conn->query($sql);
        echo json_encode($result -> fetch_all(MYSQLI_ASSOC));
    }

    if($act == 'update'){
        $sql = "update access set accessName = '{$conn->real_escape_string($accessName)}' where accessCode = '{$conn->real_escape_string($accessCode)}'";
        if($conn -> query($sql)){
            $msg = "<div style='color:green';>แก้ไขรายการสำเร็จ</div>";
        }else{
            $msg = "<div style='color:red'>แก้ไขรายการไม่สำเร็จ</div>";
        }
        echo $msg;
    }

    if($act == 'delete'){
        $sql = "UPDATE access SET accessIsDelete = '1'  where accessCode ='{$conn->real_escape_string($accessCode)}'";
        // $sql = "delete from access where accessCode ='$accessCode'";
        if($conn -> query($sql)){
            $msg = "ลบข้อมูลสำเร็จ";
        }else{
            $msg ="ลบข้อมูลไม่สำเร็จ";
        }
        echo $msg;
    }
?>
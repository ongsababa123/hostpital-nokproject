<?php
    $msg ="('status':'fail')";
    require_once('../dbConnect/connect.php');

    $act = isset($_POST['act'])?$_POST['act'] : "";
    $cotCode = isset($_POST['cotCode'])?$_POST['cotCode'] : "";
    $cotName = isset($_POST['cotName'])?$_POST['cotName'] : "";

    if($act == 'create'){
        $sql = "insert into cotton (cotCode,cotName) values ('$cotCode','$cotName')";
        if($conn -> query($sql)){
            $msg = "<div style='color:green';>บันทึกรายการสำเร็จ</div>";
        }else{
            $msg = "<div style='color:red'>บันทึกรายการไม่สำเร็จ</div>";
        }
        echo $msg;
    }

    if($act == 'read'){
        $sql = 'select * from cotton ';
        $result = $conn->query($sql);
        echo json_encode($result -> fetch_all(MYSQLI_ASSOC));
    }

    if($act == 'update'){
        $sql = "update cotton set cotName = '$cotName' where cotCode = '$cotCode'";
        if($conn -> query($sql)){
            $msg = "<div style='color:green';>แก้ไขรายการสำเร็จ</div>";
        }else{
            $msg = "<div style='color:red'>แก้ไขรายการไม่สำเร็จ</div>";
        }
        echo $msg;
    }

    if($act == 'delete'){
        $sql = "delete from cotton where cotCode ='$cotCode'";
        if($conn -> query($sql)){
            $msg = "ลบข้อมูลสำเร็จ";
        }else{
            $msg ="ลบข้อมูลไม่สำเร็จ";
        }
        echo $msg;
    }
?>
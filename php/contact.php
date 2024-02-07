<?php
    $msg ="('status':'fail')";
    require_once('../dbConnect/connect.php');

    $act = isset($_POST['act'])?$_POST['act'] : "";
    $email = isset($_POST['email'])?$_POST['email'] : "";
    $name = isset($_POST['name'])?$_POST['name'] : "";
    $subject = isset($_POST['subject'])?$_POST['subject'] : "";
    $message = isset($_POST['message'])?$_POST['message'] : "";

    if($act == 'create'){
        $sql = "insert into contect (email,name,subject,message) values ('$email','$name','$subject','$message')";
        // $die($sql)
        if($conn -> query($sql)){
            $msg = "<div style='color:green';>บันทึกรายการสำเร็จ</div>";
        }else{
            $msg = "<div style='color:red'>บันทึกรายการไม่สำเร็จ</div>";
        }
        echo $msg;
    }
?>
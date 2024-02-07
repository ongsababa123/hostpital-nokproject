<?php
    $msg ="('status':'fail')";
    require_once('../dbConnect/connect.php');


    $act = isset($_POST['act'])?$_POST['act'] : "";
    $Personal_ID = isset($_POST['Personal_ID'])?$_POST['Personal_ID'] : "";
    $prefixCode = isset($_POST['prefixCode'])?$_POST['prefixCode'] : "";
    $Fname = isset($_POST['Fname'])?$_POST['Fname'] : "";
    $Lname = isset($_POST['Lname'])?$_POST['Lname'] : "";
    $positCode = isset($_POST['positCode'])?$_POST['positCode'] : "";
    // $Group_ID = isset($_POST['Group_ID'])?$_POST['Group_ID'] : "";
    $departCode = isset($_POST['departCode'])?$_POST['departCode'] : "";
    $Password = isset($_POST['Password'])? $_POST['Password'] : "";
    if($Password != "") $Password = hash('sha256',$_POST['Password']);
    $accessCode = isset($_POST['accessCode'])?$_POST['accessCode'] : "";
    $statusCode = isset($_POST['statusCode'])?$_POST['statusCode'] : "";
   
    

      if($act == 'Personal'){  
        $departCode = $_POST['departCode'];
        $sql = "select * from personal where departCode = '$departCode'";
        $result = $conn->query($sql);
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    }
     
    if($act == 'detail'){  
        $sql = "select s.Personal_ID, s.prefixCode, s.Fname, s.Lname, s.positCode, s.departCode,s.accessCode,s.password,
        w.departCode, w.departName, 
        f.prefixCode,f.prefixName,o.positCode, o.positName,a.accessCode,a.accessName
        from personal s left join position o on(s.positCode = o.positCode)
        left join access a on(s.accessCode = a.accessCode)
        left join department w on(s.departCode = w.departCode)
        left join prefix f on(s.prefixCode = f.prefixCode)
        where s.Personal_ID = '$Personal_ID'";
        $result = $conn->query($sql);
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    }

    if($act == 'read'){ 
        $sql = "SELECT 
                    s.id,
                    s.Personal_ID,
                    s.prefixCode,
                    s.Fname,
                    s.Lname,
                    s.positCode,
                    s.departCode,
                    s.accessCode,
                    s.is_active,
                    w.departCode,
                    w.departName,
                    f.prefixCode,
                    f.prefixName,
                    o.positCode,
                    o.positName,
                    a.accessCode,
                    a.accessName
                FROM 
                    personal s 
                    LEFT JOIN position o ON(s.positCode = o.positCode)
                    LEFT JOIN access a ON(s.accessCode = a.accessCode)
                    LEFT JOIN department w ON(s.departCode = w.departCode)
                    LEFT JOIN prefix f ON(s.prefixCode = f.prefixCode)
                WHERE
                    is_delete = '0'
        ";
        $result = $conn->query($sql);
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    }
    if($act == 'create'){
        $sql = "SELECT * FROM `personal` WHERE `Personal_ID` = '".$conn->real_escape_string($Personal_ID)."' AND `is_delete` = '0'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            echo json_encode(array("status"=>false, "msg"=> "เนื่องจากรหัสเจ้าหน้าที่/บุคลากรนี้ ถูกใช้งานแล้ว กรุณาตรวจสอบใหม่อีกครั้ง"));
            exit();
        }
        $sql = "INSERT INTO `personal`(`Personal_ID`,`prefixCode`,`Fname`,`Lname`,`positCode`,`departCode`,`password`,`accessCode`, `is_active`) 
        VALUES ('".$conn->real_escape_string($Personal_ID)."','{$conn->real_escape_string($prefixCode)}','".$conn->real_escape_string($Fname)."','".$conn->real_escape_string($Lname)."','{$conn->real_escape_string($positCode)}','{$conn->real_escape_string($departCode)}','".$Password."','$accessCode','$statusCode')";
       if($conn -> query($sql)){
            $msg["status"] = true;
            $msg["msg"] = "บันทึกรายการสำเร็จ";
            // $msg = "<div style='color:green';>บันทึกรายการสำเร็จ</div>";
        }else{
            $msg["status"] = false;
            $msg["msg"] = "บันทึกรายการไม่สำเร็จ";
            // $msg = "<div style='color:red'>บันทึกรายการไม่สำเร็จ</div>";
        }
        echo json_encode($msg);
    }

    if($act == 'update'){

        $sql = "SELECT * FROM `personal` WHERE `Personal_ID` = '{$conn->real_escape_string($Personal_ID)}' AND `is_delete` = '0' AND `Personal_ID` != '{$conn->real_escape_string($Personal_ID)}'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            echo json_encode(array("status"=>false, "msg"=> "เนื่องจากรหัสเจ้าหน้าที่/บุคลากรนี้ ถูกใช้งานแล้ว กรุณาตรวจสอบใหม่อีกครั้ง"));
            exit();
        }

        $sql = "UPDATE `personal` SET 
                    `prefixCode`='{$conn->real_escape_string($prefixCode)}',
                    `Fname`='{$conn->real_escape_string($Fname)}',
                    `Lname`='{$conn->real_escape_string($Lname)}',
                    `positCode`='{$conn->real_escape_string($positCode)}',
                    `departCode`='{$conn->real_escape_string($departCode)}',
                    `is_active`='$statusCode',
                    ";
        if($Password != "") $sql .= "`password`='$Password',";
        $sql .= "`accessCode`= '{$conn->real_escape_string($accessCode)}'
         WHERE `Personal_ID`='{$conn->real_escape_string($Personal_ID)}'";
    //   if($conn -> query($sql)){
    //         $msg = "<div style='color:green';>แก้ไขรายการสำเร็จ</div>";
    //     }else{
    //         $msg = "<div style='color:red'>แก้ไขรายการไม่สำเร็จ</div>";
    //     }
    //     echo $msg;
    $msg = [];
        if($conn -> query($sql)){
            $msg["status"] = true;
            $msg["msg"] = "แก้ไขรายการสำเร็จ";
        }else{
            $msg["status"] = false;
            $msg["msg"] = "แก้ไขรายการไม่สำเร็จ";
        }
        echo json_encode($msg);
    }

    if($act == 'delete'){
        $sql = "UPDATE `personal` SET `is_delete`= '1' WHERE `Personal_ID` = '{$conn->real_escape_string($Personal_ID)}'";
        if($conn -> query($sql)){
            $msg = "ลบข้อมูลสำเร็จ";
        }else{
            $msg ="ลบข้อมูลไม่สำเร็จ";
        }
        echo $msg;
    }
?>
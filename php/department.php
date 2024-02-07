<?php
    $msg ="('status':'fail')";
    require_once('../dbConnect/connect.php');


    $act = isset($_POST['act'])?$_POST['act'] : "";
    $departCode = isset($_POST['departCode'])?$_POST['departCode'] : "";
    $departName = isset($_POST['departName'])?$_POST['departName'] : "";
    $Personal_ID = isset($_POST['Personal_ID'])?$_POST['Personal_ID'] : "";

    if($act == 'create'){
        $conn->autocommit(false);
        $sql = "insert into department (departCode,departName) values ('{$conn->real_escape_string($departCode)}','{$conn->real_escape_string($departName)}')";
        if($conn -> query($sql)){
            $sql = "UPDATE `personal` SET is_leader = '0' WHERE is_leader = '1' AND departCode = '{$conn->real_escape_string($departCode)}'";
            if($conn->query($sql)){
                $sql = "UPDATE `personal` SET is_leader = '1' WHERE Personal_ID = '{$conn->real_escape_string($Personal_ID)}'";
                if($conn->query($sql)){
                    $conn->commit();
                    $msg = "<div style='color:green';>บันทึกรายการสำเร็จ</div>";
                }else $msg = "<div style='color:red'>บันทึกรายการไม่สำเร็จ</div>";
            }else $msg = "<div style='color:red'>บันทึกรายการไม่สำเร็จ</div>";
        }else{
            $msg = "<div style='color:red'>บันทึกรายการไม่สำเร็จ</div>";
        }
        echo $msg;
    }

    if($act == 'personal'){
        $sql = 'select * from personal where is_delete = "0" AND is_active = "1"';
        $result = $conn->query($sql);
        echo json_encode($result -> fetch_all(MYSQLI_ASSOC));
    }
    

    if($act == 'read'){
        $sql = "SELECT 
                    w.departCode,
                    w.departName,
                    IFNULL(p.Personal_ID,'') AS Personal_ID,
                    IFNULL(p.prefixCode,'') AS prefixCode,
                    IFNULL(p.Fname,'') AS Fname,
                    IFNULL(p.Lname,'') AS Lname,
                    IFNULL(f.prefixCode,'') AS prefixCode,
                    IFNULL(f.prefixName,'') AS prefixName
                FROM 
                    department w 
                    left join personal p on(w.departCode = p.departCode ) AND (p.is_leader = '1')
                    left join prefix f on (p.prefixCode = f.prefixCode)
                WHERE
                    departIsDelete = '0'
                ";
        $result = $conn->query($sql);
        echo json_encode($result -> fetch_all(MYSQLI_ASSOC));
    }

    if($act == 'update'){   
        $conn->autocommit(false);
        $sql = "update department set departName = '$departName' where departCode = '$departCode'";
        if($conn -> query($sql)){
            $sql = "UPDATE `personal` SET is_leader = '0' WHERE is_leader = '1' AND departCode = '{$conn->real_escape_string($departCode)}'";
            if($conn->query($sql)){
                $sql = "UPDATE `personal` SET is_leader = '1' WHERE Personal_ID = '{$conn->real_escape_string($Personal_ID)}'";
                if($conn->query($sql)){
                    $conn->commit();
                    $msg = "<div style='color:green';>แก้ไขรายการสำเร็จ</div>";
                }else $msg = "<div style='color:red'>แก้ไขรายการไม่สำเร็จ</div>";
            }else $msg = "<div style='color:red'>แก้ไขรายการไม่สำเร็จ</div>";
            
        }else{
            $msg = "<div style='color:red'>แก้ไขรายการไม่สำเร็จ</div>";
        }
        echo $msg;
    }

    if($act == 'delete'){
        $sql = "UPDATE department SET departIsDelete = '1' WHERE departCode ='{$conn->real_escape_string($departCode)}'";
        if($conn -> query($sql)){
            $msg = "ลบข้อมูลสำเร็จ";
        }else{
            $msg ="ลบข้อมูลไม่สำเร็จ";
        }
        echo $msg;
    }

    if($act == 'work'){  
        $cottonCode = $_POST['cottonCode'];
        $sql = "SELECT * from `cotton` WHERE cottonCode = '$cottonCode'";
        $result = $conn->query($sql);
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    }
?>
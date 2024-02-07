<?php
    require_once('../dbConnect/connect.php');
    $data =[];
    $act = isset($_POST['act'])?$_POST['act'] : "";
    $username = isset($_POST['username'])?$_POST['username'] : "";
    $pass = isset($_POST['pass'])?$_POST['pass'] : "";

    if($act == 'login'){
        $complete = json_decode($complete);
        $invalid = json_decode($invalid);
        $sql = "select * from personal where Personal_ID = '$username'";
        $result = $conn->query($sql);
        if($result -> num_rows == 1){
            $row = $result->fetch_assoc();
            if($username == $row["Personal_ID"] && hash("sha256",$pass) == $row["Password"]){
                $sql = "select id,Personal_ID,prefixCode,Fname,Lname,positCode,departCode,accessCode,is_leader from personal where id = {$row['id']}";
                $result = $conn->query($sql);
                if($result -> num_rows == 1){
                    $data[0] = $complete;
                    $data[1] = $result->fetch_assoc();
                }else{
                    $data[0] = $invalid;
                }
            }else{
                $data[0] = $invalid;
            }
        }else{
            $data[0] = $invalid;
        }
    }else{
        $data[0] = $invalid;
    }
    echo json_encode($data);
?>
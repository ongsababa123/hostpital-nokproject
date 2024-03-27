<?php
    require_once('../dbConnect/connect.php');
    
    
    $conn->autocommit(false);

    $act = isset($_POST['act']) ? $_POST['act'] : "";
    $find_department = isset($_POST['find_department']) ? $_POST['find_department'] : "";
    $find_position = isset($_POST['find_position']) ? $_POST['find_position'] : "";
    $currentDateSelect = isset($_POST['currentDateSelect']) ? $_POST['currentDateSelect'] : "";

    $depart_code = isset($_POST['depart_code']) ? $_POST['depart_code'] : '';
    $position_code = isset($_POST['position_code']) ? $_POST['position_code'] : '';
    $idEdit_arr = isset($_POST['idEdit_arr']) ? json_decode($_POST['idEdit_arr']) : [];
    $personalId_arr = isset($_POST['personalId_arr']) ? json_decode($_POST['personalId_arr']) : [];
    $personalShift_arr = isset($_POST['personalShift_arr']) ? json_decode($_POST['personalShift_arr']) : [];
    $presonalShiftType_arr = isset($_POST['presonalShiftType_arr']) ? json_decode($_POST['presonalShiftType_arr']) : [];

    $cookie = $_COOKIE['hostpital'];
    $updated_by = base64_decode(json_decode(base64_decode($cookie))->Personal_ID);

    if($act == "departmentList"){
        $sql = "SELECT * FROM `department` WHERE `departIsDelete` = '0'";
        $result = $conn->query($sql);
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    }
    if($act == "personaltList"){
        $data = [];
        $sql = "SELECT 
                    a.*, 
                    b.prefixName
                FROM 
                    `personal` a
                    LEFT JOIN `prefix` b ON a.`prefixCode` = b.`prefixCode`
                WHERE 
                    a.`positCode` = '$find_position'
                    AND a.`departCode` = '$find_department'
                ";
        $result = $conn->query($sql);
        $data['personal'] = $result->fetch_all(MYSQLI_ASSOC);

        $sql = "SELECT
                    a.*,
                    b.Fname,
                    b.Lname,
                    c.prefixName
                FROM 
                    `shift_schedule` a
                    LEFT JOIN `personal` b ON a.`Personal_ID` = b.`Personal_ID`
                    LEFT JOIN `prefix` c ON b.`prefixCode` = c.`prefixCode`
                WHERE 
                    a.`positCode` = '$find_position'
                    AND a.`departCode` = '$find_department'
                    AND a.`shift_date` = '$currentDateSelect'
                    AND a.`shift_status` = '1'
                ";
        $result = $conn->query($sql);
        $data['shift'] = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($data);
    }
    
    if($act == "shiftManageList"){
        $data = [];
        $sql = "SELECT 
                    tb.positCode,
                    p.positName
                FROM
                    (
                        (
                            SELECT 
                                `positCode` AS positCode
                            FROM 
                                `shift_schedule` 
                            WHERE 
                                `shift_date` = '$currentDateSelect' 
                                AND `shift_status` = '1' 
                                AND `departCode` = '$find_department'
                            GROUP BY
                                `positCode` 
                        )
                        UNION
                        (
                            SELECT 
                                `position_code` AS  positCode
                            FROM 
                                `shift_setting` 
                            GROUP BY
                                `position_code`
                        )
                    ) tb
                    LEFT JOIN `position` p ON tb.positCode = p.positCode
                GROUP BY
                    tb.positCode
                ";
        $result = $conn->query($sql);
        $data["position_shift"] = $result->fetch_all(MYSQLI_ASSOC);

        $sql = "SELECT 
                    a.*,
                    b.`Fname`,
                    b.`Lname`,
                    c.`prefixName`,
                    IF(a.`is_shift` = 0, '', 
                        IF(a.`shift_type` = 'M', 'เช้า',
                            IF(a.`shift_type` = 'A','บ่าย','ดึก')
                        )
                    ) AS shift_name,
                    IF(a.`is_shift` = 0, 0, 
                        IF(a.`shift_type` = 'M',1,
                            IF(a.`shift_type` = 'A',2,3)
                        )
                    ) AS sort
                FROM 
                    `shift_schedule` a
                    LEFT JOIN `personal` b ON a.`Personal_ID` = b.`Personal_ID`
                    LEFT JOIN `prefix` c ON b.`prefixCode` = c.`prefixCode`
                WHERE 
                    a.`shift_date` = '$currentDateSelect' 
                    AND a.`shift_status` = '1' 
                    AND a.`departCode` = '$find_department'
                ORDER BY 
                    sort ASC, c.`prefixName` ASC, b.`Fname` ASC, b.`Lname` ASC
                ";
        $result = $conn->query($sql);
        $data["personal_shift"] = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($data);

        // $sql = "SELECT * FROM `shift_schedule` WHERE `shift_date` = '$currentDateSelect' AND `shift_status` = '1' AND `departCode` = '$find_department'";
        // $result = $conn->query($sql);
        // echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    }

    if($act == "add"){
        $state = true;
        $updateId = $idEdit_arr;
        $updateId = array_filter($updateId, fn($value) => !is_null($value) && $value != '');
        if(count($updateId) > 0){
            $sql = "UPDATE `shift_schedule` SET `shift_status` = '0' WHERE id NOT IN (".implode(",", $updateId).")";
            if(!$conn->query($sql)) $state = false;
        }

        for ($i=0; $i < count($personalId_arr) ; $i++) { 
            $sql = "INSERT INTO `shift_schedule` (
                        `id`,
                        `Personal_ID`,
                        `positCode`,
                        `departCode`,
                        `shift_date`,
                        `is_shift`,
                        `shift_type`,
                        `shift_status`,
                        `created_by`,
                        `created_date`,
                        `updated_by`,
                        `updated_date`
                    ) VALUES (
                        ".($idEdit_arr[$i] != "" ? "'".$idEdit_arr[$i]."'" : "NULL").",
                        '".$personalId_arr[$i]."',
                        '".$position_code."',
                        '".$depart_code."',
                        '".$currentDateSelect."',
                        '".$personalShift_arr[$i]."',
                        ".($personalShift_arr[$i] == "1" ? "'".$presonalShiftType_arr[$i]."'" : "NULL").",
                        '1',
                        '$updated_by',
                        now(),
                        '$updated_by',
                        now()
                    ) ON DUPLICATE KEY UPDATE
                        `is_shift` = '".$personalShift_arr[$i]."',
                        `shift_type` = ".($personalShift_arr[$i] == "1" ? "'".$presonalShiftType_arr[$i]."'" : "NULL").",
                        `updated_by` = '$updated_by',
                        `updated_date` = now()
                    ";
            if(!$conn->query($sql)) {
                $state = false;
            }
        }

        if($state) {
            $conn->commit();
            echo $complete;
        }else echo $invalid;
    }

?>
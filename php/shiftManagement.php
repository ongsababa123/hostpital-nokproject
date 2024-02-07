<?php
    require_once('../dbConnect/connect.php');
    
    
    $conn->autocommit(false);

    $act = isset($_POST['act']) ? $_POST['act'] : "";
    $find_department = isset($_POST['find_department']) ? $_POST['find_department'] : "";
    $find_position = isset($_POST['find_position']) ? $_POST['find_position'] : "";
    $currentDateSelect = isset($_POST['currentDateSelect']) ? $_POST['currentDateSelect'] : "";

    if($act == "departmentList"){
        $sql = "SELECT * FROM `department` WHERE `departIsDelete` = '0'";
        $result = $conn->query($sql);
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    }
    if($act == "personaltList"){
        $sql = "SELECT 
                    a.*, 
                    b.prefixName
                FROM 
                    `personal` a
                    LEFT JOIN `prefix` b ON a.`prefixCode` = b.`prefixCode`
                WHERE 
                    a.`is_active` = '1' 
                    AND a.`is_delete` = '0'
                    AND a.`positCode` = '$find_position'
                    AND a.`departCode` = '$find_department'
                ";
        $result = $conn->query($sql);
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
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

?>
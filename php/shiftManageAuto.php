<?php
    require_once('../dbConnect/connect.php');
    require_once('classSchedule.php');
    ini_set("memory_limit","2024M");
    $conn->autocommit(false);

    $current = date("Y-m-d");
    $current = explode("-",$current);

    if($current[2] == "24"){
        insertDetail();
    }else{
        if(intval($current[2]) > 24){
            if(substr($current[1], 0, 1) == "0") $current[1] = substr($current[1], 1, 1);
            $sql = "SELECT * FROM `shiftauto_created` WHERE YEAR(lastDate) = '".$current[0]."' AND  MONTH(lastDate) = '".$current[1]."'";
            if($result->num_rows == 0){
                insertDetail();
            }
        }
    }

    function insertDetail(){
        $sql = "SELECT GROUP_CONCAT(p.Personal_ID) AS Personal_ID_arr,p.positCode,p.departCode,s.is_shift,s.moring_shift_count,s.afternoon_shift_count,s.night_shift_count  FROM `personal` p LEFT JOIN `shift_setting` s ON (p.positCode = s.position_code) WHERE s.position_code IS NOT NULL AND s.all_department = '0' GROUP BY p.positCode,p.departCode";
        $result = $conn->query($sql);

        $detailDifPart = json_encode($result->fetch_all(MYSQLI_ASSOC));
        $detailDifPart = json_decode($detailDifPart);

        $sql = "SELECT GROUP_CONCAT(p.Personal_ID) AS Personal_ID_arr,p.positCode,p.departCode,s.is_shift,s.moring_shift_count,s.afternoon_shift_count,s.night_shift_count  FROM `personal` p LEFT JOIN `shift_setting` s ON (p.positCode = s.position_code) WHERE s.position_code IS NOT NULL AND s.all_department = '1' GROUP BY p.positCode";
        $result = $conn->query($sql);

        $detailAllPart = json_encode($result->fetch_all(MYSQLI_ASSOC));
        $detailAllPart = json_decode($detailAllPart);

        $current = date("Y-m-d");
        $current = explode("-",$current);
        
        $nextMonth = date('Y-m-d', strtotime('first day of +1 month'));
        $nextMonth = explode("-",$nextMonth);
        $countDayThisMonth = cal_days_in_month(CAL_GREGORIAN,$nextMonth[1],$nextMonth[0]);

        $state = true;

        foreach ($detailDifPart as $key => $value){
            $Personal_ID_arr = json_decode(json_encode(explode(",",$value->Personal_ID_arr)));
            if($value->is_shift == "1"){
                $shift[0] = $value->moring_shift_count;
                $shift[1] = $value->afternoon_shift_count;
                $shift[2] = $value->night_shift_count;
            }else{
                $shift[0] = $value->moring_shift_count;
            }
            $autoSchedule = new AutoSchedule($Personal_ID_arr, $value->is_shift, $shift, 0, $countDayThisMonth);
            $ListShift = $autoSchedule->manageSchedule();

            foreach ($ListShift as $keyDay => $valueDay) {
                $day = $keyDay + 1;
                foreach ($valueDay as $keyShift => $valueShift) {
                    if($keyShift == 0) $shiftType = "M";
                    else if($keyShift == 1) $shiftType = "A";
                    else if($keyShift == 2) $shiftType = "N";
                    
                    foreach ($valueShift as $keyPersona => $valuePersona){
                        $sql = "INSERT INTO  `shift_schedule` (
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
                                ) SELECT 
                                    `Personal_ID`,
                                    `positCode`,
                                    `departCode`,
                                    '".($current[0]."-".$current[1]."-".(strlen($day) <= 1 ? "0".$day: $day))."',
                                    '".$value->is_shift."',
                                    ".($value->is_shift == "0" ? "NULL" : "'".$shiftType."'").",
                                    '1',
                                    NULL,
                                    now(),
                                    NULL,
                                    now()
                                FROM
                                    `personal`
                                WHERE
                                    `Personal_ID` = '".$valuePersona."'";
                        if(!$conn->query($sql)) $state = false;
                    }
                }
            }
        }

        foreach ($detailAllPart as $key => $value){
            $Personal_ID_arr = json_decode(json_encode(explode(",",$value->Personal_ID_arr)));
            if($value->is_shift == "1"){
                $shift[0] = $value->moring_shift_count;
                $shift[1] = $value->afternoon_shift_count;
                $shift[2] = $value->night_shift_count;
            }else{
                $shift[0] = $value->moring_shift_count;
            }
            $autoSchedule = new AutoSchedule($Personal_ID_arr, $value->is_shift, $shift, 0, $countDayThisMonth);
            $ListShift = $autoSchedule->manageSchedule();
            foreach ($ListShift as $keyDay => $valueDay) {
                $day = $keyDay + 1;
                foreach ($valueDay as $keyShift => $valueShift) {
                    if($keyShift == 0) $shiftType = "M";
                    else if($keyShift == 1) $shiftType = "A";
                    else if($keyShift == 2) $shiftType = "N";
                    
                    foreach ($valueShift as $keyPersona => $valuePersona){
                        $sql = "INSERT INTO  `shift_schedule` (
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
                                ) SELECT 
                                    `Personal_ID`,
                                    `positCode`,
                                    `departCode`,
                                    '".($current[0]."-".$current[1]."-".(strlen($day) <= 1 ? "0".$day: $day))."',
                                    '".$value->is_shift."',
                                    ".($value->is_shift == "0" ? "NULL" : "'".$shiftType."'").",
                                    '1',
                                    NULL,
                                    now(),
                                    NULL,
                                    now()
                                FROM
                                    `personal`
                                WHERE
                                    `Personal_ID` = '".$valuePersona."'";
                        if(!$conn->query($sql)) $state = false;
                    }
                }
            }
        }

        $sql = "INSERT INTO shiftauto_created (`lastDate`) VALUES (now())";
        if(!$conn->query($sql)) $state = false;

        if($state){
            $conn->commit();
        }
    }

?>
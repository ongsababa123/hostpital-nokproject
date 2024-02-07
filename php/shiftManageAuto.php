<?php
    require_once('../dbConnect/connect.php');
    
    
    $conn->autocommit(false);

    $sql = "SELECT GROUP_CONCAT(p.Personal_ID) AS Personal_ID_arr,p.positCode,p.departCode,s.is_shift,s.moring_shift_count,s.afternoon_shift_count,s.night_shift_count  FROM `personal` p LEFT JOIN `shift_setting` s ON (p.positCode = s.position_code) WHERE s.position_code IS NOT NULL GROUP BY p.positCode,p.departCode";
    $result = $conn->query($sql);

    $detail = json_encode($result->fetch_all(MYSQLI_ASSOC));
    $detail = json_decode($detail);


    foreach ($detail as $key => $value) {
        $countPeople = explode(",",$value->Personal_ID_arr);
        $countDay = cal_days_in_month(CAL_GREGORIAN,1,2024);
        $value->moring_shift_count = 2;
        
        switch ($value->is_shift) {
            case '0': {
                $lastIndex = null;
                $shiftCount = floor(($countDay * floatval($value->moring_shift_count)) / count($countPeople));


                if(count($countPeople) >= (floatval($value->moring_shift_count) * 3)){
                    $lastIndex = [];
                    $amtShift = [];
                    for ($i=1; $i <= $countDay; $i++){
                        $newArray = [];
                        for ($j = 0; $j < floatval($value->moring_shift_count); $j++) { 
                            $randomeIndex = manageFindIndex(count($countPeople), $newArray,  $lastIndex, $amtShift, $shiftCount);
                            $newArray[$j] = $randomeIndex;
                            if(array_key_exists($randomeIndex, $amtShift)) $amtShift[$randomeIndex] = $amtShift[$randomeIndex] + 1;
                            else $amtShift[$randomeIndex] = 1;
                            if((floatval(array_sum($amtShift)) / count($amtShift)) == $shiftCount) {
                                $shiftCount = $shiftCount+1;
                            }
                        }
                        $lastIndex = $newArray;
                        print_r($lastIndex);    
                        print_r("<br>");
                    }
                    print_r(array_sum($amtShift));
                }else{
                    for ($i=1; $i <= $countDay; $i++){
                        $randomeIndex = rand(0,$limitpeopleDay);
                    }
                }
               

                break;
            }
            case '1': {

                break;
            }
        }
    }

    function manageFindIndex($limitpeopleDay, $inSameDay, $yesterDay, $checkShiftAmt, $limitAmt){
        $newIndex = rand(0,$limitpeopleDay);
        if(!array_key_exists($newIndex, $checkShiftAmt) || (array_key_exists($newIndex, $checkShiftAmt) &&  floatval($checkShiftAmt[$newIndex]) < $limitAmt)){
            if(!in_array($newIndex, $inSameDay, true) && !in_array($newIndex, $yesterDay, true)){
                return $newIndex;
            }else{
                return manageFindIndex($limitpeopleDay, $inSameDay, $yesterDay, $checkShiftAmt, $limitAmt);
            }
        }else{
            return manageFindIndex($limitpeopleDay, $inSameDay, $yesterDay, $checkShiftAmt, $limitAmt); 
        }
        
    }

    // $isShift = true;
    // $shiftPeople = 2;
    // manageShift($detail, $isShift, $shiftPeople);
    // foreach ($detail as $key => $value) {

    // }

    // function manageShift($data, $isShift, $shiftPeople){
        // $countDay = cal_days_in_month(CAL_GREGORIAN,1,2024);
        // $lastIndex = null;
        // $limitpeopleDay = floor($countDay / count($detail)) <= 0 ? 1 : floor($countDay / count($data));


        // $countArr = [];

        // for ($i=1; $i <= $countDay; $i++) { 
        //     $randomeIndex = findIndex((count($data) - 1), $countArr, $limitpeopleDay, $lastIndex);
        //     echo $data[$randomeIndex]->Fname." ".$data[$randomeIndex]->Lname." <br>";
        //     if(array_key_exists($randomeIndex, $countArr)) $countArr[$randomeIndex] = intval($countArr[$randomeIndex]) + 1;
        //     else $countArr[$randomeIndex] = 1;
        //     $lastIndex = $randomeIndex;
        //     if(array_sum($countArr) == ($limitpeopleDay * count($data))) $limitpeopleDay++;
        // }
    // }

    // function findIndex($limitRandom, $checkCount, $limitpeople, $lastIndex){
    //     // $newIndex = rand(0,$limitRandom);
    //     // if($newIndex != $lastIndex){
    //     //     if(array_key_exists($newIndex, $checkCount)){
    //     //         if(intval($checkCount[$newIndex]) < $limitpeople){
    //     //             return $newIndex;
    //     //         }else return findIndex($limitRandom, $checkCount, $limitpeople, $lastIndex);
    //     //     }else return $newIndex; 
    //     // }else{
    //     //     return findIndex($limitRandom, $checkCount, $limitpeople, $lastIndex);
    //     // }
    // }

?>
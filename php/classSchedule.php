<?php

class AutoSchedule{
    private $employees;
    private $is_shif;
    private $all_department;
    private $dateOfMonth;
    private $employeesCopy;
    private $lastUse;
    private $lastUseArray;
    private $enoughForDay;

    public function __construct($employees, $is_shif, $shift, $all_department, $dateOfMonth) {
        $this->employees = $employees;
        $this->is_shif = $is_shif;
        $this->shift = $shift;
        $this->all_department = $all_department;
        $this->dateOfMonth = $dateOfMonth;
        $this->employeesCopy = array_flip(array_flip($employees));
    }

    public function manageSchedule(){
        $schedule = [];
        if($this->is_shif == "1"){
            $sum = array_sum($this->shift);
            $enough = floor(count($this->employees) / $sum);
            $this->enoughForDay =  $enough >= 1 ? "true" : "false";
        }
        for ($i = 0; $i < $this->dateOfMonth; $i++){
            
            if($this->is_shif != "1"){
                $schedule[] = $this->randomScheduleNotShift();
            }else{
                $schedule[] = $this->randomScheduleIsShift();
            }
        }
        return $schedule;
    }

    public function randomScheduleNotShift(){
        $listShift = [];
        for($i = 0; $i < count($this->shift); $i++){
            $listPersonal = [];
            $this->lastUse = "";
            for ($j=0; $j < $this->shift[$i]; $j++) { 
                $index = $this->randomIndexNotShift();
                $listPersonal[$j] = $this->employees[$index];
                $this->lastUse = $listPersonal[$j];
                array_splice($this->employees, $index, 1);
                if(count($this->employees) <= 0){
                    $this->employees = array_flip(array_flip($this->employeesCopy));
                }
            }
            
            $listShift[$i] = $listPersonal;
        }

        return $listShift;
    }
    public function randomScheduleIsShift(){
        $this->lastUseArray = [];
        $listShift = [];
        for($i = 0; $i < count($this->shift); $i++){
            $listPersonal = [];
            $this->lastUse = "";
            for ($j=0; $j < $this->shift[$i]; $j++) { 
                $index = $this->randomIndexIsShift();
                $listPersonal[$j] = $this->employees[$index];
                $this->lastUseArray[] = $listPersonal[$j];
                $this->lastUse = $listPersonal[$j];
                array_splice($this->employees, $index, 1);
                if(count($this->employees) <= 0){
                    $this->employees = array_flip(array_flip($this->employeesCopy));
                }
            }
            $listShift[$i] = $listPersonal;
        }

        return $listShift;
    }

    public function randomIndexNotShift(){
        $index = rand(0, count($this->employees) - 1);
        if($this->lastUse != $this->employees[$index]){
            return $index;
        }else{
            if($index < (count($this->employees) - 1)){
                return $index + 1;
            }else{
                return $index - 1;
            }
            
        }
    }
    
    public function randomIndexIsShift(){

        $index = rand(0, count($this->employees) - 1);
        if($this->enoughForDay === "true"){
            if($this->lastUse != $this->employees[$index]){
                return $index = $this->findValueInOneDay($index);
            }else{
                if($index < (count($this->employees) - 1)){
                    return $index = $this->findValueInOneDay($index + 1);;
                }else{
                    return $index = $this->findValueInOneDay($index - 1);
                }
            }
        }else{
            if($this->lastUse != $this->employees[$index]){
                return $index;
            }else{
                if($index < (count($this->employees) - 1)){
                    return $index + 1;
                }else{
                    return $index - 1;
                }
            }
        }
        
    }
    public function findValueInOneDay($value){
        if(count($this->lastUseArray) > 0){
            $find = array_search($this->employees[$value], $this->lastUseArray);
            if($find !== false){
                for ($i=0; $i < count($this->employees); $i++){
                    $find2 = array_search($this->employees[$i], $this->lastUseArray);
                    if($find2 === false){
                        $index = $i;
                        break;
                    }
                }
            }else{
                $index = $value;
            }
        }else{
            $index = $value;
        }
        return $index;
    }
}

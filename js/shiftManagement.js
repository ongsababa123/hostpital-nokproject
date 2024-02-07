var shiftManagement_prg = "php/shiftManagement.php",
    currentMonth = 0,
    monthArray = ["มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิการยน","ธันวาคม"],
    currentDateSelect = new Date().toLocaleDateString('sv-SE'),
    personalFormat = [];
$(function(){
    shiftManagementinit();

    $("#before_button").click(function(){
        currentMonth--;
        calendarList();
    });
    $("#next_button").click(function(){
        currentMonth++;
        calendarList();
    });

    $("#find_department").change(function(){
        shiftManagementDisplay();
    })
});

async function shiftManagementinit(){
    await calendarList();
    await dapartmentList();
    shiftManagementDisplay();
}

async function calendarList(){
    $("#DayShow").empty();
    let find_date = new Date();
    find_date.setMonth(find_date.getMonth() + currentMonth);
    let currentMonthDate = daysInMonth(find_date.getMonth(), find_date.getYear()),
        today = new Date();
        dayOneOfMonth = find_date.getFullYear()+"-"+(((find_date.getMonth() + 1).toString()).length > 1 ? ((find_date.getMonth() + 1).toString() ): "0"+(find_date.getMonth() + 1).toString())+"-01";
        dayOneOfMonth = new Date(dayOneOfMonth),
        grid = dayOneOfMonth.getDay() == 0 ? 7 : dayOneOfMonth.getDay();

        $("#montShow").html(monthArray[dayOneOfMonth.getMonth()]);
        $("#yearShow").html(dayOneOfMonth.getFullYear());
        for(i = 1; i <= currentMonthDate; i++){
            let str = `onclick="dateSelect('${i}', '${dayOneOfMonth.getMonth() + 1}', '${dayOneOfMonth.getFullYear()}')" `;
            if(
                today.getDate() == i &&
                today.getMonth() == dayOneOfMonth.getMonth() &&
                today.getFullYear() == dayOneOfMonth.getFullYear()
            ) str += "class='today'";
            else str += '';
            await $("#DayShow").append(`<button ${str}><time>${i}</time></button>`);
        }
        $("#DayShow button:first-child").css("grid-column",grid);
}

function daysInMonth (month, year) {
    return new Date(parseInt(year), parseInt(month) + 1, 0).getDate();
}

function dateSelect(date, month, year){
    if(date.length <= 1) date = "0"+date;
    if(month.length <= 1) month = "0"+month;

    currentDateSelect = year+"-"+month+"-"+date;

    shiftManagementDisplay();
}

function shiftManagementDisplay(){
    $("#showPositionShift").empty();
    let find_department = $("#find_department").val();
    $.post(shiftManagement_prg,{
        "act" : "shiftManageList",
        "find_department" : find_department,
        "currentDateSelect" : currentDateSelect
    },(data)=>{
        let result = JSON.parse(data);
        $positionShiftFormat = result["position_shift"];
        $personalShiftFormat = result["personal_shift"];
        let str = ``;
        $.each($positionShiftFormat,(i, item)=>{
            let people = $.grep($personalShiftFormat, (n)=>{return  n.positCode == item.positCode;});
            str += `<div class="card p-4">
                        <div class="row d-flex justify-content-center">
                            <div class="col-12 text-left">
                                <div class="row">
                                    <div class="col-12">
                                        <h4>${item.positName} &nbsp;${new Date(currentDateSelect) > new Date() ? people.length > 0 ? `<button type="button" onclick="shiftManagementModalOpen('${find_department}', '${item.positCode}','${item.positName}')" class="btn btn-warning"><i class="fas fa-edit"></i></button>` : `<button type="button" onclick="shiftManagementModalOpen('${find_department}', '${item.positCode}','${item.positName}')" class="btn btn-success"><i class="fas fa-plus"></i></button>` : ""}</h4>
                                    </div>
                                    <hr class="my-1 mx-0 w-100">
                                   `;
                if(people.length > 0){
                    $.each(people,(i_x, item_x)=>{
                        str += `<div class="col-12">
                                    <div class="row">
                                        <div class="col-6"><h5>${i_x + 1}. ${item_x.prefixName} ${item_x.Fname} ${item_x.Lname} </h5></div>
                                        <div class="col-4"><h5>${item_x.shift_name}</h5></div>
                                    </div>
                                </div>`;
                    });
                }else{
                    str += `<div class="col-12">
                                <h5>ไม่รายชื่อปฏิบัติงาน</h5>
                            </div>`;
                }
                                   str +=`
                                </div>
                            </div>
                        </div>
                    </div>`;
        });
        $("#showPositionShift").html(str);

    });
}

async function dapartmentList(){
    let formData = new FormData();
    formData.append("act", "departmentList");
    $.ajax({
        type: "POST",
        url: shiftManagement_prg,
        data: formData,
        async: false,
        success: function(data) {
            let result = JSON.parse(data),
                myDepartMent = callCookies("hostpital");
            $("#find_department").empty();
            
            $.each(result, function(i, item){
                let s = "";
                if(item.departCode == myDepartMent.departCode) s = "selected";
                let $option = $(`<option value='${item.departCode}' ${s}></option>`).text(item.departName);
                $("#find_department").append($option);
            })
        },
        cache: false,
        contentType: false,
        processData: false
    });
}

function shiftManagementModalOpen(depart_code, position_code, position_name){
    $.post(shiftManagement_prg,{
        act : "personaltList",
        find_department : depart_code,
        find_position : position_code
    },function(data){
        personalFormat = JSON.parse(data);
        $("#add_personal").empty().append(`<option>- เลือกเจ้าหน้าที่/บุคลากร -</option>`);
        $.each(personalFormat,function(i,item){
            let $option = $(`<option value="${item.positCode}"></option>`).text(`${item.prefixName} ${item.Fname} ${item.Lname}`);
            $("#add_personal").append($option);
        })
    });

    $("#shiftManagementLabel").html(position_name);
    $("#shiftManagementModal").modal('show');

    
}
function shiftManagementModalClose(){
    personalFormat = [];
    $("#shiftManagementLabel").html("");
    $("#shiftManagementModal").modal('hide');
}
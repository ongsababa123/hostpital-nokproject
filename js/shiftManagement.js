var shiftManagement_prg = "php/shiftManagement.php",
    currentMonth = 0,
    monthArray = ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิการยน", "ธันวาคม"],
    currentDateSelect = new Date().toLocaleDateString('sv-SE'),
    personalFormat = [];
$(function() {
    shiftManagementinit();

    // $("#before_button").click(function() {
    //     currentMonth--;
    //     calendarList();
    // });
    // $("#next_button").click(function() {
    //     currentMonth++;
    //     calendarList();
    // });

    $("#find_dateShift").keyup(function(e){
        if(e.key == "Enter"){
            shiftManagementDisplay();
        }
    });

    $("#find_department").change(function() {
        shiftManagementDisplay();
    })
});

async function shiftManagementinit() {
    // await calendarList();
    await dapartmentList();
    await $("#find_dateShift").val(currentDateSelect);
    shiftManagementDisplay();
}

async function calendarList() {
    $("#DayShow").empty();
    let find_date = new Date();
    find_date.setMonth(find_date.getMonth() + currentMonth);
    let currentMonthDate = daysInMonth(find_date.getMonth(), find_date.getYear()),
        today = new Date();
    dayOneOfMonth = find_date.getFullYear() + "-" + (((find_date.getMonth() + 1).toString()).length > 1 ? ((find_date.getMonth() + 1).toString()) : "0" + (find_date.getMonth() + 1).toString()) + "-01";
    dayOneOfMonth = new Date(dayOneOfMonth),
        grid = dayOneOfMonth.getDay() == 0 ? 7 : dayOneOfMonth.getDay();

    $("#montShow").html(monthArray[dayOneOfMonth.getMonth()]);
    $("#yearShow").html(dayOneOfMonth.getFullYear());
    for (i = 1; i <= currentMonthDate; i++) {
        let str = `onclick="dateSelect('${i}', '${dayOneOfMonth.getMonth() + 1}', '${dayOneOfMonth.getFullYear()}')" `;
        if (
            today.getDate() == i &&
            today.getMonth() == dayOneOfMonth.getMonth() &&
            today.getFullYear() == dayOneOfMonth.getFullYear()
        ) str += "class='today'";
        else str += '';
        await $("#DayShow").append(`<button ${str}><time>${i}</time></button>`);
    }
    $("#DayShow button:first-child").css("grid-column", grid);
}

function daysInMonth(month, year) {
    return new Date(parseInt(year), parseInt(month) + 1, 0).getDate();
}

// function dateSelect(date, month, year) {
//     if (date.length <= 1) date = "0" + date;
//     if (month.length <= 1) month = "0" + month;

//     currentDateSelect = year + "-" + month + "-" + date;

//     shiftManagementDisplay();
// }

function shiftManagementDisplay() {
    $("#showPositionShift").empty();
    let find_department = $("#find_department").val(),
        currentDateSelect = $("#find_dateShift").val(),
        today = new Date();

        currentDateSelect = new Date(currentDateSelect);
        currentDateSelect.setHours(0,0,0,0);
        today.setHours(0,0,0,0);

    $.post(shiftManagement_prg, {
                "act": "shiftManageList",
                "find_department": find_department,
                "currentDateSelect": $("#find_dateShift").val()
            }, (data) => {
                let result = JSON.parse(data);
                $positionShiftFormat = result["position_shift"];
                $personalShiftFormat = result["personal_shift"];
                let str = ``;
                $.each($positionShiftFormat, (i, item) => {
                            let people = $.grep($personalShiftFormat, (n) => { return n.positCode == item.positCode; });
                            str += `<div class="card p-4">
                        <div class="row d-flex justify-content-center">
                            <div class="col-12 text-left">
                                <div class="row">
                                    <div class="col-12">
                                        <h4>${item.positName} &nbsp;${currentDateSelect >= today ? people.length > 0 ? `<button type="button" onclick="shiftManagementModalOpen('${find_department}', '${item.positCode}','${item.positName}')" class="btn btn-warning"><i class="fas fa-edit"></i></button>` : `<button type="button" onclick="shiftManagementModalOpen('${find_department}', '${item.positCode}','${item.positName}')" class="btn btn-success"><i class="fas fa-plus"></i></button>` : ""}</h4>
                                    </div>
                                    <hr class="my-1 mx-0 w-100">
                                   `;
                if(people.length > 0){
                    $.each(people,(i_x, item_x)=>{
                        str += `<div class="col-12">
                                    <div class="row">
                                        <div class="col-6"><h5>${i_x + 1}. ${item_x.prefixName} ${item_x.Fname} ${item_x.Lname} </h5></div>
                                        <div class="col-3"><h5>${item_x.shift_name}</h5></div>
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

async function shiftManagementModalOpen(depart_code, position_code, position_name){
    let currentDateSelect = $("#find_dateShift").val();
    await $.post(shiftManagement_prg,{
        act : "personaltList",
        find_department : depart_code,
        find_position : position_code,
        currentDateSelect : currentDateSelect
    },function(data){
        let result = JSON.parse(data);
        shiftListFormat = result['shift'];
        personalFormat = result['personal'];

        $("#showListShiftManage").html('');
        $.each(shiftListFormat,function(i,item){
            let $str = `<div class="row mt-1">
                            <div class="col-4">
                                <input type="hidden" class="form-control" name="shift_idEdit[]" value="${item.id}">
                                <input type="hidden" class="form-control" name="shift_presonalId[]" value="${item.Personal_ID}">
                                <input type="text" class="form-control" value="${item.prefixName+" "+item.Fname+" "+item.Lname}" readonly>
                            </div>
                            <div class="col-7">
                                <div class="row">
                                    <div class="col-6 pl-0">
                                        <div class="form-group d-flex align-items-center px-2 m-0 rounded" style="border: 1px solid #ced4da; background-color: #e9ecef; height: 38px;">
                                            <label for="is_shift_${item.Personal_ID}" class="m-0">
                                                <input type="checkbox" onchange="shiftTypeDisbled(this, '${item.Personal_ID}')" id="is_shift_${item.Personal_ID}" name="shift_presonalShift[]" value="1" ${item.is_shift == "1" ? "checked" : ""}> เป็นกะ
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6 pl-0">
                                        <select name="shift_presonalShiftType[]" id="shift_type_${item.Personal_ID}" class="form-select" ${item.is_shift == "0" ? "disabled" : ""}>
                                            <option value="M" ${item.shift_type == "M" ? "selected" : ""}>กะเช้า</option>
                                            <option value="A" ${item.shift_type == "A" ? "selected" : ""}>กะบ่าย</option>
                                            <option value="N" ${item.shift_type == "N" ? "selected" : ""}>กะดึก</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-1">
                                <button type="button" class="btn btn-danger" onclick="shiftManageListDel(this,'${item.Personal_ID}','${item.prefixName+" "+item.Fname+" "+item.Lname}')"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                        `;
            $("#showListShiftManage").append($str);
        });

        $("#add_personal").empty().append(`<option value=''>- เลือกเจ้าหน้าที่/บุคลากร -</option>`);
        $.each(personalFormat,function(i,item){
            let find_item = shiftListFormat.find(itemx => itemx.Personal_ID == item.Personal_ID);
            if(typeof find_item == "undefined"){
                let $option = $(`<option value="${item.Personal_ID}"></option>`).text(`${item.prefixName} ${item.Fname} ${item.Lname}`);
                $("#add_personal").append($option);
            }
        });
    });

    $("#shiftManagementLabel").html(position_name);
    $("#shiftManagementModal").modal('show');

    $("#addPersonalShift").off('click').on('click',function(){
        let id = $("#add_personal").val(),
            name = $("#add_personal option[value='"+id+"']").text();

        if(id != '' && id != null){
            $("#add_personal option[value='"+id+"']").remove();
            shiftManageListDisplay(id, name);
    
            setTimeout(() => {
                $("#add_personal").val('');
            }, 200);
        }
    });

    $("form#shiftManagementForm").off("submit").on("submit",function(){
        let formData = new FormData();
        formData.append("act", "add");
        formData.append("currentDateSelect", currentDateSelect);
        formData.append("depart_code", depart_code);
        formData.append("position_code", position_code);
        formData.append("idEdit_arr", JSON.stringify($("input[name='shift_idEdit[]']").map(function(){return $(this).val();}).get()));
        formData.append("personalId_arr", JSON.stringify($("input[name='shift_presonalId[]']").map(function(){return $(this).val();}).get()));
        formData.append("personalShift_arr", JSON.stringify($("input[name='shift_presonalShift[]']").map(function(){return $(this).is(":checked") ? "1" : "0";}).get()));
        formData.append("presonalShiftType_arr", JSON.stringify($("select[name='shift_presonalShiftType[]']").map(function(){return $(this).val();}).get()));
        $.ajax({
            type: "POST",
            url: shiftManagement_prg,
            data: formData,
            async: false,
            success: function(data) {
                let result = JSON.parse(data);
                if(result.status){
                    setTimeout(() => {
                        shiftManagementDisplay();
                    }, 500);
                    alert("Success");
                    shiftManagementModalClose();
                    
                }else{
                    alert("Fail");
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
        return false;
    })

}

function shiftManageListDisplay(Personal_ID, presonalName){
    let $str = `<div class="row mt-1">
                    <div class="col-4">
                    <input type="hidden" class="form-control" name="shift_idEdit[]" value="">
                        <input type="hidden" class="form-control" name="shift_presonalId[]" value="${Personal_ID}">
                        <input type="text" class="form-control" value="${presonalName}" readonly>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-6 pl-0">
                                <div class="form-group d-flex align-items-center px-2 m-0 rounded " style="border: 1px solid #ced4da; background-color: #e9ecef; height: 38px;">
                                    <label for="is_shift_${Personal_ID}" class="m-0">
                                        <input onchange="shiftTypeDisbled(this, '${Personal_ID}')" type="checkbox" value="1" id="is_shift_${Personal_ID}" name="shift_presonalShift[]"> เป็นกะ
                                    </label>
                                </div>
                            </div>
                            <div class="col-6 pl-0">
                                <select name="shift_presonalShiftType[]" id="shift_type_${Personal_ID}" disabled class="form-control">
                                    <option value="M">กะเช้า</option>
                                    <option value="A">กะบ่าย</option>
                                    <option value="N">กะดึก</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-danger w-100" onclick="shiftManageListDel(this,'${Personal_ID}','${presonalName}')"><i class="fas fa-trash"></i></button>
                    </div>
                </div>`;
    $("#showListShiftManage").append($str);
}

function shiftTypeDisbled(ele, Personal_ID){
    if($(ele).is(":checked"))$("#shift_type_"+Personal_ID).prop("disabled",false);
    else $("#shift_type_"+Personal_ID).prop("disabled",true);
}

function shiftManageListDel(ele, personalId, personalName){
    let $option = $(`<option value="${personalId}"></option>`).text(personalName);
    $("#add_personal").append($option);

    let parent = $(ele).parent().parent();
    $(parent).remove();
}

function shiftManagementModalClose(){
    personalFormat = [];
    $("#shiftManagementLabel").html("");
    $("#shiftManagementModal").modal('hide');
}
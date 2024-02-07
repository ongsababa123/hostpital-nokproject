var shiftSetting_prg = "php/shiftSetting.php",
    shiftAct = 'add',
    positionList = [];

$(function(){

    $("form#prefixform").submit(function(e){
        let $position_id = $('select[name="position_id[]"]'),
            state = true,
            checkId = [];
        if($position_id.length <= 0){
            alert("กรุณาเพิ่มรายการ");
            return false;
        }
        $.each($position_id,function(i,item){
            if($.inArray($(item).val(), checkId) <= -1) checkId.push($(item).val());
            else state = false;
        });

        if(!state) {
            alert("มีตำแหน่งซ้ำ กรุณาตรวจสอบให้เรียบร้อย");
            return false;
        }

        let formData = new FormData();
        formData.append('act', shiftAct);
        formData.append('detailCount', $position_id.length);
        formData.append('position_id', JSON.stringify($("select[name='position_id[]']").map(function(){return $(this).val();}).get()));
        formData.append('type_shift', JSON.stringify($("input[name='type_shift[]']").map(function(){return $(this).is(':checked') ? "1" : "0";}).get()));
        formData.append('morning_shift_count', JSON.stringify($("input[name='morning_shift_count[]']").map(function(){return $(this).val();}).get()));
        formData.append('afternoon_shift_count', JSON.stringify($("input[name='afternoon_shift_count[]']").map(function(){return $(this).val();}).get()));
        formData.append('night_shift_count', JSON.stringify($("input[name='night_shift_count[]']").map(function(){return $(this).val();}).get()));
        formData.append('shift_count', JSON.stringify($("input[name='shift_count[]']").map(function(){return $(this).val();}).get()));
        $.ajax({
            type: "POST",
            url: shiftSetting_prg,
            data: formData,
            async: false,
            success: function(data) {
                let result = JSON.parse(data);
                if(result.status){
                    alert("บันทึกข้อมูลสำเร็จ");
                }else{
                    alert("บันทึกข้อมูลไม่สำเร็จ");
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
        return false;
    })

    $("#addSettingList").click(function(){
        let ele = $("select[name='position_id[]']"),
            lastEle = ele[ele.length - 1];
        $(lastEle).prop("disabled",true);

        if(ele.length == positionList.length ){
            $(this).hide();
        }else{
            $(this).show();
        }
        createSettingList(false);
    });

    $("#department_id").change(function(){
        let val = $(this).val();
        $("#addSettingList").prop('disabled', val != "" ? false : true);
        createSettingList();
    })
    shiftSettinginit();
});

async function shiftSettinginit(){
    await getPositionList();
    await createSettingList();
}

async function getPositionList(){
    $("#showSettingList").empty();
    await $.post(shiftSetting_prg,{act : "getPositionList"},function(data){
        positionList = JSON.parse(data);
    });
}

async function createSettingList(isClear = true){
    
    if(isClear) {
        $("#showSettingList").empty();
        $.post(shiftSetting_prg,{act : "getSettingShift"},function(data){
            let result = JSON.parse(data);
            if(result.length > 0){
                $.each(result,function(i,item){
                    let str = `<div class="row d-flex justify-content-center rowSettingList">
                                <div class="col-1">
                                    <label for=""></label>
                                    <div class="input-group">
                                        <button type="button" onclick="removeSettingList(this)" class="btn btn-danger mt-1"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <label for="">ตำแหน่ง</label>
                                    <select name="position_id[]" class="form-control" required style="font-size: 14px;" ${ i < (result.length - 1)  ? "disabled" : ""}>
                        `;
                    let checkUse = $("select[name='position_id[]']").map(function(){return $(this).val();}).get();
                    $.each(positionList,function(i_x, item_x){
                        if($.inArray(item_x.positCode,checkUse) == -1){
                            str += `<option value='${item_x.positCode}' ${item.position_code == item_x.positCode ? "selected" : ""} >${item_x.positName}</option>`;
                        }
                    });
                    str +=    `     </select>
                                </div>
                                <div class="col-2">
                                    <label for="">&nbsp;</label>
                                    <div class="input-group d-flex pl-2 align-items-center" style="background: #e9ecef;height: 4vh;border-radius: 5px;border: 1px solid #ced4da;">
                                        <label class="w-100 m-0">
                                            <input type="checkbox" onchange='shiftTypeInput(this)' value='1' name='type_shift[]' ${item.is_shift == "1" ? "checked" : ""}> แบบกะ
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row" ${item.is_shift == "1" ? "" : 'style="display:none;"'}>
                                        <div class="col-4">
                                            <label for="">จำนวนกะเช้า</label>
                                            <input type="number" class="form-control" name="morning_shift_count[]" ${item.is_shift == "1" ? "required min='1' value='"+item.moring_shift_count+"'" : ""}>
                                        </div>
                                        <div class="col-4">
                                            <label for="">จำนวนกะบ่าย</label>
                                            <input type="number" class="form-control" name="afternoon_shift_count[]" ${item.is_shift == "1" ? "required min='1' value='"+item.afternoon_shift_count+"'" : ""}>
                                        </div>
                                        <div class="col-4">
                                            <label for="">จำนวนกะดึก</label>
                                            <input type="number" class="form-control" name="night_shift_count[]" ${item.is_shift == "1" ? "required min='1' value='"+item.night_shift_count+"'" : ""}>
                                        </div>
                                    </div>
                                    <div class="row" ${item.is_shift != "1" ? "" : 'style="display:none;"'}>
                                        <div class="col-4">
                                            <label for="">จำนวนเข้าเวร</label>
                                            <input type="number" class="form-control" min='1' name="shift_count[]" ${item.is_shift != "1" ? "required min='1' value='"+item.moring_shift_count+"'" : ""}>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `;
                    $("#showSettingList").append(str);
                });
                if(result.length == positionList.length ){
                    $("#addSettingList").hide();
                }else{
                    $("#addSettingList").show();
                }
            }
        });
        
    }else{

        let str = `<div class="row d-flex justify-content-center rowSettingList">
                    <div class="col-1">
                        <label for=""></label>
                        <div class="input-group">
                            <button type="button" onclick="removeSettingList(this)" class="btn btn-danger mt-1"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    <div class="col-2">
                        <label for="">ตำแหน่ง</label>
                        <select name="position_id[]" class="form-control" required style="font-size: 14px;">
            `;
        let checkUse = $("select[name='position_id[]']").map(function(){return $(this).val();}).get();
        $.each(positionList,function(i, item){
            if($.inArray(item.positCode,checkUse) == -1){
                str += `<option value='${item.positCode}'>${item.positName}</option>`;
            }
        })
        str +=    `     </select>
                    </div>
                    <div class="col-2">
                        <label for="">&nbsp;</label>
                        <div class="input-group d-flex pl-2 align-items-center" style="background: #e9ecef;height: 4vh;border-radius: 5px;border: 1px solid #ced4da;">
                            <label class="w-100 m-0">
                                <input type="checkbox" onchange='shiftTypeInput(this)' value='1' name='type_shift[]'> แบบกะ
                            </label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row" style="display:none;">
                            <div class="col-4">
                                <label for="">จำนวนกะเช้า</label>
                                <input type="number" class="form-control" name="morning_shift_count[]">
                            </div>
                            <div class="col-4">
                                <label for="">จำนวนกะบ่าย</label>
                                <input type="number" class="form-control" name="afternoon_shift_count[]">
                            </div>
                            <div class="col-4">
                                <label for="">จำนวนกะดึก</label>
                                <input type="number" class="form-control" name="night_shift_count[]">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <label for="">จำนวนเข้าเวร</label>
                                <input type="number" class="form-control" min='1' name="shift_count[]" required>
                            </div>
                        </div>
                    </div>
                </div>
                `;
        $("#showSettingList").append(str);
    }
   
}

async function removeSettingList(ele){
    let $ele = $(ele).parent().parent().parent(),
        $parentEle = $(ele).parent().parent().parent().parent();
    await $($ele).remove();
    let $childrenEle = $parentEle.children(),
        $lastEle = $childrenEle[$childrenEle.length - 1];
   
    let str,$findEle = $($lastEle).find("select[name='position_id[]']")[0],
        checkUse = $("select[name='position_id[]']").map(function(){return $(this).val();}).get();
    $.each(positionList,function(i_x, item_x){
        if($.inArray(item_x.positCode,checkUse) == -1 || item_x.positCode == $($findEle).val()){
            str += `<option value='${item_x.positCode}' ${item_x.positCode == $($findEle).val() ? "selected" : ""} >${item_x.positName}</option>`;
        }
    });
    $($findEle).empty().append(str);
    $($findEle).prop("disabled",false);
    if($childrenEle.length == positionList.length ){
        $("#addSettingList").hide();
    }else{
        $("#addSettingList").show();
    }
}

function shiftTypeInput(ele){
    let parentEle = $(ele).parent().parent().parent().parent(),
        childrenEle = $(parentEle).children();
        childrenEle = $(childrenEle[3]).children();
        let eleChange1 = childrenEle[0],eleChange2 = childrenEle[1];
    if($(ele).is(":checked")){
        $($(eleChange1).find('input[name="morning_shift_count[]"]')[0]).prop('required',true);
        $($(eleChange1).find('input[name="afternoon_shift_count[]"]')[0]).prop('required',true);
        $($(eleChange1).find('input[name="night_shift_count[]"]')[0]).prop('required',true);
        $($(eleChange1).find('input[name="morning_shift_count[]"]')[0]).attr('min','1');
        $($(eleChange1).find('input[name="afternoon_shift_count[]"]')[0]).attr('min','1');
        $($(eleChange1).find('input[name="night_shift_count[]"]')[0]).attr('min','1');
        $($(eleChange2).find('input[name="shift_count[]"]')[0]).prop('required',false);
        $($(eleChange2).find('input[name="shift_count[]"]')[0]).removeAttr('min');
        $(eleChange2).hide();
        $(eleChange1).show();
    }else{
        $($(eleChange1).find('input[name="morning_shift_count[]"]')[0]).prop('required',false);
        $($(eleChange1).find('input[name="afternoon_shift_count[]"]')[0]).prop('required',false);
        $($(eleChange1).find('input[name="night_shift_count[]"]')[0]).prop('required',false);
        $($(eleChange1).find('input[name="morning_shift_count[]"]')[0]).removeAttr('min');
        $($(eleChange1).find('input[name="afternoon_shift_count[]"]')[0]).removeAttr('min');
        $($(eleChange1).find('input[name="night_shift_count[]"]')[0]).removeAttr('min');
        $($(eleChange2).find('input[name="shift_count[]"]')[0]).prop('required',true);
        $($(eleChange2).find('input[name="shift_count[]"]')[0]).attr('min','1');
        $(eleChange1).hide();
        $(eleChange2).show();
    }
}
var specialShift_prg = "php/specialShiftManagement.php",
    specialShift_act = "add",
    specialShift = [];

$(function(){
    specialShiftinit();

    $("form#specialShiftForm").submit(function(){
        let formData = new FormData(this);
        formData.append("act", specialShift_act);
        $.ajax({
            type: "POST",
            url: specialShift_prg,
            data: formData,
            async: false,
            success: function(data) {
                let respone = JSON.parse(data);
                if(respone.status){
                    specialShiftClearAll();
                    specialShiftDisplay();
                    alert("success");
                }else{
                    alert("Fail");
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
        return false;
    });

    $("#add_button").click(function(){
        specialShiftDefualt();
    });
});

function specialShiftinit(){
    
    specialShiftDefualt();
    specialShiftDisplay();
}

function specialShiftDefualt(){
    $("#special_personal").focus();
    let d = new Date().toLocaleDateString('fr-CA');
    $("#special_date").val(d);
    $("#special_time").val("06:00");

    specialShiftPersonalList();
}

function specialShiftPersonalList(){
    $("#special_personal").empty();
    $.post("php/personal.php",{
        act : "read"
    },function(data){
        let respone = JSON.parse(data);
        $("#special_personal").append(`<option value="]">- เลือก เจ้าหน้าที่/บุคลากร -</option>`);
        $.each(respone, function(i, item){
            $("#special_personal").append(`<option value="${item.Personal_ID}">${item.prefixName+" "+item.Fname+" "+item.Lname}</option>`);
        });
    });
}

function specialShiftDisplay(){
    $.post(specialShift_prg,{
        act : "read"
    },function(data){
        specialShift = JSON.parse(data);
        let str = `<table class = 'table table-hover' id='specialShiftTable'>
                        <thead class="table-info">
                            <tr>
                                <th>ลำดับ</th>
                                <th>เจ้าหน้าที่/บุคลากร</th>
                                <th>วันที่ปฏิบัติงาน</th>
                                <th>เวลาปฏิบัติงาน</th>
                                <th>ชื่อผู้ป่วย</th>
                                <th>ค่าตอบแทน</th>
                                <th>รายละเอียดงาน</th>
                                <th>ดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>`;
            $.each(specialShift,function(i,item){
                    str += `<tr>
                                <td>${i+1}</td>
                                <td>${item.prefixName+" "+item.Fname+" "+item.Lname}</td>
                                <td>${item.special_date}</td>
                                <td>${item.special_time}</td>
                                <td>${item.special_patient}</td>
                                <td>${item.special_payment}</td>
                                <td>${item.special_detail}</td>
                                <td class="text-center">
                                    <button type="button" class="btn bg-warning" onclick="specialShiftEdit(${item.id})"> <i class="fa fa-edit"></i> </button> &nbsp;
                                    <button type="button" class="btn bg-danger" onclick="specialShiftDel(${item.id})"> <i class="fas fa-trash-alt text-white"> </i> </button>
                                </td>
                            </tr>`;
            });
            str +=      `</tbody>
                    </table>`;
        $("#showSpecialShiftList").html(str);
        $('#specialShiftTable').DataTable({
            columnDefs: [
                { orderable: false, targets: [-1] },

            ],
            deferRender: true,
            scrollY: 1000,
            scrollCollapse: true,
            scroller: true,
            pageLength: 20,
            lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],
            searching: true,
            "oLanguage": {
                sEmptyTable: "ไม่มีข้อมูลในตาราง",
                sInfo: "แสดง _START_ ถึง _END_ จาก _TOTAL_ แถว",
                sInfoEmpty: "แสดง 0 ถึง 0 จาก 0 แถว",
                sInfoFiltered: "(กรองข้อมูล _MAX_ ทุกแถว)",
                sInfoPostFix: "",
                sInfoThousands: ",",
                sLengthMenu: "แสดง _MENU_ แถว",
                sLoadingRecords: "กำลังโหลดข้อมูล...",
                sProcessing: "กำลังดำเนินการ...",
                sSearch: "ค้นหา: ",
                sZeroRecords: "ไม่พบข้อมูล",
                oPaginate: {
                    sFirst: "หน้าแรก",
                    sPrevious: "ก่อนหน้า",
                    sNext: "ถัดไป",
                    sLast: "หน้าสุดท้าย"
                },
                oAria: {
                    sSortAscending: ": เปิดใช้งานการเรียงข้อมูลจากน้อยไปมาก",
                    sSortDescending: ": เปิดใช้งานการเรียงข้อมูลจากมากไปน้อย"
                }

            }
        });

    });
}

function specialShiftEdit(id){
    specialShift_act = "edit";
    let item_find = specialShift.find(item => item.id == id);
    $("#special_edit_id").val(item_find.id);
    $("#special_personal").val(item_find.Personal_ID);
    $("#special_date").val(new Date(item_find.special_date).toLocaleDateString('fr-CA'));
    $("#special_time").val(item_find.special_time);
    $("#special_patient").val(item_find.special_patient);
    $("#special_payment").val(item_find.special_payment);
    $("#special_detail").val(item_find.special_detail);
}

function specialShiftDel(id){
    if(confirm("ต้องการ ยกเลิก รายการปฏิบัติงานพิเศษนี้หรือไม่?")){
        $.post(specialShift_prg,{
            act : "del",
            shift_del_id : id
        },function(data){
            let respone = JSON.parse(data);
            if(respone.status){
                specialShiftClearAll();
                specialShiftDisplay();
            }
        })
    }
}

function specialShiftClearAll(){
    specialShift_act = 'add';
    specialShiftDefualt();
    $("#special_patient").val('');
    $("#special_payment").val('0');
    $("#special_detail").val('');
}
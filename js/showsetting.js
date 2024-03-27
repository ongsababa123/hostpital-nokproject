var act = "add";
var per = [];
var prg = 'php/shiftSetting.php';
$(function() {
    display('read');

    $("form#prefixform").submit(function() {
        var formData = new FormData(this);
        formData.append("act", act);
        $.ajax({
            type: "POST",
            url: prg,
            data: formData,
            async: false,
            success: function(data) {
                let result = JSON.parse(data);
                if (result.status) {
                    alert(result.msg);
                    clear();
                    display('read');
                } else {
                    alert(result.msg);
                }
                // alert("success");
                // 

            },
            cache: false,
            contentType: false,
            processData: false
        });

        return false;
    })

})

function display(act) {
    $.post(prg, { "act": act }, function(data, status) {
        row = "<div class = 'card'>"
            // row += "<div class = 'card-header '> <button type='button' class='btn btn-success' style='float: right;' onclick = add()><i class = 'fas fa-plus'></i>&nbsp;&nbsp;เพิ่ม</button>"
        row += "<div class = 'card-header '> "
        row += "</div>"
        row += "<div class = 'card-body'>"
        row += "<table class = 'table table-hover' id='perTable'>"
        row += "<thead class = 'table-info'>"
        row += "<tr>"
        row += "<th >รหัส</th>"
        row += "<th>ตำแหน่งงาน</th>"
        row += "<th>เช้า</th>"
        row += "<th>บ่าย</th>"
        row += "<th>ดึก</th>"
        row += "<th>ดำเนินการ</th>"
        row += "</tr>"
        row += "</thead>"
        row += "<tbody>"
        per = JSON.parse(data);
        $.each(per, function(index, obj, i) {
            row += "<tr id= 'ss'>"
            row += "<td>" + obj.position_code + "</td>"
            row += "<td>" + obj.positName + "</td>"
            row += "<td>" + obj.accessName + "</td>"
            row += "<td>" + obj.accessName + "</td>"
            row += "<td>" + obj.accessName + "</td>"
            row += "<td><button id = 'btnde1' type='button'  data-toggle='modal' data-target='#myModal' class='btn btn-primary 'onclick = detail('" + obj.Personal_ID + "')><i class = 'fas fa-eye ' ></i></button> &nbsp;&nbsp;"
                // row += "<button id = 'btnde2'   type='button' class='btn btn-warning'onclick = edit('" + index + "')><i class = 'fa fa-edit'></i></button> &nbsp;&nbsp;"
                // row += "<button id = 'btnde3'  type ='button' class ='btn btn-danger' onclick = del('" + obj.Personal_ID + "')><i class = 'fas fa-trash-alt'> </i></button ></td>"
            row += "</tr>"
        });
        row += "</tbody>"
        row += "</table>"
        row += "</div>"
        row += "</div>"

        $("#showsetting").html(row);

        $('#perTable').DataTable({
            columnDefs: [
                { orderable: false, targets: [-1] },

            ],
            deferRender: true,
            scrollY: 1000,
            scrollCollapse: true,
            scroller: true,
            pageLength: 5,
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
        })

    });

}

// function edit(i) {
//     act = "update";
//     $("#Personal_ID").val(per[i].Personal_ID).prop("readonly", true);
//     $("#prefixCode").val(per[i].prefixCode);
//     $("#Fname").val(per[i].Fname);
//     $("#Lname").val(per[i].Lname);
//     $("#positCode").val(per[i].positCode);
//     $("#accessCode").val(per[i].accessCode);
//     $("#departCode").val(per[i].departCode);
//     $("#statusCode").val(per[i].is_active);
//     $("#Fname").focus();
//     $("#Password").prop("required", false);
//     $("#Password2").prop("required", false);
// }

// function clear() {
//     act = "create";
//     $("#Personal_ID").prop("readonly", false);
//     $("#Personal_ID").val("");
//     $("#prefixCode").val("");
//     $("#Fname").val("");
//     $("#Lname").val("");
//     $("#Password").val("");
//     $("#Password2").val("");
//     $("#accessCode").val("");
//     $("#positCode").val("");
//     $("#departCode").val("");
//     $("#Password").prop("required", true);
//     $("#Password2").prop("required", true);
// }

// function del(Personal_ID) {
//     if (confirm('ต้องการลบ ? ')) {
//         $.post(prg, { Personal_ID, act: 'delete' }, function(data, status) {
//             alert(data)
//             display('read');
//         })
//     }

// }

// function add() {
//     act = "create";
//     $("#Personal_ID").prop("readonly", false);
//     $("#Personal_ID").focus();
//     clear();
// }

function detail(Personal_ID) {
    let prg = "php/Personal.php"
    $.post(prg, { Personal_ID: Personal_ID, act: 'detail' }, function(data) {
        per = JSON.parse(data)
        for (i = 0; i < per.length; i++) {
            row = "<table class =' table table-bordered'>"
            row += "<tr>"
            row += "<th>รหัสบุคลากร : </th>"
            row += "<td>" + per[i].Personal_ID + "</td>"
            row += "</tr>"
            row += "<tr>"
            row += "<th>ตำแหน่ง :</th>"
            row += "<td>" + per[i].positName + "</td>"
            row += "</tr>"
            row += "<tr>"
            row += "<th>งาน : </th>"
            row += "<td>" + per[i].departName + "</td>"
            row += "</tr>"
            row += "<tr>"
            row += "<th>สิทธิ์ : </th>"
            row += "<td>" + per[i].accessName + "</td>"
            row += "</tr>"

            row += "</table>"
        };

        $("#showview").html(row);
    })
}
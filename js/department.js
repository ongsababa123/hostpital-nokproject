var act = "create";
var prefix = [];
var prg = "php/department.php"
$(function() {
    personal()
    display('read');
    $("#departCode").focus();

    $("form#departform").submit(function() {
        var formData = new FormData(this);
        formData.append("act", act);
        $.ajax({
            type: "POST",
            url: prg,
            data: formData,
            async: false,
            success: function(data) {
                console.log(data);
                alert("success");
                // $("#showdata1").html(data)
                display('read');
            },
            cache: false,
            contentType: false,
            processData: false
        });
        clear();
        return false;
    })
})

function personal() {
    let prg = "php/department.php";
    $.post(prg, { act: "personal" }, function(data, status) {
        row = JSON.parse(data);
        $.each(row, function(index, obj) {
            $("#Personal_ID").append("<option value='" + obj.Personal_ID + "'>" + obj.Fname + '&nbsp;&nbsp;' + obj.Lname + "</option>");
        })
    })
}

function display(act) {
    $.post(prg, { "act": act }, function(data, status) {
        row = "<div class = 'card'>"
        row += "<div class = 'card-header alert-light text-white'style ='background-image: linear-gradient(rgb(76, 122, 238), rgb(28, 114, 243));'>รายละเอียดข้อมูล <button type='button' class='btn btn-success' style='float: right;' onclick = add()><i class = 'fas fa-plus'></i>&nbsp;&nbsp;เพิ่ม</button>"
        row += "</div>"
        row += "<div class = 'card-body'><br>"
        row += "<table class = 'table table-hover' id='departTable'>"
        row += "<thead class = 'table-info'>"
        row += "<tr>"
        row += "<th>รหัสแผนก/หน่วยงาน</th>"
        row += "<th>ชื่อแผนก/หน่วยงาน</th>"
        row += "<th>หัวหน้างาน</th>"
        row += "<th>ดำเนินการ</th>"
        row += "</tr>"
        row += "</thead>"
        row += "<tbody>"
        depart = JSON.parse(data);
        $.each(depart, function(index, obj) {
            row += "<tr>"
            row += "<td>" + obj.departCode + "</td>"
            row += "<td>" + obj.departName + "</td>"
            row += "<td>" + obj.prefixName + '' + obj.Fname + '&nbsp&nbsp' + obj.Lname + "</td>"
            row += "<td><button type='button' class='btn btn-warning'onclick = edit('" + index + "')><i class = 'fa fa-edit'></i></button> &nbsp;&nbsp;"
            row += "<button type ='button' class ='btn btn-danger' onclick = del('" + obj.departCode + "')><i class = 'fas fa-trash-alt'> </i></button ></td>"
            row += "</tr>"
        });
        row += "</tbody>"
        row += "</table>"
        row += "</div>"
        row += "</div>"
        $("#showdepart").html(row);
        $('#departTable').DataTable({
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
        });

    });
}

function edit(i) {
    act = "update";
    $("#departCode").val(depart[i].departCode);
    $("#departName").val(depart[i].departName);
    $("#Personal_ID").val(depart[i].Personal_ID);
    $("#departCode").prop("readonly", true);

    $("#departName").focus();
    $("#departName").prop("readonly", false);
    $("#Personal_ID").prop("disabled", false);
    $("#btnSave").prop("disabled", false);
}



function clear() {
    act = "create";
    $("#departCode").val("");
    $("#departName").val("");
    $("#Personal_ID").val("");
    $("#departCode").prop("readonly", true);
    $("#departName").prop("readonly", true);
    $("#Personal_ID").prop("disabled", true);
    $("#btnSave").prop("disabled", true);

}

function del(departCode) {
    if (confirm('ต้องการลบ ? ')) {
        $.post(prg, { departCode, act: 'delete' }, function(data, status) {
            display('read');
            alert(data)
        })
    }

}

function add() {
    act = "create";
    $("#departCode").val("");
    $("#departName").val("");
    $("#Personal_ID").val("");
    $("#departCode").focus();
    $("#departCode").prop("readonly", false);
    $("#departName").prop("readonly", false);
    $("#Personal_ID").prop("disabled", false);
    $("#btnSave").prop("disabled", false);
}
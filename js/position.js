var act = "create";
var prefix = [];
var prg = "php/position.php"
$(function() {
    display('read');
    $("#positCode").focus();

    $("form#positform").submit(function() {
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

function display(act) {
    $.post(prg, { "act": act }, function(data, status) {
        row = "<div class = 'card'>"
        row += "<div class = 'card-header alert-light text-white'style ='background-image: linear-gradient(rgb(76, 122, 238), rgb(28, 114, 243));'>รายละเอียดข้อมูล <button type='button' class='btn btn-success' style='float: right;' onclick = add()><i class = 'fas fa-plus'></i>&nbsp;&nbsp;เพิ่ม</button>"
        row += "</div>"
        row += "<div class = 'card-body'><br>"
        row += "<table class = 'table table-hover' id='positTable'>"
        row += "<thead class = 'table-info'>"
        row += "<tr>"
        row += "<th>รหัสตำแหน่ง</th>"
        row += "<th>ชื่อตำแหน่ง</th>"
        row += "<th>ดำเนินการ</th>"
        row += "</tr>"
        row += "</thead>"
        row += "<tbody>"
        posit = JSON.parse(data);
        $.each(posit, function(index, obj) {
            row += "<tr>"
            row += "<td>" + obj.positCode + "</td>"
            row += "<td>" + obj.positName + "</td>"
            row += "<td><button type='button' class='btn btn-warning'onclick = edit('" + index + "')><i class = 'fa fa-edit'></i></button> &nbsp;&nbsp;"
            row += "<button type ='button' class ='btn btn-danger' onclick = del('" + obj.positCode + "')><i class = 'fas fa-trash-alt'> </i></button ></td>"
            row += "</tr>"
        });
        row += "</tbody>"
        row += "</table>"
        row += "</div>"
        row += "</div>"
        $("#showposit").html(row);
        $('#positTable').DataTable({
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
    $("#positCode").val(posit[i].positCode);
    $("#positName").val(posit[i].positName);
    $("#positCode").prop("readonly", true);
    $("#positName").prop("readonly", false);
    $("#btnSave").prop("disabled", false);
    $("#positName").focus();
}

function clear() {
    act = 'create'
    $("#positCode").val("");
    $("#positName").val("");
    $("#positCode").prop("readonly", true);
    $("#positName").prop("readonly", true);
    $("#btnSave").prop("disabled", true);
}

function del(positCode) {
    if (confirm('ต้องการลบ ? ')) {
        $.post(prg, { positCode, act: 'delete' }, function(data, status) {
            display('read');
            alert(data)
        })
    }

}

function add() {
    act = 'create'
    $("#positCode").val("");
    $("#positName").val("");
    $("#positCode").focus();
    $("#positCode").prop("readonly", false);
    $("#positName").prop("readonly", false);
    $("#btnSave").prop("disabled", false);
}
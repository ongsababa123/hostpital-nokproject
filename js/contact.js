var act = "create";
var pre = [];
var prg = "php/contact.php"
$(function() {
    // display('read');

    $("form#contactform").submit(function() {
        var formData = new FormData(this);
        formData.append("act", act);
        $.ajax({
            type: "POST",
            url: prg,
            data: formData,
            async: false,
            success: function(data) {
                console.log(data);
                // alert("success");
                // $("#showdata1").html(data)
                // display('read');
            },
            cache: false,
            contentType: false,
            processData: false
        });
        clear();
        return false;
    })
})

// function display(act) {
//     $.post(prg, { "act": act }, function(data, status) {
//         row = "<div class = 'card'>"
//         row += "<div class = 'card-header alert-light text-white'style ='background-image: linear-gradient(rgb(76, 122, 238), rgb(28, 114, 243));'>รายละเอียดข้อมูล <button type='button' class='btn btn-success' style='float: right;' onclick = add()><i class = 'fas fa-plus'></i>&nbsp;&nbsp;เพิ่ม</button>"
//         row += "</div>"
//         row += "<div class = 'card-body'><br>"
//         row += "<table class = 'table table-hover' id='accessTable'>"
//         row += "<thead class = 'table-info'>"
//         row += "<tr>"
//         row += "<th>รหัสสิทธิ์ใช้งาน</th>"
//         row += "<th>ชื่อสิทธิ์ใช้งาน</th>"
//         row += "<th>ดำเนินการ</th>"
//         row += "</tr>"
//         row += "</thead>"
//         row += "<tbody>"
//         access = JSON.parse(data);
//         $.each(access, function(index, obj) {
//             row += "<tr>"
//             row += "<td>" + obj.accessCode + "</td>"
//             row += "<td>" + obj.accessName + "</td>"
//             row += "<td><button type='button' class='btn btn-warning'onclick = edit('" + index + "')><i class = 'fa fa-edit'></i></button> &nbsp;&nbsp;"
//             row += "<button type ='button' class ='btn btn-danger' onclick = del('" + obj.accessCode + "')><i class = 'fas fa-trash-alt'> </i></button ></td>"
//             row += "</tr>"
//         });
//         row += "</tbody>"
//         row += "</table>"
//         row += "</div>"
//         row += "</div>"
//         $("#showaccess").html(row);
//         $('#accessTable').DataTable({
//             columnDefs: [
//                 { orderable: false, targets: [-1] },

//             ],
//             deferRender: true,
//             scrollY: 1000,
//             scrollCollapse: true,
//             scroller: true,
//             pageLength: 5,
//             lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],
//             searching: true,
//             "oLanguage": {
//                 sEmptyTable: "ไม่มีข้อมูลในตาราง",
//                 sInfo: "แสดง _START_ ถึง _END_ จาก _TOTAL_ แถว",
//                 sInfoEmpty: "แสดง 0 ถึง 0 จาก 0 แถว",
//                 sInfoFiltered: "(กรองข้อมูล _MAX_ ทุกแถว)",
//                 sInfoPostFix: "",
//                 sInfoThousands: ",",
//                 sLengthMenu: "แสดง _MENU_ แถว",
//                 sLoadingRecords: "กำลังโหลดข้อมูล...",
//                 sProcessing: "กำลังดำเนินการ...",
//                 sSearch: "ค้นหา: ",
//                 sZeroRecords: "ไม่พบข้อมูล",
//                 oPaginate: {
//                     sFirst: "หน้าแรก",
//                     sPrevious: "ก่อนหน้า",
//                     sNext: "ถัดไป",
//                     sLast: "หน้าสุดท้าย"
//                 },
//                 oAria: {
//                     sSortAscending: ": เปิดใช้งานการเรียงข้อมูลจากน้อยไปมาก",
//                     sSortDescending: ": เปิดใช้งานการเรียงข้อมูลจากมากไปน้อย"
//                 }

//             }
//         });

//     });
// }

function clear() {
    act = "create";
    $("#name").val("");
    $("#email").val("");
    $("#subject").val("");
    $("#message").val("");
    // $("#subject").prop("readonly", true);
    // $("#message").prop("readonly", true);
    // $("#name").prop("readonly", true);
    // $("#email").prop("readonly", true);
    // $("#btnSave").prop("disabled", true);

}
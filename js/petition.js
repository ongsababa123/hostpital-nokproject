var prg = 'php/petition.php';
var hostpital = callCookies('hostpital');
if (!hostpital) {
    if (location.pathname != "/index.html" && location.href != "/") {
        let pathname = window.location.pathname.replace("home.html", '');
        location.href = window.location.origin + pathname + "index.html";
    }
}
$(function() {
    $("#petitionform").submit(function() {
        var formData = new FormData(this);
        formData.append("act", "create");
        formData.append("Personal_ID", Base64.decode(hostpital.Personal_ID));
        $.ajax({
            type: "POST",
            url: prg,
            data: formData,
            async: false,
            success: function(data) {
                let result = JSON.parse(data);
                if (result.status) {
                    swal({
                        icon: 'success',
                        text: result.msg,
                        allowOutsideClick: true,
                        showConfirmButton: true,
                        confirmButtonText: 'ตกลง'
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    swal({
                        icon: 'error',
                        text: result.msg,
                        allowOutsideClick: true,
                        showConfirmButton: true,
                        confirmButtonText: 'ตกลง'
                    });
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
});

function display_petition() {
    $.post(prg, { "act": "read", "Personal_ID": Base64.decode(hostpital.Personal_ID) }, function(data) {
        let row = JSON.parse(data);
        // เรียงลำดับข้อมูลตาม date_upload
        $("#list_petition").html('');
        row.sort((a, b) => new Date(b.date_upload) - new Date(a.date_upload));
        if (row.length > 0) {
            row.forEach(element => {
                var statusBadge;
                if (element.status == 0) {
                    statusBadge = `<span class="badge bg-warning text-dark">รออนุมัติ</span>`;
                } else if (element.status == 1) {
                    statusBadge = `<span class="badge bg-success">อนุมัติ</span>`;
                } else {
                    statusBadge = `<span class="badge bg-danger">ไม่อนุมัติ</span>`;
                }

                var approveButton = '';
                if (element.status == 0) {
                    approveButton = `
                        <div class="col-1">
                            <button class="btn btn-success" onclick="change_status(${element.id_petitions}, 1, 'ต้องการอนุมัติคำร้องนี้ใช่หรือไม่')">อนุมัติ</button>
                        </div>
                        <div class="col-1">
                            <button class="btn btn-danger" onclick="change_status(${element.id_petitions}, 2, 'ต้องการไม่อนุมัติคำร้องนี้ใช่หรือไม่')">ไม่อนุมัติ</button>
                        </div>`;
                } else {
                    approveButton = `<div class="col-2"></div>`
                }

                var list_petition = `
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mt-4">
                                    <div class="col-2 pt-1">
                                        <h6><strong>รหัสคำร้อง:</strong> ${element.id_petitions}</h6>
                                    </div>
                                    <div class="col-3 pt-1">
                                        <h6><strong>วันที่ส่งคำร้อง:</strong> ${element.date_upload}</h6>
                                    </div>
                                    <div class="col-3 pt-1">
                                        <h6><strong>สถานะคำร้อง: </strong> ${statusBadge}</h6>
                                    </div>
                                    ${approveButton}
                                    <div class="col-1">
                                        <a class="btn btn-primary" href="read_pdf.php?id=${element.id_petitions}" target="_blank">ดูรายละเอียด</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                $("#list_petition").append(list_petition);
            });
        }
    });
}

function display_petition_approve() {
    $.post(prg, { "act": "read_approve", "Personal_ID": Base64.decode(hostpital.Personal_ID) }, function(data) {
        let row = JSON.parse(data);
        // เรียงลำดับข้อมูลตาม date_upload
        $("#list_petition").html('');
        row.sort((a, b) => new Date(b.date_upload) - new Date(a.date_upload));
        if (row.length > 0) {
            row.forEach(element => {
                var list_petition = `
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mt-4">
                                    <div class="col-2 pt-1">
                                        <h6><strong>รหัสคำร้อง:</strong> ${element.id_petitions}</h6>
                                    </div>
                                    <div class="col-3 pt-1">
                                        <h6><strong>วันที่ส่งคำร้อง:</strong> ${element.date_upload}</h6>
                                    </div>
                                    <div class="col-3 pt-1">
                                        <h6><strong>สถานะคำร้อง: </strong><span class="badge bg-success">อนุมัติ</span></h6>
                                    </div>
                                    <div class="col-2"></div>
                                    <div class="col-1">
                                        <a class="btn btn-primary" href="read_pdf.php?id=${element.id_petitions}" target="_blank">ดูรายละเอียด</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                $("#list_petition").append(list_petition);
            });
        }
    });
}

function change_status(id, status, text) {
    swal({
        title: text,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
        buttons: ['ยกเลิก', 'ตกลง'],
    }).then((willApprove) => {
        if (willApprove) {
            $.post(prg, { "act": "change_status", "id_petitions": id, "status": status }, function(data) {
                display_petition();
                let result = JSON.parse(data);
                if (result.status) {
                    swal({
                        icon: 'success',
                        text: result.msg,
                        allowOutsideClick: true,
                        showConfirmButton: true,
                        confirmButtonText: 'ตกลง'
                    });
                } else {
                    swal({
                        icon: 'error',
                        text: result.msg,
                        allowOutsideClick: true,
                        showConfirmButton: true,
                        confirmButtonText: 'ตกลง'
                    });
                }
            })
        }
    });
}
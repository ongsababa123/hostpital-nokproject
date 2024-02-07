var prg = 'php/petition.php';
var hostpital = callCookies('hostpital');
if (!hostpital) {
    if (location.pathname != "/index.html" && location.href != "/") {
        let pathname = window.location.pathname.replace("home.html", '');
        location.href = window.location.origin + pathname + "index.html";
    }
}
$(function () {


    $("#petitionform").submit(function () {
        var formData = new FormData(this);
        formData.append("act", "create");
        formData.append("Personal_ID", Base64.decode(hostpital.Personal_ID));
        $.ajax({
            type: "POST",
            url: prg,
            data: formData,
            async: false,
            success: function (data) {
                let result = JSON.parse(data);
                console.log(result);
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


})
function display_petition() {
    $.post(prg, { "act": "read", "Personal_ID": Base64.decode(hostpital.Personal_ID) }, function (data) {
        let row = JSON.parse(data);
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

                var list_petition = `
                    <div class="col-xl-10">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mt-4">
                                    <div class="col-2 pt-1">
                                        <h6><strong>รหัสคำร้อง:</strong> ${element.id_petitions}</h6>
                                    </div>
                                    <div class="col-3 pt-1">
                                        <h6><strong>วันที่ส่งคำร้อง:</strong> ${element.date_upload}</h6>
                                    </div>
                                    <div class="col-5 pt-1">
                                        <h6>
                                            <strong>สถานะคำร้อง: </strong> ${statusBadge}
                                        </h6>
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-primary" onclick="view_detail(${element.id_petitions})">ดูรายละเอียด</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                console.log(element);
                $("#list_petition").append(list_petition);
            });

        }
    });

}

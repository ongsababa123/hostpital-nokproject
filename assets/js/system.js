var Base64 = {
    encode: function (s) {
        return btoa(unescape(encodeURIComponent(s)));
    },
    decode: function (s) {
        return decodeURIComponent(escape(atob(s)));
    }
}
$(function () {
    let hostpital = callCookies('hostpital');
    if (!hostpital) {
        if (location.pathname != "/index.html" && location.href != "/") {
            let pathname = window.location.pathname.replace("home.html", '');
            location.href = window.location.origin + pathname + "index.html";
        }
        return false;
    } else {
        switch (hostpital.accessCode) {
            case "61":
                {
                    $("#time").remove();
                    $("#data1").remove();
                    $("#data3").remove();
                    $("#show1").remove();
                    $("#show4").remove();
                    $("#shiftSetting").remove();
                    $("#shiftManagement").remove();
                    break;
                }
            case "63":
                {
                    $("#time").remove();
                    $("#data1").remove();
                    $("#show1").remove();
                    $("#show4").remove();
                    $("#show2").remove();
                    $("#pet_follow").remove();
                    $("#shiftSetting").remove();
                    $("#shiftManagement").remove();
                    break;
                }
            case "62":
                {
                    $("#time").remove();
                    $("#shiftSetting").remove();
                    $("#data1").remove();
                    $("#pet_approve").remove();
                    $("#pet").remove();
                    $("#show1").remove();
                    $("#show2").remove();
                    break;
                }

            default:
                break;
        }
    }
    $("#usersshow,#user1").html(Base64.decode(hostpital.Fname) + "&nbsp;&nbsp;" + Base64.decode(hostpital.Lname));
    // $("#access1").html(Base64.decode(hostpital.accessCode));

    $("#logoButton").click(function () {
        let hostpital = callCookies('hostpital');
        if (hostpital) {

            if (location.pathname != "/home.html") {
                location.reload();
            }
        } else {
            if (location.pathname != "/index.html" && location.href != "/") {
                location.href = "index.html";
            }
        }

    });

    $("#logout_button").click(function () {
        deleteCookies()
    });

    $('#time').click(function () {
        $('#detail').load('html/personal.html');
    });
    $('#prefix').click(function () {
        $('#detail').load('html/prefix.html');
    });
    $('#position').click(function () {
        $('#detail').load('html/position.html');
    });
    $('#cotton').click(function () {
        $('#detail').load('html/cotton.html');
    });
    $('#department').click(function () {
        $('#detail').load('html/department.html');
    });
    $('#status').click(function () {
        $('#detail').load('html/status.html');
    });
    $('#access').click(function () {
        $('#detail').load('html/Access_rights.html');
    });
    $('#user_pro').click(function () {
        $('#detail').load('html/profile.html');
    });
    $('#pet').click(function () {
        $('#detail').load('html/petition.html');
    });
    $('#pet_follow').click(function () {
        $('#detail').load('html/petition_follow.html');
    });
    $('#pet_approve').click(function () {
        $('#detail').load('html/petition_approve.html');
    });
    $('#contact').click(function () {
        $('#detail').load('html/contact.html');
    });
    $('#shiftSetting').click(function () {
        $('#detail').load('html/shiftSetting.html');
    });
    $('#shiftManagement').click(function () {
        $('#detail').load('html/shiftManagement.html');
    });
    $('#shiftSchedule').click(function () {
        $('#detail').load('html/shiftSchedule.html');
    });
    $('#specialShiftManagement').click(function () {
        $('#detail').load('html/specialShiftManagement.html');
    });
    $('#compensation').click(function () {
        $('#detail').load('html/compensation.html');
    });
    $.post("php/shiftManageAuto.php", function (data) {

    });
});

function deleteCookies() {
    let confirmAction = confirm("ต้องการออกจากระบบใช่หรือไม่");
    if (confirmAction) {
        $.jCookies({ erase: 'hostpital' });
        let pathname = window.location.pathname.replace("home.html", '');
        location.href = window.location.origin + pathname + "index.html";
        // alert("ออกจากระบบสำเร็จ");
    }
}

function callCookies(cName) {
    let cookies = $.jCookies({ get: cName });
    return cookies;
}
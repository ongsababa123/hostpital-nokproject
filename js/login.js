var act = "login";
var prg = "php/login.php";
var Base64 = {
    encode: function(s) {
        return btoa(unescape(encodeURIComponent(s)));
    },
    decode: function(s) {
        return decodeURIComponent(escape(atob(s)));
    }
}
$(function() {
    let hostpital = callCookies('hostpital');
    if (hostpital) {
        
        if(location.pathname != "/home.html"){
            location.href = window.location.origin+window.location.pathname+"home.html"; 
        }
    }
    $("form#loginForm").submit(function() {
        var formData = new FormData(this);
        formData.append("act", act);
        $.ajax({
            type: "POST",
            url: prg,
            data: formData,
            async: false,
            success: function(data) {
                let result = JSON.parse(data);
                if (result[0].status) {
                    result[1].Personal_ID = Base64.encode(result[1].Personal_ID);
                    result[1].Fname = Base64.encode(result[1].Fname);
                    result[1].Lname = Base64.encode(result[1].Lname);
                    let setCookies = $.jCookies({
                        name: 'hostpital',
                        value: result[1]
                    })
                    if (setCookies) location.replace("home.html");
                } else {
                    setTimeout(function() {
                        swal({
                            icon: 'error',
                            title: 'กรุณาเข้าสู่ระบบอีกครั้ง',
                            text: 'ชื่อผู้ใช้งาน หรือ รหัสผ่านไม่ถูกต้อง!'
                        })
                    }, 400)
                }
                // alert(msg);
            },
            cache: false,
            contentType: false,
            processData: false
        });
        return false;
    })

})

function callCookies(cName) {
    let cookies =  $.jCookies({ get: cName });
    return cookies;
}

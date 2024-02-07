var currentMonth = 0,
    monthArray = ["มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิการยน","ธันวาคม"],
    shiftSchedule_prg = "php/shiftSchedule.php",
    currentDateStay;
$(function(){

    shiftScheduleinit();

    $("#before_button").click(function(){
        currentMonth--;
        calendarList();
    });
    $("#next_button").click(function(){
        currentMonth++;
        calendarList();
    });
});

async function shiftScheduleinit(){
    await calendarList();
}

async function calendarList(){
    $("#DayShow").empty();
    let find_date = new Date();
    find_date.setMonth(find_date.getMonth() + currentMonth);
    let currentMonthDate = daysInMonth(find_date.getMonth(), find_date.getYear()),
        today = new Date();
        dayOneOfMonth = find_date.getFullYear()+"-"+(((find_date.getMonth() + 1).toString()).length > 1 ? ((find_date.getMonth() + 1).toString() ): "0"+(find_date.getMonth() + 1).toString())+"-01";
        dayOneOfMonth = new Date(dayOneOfMonth),
        grid = dayOneOfMonth.getDay() == 0 ? 7 : dayOneOfMonth.getDay();

        currentDateStay = dayOneOfMonth;

        $("#montShow").html(monthArray[dayOneOfMonth.getMonth()]);
        $("#yearShow").html(dayOneOfMonth.getFullYear());
        for(i = 1; i <= currentMonthDate; i++){
            let str = "";
            if(
                today.getDate() == i &&
                today.getMonth() == dayOneOfMonth.getMonth() &&
                today.getFullYear() == dayOneOfMonth.getFullYear()
            ) str = "class='today'";
            else str = '';
            await $("#DayShow").append(`<button ${str} ><time><div>${i}</div> <div id="detailDay${i}"></div></time></button>`);
        }
        $("#DayShow button:first-child").css("grid-column",grid);
    await shiftScheduleList();
}

async function shiftScheduleList(){
    $.post(shiftSchedule_prg,{
        act : "scheduleList",
        month : currentDateStay.getMonth() + 1,
        year : currentDateStay.getFullYear()
    },function(data){
        let respone = JSON.parse(data);
        $.each(respone, function(i,item){
            let date = (item.shift_date).toString().split('-');
            let str = `<div class="bg-success px-4 py-1 mt-1 text-white rounded">ปฏิบัติงาน</div>`;
                switch (item.shift_type) {
                    case 'M':{
                        str += `<div class="mt-1 px-4 py-1 text-white rounded bg-primary">เช้า</div>`;
                        break;
                    }
                    case 'A':{
                        str += `<div class="mt-1 px-4 py-1 text-white rounded bg-warning">เช้า</div>`;
                        break;
                    }
                    case 'N':{
                        str += `<div class="mt-1 px-4 py-1 text-white rounded bg-danger">เช้า</div>`;
                        break;
                    }
                    default :{
                        str += `<div class="mt-1 px-4 py-1 text-white rounded bg-success">เต็มวัน</div>`;
                        break;
                    }
                }
            $("#detailDay"+date[2]).html(str);
        });
    });
}

function daysInMonth (month, year) {
    return new Date(parseInt(year), parseInt(month) + 1, 0).getDate();
}
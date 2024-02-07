<?php
// เชื่อมต่อกับฐานข้อมูล
require_once('../dbConnect/connect.php');
$act = isset($_POST['act']) ? $_POST['act'] : "";
$Personal_ID = isset($_POST['Personal_ID']) ? $_POST['Personal_ID'] : "";

if ($act == "create") {
    // รับข้อมูลจากฟอร์มแบบ POST
    $subject = $_POST['subject'];
    $name = $_POST['name'];
    $position = $_POST['position'];
    $duty = $_POST['duty'];
    $day = $_POST['day'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $request = $_POST['request'];
    $substitute = $_POST['substitute'];
    $substitute_day = $_POST['substitute_day'];
    $substitute_month = $_POST['substitute_month'];
    $substitute_year = $_POST['substitute_year'];
    $reason = $_POST['reason'];
    $return_day = $_POST['return_day'];
    $return_month = $_POST['return_month'];
    $return_year = $_POST['return_year'];

    // คำสั่ง SQL เพื่อเพิ่มข้อมูลลงในฐานข้อมูล
    $sql = "INSERT INTO petitions (Personal_ID, subject, name, position, duty, day, month, year, request, substitute, substitute_day, substitute_month, substitute_year, reason, return_day, return_month, return_year, status, date_upload)
        VALUES ('$Personal_ID', '$subject', '$name', '$position', '$duty', '$day', '$month', '$year', '$request', '$substitute', '$substitute_day', '$substitute_month', '$substitute_year', '$reason', '$return_day', '$return_month', '$return_year' ,'0', NOW())";

    // ทำการ execute คำสั่ง SQL
    if ($conn->query($sql) === TRUE) {
        $msg["status"] = true;
        $msg["msg"] = "บันทึกข้อมูลสําเร็จ";
        $msg["Personal_ID"] = $Personal_ID;
    } else {
        $msg["status"] = false;
        $msg["msg"] = "บันทึกข้อมูลไม่สําเร็จ";
    }
    echo json_encode($msg);
}

if ($act == "read") {
    // คำสั่ง SQL เพื่อดึงข้อมูล
    $sql = "SELECT * FROM petitions WHERE Personal_ID = '$Personal_ID'";

    // ทำการ execute คำสั่ง SQL
    $result = $conn->query($sql);
    $data["Personal_ID"] = $Personal_ID;

    // ตรวจสอบว่ามีข้อมูลหรือไม่
    if ($result->num_rows > 0) {
        // สร้างตัวแปร array เพื่อเก็บข้อมูล
        $data = array();

        // Loop รับข้อมูลและเก็บลงในตัวแปร array
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo json_encode(false);
    }
}

?>
<?php
$msg = "('status':'fail')";
require_once('../dbConnect/connect.php');

$act = isset($_GET['act']) ? $_GET['act'] : "";

if ($act == 'get_dataTable_personal') {
    $limit = isset($_GET['length']) ? intval($_GET['length']) : 10;
    $start = isset($_GET['start']) ? intval($_GET['start']) : 0;

    $position = isset($_GET['position']) ? $_GET['position'] : "";
    $department = isset($_GET['department']) ? $_GET['department'] : "";
    $shift = isset($_GET['shift']) ? $_GET['shift'] : "";
    $search = isset($_GET['search']) ? $_GET['search'] : "";
    $searchValue = $search['value'];

    // ดึงข้อมูล แผนกทั้งหมด
    $sql_department = "SELECT * FROM department";
    $result_department = $conn->query($sql_department);
    // ดึงข้อมูล ตำแหน่งทั้งหมด
    $sql_position = "SELECT * FROM position";
    $result_position = $conn->query($sql_position);

    // จำนวนเร็คคอร์ดทั้งหมด
    $sql_total = "SELECT COUNT(*) AS total_count FROM personal";

    $whereClause_count = [];
    if ($searchValue !== '') {
        $whereClause_count[] = "CONCAT(Fname, Lname) LIKE '%$searchValue%'";
    }

    if ($position !== 'all') {
        foreach ($result_position as $value) {
            if ($value['positName'] === $position) {
                $position = $value['positCode'];
            }
        }
        $whereClause_count[] = "positCode = '$position'";
    }

    if ($department !== 'all') {
        foreach ($result_department as $value) {
            if ($value['departName'] === $department) {
                $department = $value['departCode'];
            }
        }
        $whereClause_count[] = "departCode = '$department'";
    }

    if (!empty($whereClause_count)) {
        $sql_total .= " WHERE " . implode(" AND ", $whereClause_count);
    }

    $result_total = $conn->query($sql_total);
    $row_total = $result_total->fetch_assoc();
    $total_count = $row_total['total_count'];

    // ดึงข้อมูลจากตาราง personal
    $sql_personal = "SELECT * FROM personal";

    $whereClause = [];

    if ($searchValue !== '') {
        $whereClause[] = "CONCAT(Fname, Lname) LIKE '%$searchValue%'";
    }

    if ($position !== 'all') {
        $whereClause[] = "positCode = '$position'";
    }

    if ($department !== 'all') {
        $whereClause[] = "departCode = '$department'";
    }

    if (!empty($whereClause)) {
        $sql_personal .= " WHERE " . implode(" AND ", $whereClause);
    }

    $sql_personal .= " LIMIT $start, $limit";

    $result_personal = $conn->query($sql_personal);

    // ดึงข้อมูลคำนำหน้า ทั้งหมด
    $sql_prefix = "SELECT * FROM prefix";
    $result_prefix = $conn->query($sql_prefix);

    // ดึงข้อมูลคำเวลาทำงานทั้งหมดตามวันที่ shift
    $date = explode('-', $shift);
    $year = $date[0];
    $month = $date[1];

    // Construct the SQL query to match the year and month
    $sql_shift_schedule = "SELECT * FROM shift_schedule  WHERE YEAR(shift_date) = '$year' AND MONTH(shift_date) = '$month'";
    $result_shift = $conn->query($sql_shift_schedule);

    $data = array();
    while ($row = $result_personal->fetch_assoc()) {
        $data_shift = array();
        // แมพ prefixCode เป็น prefixName
        foreach ($result_prefix as $prefix_row) {
            if ($row['prefixCode'] == $prefix_row['prefixCode']) {
                $row['prefixCode'] = $prefix_row['prefixName'];
                break;
            }
        }
        // แมพ departCode เป็น departName
        foreach ($result_department as $department_row) {
            if ($row['departCode'] == $department_row['departCode']) {
                $row['departCode'] = $department_row['departName'];
                break;
            }
        }
        // แมพ positCode เป็น positName
        foreach ($result_position as $position_row) {
            if ($row['positCode'] == $position_row['positCode']) {
                if ($position_row['positName'] === "แพทย์") {
                    $row['salary'] = 1100;
                } else if ($position_row['positName'] === "พยาบาล") {
                    $row['salary'] = 280;
                } else if ($position_row['positName'] === "ผู้ช่วยพยาบาล") {
                    $row['salary'] = 170;
                }
                $row['positCode'] = $position_row['positName'];
                break;
            }
        }

        foreach ($result_shift as $key => $value) {
            if ($row['Personal_ID'] == $value['Personal_ID']) {
                $data_shift[] = $value;
            }
        }
        $row['data_shift'] = $data_shift;
        $data[] = $row;
    }

    // ตอบกลับในรูปแบบ JSON สำหรับ DataTable
    $response = array(
        "draw" => isset($_GET['draw']) ? intval($_GET['draw']) : 1,
        "limit" => $limit,
        "recordsTotal" => $total_count,
        "recordsFiltered" => $total_count,
        "data" => $data,
        "searchValue" => $searchValue
    );

    echo json_encode($response);
}

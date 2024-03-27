<?php
$department = isset($_GET['department']) ? htmlspecialchars($_GET['department']) : '';
$position = isset($_GET['position']) ? htmlspecialchars($_GET['position']) : '';
$shift = isset($_GET['month_year']) ? htmlspecialchars($_GET['month_year']) : '';
$searchValue = isset($_GET['searching']) ? htmlspecialchars($_GET['searching']) : '';
require_once ('dbConnect/connect.php');

// ดึงข้อมูล แผนกทั้งหมด
$sql_department = "SELECT * FROM department";
$result_department = $conn->query($sql_department);
foreach ($result_department as $value) {
    if ($value['departName'] === $department) {
        $department = $value['departCode'];
    }
}
// ดึงข้อมูล ตำแหน่งทั้งหมด
$sql_position = "SELECT * FROM position";
$result_position = $conn->query($sql_position);
foreach ($result_position as $value) {
    if ($value['positName'] === $position) {
        $position = $value['positCode'];
    }
}
// Prepare and execute the SQL query
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

require_once __DIR__ . '/vendor/autoload.php';

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new \Mpdf\Mpdf([
    'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/tmp',
    ]),
    'default_font_size' => 13,
    'fontdata' => $fontData + [ // lowercase letters only in font key
        'sarabun' => [
            'R' => 'THSarabunNew.ttf',
            'I' => 'THSarabunNew Italic.ttf',
            'B' => 'THSarabunNew Bold.ttf',
            'BI' => 'THSarabunNew BoldItalic.ttf',
        ]
    ],
    'default_font' => 'sarabun'
]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@200;300;400&display=swap" rel="stylesheet">
</head>
<style>
    .body {
        font-family: 'Sarabun', sans-serif;
    }
</style>
<?php
// Function to generate dots based on data length
function generateDots($text, $desiredLength)
{
    // Calculate the number of dots needed
    $dotsNeeded = $desiredLength - mb_strlen($text, 'UTF-8');

    // Generate the dots
    $dots = str_repeat('.', $dotsNeeded);

    return $dots;
}

$html = '<table id="example1" style="width: 100%;">
            <thead>
            <tr>
            <th style="width: 20px;background-color: #658ABF;color: floralwhite; vertical-align: middle; border: 1px solid #000;" rowspan="2">ลำดับ</th>
            <th style="width: 170px;background-color: #658ABF;color: floralwhite; vertical-align: middle; border: 1px solid #000;" rowspan="2">ยศ - ชื่อ</th>
            <th style="width: 100px;background-color: #658ABF;color: floralwhite; vertical-align: middle; border: 1px solid #000;" rowspan="2" id="th_2" name="th_2">แผนก</th>
            <th style="width: 100px;background-color: #658ABF;color: floralwhite; vertical-align: middle; border: 1px solid #000;" rowspan="2" id="th_3" name="th_3">ตำแหน่ง</th>
            <th style="width: 100px;background-color: #658ABF;color: floralwhite; vertical-align: middle; border: 1px solid #000;" rowspan="2">วันปฎิบัติราชการ</th>
            <th style="width: 100px; background-color: #658ABF;color: floralwhite; font-weight: 500; border: 1px solid #000;" colspan="3">วันปฎิบัติราชการ</th>
        </tr>
        <tr>
            <th style="background-color: #E2EEFF; font-weight: 400; border: 1px solid #000;">จำนวนวัน</th>
            <th style="background-color: #E2EEFF; font-weight: 400; border: 1px solid #000;">อัตราวันละ</th>
            <th style="background-color: #E2EEFF; font-weight: 400; border: 1px solid #000;">รวม</th>
        </tr>
            </thead>
            <tbody style="border: 1px solid;">
            </tbody>';
foreach ($data as $key => $value) {
    $day_work = '';
    $count_day = 0;
    if ($value['data_shift'] !== []) {
        foreach ($value['data_shift'] as $k => $v) {
            $date_split = explode('-', $v['shift_date']);
            $day_work .= $date_split[2] . ', ';
            $count_day += 1;
        }
    } else {
        $day_work = '<div style="color: red">ไม่มีการปฎิบัติราชการ </div>';
    }
    $html .= '<tr>
                    <td style="text-align: center;border: 1px solid #000;">' . $key + 1 . '</td>
                    <td style="text-align: left;border: 1px solid #000;">' . $value['prefixCode'] . ' ' . $value['Fname'] . ' ' . $value['Lname'] . '</td>
                    <td style="text-align: center;border: 1px solid #000;">' . $value['departCode'] . '</td>
                    <td style="text-align: center;border: 1px solid #000;">' . $value['positCode'] . '</td>
                    <td style="text-align: center;border: 1px solid #000;">' . $day_work . '</td>
                    <td style="text-align: center;border: 1px solid #000;">' . $count_day . '</td>
                    <td style="text-align: center;border: 1px solid #000;">' . number_format($value['salary']) . '</td>
                    <td style="text-align: center;border: 1px solid #000;">' . number_format($value['salary'] * $count_day) . '</td>
                    ';
}

$html .= '</table>';

$mpdf->WriteHTML($html);
$mpdf->Output();
?>

<script>
    var data = <?php echo json_encode($data); ?>;
    console.log(data);
</script>
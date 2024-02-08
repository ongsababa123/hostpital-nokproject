<?php
$id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; // Sanitize input
require_once('dbConnect/connect.php');

// Prepare and execute the SQL query
$sql = "SELECT * FROM petitions WHERE id_petitions = '$id'";
$result = $conn->query($sql);

// Check for errors during query execution
if (!$result) {
    die("Error executing the query: " . $conn->error);
}

// Fetch the result as an associative array
$row = $result->fetch_assoc();

// Close the database connection
$conn->close();

// Check if a row was found
if (!$row) {
    die("No petition found with ID: $id");
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
    'default_font_size' => 18,
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
function generateDots($text, $desiredLength) {
    // Calculate the number of dots needed
    $dotsNeeded = $desiredLength - mb_strlen($text, 'UTF-8');

    // Generate the dots
    $dots = str_repeat('.', $dotsNeeded);

    return $dots;
}

$html = '
    <table border="0" style="width: 100%;">
        <tr>
            <td style="text-align: right;">รพ.ค่ายจักรพงษ์</td>
        </tr>
        <tr>
            <td>เรื่อง ขออนุมัติแลกเปลี่ยนการปฏิบัติหน้าที่เวร</td>
        </tr>
        <tr>
            <td>เรียน ผอ.รพ.ค่ายจักรพงษ์ (ผ่าน...' . $row['subject'] .'...)</td>
        </tr>
        <tr>
            <td style="padding-left: 100px;">กระผม/ดิฉัน.....' . $row['name'] . generateDots($row['name'], 50) . 'ตำแหน่ง...' . $row['position'] . generateDots($row['position'], 50) . '</td>
        </tr>
        <tr>
            <td style="padding-left: 0px;">ได้ปฏิบัติหน้าที่.....' . $row['duty'] . generateDots($row['duty'], 50) . 'ในวันที่.....' . $row['day'] . '.....เดือน.....' . $row['month'] . '.....พ.ศ.....' . $row['year'] . '.....</td>
        </tr>
        <tr>
            <td style="padding-left: 0px;">มีความประสงค์ขออนุมัติแลกเปลี่ยนการปฏิบัติหน้าที่.....' . $row['request'] . generateDots($row['request'], 50) . '</td>
        </tr>
        <tr>
            <td style="padding-left: 0px;">โดยให้.....' . $row['substitute'] . generateDots($row['substitute'], 30) . 'ปฏิบัติหน้าที่นายทหารเวร แทน ในวันที....' . $row['substitute_day'] . '.....เดือน.....' . $row['substitute_month'] . '.....</td>
        </tr>
        <tr>
            <td style="padding-left: 0px;">พ.ศ.....' . $row['substitute_year'] . '.....เนื่องจาก.....' . $row['reason'] . generateDots($row['reason'], 65) . '</td>
        </tr>
        <tr>
            <td>และกระผมจะกลับมาปฏิบัติหน้าที่นายทหารเวร แทน ในวันที่.....' . $row['return_day'] . '.....เดือน.....' . $row['return_month'] . '.....พ.ศ.....' . $row['return_year'] . '.....</td>
        </tr>
        <tr>
            <td style="padding-left: 100px;">จึงเรียนมาเพื่อกรุณาพิจารณา</td>
        </tr>
    </table>
    <br>
    <table border="0" style="width: 100%;">
        <tr>
            <td style="text-align: right;">(ลงชื่อ).........................................................................</td>
        </tr>
        <tr>
            <td style="text-align: right;">(...................................................................)</td>
        </tr>
        <tr>
            <td style="text-align: right;">.............................................................................</td>
        </tr>
    </table>
    <br>
    <table border="0" style="width: 100%;">
        <tr>
            <td>...........................................................................ผู้ขออนุมัติแลกเปลี่ยนเวร ฯ</td>
        </tr>
        <tr>
            <td style="padding-left: 30px;">(...................................................................)</td>
        </tr>
    </table>
    <br>
    <table border="0" style="width: 100%;">
        <tr>
            <td>...........................................................................ผู้ปฏิบัติหน้าที่นายทหารเวร แทน</td>
        </tr>
        <tr>
            <td style="padding-left: 30px;">(...................................................................)</td>
        </tr>
    </table>
    <br>
    <table border="0" style="width: 100%;">
        <tr>
            <td style="padding-left: 100px;">- อนุมัติ</td>
        </tr>
        <tr>
            <td style="padding-left: 150px;">...................................................................</td>
        </tr>
        <tr>
            <td style="padding-left: 150px;">(...................................................................)</td>
        </tr>
        <tr>
            <td style="padding-left: 50px;">(หน.กอง/แผนก)..................................................................</td>
        </tr>
    </table>
';

$mpdf->WriteHTML($html);
$mpdf->Output();
?>


<script src="js/petition.js"></script>
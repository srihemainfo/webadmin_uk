<?php


include '../../include/shi-config.php';    
include '../../include/functions.php'; 

// error_reporting(E_ALL);
// ini_set('display_errors', 1);
header('Content-Type: application/json');

// Enable emoji and Unicode support
mysqli_set_charset($con, 'utf8mb4');

$method = $_POST['method'] ?? '';

if ($method == "WhatsApp_report") {
    $result = [];

    $query = "SELECT *
              FROM whatsapp_bulk_message ORDER BY id DESC";

    $res = mysqli_query($con, $query);

    while ($row = mysqli_fetch_assoc($res)) {
        $result[] = $row;
    }

    echo json_encode($result);
    exit;
}

if ($method == "upload_sheet") {
    require_once '../../xlsx/Classes/PHPExcel.php';

    if (isset($_FILES['sheetupload_file']) && $_FILES['sheetupload_file']['error'] == 0) {
        $file = $_FILES['sheetupload_file']['tmp_name'];
        $fileName = $_FILES['sheetupload_file']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validate Excel file type
        if (!in_array($fileExtension, ['xls', 'xlsx'])) {
            echo json_encode(['status' => 'error', 'message' => 'Only Excel files (.xls, .xlsx) are allowed.']);
            exit;
        }

        // Get POST fields (null if empty)
        $whatsapp_temp    = $_POST['whatsapp_temp'] ?? '';
        $btn1_text        = $_POST['visit_textbox'] ?? '';
        $btn1_url         = $_POST['visit_textbox_url'] ?? '';
        $btn2_text        = $_POST['textbox_url'] ?? '';
        $btn2_url         = $_POST['textbox_1'] ?? '';
        $whatapp_campain  = $_POST['whatapp_campain'] ?? '';

        // Convert image to base64 if uploaded
        $images_base64 = '';
        if (isset($_FILES['image_upload']) && $_FILES['image_upload']['error'] === 0) {
            $imageTmpPath = $_FILES['image_upload']['tmp_name'];
            $mimeType = mime_content_type($imageTmpPath);
            $imageBase64 = base64_encode(file_get_contents($imageTmpPath));
            $images_base64 = 'data:' . $mimeType . ';base64,' . $imageBase64;
        }

        try {
            $excelReader = PHPExcel_IOFactory::createReaderForFile($file);
            $excelObj = $excelReader->load($file);
            $sheet = $excelObj->getActiveSheet();
            $rows = $sheet->toArray();

            // Limit to 10,000 data rows
            if (count($rows) - 1 > 20000) {
                echo json_encode(['status' => 'error', 'message' => 'Maximum 20,000 rows allowed.']);
                exit;
            }

            $inserted = 0;
            $skipped = 0;
            $duplicates = 0;
            $uniqueMobiles = []; // track already seen numbers

            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // Skip header row

                $name = mysqli_real_escape_string($con, trim($row[0] ?? ''));
                $rawMobile = trim($row[1] ?? '');
                $mobile = preg_replace('/[^0-9]/', '', $rawMobile);

                // Format mobile number
                if (strlen($mobile) === 10) {
                    $mobile = '91' . $mobile;
                } elseif (!(strlen($mobile) === 12 && substr($mobile, 0, 2) === '91')) {
                    $skipped++;
                    continue;
                }

                // Skip if already processed in current Excel
                if (in_array($mobile, $uniqueMobiles)) {
                    $duplicates++;
                    continue;
                }

                $uniqueMobiles[] = $mobile;

                $status = 'Pending';
                $insertQuery = "INSERT INTO whatsapp_bulk_message 
                    (name, to_whatsapp, status, details, btn1_text, btn1_url, btn2_text, btn2_url, whatapp_campain, images) 
                    VALUES (
                        '" . mysqli_real_escape_string($con, $name) . "',
                        '$mobile',
                        '$status',
                        '" . mysqli_real_escape_string($con, $whatsapp_temp) . "',
                        " . ($btn1_text ? "'" . mysqli_real_escape_string($con, $btn1_text) . "'" : "NULL") . ",
                        " . ($btn1_url ? "'" . mysqli_real_escape_string($con, $btn1_url) . "'" : "NULL") . ",
                        " . ($btn2_text ? "'" . mysqli_real_escape_string($con, $btn2_text) . "'" : "NULL") . ",
                        " . ($btn2_url ? "'" . mysqli_real_escape_string($con, $btn2_url) . "'" : "NULL") . ",
                        '" . mysqli_real_escape_string($con, $whatapp_campain) . "',
                        " . ($images_base64 ? "'" . mysqli_real_escape_string($con, $images_base64) . "'" : "NULL") . "
                    )";

                mysqli_query($con, $insertQuery);
                $inserted++;
            }


            echo json_encode([
                'status' => 'success',
                'message' => "Import completed: $inserted inserted, $duplicates duplicates, $skipped invalid."
            ]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to read Excel file.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No valid file uploaded.']);
    }
}





?>


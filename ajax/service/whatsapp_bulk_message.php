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

if ($method == "fetch_wa_temp") {
    $result = [];

    $query = "SELECT id, name
              FROM wamail_templates ORDER BY id DESC";

    $res = mysqli_query($con, $query);

    while ($row = mysqli_fetch_assoc($res)) {
        $result[] = $row;
    }

    echo json_encode($result);
    exit;
}

if ($method == "fetch_temp_details") {
    $result = [];

    $query = "SELECT id, name, m_type, var_count, body
              FROM wamail_templates WHERE id = '$_POST[id]' ORDER BY id DESC";

    $res = mysqli_query($con, $query);

    while ($row = mysqli_fetch_assoc($res)) {
        $result[] = $row;
    }
    
    $data = [
        'result' => $result[0],
        'status' => 1,
        'message' => 'Template details received'
    ];

    echo json_encode($data);
    exit;
}

// if ($method == "upload_sheet") {
//     require_once '../../xlsx/Classes/PHPExcel.php';

//     if (isset($_FILES['sheetupload_file']) && $_FILES['sheetupload_file']['error'] == 0) {
//         $file = $_FILES['sheetupload_file']['tmp_name'];
//         $fileName = $_FILES['sheetupload_file']['name'];
//         $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

//         // Validate Excel file type
//         if (!in_array($fileExtension, ['xls', 'xlsx'])) {
//             echo json_encode(['status' => 'error', 'message' => 'Only Excel files (.xls, .xlsx) are allowed.']);
//             exit;
//         }

//         // Get POST fields (null if empty)
//         $whatsapp_temp    = $_POST['whatsapp_temp'] ?? '';
//         $btn1_text        = $_POST['visit_textbox'] ?? '';
//         $btn1_url         = $_POST['visit_textbox_url'] ?? '';
//         $btn2_text        = $_POST['textbox_url'] ?? '';
//         $btn2_url         = $_POST['textbox_1'] ?? '';
//         $whatapp_campain  = $_POST['whatapp_campain'] ?? '';

//         // Convert image to base64 if uploaded
//         $images_base64 = '';
//         if (isset($_FILES['image_upload']) && $_FILES['image_upload']['error'] === 0) {
//             $imageTmpPath = $_FILES['image_upload']['tmp_name'];
//             $mimeType = mime_content_type($imageTmpPath);
//             $imageBase64 = base64_encode(file_get_contents($imageTmpPath));
//             $images_base64 = 'data:' . $mimeType . ';base64,' . $imageBase64;
//         }

//         try {
//             $excelReader = PHPExcel_IOFactory::createReaderForFile($file);
//             $excelObj = $excelReader->load($file);
//             $sheet = $excelObj->getActiveSheet();
//             $rows = $sheet->toArray();

//             // Limit to 10,000 data rows
//             if (count($rows) - 1 > 20000) {
//                 echo json_encode(['status' => 'error', 'message' => 'Maximum 20,000 rows allowed.']);
//                 exit;
//             }

//             $inserted = 0;
//             $skipped = 0;
//             $duplicates = 0;
//             $uniqueMobiles = []; // track already seen numbers

//             foreach ($rows as $index => $row) {
//                 if ($index === 0) continue; // Skip header row

//                 $name = mysqli_real_escape_string($con, trim($row[0] ?? ''));
//                 $rawMobile = trim($row[1] ?? '');
//                 $mobile = preg_replace('/[^0-9]/', '', $rawMobile);

//                 // Format mobile number
//                 if (strlen($mobile) === 10) {
//                     $mobile = '91' . $mobile;
//                 } elseif (!(strlen($mobile) === 12 && substr($mobile, 0, 2) === '91')) {
//                     $skipped++;
//                     continue;
//                 }

//                 // Skip if already processed in current Excel
//                 if (in_array($mobile, $uniqueMobiles)) {
//                     $duplicates++;
//                     continue;
//                 }

//                 $uniqueMobiles[] = $mobile;

//                 $status = 'Pending';
//                 $insertQuery = "INSERT INTO whatsapp_bulk_message 
//                     (name, to_whatsapp, status, details, btn1_text, btn1_url, btn2_text, btn2_url, whatapp_campain, images) 
//                     VALUES (
//                         '" . mysqli_real_escape_string($con, $name) . "',
//                         '$mobile',
//                         '$status',
//                         '" . mysqli_real_escape_string($con, $whatsapp_temp) . "',
//                         " . ($btn1_text ? "'" . mysqli_real_escape_string($con, $btn1_text) . "'" : "NULL") . ",
//                         " . ($btn1_url ? "'" . mysqli_real_escape_string($con, $btn1_url) . "'" : "NULL") . ",
//                         " . ($btn2_text ? "'" . mysqli_real_escape_string($con, $btn2_text) . "'" : "NULL") . ",
//                         " . ($btn2_url ? "'" . mysqli_real_escape_string($con, $btn2_url) . "'" : "NULL") . ",
//                         '" . mysqli_real_escape_string($con, $whatapp_campain) . "',
//                         " . ($images_base64 ? "'" . mysqli_real_escape_string($con, $images_base64) . "'" : "NULL") . "
//                     )";

//                 mysqli_query($con, $insertQuery);
//                 $inserted++;
//             }


//             echo json_encode([
//                 'status' => 'success',
//                 'message' => "Import completed: $inserted inserted, $duplicates duplicates, $skipped invalid."
//             ]);
//         } catch (Exception $e) {
//             echo json_encode(['status' => 'error', 'message' => 'Failed to read Excel file.']);
//         }
//     } else {
//         echo json_encode(['status' => 'error', 'message' => 'No valid file uploaded.']);
//     }
// }


if ($method == "upload_sheet") {
    // NOTE: This file uses functions like mysqli_real_escape_string and PHPExcel classes
    // which require proper setup (database connection $con, class library) not included here.
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

        // --- Retrieve New POST Fields ---
        $whatsapp_temp     = $_POST['whatsapp_temp'] ?? '';
        $whatapp_campain   = $_POST['whatapp_campain'] ?? '';
        $message_mode      = $_POST['message_mode'] ?? 'manual';
        $dynamic_vars_json = !empty($_POST['dynamic_vars_json'])
            ? json_decode($_POST['dynamic_vars_json'], true)
            : [];
        
        // var_dump($dynamic_vars_json);die;
        
        // Old, currently unused fields (from original backend expectation)
        $btn1_text         = $_POST['visit_textbox'] ?? ''; 
        $btn1_url          = $_POST['visit_textbox_url'] ?? ''; 
        $btn2_text         = $_POST['textbox_url'] ?? ''; 
        $btn2_url          = $_POST['textbox_1'] ?? ''; 

        // Convert image to base64 if uploaded (kept existing logic)
        $images_base64 = '';
        if (isset($_FILES['image_upload']) && $_FILES['image_upload']['error'] === 0) {
            $imageTmpPath = $_FILES['image_upload']['tmp_name'];
            $mimeType = mime_content_type($imageTmpPath);
            $imageBase64 = base64_encode(file_get_contents($imageTmpPath));
            $images_base64 = 'data:' . $mimeType . ';base64,' . $imageBase64;
        }

        // --- TEMPLATE PROCESSING (Outside Loop: Substitute Static/Form-Provided Variables) ---
        $whatsapp_temp_for_loop = $whatsapp_temp; // Default for Manual Mode is the raw message
        
        // var_dump($message_mode);die;

        // if ($message_mode == 'template') {
        //     // NOTE: In a real system, you should retrieve the actual template body from your database/API 
        //     // using the $whatsapp_temp (template ID). For demonstration, we assume $whatsapp_temp 
        //     // is the body or a body is retrieved here.
        //     $templateBody = $whatsapp_temp; // TEMPORARY ASSUMPTION: $whatsapp_temp IS the template body
            
        //     $processedTemplateBody = $templateBody;
        //     $dynamicVars = json_decode($dynamic_vars_json, true);
        //     $excelMarker = '[EXCEL_VAR_'; // Marker used in frontend for Excel-mapped vars

        //     // 1. Substitute Static/Form-Provided Variables
        //     // Identify all {{VARX}} placeholders in the template
        //     preg_match_all('/\{\{VAR(\d+)\}\}/', $processedTemplateBody, $matches);
            
        //     if (!empty($matches[0])) {
        //         foreach ($matches[1] as $varIndex) {
        //             $placeholder = "{{VAR{$varIndex}}}";
        //             $formInputKey = "var_value_{$varIndex}";
                    
        //             if (isset($dynamicVars[$formInputKey])) {
        //                 $formValue = $dynamicVars[$formInputKey];

        //                 // Substitute ONLY if the value is NOT an Excel marker
        //                 if (strpos($formValue, $excelMarker) === false) { 
        //                     // This is a static, form-provided value
        //                     $processedTemplateBody = str_replace($placeholder, $formValue, $processedTemplateBody);
        //                 }
        //             } else {
        //                 // Handle missing form data if needed
        //                 $processedTemplateBody = str_replace($placeholder, '[MISSING_STATIC_VAR]', $processedTemplateBody);
        //             }
        //         }
        //     }
        //     // The template now only contains Excel-bound placeholders ({{VAR1}} and {{VAR2}}) or substituted static text.
        //     $whatsapp_temp_for_loop = $processedTemplateBody;
        // }

        try {
            $excelReader = PHPExcel_IOFactory::createReaderForFile($file);
            $excelObj = $excelReader->load($file);
            $sheet = $excelObj->getActiveSheet();
            $rows = $sheet->toArray();

            // Limit to 20,000 data rows (kept existing logic)
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

                // Ensure columns exist and retrieve data
                $name = trim($row[0] ?? '');
                $rawMobile = trim($row[1] ?? '');
                $reason = trim($row[2] ?? ''); // Third column value for {{VAR2}}

                $mobile = preg_replace('/[^0-9]/', '', $rawMobile);

                // Format mobile number (kept existing logic)
                if (strlen($mobile) == 10) {
                    $mobile = '91' . $mobile;
                } elseif (!(strlen($mobile) == 12 && substr($mobile, 0, 2) === '91')) {
                    $skipped++;
                    continue;
                }

                // Skip if already processed in current Excel (kept existing logic)
                if (in_array($mobile, $uniqueMobiles)) {
                    $duplicates++;
                    continue;
                }
                $uniqueMobiles[] = $mobile;
                
                // --- 2. Per-Row Message Construction (Dynamic from Excel) ---
                $final_message = $whatsapp_temp_for_loop;
                $name_escaped = mysqli_real_escape_string($con, $name);
                
                $stmt = $con->prepare("SELECT id, name, m_type, var_count, body FROM wamail_templates WHERE id = ? ORDER BY id DESC");
                $stmt->bind_param("s", $whatsapp_temp);
                $stmt->execute();
                $result = $stmt->get_result();
                
                // $final_message = '';
                if ($rows = $result->fetch_assoc()) {
                    $final_message = $rows['body'];
                }
                
                if ($message_mode == 'template') {
                    
                    $reason_escaped = mysqli_real_escape_string($con, $reason);
                    
                    $j = 1;

                    foreach ($dynamic_vars_json as $ind => $vlu) {
                        $placeholder = "{{VAR$j}}";
                        $final_message = str_replace($placeholder, trim($row[(int)$vlu] ?? ''), $final_message);
                        $j++;
                        // var_dump($vlu);die;
                    }

                    // var_dump($final_message);die;
                    
                    $final_message_db = mysqli_real_escape_string($con, $final_message);

                } else {
                    
                    $final_message_db = mysqli_real_escape_string($con, $final_message);
                }
                
                $status = 'Pending';
                $insertQuery = "INSERT INTO whatsapp_bulk_message
                    (name, to_whatsapp, status, details, btn1_text, btn1_url, btn2_text, btn2_url, whatapp_campain, images)
                    VALUES (
                        '" . $name_escaped . "',
                        '$mobile',
                        '$status',
                        '" . $final_message_db . "',
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
                // 'message' => "Import completed: $inserted inserted, $duplicates duplicates, $skipped invalid."
                'message' => "Import completed: $inserted inserted."
            ]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No valid file uploaded.']);
    }
}





?>


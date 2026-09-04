<?php
include '../../include/shi-config.php';
include '../../include/functions.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

if (isset($_POST['method']) && $_POST['method'] == 'fetch_referral_report') {
    try {
        global $con;

        // DataTables server-side parameters
        $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $length = isset($_POST['length']) ? intval($_POST['length']) : 25;
        $searchValue = isset($_POST['search']['value']) ? mysqli_real_escape_string($con, $_POST['search']['value']) : '';

        // Search Query
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " AND (
                r.referral_code LIKE '%$searchValue%' OR 
                r.status LIKE '%$searchValue%' OR 
                refd.name LIKE '%$searchValue%' OR 
                refd.mobile LIKE '%$searchValue%' OR 
                refr_c.name LIKE '%$searchValue%' OR 
                refr_c.mobile LIKE '%$searchValue%' OR 
                refr_u.name LIKE '%$searchValue%' OR 
                refr_u.mobile LIKE '%$searchValue%'
            ) ";
        }

        // Base Query mapping based on your exact relational rules
        $baseQuery = "FROM referrals r
                      LEFT JOIN customer_register refd ON r.referred_user_id = refd.id
                      LEFT JOIN referral_codes rc ON r.referral_code = rc.code
                      LEFT JOIN customer_register refr_c ON r.referrer_user_id = refr_c.id AND rc.app_name = 'customer'
                      LEFT JOIN user_register refr_u ON r.referrer_user_id = refr_u.id AND rc.app_name != 'customer'
                      LEFT JOIN walletBalance_history w ON r.wallet_history_id = w.id
                      WHERE 1=1 " . $searchQuery;

        // Get Total Count
        $countQuery = "SELECT COUNT(r.id) AS total " . $baseQuery;
        $countResult = mysqli_query($con, $countQuery);
        $totalRecords = mysqli_fetch_assoc($countResult)['total'] ?? 0;

        // Get Filtered Data
        $dataQuery = "SELECT 
                        r.id,
                        r.referral_code,
                        r.status,
                        r.referrer_rewarded,
                        r.referred_rewarded,
                        r.created_at,
                        r.wallet_history_id,
                        refd.name AS referred_name,
                        refd.mobile AS referred_mobile,
                        COALESCE(refr_c.name, refr_u.name) AS referrer_name,
                        COALESCE(refr_c.mobile, refr_u.mobile) AS referrer_mobile,
                        w.global_type AS wallet_type,
                        w.uname AS wallet_user_name,
                        w.bonus AS wallet_bonus
                      " . $baseQuery . " ORDER BY r.id DESC LIMIT $start, $length";

        $dataResult = mysqli_query($con, $dataQuery);
        $data = [];

        if ($dataResult && mysqli_num_rows($dataResult) > 0) {
            $sno = $start + 1;
            while ($row = mysqli_fetch_assoc($dataResult)) {
                
                $date = !empty($row['created_at']) ? date('d M Y, h:i A', strtotime($row['created_at'])) : '-';

                // Status Badge Logic
                $statusText = strtoupper($row['status']);
                if ($statusText === 'COMPLETED') {
                    $statusHtml = '<span class="badge bg-success" style="font-size: 11px; padding: 5px 8px;">COMPLETED</span>';
                } elseif ($statusText === 'PENDING') {
                    $statusHtml = '<span class="badge bg-warning text-dark" style="font-size: 11px; padding: 5px 8px;">PENDING</span>';
                } else {
                    $statusHtml = '<span class="badge bg-danger" style="font-size: 11px; padding: 5px 8px;">FAILED</span>';
                }

                // Table Formatting
                $codeHtml = "<span class='badge bg-light text-dark border px-2 py-1' style='font-family: monospace; font-size: 13px; letter-spacing: 1px;'>" . ($row['referral_code'] ?: '-') . "</span>";
                
                $referrerHtml = "<strong class='text-dark'>" . ($row['referrer_name'] ?: 'Unknown') . "</strong><br><small class='text-muted'><i class='fa fa-phone me-1'></i>" . ($row['referrer_mobile'] ?: 'N/A') . "</small>";
                
                $referredHtml = "<strong class='text-dark'>" . ($row['referred_name'] ?: 'Unknown') . "</strong><br><small class='text-muted'><i class='fa fa-phone me-1'></i>" . ($row['referred_mobile'] ?: 'N/A') . "</small>";
                
                $walletHtml = $row['wallet_history_id'] ? 
                    "<strong class='text-primary'>#ID: " . $row['wallet_history_id'] . "</strong><br>
                     <small class='text-muted fw-bold text-uppercase'>" . ($row['wallet_type'] ?? '') . "</small><br>
                     <small class='text-muted'>" . ($row['wallet_user_name'] ?? '') . "</small>" 
                    : '<span class="text-muted">-</span>';

                $data[] = [
                    $sno++,
                    "<span class='text-muted small'><i class='fa fa-calendar me-1'></i>" . $date . "</span>",
                    $codeHtml,
                    $referrerHtml,
                    $referredHtml,
                    "<strong class='text-success'>₹" . ($row['referrer_rewarded'] ?: '0') . "</strong>",
                    "<strong class='text-success'>₹" . ($row['referred_rewarded'] ?: '0') . "</strong>",
                    $statusHtml,
                    $walletHtml
                ];
            }
        }

        echo json_encode([
            "draw" => $draw,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data" => $data
        ]);
        exit;

    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
        exit;
    }
}
?>
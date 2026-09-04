<?php
// Prevent PHP warnings from breaking JSON output causing DataTables Ajax Errors
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

include '../../include/shi-config.php';

if (!$con || $con->connect_error || mysqli_connect_errno()) {
    echo json_encode(['data' => [], 'error' => 'Database connection failed']);
    exit;
}

$method = $_POST['method'] ?? '';

if ($method === 'fetch_completed_reports') {
        
    // ADDED: Strict filters to ensure assigned_to is NOT NULL, NOT EMPTY, and NOT 0
    $sql = "SELECT c.*, d.name as driver_name, d.mobile as driver_mobile 
            FROM `cus_job_temp` c
            LEFT JOIN `user_register` d ON c.assigned_to = d.id AND d.deletes = '0'
            WHERE c.deletes = '0' 
            AND c.job_no NOT LIKE 'GRP-%' 
            AND c.job_status = 'completed'
            AND c.assigned_to IS NOT NULL 
            AND c.assigned_to != '' 
            AND c.assigned_to != '0'
            ORDER BY c.pickup_date DESC";

    $res = mysqli_query($con, $sql);

    $jobsMap = [];
    if ($res && mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $jobsMap[$row['job_no']] = $row;
        }
    }

    $customer_ids_to_fetch = [];
    $driver_ids_to_fetch = [];

    foreach ($jobsMap as $job_no => $row) {
        $uid = $row['user_id'] ?? 0;
        
        if (isset($row['global_type']) && $row['global_type'] === 'schedule') {
            $g_type = 'schedule';
        } else if (empty($uid) || $uid == 0 || !empty($row['user_details'])) {
            $g_type = 'website';
        } else if (strpos($job_no, 'GRC') === 0) {
            $g_type = 'customer';
        } else if (strpos($job_no, 'GRD') === 0) {
            $g_type = 'driver'; 
        } else {
            $g_type = 'website';
        }
        $jobsMap[$job_no]['calc_global_type'] = $g_type;

        if (!empty($uid) && $uid != 0 && $g_type !== 'website') {
            if ($g_type === 'driver') {
                $driver_ids_to_fetch[$uid] = $uid;
            } else {
                $customer_ids_to_fetch[$uid] = $uid;
            }
        }
    }

    $customer_data = [];
    if (!empty($customer_ids_to_fetch)) {
        $ids = implode(',', $customer_ids_to_fetch);
        $q = mysqli_query($con, "SELECT id, name, mobile FROM customer_register WHERE id IN ($ids)");
        if ($q) while($r = mysqli_fetch_assoc($q)) $customer_data[$r['id']] = $r;
    }

    $driver_data = [];
    if (!empty($driver_ids_to_fetch)) {
        $ids = implode(',', $driver_ids_to_fetch);
        $q = mysqli_query($con, "SELECT id, name, mobile FROM user_register WHERE id IN ($ids)");
        if ($q) while($r = mysqli_fetch_assoc($q)) $driver_data[$r['id']] = $r;
    }

    $finalJobs = [];
    foreach ($jobsMap as $job_no => $row) {
        
        // --- STRICT JSON PARSING (ONLY using fare_breakdown) ---
        $totalFare = 0;
        $commission = 0;
        $tax = 0;
        
        if (!empty($row['fare_breakdown']) && is_string($row['fare_breakdown'])) {
            $fareData = json_decode($row['fare_breakdown'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($fareData)) {
                // Fetch strictly from JSON keys
                $totalFare = isset($fareData['total_fare']) ? (float)$fareData['total_fare'] : 0;
                $commission = isset($fareData['com']) ? (float)$fareData['com'] : 0;
                $tax = isset($fareData['tax']) ? (float)$fareData['tax'] : 0;
            }
        }
        
        // Exact Calculation: Deduct both Commission and Tax from Customer Paid
        $driverEarned = $totalFare - $commission - $tax;
        if ($driverEarned < 0) {
            $driverEarned = 0; 
        }
        // --------------------------------------------------------

        $uid = $row['user_id'] ?? 0;
        $customer_name = 'Customer';
        $customer_mobile = '';
        $g_type = $row['calc_global_type'];

        if ($g_type === 'website') {
            if (!empty($row['user_details'])) {
                $uDetails = is_string($row['user_details']) ? json_decode($row['user_details'], true) : $row['user_details'];
                $customer_name = isset($uDetails['name']) ? $uDetails['name'] : 'Website Customer';
                $customer_mobile = isset($uDetails['mobile']) ? $uDetails['mobile'] : '';
            } else {
                $customer_name = 'Website Customer';
            }
        } else {
            if (!empty($uid) && $uid != 0) {
                if ($g_type === 'driver' && isset($driver_data[$uid])) {
                    $customer_name = $driver_data[$uid]['name'];
                    $customer_mobile = $driver_data[$uid]['mobile'];
                } elseif (isset($customer_data[$uid])) {
                    $customer_name = $customer_data[$uid]['name'];
                    $customer_mobile = $customer_data[$uid]['mobile'];
                }
            }
        }

        if (empty($customer_mobile) && !empty($row['mobile'])) {
            $customer_mobile = $row['mobile'];
        }

        $finalJobs[] = [
            'pickup_date'     => $row['pickup_date'],
            'job_no'          => $job_no,
            'customer_name'   => $customer_name,
            'customer_mobile' => $customer_mobile,
            'driver_name'     => $row['driver_name'] ?? null,
            'driver_mobile'   => $row['driver_mobile'] ?? null,
            'customer_paid'   => $totalFare,
            'commission'      => $commission, 
            'tax'             => $tax,
            'driver_earned'   => $driverEarned, 
            'job_status'      => $row['job_status']
        ];
    }

    echo json_encode(['data' => array_values($finalJobs)]);
    exit;
}

echo json_encode(['data' => [], 'error' => 'Invalid method']);
exit;
?>
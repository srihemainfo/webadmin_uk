<?php
// Include your database connection here
include '../../include/shi-config.php';
include '../../include/functions.php'; 

header('Content-Type: application/json');

if (isset($_POST['method'])) {
    global $con;

    // =================================================================================
    // 1. FETCH ALL JOBS FOR DATATABLE
    // =================================================================================
    if ($_POST['method'] == 'fetch_all_jobs') {
        try {
            $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
            $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
            $length = isset($_POST['length']) ? intval($_POST['length']) : 25;
            $searchValue = isset($_POST['search']['value']) ? mysqli_real_escape_string($con, $_POST['search']['value']) : '';
            
            // Filters
            $startDate = isset($_POST['startDate']) ? mysqli_real_escape_string($con, $_POST['startDate']) : '';
            $endDate = isset($_POST['endDate']) ? mysqli_real_escape_string($con, $_POST['endDate']) : '';
            $filterType = isset($_POST['filterType']) ? mysqli_real_escape_string($con, $_POST['filterType']) : 'created_at';
            $jobStatusFilter = isset($_POST['jobStatusFilter']) ? mysqli_real_escape_string($con, strtolower($_POST['jobStatusFilter'])) : '';

            $searchQuery = "";
            if ($searchValue != '') {
                $searchQuery = " AND (
                    c.job_no LIKE '%$searchValue%' OR 
                    c.from_place LIKE '%$searchValue%' OR 
                    c.to_place LIKE '%$searchValue%' OR 
                    cr.name LIKE '%$searchValue%' OR 
                    cr.mobile LIKE '%$searchValue%'
                ) ";
            }

            // Append Date Filters
            if (!empty($startDate) && !empty($endDate)) {
                $col = ($filterType === 'pickup_date') ? 'c.pickup_date' : 'c.created_at';
                $searchQuery .= " AND DATE($col) >= '$startDate' AND DATE($col) <= '$endDate' ";
            }

            // Append Job Status Filter
            $statusFilterQuery = "";
            if ($jobStatusFilter === 'created') {
                $statusFilterQuery = " AND LOWER(c.job_status) = 'created' ";
            } else if ($jobStatusFilter === 'accept') {
                $statusFilterQuery = " AND LOWER(c.job_status) IN ('accept', 'accepted') ";
            }

            // STRICT FILTERING: 
            // 1. Not Cancelled
            // 2. bids_details MUST exist and have content
            // 3. Exclude 'CREATED' jobs if their pickup_date has passed
            $baseQuery = "FROM cus_job_temp c 
                          LEFT JOIN customer_register cr ON c.user_id = cr.id 
                          WHERE c.deletes = '0' 
                          AND LOWER(c.job_status) != 'cancelled'
                          AND c.bids_details IS NOT NULL 
                          AND LENGTH(TRIM(c.bids_details)) > 5
                          AND (LOWER(c.job_status) != 'created' OR c.pickup_date >= NOW())
                          " . $searchQuery . $statusFilterQuery;
            
            // Get total count
            $countRes = mysqli_query($con, "SELECT COUNT(c.id) AS total " . $baseQuery);
            $totalRecords = mysqli_fetch_assoc($countRes)['total'] ?? 0;

            // Get data 
            $dataQuery = "SELECT c.id, c.job_no, c.preview_hash, c.job_type, c.from_place, c.to_place, c.pickup_date, 
                                 c.job_status, c.user_details, c.bids_details, c.fare_breakdown, 
                                 cr.name AS cr_name, cr.mobile AS cr_mobile 
                          " . $baseQuery . " ORDER BY c.id DESC LIMIT $start, $length";
            
            $dataResult = mysqli_query($con, $dataQuery);
            $data = [];

            if ($dataResult && mysqli_num_rows($dataResult) > 0) {
                while ($row = mysqli_fetch_assoc($dataResult)) {
                    
                    $uDetails = [];
                    if (!empty($row['user_details'])) {
                        $dec = json_decode($row['user_details'], true);
                        if (is_string($dec)) $dec = json_decode($dec, true);
                        if (is_array($dec)) $uDetails = $dec;
                    }

                    $custName = !empty($uDetails['name']) ? $uDetails['name'] : ($row['cr_name'] ?: 'UNKNOWN');
                    $custMobile = !empty($uDetails['mobile']) ? $uDetails['mobile'] : ($row['cr_mobile'] ?: 'UNKNOWN');

                    // Extract actual bids
                    $bids = [];
                    if (!empty($row['bids_details'])) {
                        $decBids = json_decode($row['bids_details'], true);
                        if (is_string($decBids)) $decBids = json_decode($decBids, true);
                        if (is_array($decBids)) $bids = $decBids;
                    }
                    
                    $fareData = [];
                    if (!empty($row['fare_breakdown'])) {
                        $decFare = json_decode($row['fare_breakdown'], true);
                        if (is_string($decFare)) $decFare = json_decode($decFare, true);
                        if (is_array($decFare)) $fareData = $decFare;
                    }

                    $bidCount = count($bids); 
                    
                    $row['fare_breakdown'] = !empty($fareData) ? $fareData : null;

                    $data[] = [
                        "id" => $row['id'],
                        "job_no" => $row['job_no'],
                        "preview_hash" => $row['preview_hash'] ?? '',
                        "customer_name" => strtoupper($custName),
                        "customer_mobile" => $custMobile,
                        "route" => strtoupper($row['from_place']) . " -> " . strtoupper($row['to_place']),
                        "pickup_date" => !empty($row['pickup_date']) ? date('d-M-Y H:i', strtotime($row['pickup_date'])) : '-',
                        "status" => strtoupper($row['job_status'] ?: 'UNKNOWN'),
                        "bid_count" => $bidCount,
                        "fare_breakdown" => $row['fare_breakdown']
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
            echo json_encode(["error" => strtoupper($e->getMessage())]);
            exit;
        }
    }

    // =================================================================================
    // 2. FETCH SPECIFIC BIDS FOR MODAL 
    // =================================================================================
    if ($_POST['method'] == 'fetch_job_bids') {
        try {
            $jobId = intval($_POST['job_id']);
            
            $query = mysqli_query($con, "SELECT bids_details, fare_breakdown, job_no, job_status FROM cus_job_temp WHERE id = '$jobId'");
            $row = mysqli_fetch_assoc($query);

            if (!$row) {
                echo json_encode(["status" => "error", "message" => "JOB NOT FOUND."]);
                exit;
            }

            $bids = [];
            if (!empty($row['bids_details'])) {
                $decBids = json_decode($row['bids_details'], true);
                if (is_string($decBids)) $decBids = json_decode($decBids, true);
                if (is_array($decBids)) $bids = $decBids;
            }

            $fareData = [];
            if (!empty($row['fare_breakdown'])) {
                $decFare = json_decode($row['fare_breakdown'], true);
                if (is_string($decFare)) $decFare = json_decode($decFare, true);
                if (is_array($decFare)) $fareData = $decFare;
            }
            
            $finalBidderId = isset($fareData['bidder_id']) ? (string)$fareData['bidder_id'] : null;

            $combinedBids = [];
            foreach ($bids as $dId => $bidInfo) {
                $combinedBids[(string)$dId] = [
                    "driver_id" => $dId,
                    "amount" => $bidInfo['amount'] ?? 0,
                    "status" => strtoupper($bidInfo['status'] ?? 'UNKNOWN'),
                    "remark" => !empty($bidInfo['remark']) ? $bidInfo['remark'] : '-',
                    "is_final" => ((string)$dId === $finalBidderId)
                ];
            }

            if (empty($combinedBids)) {
                echo json_encode(["status" => "success", "job_no" => $row['job_no'], "data" => []]);
                exit;
            }

            $driverIds = array_keys($combinedBids);
            $driverIdsStr = implode("','", array_map('intval', $driverIds));
            
            $driverQuery = mysqli_query($con, "SELECT id, name, mobile FROM user_register WHERE id IN ('$driverIdsStr')");
            $driverDict = [];
            if ($driverQuery) {
                while ($d = mysqli_fetch_assoc($driverQuery)) {
                    $driverDict[$d['id']] = $d;
                }
            }

            $mappedBids = [];
            foreach ($combinedBids as $dId => $bidInfo) {
                $mappedBids[] = [
                    "driver_id" => $dId,
                    "driver_name" => strtoupper($driverDict[$dId]['name'] ?? 'UNKNOWN DRIVER'),
                    "driver_mobile" => $driverDict[$dId]['mobile'] ?? 'N/A',
                    "amount" => $bidInfo['amount'],
                    "status" => $bidInfo['status'],
                    "remark" => $bidInfo['remark'],
                    "is_final" => $bidInfo['is_final']
                ];
            }

            echo json_encode([
                "status" => "success", 
                "job_no" => $row['job_no'], 
                "data" => $mappedBids
            ]);
            exit;

        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => strtoupper($e->getMessage())]);
            exit;
        }
    }
}
?>
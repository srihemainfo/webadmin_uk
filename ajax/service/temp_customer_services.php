<?php
include '../../include/shi-config.php';
include '../../include/functions.php';

if (isset($_POST['method']) && $_POST['method'] === 'get_temp_customers') {
    
    // Clean output buffer to prevent JSON corruption
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');

    // 1. Sanitize Inputs
    $startDate = mysqli_real_escape_string($con, $_POST['startDate'] ?? '');
    $endDate   = mysqli_real_escape_string($con, $_POST['endDate'] ?? '');
    $search    = mysqli_real_escape_string($con, $_POST['search'] ?? '');
    $userType  = mysqli_real_escape_string($con, $_POST['userType'] ?? '');

    // 2. Build WHERE Clause
    $whereParts = [];

    // Exclude Customers (name is empty) who already exist in customer_register
    $whereParts[] = "NOT EXISTS (SELECT 1 FROM customer_register cr WHERE cr.mobile = users_temp.mobile AND (users_temp.name IS NULL OR TRIM(users_temp.name) = ''))";

    // Exclude Drivers (name is not empty) who already exist in user_register
    $whereParts[] = "NOT EXISTS (SELECT 1 FROM user_register ur WHERE ur.mobile = users_temp.mobile AND users_temp.name IS NOT NULL AND TRIM(users_temp.name) != '')";

    // Date Filter
    if (!empty($startDate) && !empty($endDate)) {
        $whereParts[] = "users_temp.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
    }

    // Search Filter (Phone, Name, Email)
    if (!empty($search)) {
        $whereParts[] = "(users_temp.name LIKE '%$search%' OR users_temp.lname LIKE '%$search%' OR users_temp.mobile LIKE '%$search%' OR users_temp.email LIKE '%$search%')";
    }

    // User Type Filter Logic: Driver (Name is NOT NULL) vs Customer (Name is NULL/Empty)
    if ($userType === 'driver') {
        $whereParts[] = "(users_temp.name IS NOT NULL AND TRIM(users_temp.name) != '')";
    } elseif ($userType === 'customer') {
        $whereParts[] = "(users_temp.name IS NULL OR TRIM(users_temp.name) = '')";
    }

    $whereClause = "";
    if (count($whereParts) > 0) {
        $whereClause = "WHERE " . implode(" AND ", $whereParts);
    }

    // 3. Execute Query
    $query = "SELECT name, lname, mobile, email, created_at, password FROM users_temp $whereClause ORDER BY id DESC";
    $run = mysqli_query($con, $query);

    $data = [];

    // 4. Format Data for DataTables
    if ($run && mysqli_num_rows($run) > 0) {
        while ($row = mysqli_fetch_assoc($run)) {
            
            // Check if name exists to determine User Type
            $isDriver = !empty(trim($row['name'] ?? ''));
            $type = $isDriver ? 'Driver' : 'Customer';

            // Combine First and Last Name
            $fullName = trim(($row['name'] ?? '') . ' ' . ($row['lname'] ?? ''));

            // Format Date
            $formattedDate = '-';
            if (!empty($row['created_at'])) {
                $formattedDate = date('d M Y h:i a', strtotime($row['created_at']));
            }

            $data[] = array(
                "created_at" => $formattedDate,
                "user_type"  => $type, // New Field passed to frontend
                "full_name"  => !empty($fullName) ? $fullName : 'Guest / No Name',
                "mobile"     => !empty($row['mobile']) ? $row['mobile'] : '-',
                "email"      => !empty($row['email']) ? $row['email'] : '-',
                "password"   => !empty($row['password']) ? $row['password'] : '-' 
            );
        }
    }

    // 5. Return JSON
    echo json_encode(["data" => $data]);
    exit;
}
?>
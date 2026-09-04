<?php

include '../../include/shi-config.php';
include '../../include/functions.php';


$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";

$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";


if ($type == 'agent') {

    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($_REQUEST[role]) AND" : "";
} else {

    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$_REQUEST[role]' AND" : "";
}


$headers = apache_request_headers();
$result = array();
$post_csrf = $headers['X-Csrf-Token'] ?? '';

if ($method == "list_draw") {



    $draw = select_query($con, "draw", "", "(`deletes` = 0 AND `status` = 'Completed') OR (`deletes` = 0 AND `status` = 'Active' AND `id` IN ( SELECT `id` FROM ( SELECT `id` FROM `draw` WHERE `deletes` = 0 AND `status` = 'Active' ORDER BY `id` ASC LIMIT 1 ) t )) ORDER BY `id` DESC", "", "");
    if ($draw['nr'] > 0) {

        foreach ($draw['result'] as $key => $value) {

            $drawid = $value['id'];

            $action = '';


            $consolidated_Report = select_query($con, "consolidated_Report", "", "`drawid` = '$drawid' ORDER BY `id` DESC LIMIT 1", "", "");

            if ($consolidated_Report['nr'] > 0) {
                $si =  $consolidated_Report['result'][0]['reportstatus'];
                if ($si == 'YES') {
                    $action .= '<a href="' . $consolidated_Report['result'][0]['file_path'] . '" download=""><button class="btn" style="color: green;"><i class="fa fa-download"></i> Download</button></a>';
                } else {
                    if ($drawid >= 49) {
                        $action .= '<a href="' . constant('cronURL') . 'cron/consolidatedReport.php?id=' . $drawid . '"><button class="btn" style="color: red;"><i class="fa fa-download"></i> Generate</button></a>';
                    }
                }
            } else {

                if ($drawid >= 49) {
                    if ($value['status'] == 'Completed') {
                        $action .= '<a href="' . constant('cronURL') . 'cron/consolidatedReport.php?id=' . $drawid . '"><button class="btn" style="color: red;"><i class="fa fa-download"></i> Generate</button></a>';
                    } else {
                        if ((strtotime($value['ticket_end_datetime']) < strtotime($dubaidate_time) && strtotime($value['result_datetime']) > strtotime($dubaidate_time)) || (strtotime($value['result_datetime']) < strtotime($dubaidate_time))) {
                            $action .= '<a href="' . constant('cronURL') . 'cron/consolidatedReport.php?id=' . $drawid . '"><button class="btn" style="color: red;"><i class="fa fa-download"></i> Generate</button></a>';
                        }
                    }
                }
            }



            if ($drawid > 92 && $value['status'] == 'Completed') {
                $action .= '<a href="' . constant('cronURL') . 'cron/salesReport.php?drawid=' . $drawid . '&download=yes&sales=yes" ><button class="btn" style="color: blue;"><i class="fa fa-download"></i> Sales Report</button></a>';
                $action .= '<a href="' . constant('cronURL') . 'cron/salesReport.php?drawid=' . $drawid . '&download=yes&winner=yes"><button class="btn" style="color: #3f3f0d;"><i class="fa fa-download"></i> Winner Report</button></a>';
                $action .= '<a href="' .  constant('cronURL')  . 'cron/salesReport.php?drawid=' . $drawid . '&download=yes&stack=yes"><button class="btn" style="color: #3f3f0d;"><i class="fa fa-download"></i> Draw Summary Report </button></a>';
            }



            $result[] = ["drawno" => $value['draw_no'], "id" => $value['id'], "nid" => str_pad($value['id'], 7, "0", STR_PAD_LEFT), "name" => $value['name'], "ticket_start_datetime" => $value['ticket_start_datetime'], "ticket_end_datetime" => $value['ticket_end_datetime'], "result_datetime" => $value['result_datetime'], "status" => $value['status'], "output" => $action];
        }
    }


    echo json_encode($result);
}

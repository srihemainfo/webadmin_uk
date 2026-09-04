<?php



include '../../include/shi-config.php';
include '../../include/functions.php';
include '../../include/payment-config.php';



$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
$headers = apache_request_headers();
$result = array();

// $post_csrf = $headers['X-Csrf-Token'] ?? '';


class Draw
{
    // Properties
    public $con;
    public $color;

    // Methods
    function __construct($con)
    {
        $this->con = $con;
    }


    function getDayOrder($currentDay, $frequency)
    {
        $days = [];
        $timestamp = strtotime($currentDay);
        for ($i = 0; $i < 7; $i++) {
            $day = strftime('%A', $timestamp);
            if (in_array($day, $frequency)) {
                $days[]  = strftime('%A', $timestamp);
            }
            $timestamp = strtotime('+1 day', $timestamp);
        }
        return $days;
    }

    function displayDates($date1, $date2, $format = 'd-m-Y')
    {
        // var_dump($date2);
        $dates = array();
        $current = strtotime($date1);
        $date2 = strtotime($date2);
        $stepVal = '+1 day';
        while ($current <= $date2) {
            $dates[] = date($format, $current);
            $current = strtotime($stepVal, $current);
        }
        return $dates;
    }
}

$da = new Draw($con);


if ($method == "get_draw_date") {
    $result = [];
    $frq = $_POST['frq'];

    if ($_SESSION['memid'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Login Required';
        goto resultFI;
    }

    if ($_POST['frq'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Kindly Select the Draw Frequency';
        goto resultFI;
    }

    $draw_frequency = select_query($con, "draw_frequency", "", "`id` = '$frq' AND `deletes` = '0' ORDER BY `id` ASC LIMIT 1", "", "");
    if ($draw_frequency['nr'] < 1) {
        $result['type'] = '0';
        $result['result'] = 'Draw Frequency Not Found!';
        goto resultFI;
    }

    $draw = select_query($con, "draw", "", "`deletes` = 0 AND `status` = 'Active' ORDER BY `result_datetime` ASC LIMIT 1", "", "");
    if ($draw['nr'] < 1) {
        $result['type'] = '1';
        $result['result'] = 'Active Draw Not Found!';
        $result['start'] = date('Y-m-d', strtotime($dubaidate_time));
        goto resultFI;
    } else {
        $result['type'] = '1';
        $result['result'] = 'Active Draw Found!';
        $result['start'] = date('Y-m-d', strtotime($draw['result'][0]['result_datetime']));
        goto resultFI;
    }

    resultFI:
    echo json_encode($result);
} else if ($method == "generateDraw") {
    $result = [];
    $frq = BlockSQLInjectionforreportrange($_POST["frq"]);
    $startDate = BlockSQLInjectionforreportrange($_POST["startDate"]);
    $endDate = BlockSQLInjectionforreportrange($_POST["endDate"]);
    $drawprefix = BlockSQLInjectionforreportrange($_POST["drawprefix"]);
  
    if ($_SESSION['memid'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Login Required';
        goto resultGI;
    }
    if ($drawprefix == '') {
        $result['type'] = '0';
        $result['result'] = 'Kindly Enter The Draw Prefix!';
        goto resultGI;
    }
    if ($_POST['frq'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Kindly Select the Draw Frequency';
        goto resultGI;
    }
    $draw_frequency = select_query($con, "draw_frequency", "", "`id` = '$frq' AND `deletes` = '0' ORDER BY `id` ASC LIMIT 1", "", "");
    if ($draw_frequency['nr'] < 1) {
        $result['type'] = '0';
        $result['result'] = 'Draw Frequency Not Found!';
        goto resultGI;
    }
    if ($startDate == '') {
        $result['type'] = '0';
        $result['result'] = 'Kindly Select the Start Date!';
        goto resultGI;
    }
    if ($endDate == '') {
        $result['type'] = '0';
        $result['result'] = 'Kindly Select the End Date!';
        goto resultGI;
    }

    if (strtotime($startDate) > strtotime($endDate)) {
        $result['type'] = '0';
        $result['result'] = 'Start date is in front of end date!';
        goto resultGI;
    } else {


        $ORendTime = $endDate;

        $endDate = date('Y-m-d', strtotime('+15 day', strtotime($endDate)));


        $frequency = explode(',', $draw_frequency['result'][0]['frequency']);
        $lastdrawDate = '';
        $lastdrawno = '';
        $ticketCloseTime = '';


        $startDateCon = date("Y-m-d", strtotime($startDate));
        $draw = select_query($con, "draw", "", "`result_datetime` > '$startDateCon 00:00:00' AND `status` = 'Active' AND `deletes` = 0 ORDER BY `id` ASC LIMIT 1", "", "");
        if ($draw['nr'] > 0) {
            $lastdrawDate = date("Y-m-d H:i:s", strtotime($draw['result'][0]['result_datetime']));
            $lastdrawno = $draw['result'][0]['draw_no'];
            $ticketCloseTime = date("Y-m-d H:i:s", strtotime($draw['result'][0]['ticket_end_datetime']));
        } else {
            $draw = select_query($con, "draw", "", "`deletes` = 0 AND `status` = 'Active' ORDER BY `result_datetime` DESC LIMIT 1", "", "");
            if ($draw['nr'] > 0) {
                $lastdrawDate = date("Y-m-d H:i:s", strtotime($draw['result'][0]['result_datetime']));
                $lastdrawno = $draw['result'][0]['draw_no'];
                $ticketCloseTime = date("Y-m-d H:i:s", strtotime($draw['result'][0]['ticket_end_datetime']));
            } else {
                $lastdrawDate = date("Y-m-d", strtotime($startDate)) . ' 21:00:00';
                $lastdrawno = 0;
                $ticketCloseTime = date("Y-m-d", strtotime($startDate)) . ' 19:55:00';
            }
        }

// var_dump($ticketCloseTime);die;

        if ($lastdrawDate == '' || $lastdrawno == '' || $ticketCloseTime == '') {
            $result['type'] = '0';
            $result['result'] = 'Last Draw Missing Kindly Contact Developers';
            goto resultGI;
        }

        $currentDay = date("l", strtotime($lastdrawDate));
        $final = [];
        --$lastdrawno;
        $days = $da->getDayOrder($currentDay, $frequency);
        $drawDates = [];

        $dates_between = $da->displayDates(date('Y-m-d', strtotime($startDate)), date('Y-m-d', strtotime($endDate)));



        foreach ($dates_between as $key => $value) {
            if (strtotime($lastdrawDate) <  strtotime($value)) {

                $dayC = date("l", strtotime($value));
                if (in_array($dayC, $days)) {
                    $drawDates[] = $value;
                }
            }
        }

        foreach ($drawDates as $key => $value) {
            $drawNo = ++$lastdrawno;
            $final[$drawNo]['drawno'] = ++$drawNo;
            $final[$drawNo]['date'] = $value;
            $salestart = date("Y-m-d H:i:s",  strtotime($ticketCloseTime));
            $preNo = intval($drawNo) - 1;

            $preStart = $final[strval($preNo)]['salestart'];
            if ($preStart != NULL) {
                $salestart = date("Y-m-d H:i:s", strtotime($final[strval($preNo)]['date'] . date("H:i:s", strtotime('-1 second', strtotime($preStart)))));
            } else {

                $salestart = date("Y-m-d H:i:s",  strtotime($ticketCloseTime));
            }

            $final[$drawNo]['salestart'] = date("Y-m-d H:i:s", strtotime('+1 second', strtotime($salestart)));
        }



        foreach ($final as $key => $value) {
            $drawNo = (int)$key;
            $saleend = '';
            $result_time = '';

            $preend = $final[strval(++$drawNo)]['salestart'];

            if ($preend != NULL) {
                $saleend = date("Y-m-d H:i:s", strtotime('-1 second', strtotime($preend)));
            }
            if ($saleend != '') {
                $result_time = date("Y-m-d H:i:s", strtotime('+5 minutes', strtotime($saleend)));
            }

            $final[$key]['saleend'] = $saleend;
            $final[$key]['result_time'] =  $result_time;
        }


        foreach ($final as $key => $value) {
            $drawNo = (int)$key;

            $resultTime = $final[strval($drawNo)]['salestart'];

            $salestart = date("Y-m-d H:i:s", strtotime($final[strval($drawNo)]['salestart']));
            $saleend = date("Y-m-d H:i:s", strtotime($final[strval($drawNo)]['saleend']));


            $checkPrev =  select_query($con, "draw", "", "`result_datetime` > '$salestart' AND `status` = 'Active' AND `deletes`='0' order by `result_datetime` ASC", "", "");

            if ($checkPrev['nr'] > 0) {
                $final[$key]['between_draw'] = true;
            } else {
                $final[$key]['between_draw'] = false;
            }
            $final[$key]['frq'] = $frq;

            $final[$key]['day'] = date("l", strtotime($final[strval($drawNo)]['date']));
            $final[$key]['type'] =  $draw_frequency['result'][0]['name'];
            $final[$key]['drawprefix'] = $drawprefix;
            $final[$key]['drawname'] = '#' . str_pad($final[strval($drawNo)]['drawno'], 3, '0', STR_PAD_LEFT);
            $final[$drawNo]['popup'] =   $salestart;
            if ((strtotime($resultTime) > strtotime($ORendTime)) || (strtotime($final[strval($drawNo)]['saleend']) > strtotime($ORendTime)) || $resultTime == '') {
                unset($final[$key]);
            }
        }





        if (count($final) == 0) {
            $result['type'] = '0';
            $result['result'] = 'Could not create draw. Kindly increase End Date';
            goto resultGI;
        } else {
            $result['type'] = '1';
            $result['result'] = 'Draw Generated Successfully';
            $_SESSION['drawlist'] = $final;
            $result['drawlist'] = $final;
            goto resultGI;
        }
    }



    resultGI:
    echo json_encode($result);
} else if ($method == "deleteDraw") {
    $result = [];
    $deleteDraw = $_POST['id'];

    $final = $_SESSION['drawlist'];
    $ORdata = [];


    if ($_SESSION['memid'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Login Required';
        goto resultFSI;
    }

    if ($deleteDraw == '') {
        $result['type'] = '0';
        $result['result'] = 'Delete ID Missing. Kindly Refresh and Try Again!';
        goto resultFSI;
    }


    if ($final == '') {
        $result['type'] = '0';
        $result['result'] = 'Draw List Missing Kindly Refresh and Try Again.';
        goto resultFSI;
    }


    $startNo = array_key_first($final);
    $lastNo = array_key_last($final);

    foreach ($final as $key => $value) {
        $drawNo = (int)$key;
        if (intval($key) == intval($deleteDraw)) {
            $preID = (int)$key;

            if (intval($lastNo) == intval($key)) {
                goto clsRE;
            } else if (intval($startNo) == intval($key)) {
                $salestart = $final[strval(--$preID)]['salestart'];
                if ($salestart != '') {
                    $salestart = date("Y-m-d H:i:s", strtotime($salestart));
                } else {
                    $salestart = date("Y-m-d H:i:s", strtotime($final[strval($drawNo)]['salestart']));
                }
                $final[++$drawNo]['salestart'] = $salestart;



                goto clsRE;
            } else {
                $salestart = $final[strval(--$preID)]['saleend'];
                if ($salestart != '') {
                    $salestart = date("Y-m-d H:i:s", strtotime($salestart));
                } else {
                    $salestart = date("Y-m-d H:i:s", strtotime($final[strval($drawNo)]['saleend']));
                }
                $final[++$drawNo]['salestart'] = date("Y-m-d H:i:s", strtotime('+1 second', strtotime($salestart)));
                goto clsRE;
            }

            clsRE:
            unset($final[$key]);
        }
    }

    $lastID = --$startNo;
    foreach ($final as $key => $value) {

        $drawNo = (int)$key;
        $no = ++$lastID;
        $final[$drawNo]['drawno'] =  $no;
        $final[$drawNo]['drawname'] = '#' . str_pad($no, 3, '0', STR_PAD_LEFT);
        $ORdata[$no] = $final[$drawNo];
    }

    foreach ($ORdata as $key => $value) {
        $drawNo = (int)$key;

        $resultTime = $ORdata[strval($drawNo)]['salestart'];

        $salestart = date("Y-m-d H:i:s", strtotime($ORdata[strval($drawNo)]['salestart']));
        $saleend = date("Y-m-d H:i:s", strtotime($ORdata[strval($drawNo)]['saleend']));


        $checkPrev =  select_query($con, "draw", "", "`result_datetime` > '$salestart' AND `status` = 'Active' AND `deletes`='0' order by `result_datetime` ASC", "", "");

        if ($checkPrev['nr'] > 0) {
            $ORdata[$key]['between_draw'] = true;
        } else {
            $ORdata[$key]['between_draw'] = false;
        }
    }




    if (count($ORdata) == 0) {
        $result['type'] = '0';
        $result['result'] = 'Could not create draw. Kindly increase End Date';
        goto resultFSI;
    } else {
        $result['type'] = '1';
        $result['result'] = 'Draw Generated Successfully';
        $_SESSION['drawlist'] = $ORdata;
        $result['drawlist'] = $ORdata;
        goto resultFSI;
    }


    resultFSI:
    echo json_encode($result);
} else if ($method == 'editDraw') {
    $result = [];
    $editDraw = preg_replace("/[^0-9]/", "", $_POST['id']);
    $newTime = $_POST['newTime'];
    $final = $_SESSION['drawlist'];
    $cKey = preg_replace('/\d+/u', '', $_POST['id']);
    $endDate = $_POST['endDate'];
    $frq = $_POST['frq'];
    $drawprefix = $_POST['drawprefix'];


    if ($_SESSION['memid'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Login Required';
        goto resultFHSI;
    }
    if ($drawprefix == '') {
        $result['type'] = '0';
        $result['result'] = 'Kindly Enter The Draw Prefix!';
        goto resultFHSI;
    }
    if ($editDraw == '') {
        $result['type'] = '0';
        $result['result'] = 'Delete ID Missing. Kindly Refresh and Try Again!';
        goto resultFHSI;
    }
    if ($final == '') {
        $result['type'] = '0';
        $result['result'] = 'Draw List Missing Kindly Refresh and Try Again.';
        goto resultFHSI;
    }
    if ($newTime == '') {
        $result['type'] = '0';
        $result['result'] = 'Changed Time Missing Kindly Refresh and Try Again.';
        goto resultFHSI;
    }
    if ($cKey == '') {
        $result['type'] = '0';
        $result['result'] = 'Changed Key Missing Kindly Refresh and Try Again.';
        goto resultFHSI;
    }
    if ($_POST['frq'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Kindly Select the Draw Frequency';
        goto resultFHSI;
    }
    $draw_frequency = select_query($con, "draw_frequency", "", "`id` = '$frq' AND `deletes` = '0' ORDER BY `id` ASC LIMIT 1", "", "");
    if ($draw_frequency['nr'] < 1) {
        $result['type'] = '0';
        $result['result'] = 'Draw Frequency Not Found!';
        goto resultGI;
    }
    if ($endDate == '') {
        $result['type'] = '0';
        $result['result'] = 'Kindly Select the End Date!';
        goto resultGI;
    }

    $ORendTime = $endDate;
    $endDate = date('Y-m-d', strtotime('+15 day', strtotime($endDate)));

    foreach ($final as $key => $value) {
        $drawNo = (int)$key;
        if ($drawNo > intval($editDraw)) {
            unset($final[$key]);
        }
    }
    $nxtPreTime = $final[strval($editDraw)]['salestart'];
    $startNo = array_key_first($final);
    $lastNo = array_key_last($final);
    if (intval($lastNo) == intval($editDraw)) {
        $final[strval($editDraw)]['saleend'] = date("Y-m-d H:i:s", strtotime($newTime));
        $final[strval($editDraw)]['result_time'] = date("Y-m-d H:i:s", strtotime('+5 minutes', strtotime($newTime)));
        $final[strval($editDraw)]['date'] = date("Y-m-d", strtotime($newTime));

        $ORdata = $final;


        $frequency = explode(',', $draw_frequency['result'][0]['frequency']);
        $lastdrawDate = date("Y-m-d H:i:s", strtotime($final[strval($editDraw)]['result_time']));
        $lastdrawno =  (int)$final[strval($editDraw)]['drawno'];
        $ticketCloseTime = date("Y-m-d H:i:s", strtotime($final[strval($editDraw)]['saleend']));





        if ($lastdrawDate == '' || $lastdrawno == '' || $ticketCloseTime == '') {
            $result['type'] = '0';
            $result['result'] = 'Last Draw Missing Kindly Contact Developer';
            goto resultFHSI;
        }


        $currentDay = date("l", strtotime($lastdrawDate));
        $final = [];
        --$lastdrawno;
        $days = $da->getDayOrder($currentDay, $frequency);
        $drawDates = [];

        $dates_between = $da->displayDates(date('Y-m-d', strtotime($newTime)), date('Y-m-d', strtotime($endDate)));

        foreach ($dates_between as $key => $value) {
            if (strtotime($lastdrawDate) <  strtotime($value)) {
                $dayC = date("l", strtotime($value));
                if (in_array($dayC, $days)) {
                    $drawDates[] = $value;
                }
            }
        }

        $i = 0;
        foreach ($drawDates as $key => $value) {
            $drawNo = ++$lastdrawno;
            $final[$drawNo]['drawno'] = ++$drawNo;
            $final[$drawNo]['date'] = $value;
            $salestart = date("Y-m-d H:i:s",  strtotime($ticketCloseTime));
            $preNo = intval($drawNo) - 1;


            $preStart = $final[strval($preNo)]['salestart'];
            if ($preStart != NULL) {
                if ($i == 1) {
                    $salestart = date("Y-m-d H:i:s", strtotime($final[strval($preNo)]['date'] . date("H:i:s", strtotime('-1 second', strtotime($nxtPreTime)))));
                } else {
                    $salestart = date("Y-m-d H:i:s", strtotime($final[strval($preNo)]['date'] . date("H:i:s", strtotime('-1 second', strtotime($preStart)))));
                }
            } else {
                $salestart = date("Y-m-d H:i:s",  strtotime($ticketCloseTime));
            }



            $final[$drawNo]['salestart'] = date("Y-m-d H:i:s", strtotime('+1 second', strtotime($salestart)));
            $i++;
        }


        foreach ($final as $key => $value) {
            $drawNo = (int)$key;
            $saleend = '';
            $result_time = '';
            $preend = $final[strval(++$drawNo)]['salestart'];

            if ($preend != NULL) {
                $saleend = date("Y-m-d H:i:s", strtotime('-1 second', strtotime($preend)));
            }
            if ($saleend != '') {
                $result_time = date("Y-m-d H:i:s", strtotime('+5 minutes', strtotime($saleend)));
            }

            $final[$key]['saleend'] = $saleend;
            $final[$key]['result_time'] =  $result_time;
        }


        foreach ($final as $key => $value) {
            $drawNo = (int)$key;

            $resultTime = $final[strval($drawNo)]['salestart'];

            $salestart = date("Y-m-d H:i:s", strtotime($final[strval($drawNo)]['salestart']));
            $saleend = date("Y-m-d H:i:s", strtotime($final[strval($drawNo)]['saleend']));


            $checkPrev =  select_query($con, "draw", "", "`result_datetime` > '$salestart'  AND `status` = 'Active' AND `deletes`='0' order by `result_datetime` ASC", "", "");

            if ($checkPrev['nr'] > 0) {
                $final[$key]['between_draw'] = true;
            } else {
                $final[$key]['between_draw'] = false;
            }
            $final[$key]['frq'] = $frq;
            $final[$key]['day'] = date("l", strtotime($final[strval($drawNo)]['date']));
            $final[$key]['type'] =  $draw_frequency['result'][0]['name'];
            $final[$key]['drawprefix'] = $drawprefix;
            $final[$key]['drawname'] = '#' . str_pad($final[strval($drawNo)]['drawno'], 3, '0', STR_PAD_LEFT);
            $final[$drawNo]['popup'] =   $salestart;
            if ((strtotime($resultTime) > strtotime($ORendTime)) || (strtotime($final[strval($drawNo)]['saleend']) > strtotime($ORendTime)) || $resultTime == '') {
                unset($final[$key]);
            }
        }


        $finalRe = array_merge($ORdata, $final);
        $final = [];
        foreach ($finalRe as $key => $value) {
            $final[$value['drawno']] = $value;
            $final[$value['drawno']]['day'] = date("l", strtotime($final[strval($value['drawno'])]['result_time']));
        }
    }

    if (count($final) == 0) {
        $result['type'] = '0';
        $result['result'] = 'Could not create draw. Kindly increase End Date';
        goto resultFHSI;
    } else {
        $result['type'] = '1';
        $result['result'] = 'Date & Time updated Successfully';
        $_SESSION['drawlist'] = $final;
        $result['drawlist'] = $final;
        goto resultFHSI;
    }

    resultFHSI:
    echo json_encode($result);
} else if ($method == 'SaveAllDraws') {
    $result = [];
    $frq = BlockSQLInjectionforreportrange($_POST["frq"]);
    $startDate = BlockSQLInjectionforreportrange($_POST["startDate"]);
    $endDate = BlockSQLInjectionforreportrange($_POST["endDate"]);


    $final = $_SESSION['drawlist'];
    if ($_SESSION['memid'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Login Required';
        goto resultGJI;
    }
    if ($_POST['frq'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Kindly Select the Draw Frequency';
        goto resultGJI;
    }
    $draw_frequency = select_query($con, "draw_frequency", "", "`id` = '$frq' AND `deletes` = '0' ORDER BY `id` ASC LIMIT 1", "", "");
    if ($draw_frequency['nr'] < 1) {
        $result['type'] = '0';
        $result['result'] = 'Draw Frequency Not Found!';
        goto resultGJI;
    }
    if ($startDate == '') {
        $result['type'] = '0';
        $result['result'] = 'Kindly Select the Start Date!';
        goto resultGJI;
    }
    if ($endDate == '') {
        $result['type'] = '0';
        $result['result'] = 'Kindly Select the End Date!';
        goto resultGJI;
    }
    if (strtotime($startDate) > strtotime($endDate)) {
        $result['type'] = '0';
        $result['result'] = 'Start date is in front of end date!';
        goto resultGJI;
    } else {
        if ($final == '') {
            $result['type'] = '0';
            $result['result'] = 'Draw List Missing Kindly Refresh and Try Again.';
            goto resultGJI;
        }
        if (count($final) == 0) {
            $result['type'] = '0';
            $result['result'] = 'Draw List Missing Kindly Refresh and Try Again.';
            goto resultGJI;
        }

        $startNo = array_key_first($final);
        $startDateCon = date("Y-m-d H:i:s", strtotime($final[$startNo]['salestart']));
        $draw = select_query($con, "draw", "", "`ticket_end_datetime` > '$startDateCon' AND `status` = 'Active' AND `deletes` = '0' ORDER BY `id` ASC", "", "");
        if ($draw['nr'] > 0) {
            $deleTeQuery = mysqli_query($con, "UPDATE draw SET `deletes` = '1', `status` = 'Deleted', `dailyConsolationStatus` = 'Deleted'  where `ticket_end_datetime` > '$startDateCon' AND `status` = 'Active' AND `deletes` = '0' ORDER BY `id` ASC");
            if ($deleTeQuery) {
                goto GVIs;
            }
        } else {
            goto GVIs;
        }
        GVIs:

        // $getLostDraw = select_query($con, "draw", "", "`deletes` = 0 ORDER BY `id` DESC LIMIT 1", "", "");
        // if ($getLostDraw['nr'] > 0) {
            $ins = [];
            foreach ($final as $key => $value) {

                // $popUpHtml = '<h3 style="margin-top: 20px; margin-bottom: 10px; font-size: 24px;"><span style="font-family: Arial;"></span><font face="Arial"><span style="font-weight: 700; font-family: Arial;">Oh, Excited ???</span></font></h3><h3 style="margin-top: 20px; margin-bottom: 10px; font-size: 24px;"><font face="Arial"><span style="font-weight: 700;"><br></span></font></h3><h3 style="margin-top: 20px; margin-bottom: 10px; font-size: 24px;"><font face="Arial"><span style="font-weight: 700; font-family: Arial;">Kindly note that now you are entering into our next ' . (isset($drawfreq) && $drawfreq === 4 ? 'Daily Draw ' : 'Tri-Daily Draw ') . ' No: ' . str_pad($final[$key]['drawno'], 3, '0', STR_PAD_LEFT) . '</span></font></h3><h3 style="margin-top: 20px; margin-bottom: 10px; font-size: 24px;"><br style="color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;"></h3>';
                // $popUpHtml = '';
                
                $draw_arr = [
                    "draw_no" => $final[$key]['drawno'],
                    "name" =>   $final[$key]['drawprefix'] . ' ' . $final[$key]['drawname'],
                    "ticket_start_datetime" => $final[$key]['salestart'],
                    "ticket_end_datetime" => $final[$key]['saleend'],
                    "result_datetime" => $final[$key]['result_time'],
                    "status" => 'Active',
                    // "deletes" => '0',
                    // "first" => '',
                    // "second" => '',
                    // "third_one" => '',
                    // "third_two" => '',
                    // "third_three" => '',
                    // "third_four" => '',
                    // "permitno" => $getLostDraw['result'][0]['permitno'],
                    // "hprize1" => $getLostDraw['result'][0]['hprize1'],
                    // "hprize2" => $getLostDraw['result'][0]['hprize2'],
                    // "hprize3" =>  $getLostDraw['result'][0]['hprize3'],
                    "createdon" => $dubaidate_time,
                    "popUpHtml" => '',
                    "drawfreq" => $final[$key]['frq'],
                    // "prizeRatio" => $getLostDraw['result'][0]['prizeRatio']
                ];


                $draw_ins = insert($con, "draw", "", $draw_arr, "", "", "");
                if ($draw_ins['id'] != '') {
                    $ins[] = $draw_ins['id'];
                }
            }
        // } else {
        //     $result['type'] = '0';
        //     $result['result'] = 'Could not get last Draw Details';
        //     goto resultFHSI;
        // }
    }

    if (count($final) != count($ins)) {
        $result['type'] = '0';
        $result['result'] = 'Could not create draw. Kindly increase End Date';
        goto resultGJI;
    } else {
        $result['type'] = '1';
        $result['result'] = 'Draw Generated Successfully';

        unset($_SESSION['drawlist']);
        goto resultGJI;
    }


    resultGJI:
    echo json_encode($result);
} else if ($method == 'getUpcommingDraw') {
    $result = [];
    $final = [];
    if ($_SESSION['memid'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Login Required';
        goto resultFG;
    }
    $draw = select_query($con, "draw", "", "`result_datetime` > '$dubaidate_time' AND `status` = 'Active' AND `deletes` = '0' ORDER BY `id` ASC", "", "");
    if ($draw['nr'] < 1) {
        $result['type'] = '0';
        $result['result'] = 'Draw Not Found Kindly Create New Draw';
        goto resultFG;
    }


    $finalDate = mysqli_query($con, "select result_datetime from draw where  `status` = 'Active' AND `deletes` = '0' ORDER BY `draw`.`result_datetime` DESC LIMIT 1");
    $finalDate = mysqli_fetch_assoc($finalDate);
    if ($finalDate['result_datetime'] == '') {
        $finalDate['result_datetime'] = date("Y-m-d H:i:s", strtotime('+365 day', strtotime($dubaidate_time)));
    }


    if ($draw['nr'] > 0) {
        foreach ($draw['result'] as $key => $value) {
            $final[$value['draw_no']]['id'] = (int)$value['id'];
            $final[$value['draw_no']]['drawname'] = $value['name'];
            $final[$value['draw_no']]['result_time'] = date("Y-m-d H:i:s", strtotime($value['result_datetime']));
            $final[$value['draw_no']]['saleend'] = date("Y-m-d H:i:s", strtotime($value['ticket_end_datetime']));
            $final[$value['draw_no']]['salestart'] = date("Y-m-d H:i:s", strtotime($value['ticket_start_datetime']));
            $final[$value['draw_no']]['date'] = date("Y-m-d", strtotime($value['result_datetime']));
            $final[$value['draw_no']]['popup'] = date("Y-m-d H:i:s", strtotime($value['ticket_start_datetime']));
            $final[$value['draw_no']]['day'] = date("l", strtotime($value['result_datetime']));
            $final[$value['draw_no']]['maxDay'] = (strtotime($value['result_datetime']) == strtotime($finalDate['result_datetime'])) ? date("Y-m-d", strtotime('+90 day', strtotime($finalDate['result_datetime']))) : date("Y-m-d", strtotime($finalDate['result_datetime']));
            $final[$value['draw_no']]['minDay'] = date("Y-m-d H:i:s", strtotime($dubaidate_time));
            $final[$value['draw_no']]['deletes'] = true;

            if (strtotime($value['ticket_start_datetime']) < strtotime($dubaidate_time) && strtotime($value['result_datetime']) > strtotime($dubaidate_time)) {
                $final[$value['draw_no']]['deletes'] = false;
            }

            $final[$value['draw_no']]['generate'] = false;
            if (strtotime($value['ticket_end_datetime']) < strtotime($dubaidate_time) && strtotime($value['result_datetime']) > strtotime($dubaidate_time)) {
                $final[$value['draw_no']]['generate'] = true;
            }
            
            $final[$value['draw_no']]['dailyThirllPrice'] = $value['dailyThirllPrice'];
            $final[$value['draw_no']]['dailyConsolationPrice'] = $value['dailyConsolationPrice'];
            
        }
    }

    $result['type'] = '1';
    $result['result'] = 'All Draw Details Get';
    $result['drawlist'] = $final;

    resultFG:
    echo json_encode($result);
} else if ($method == 'deleteDraw_new') {
    $result = [];
    $deleteID = (int)$_POST['id'];
    if ($_SESSION['memid'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Login Required';
        goto resultGHID;
    }
    if ($deleteID == '') {
        $result['type'] = '0';
        $result['result'] = 'Delete ID Missing. Kindly Refresh and Try Again!';
        goto resultGHID;
    }
    $draw = select_query($con, "draw", "", "`status` = 'Active' AND `deletes` = '0' ORDER BY `draw`.`result_datetime` DESC LIMIT 1", "", "");
    if ($draw['nr'] < 1) {
        $result['type'] = '0';
        $result['result'] = 'Draw Not Found Kindly Create New Draw';
        goto resultGHID;
    }

    $lastID = (int)$draw['result'][0]['id'];

    if ($lastID == $deleteID) {
        $deleTeQuery = mysqli_query($con, "UPDATE draw SET `deletes` = '1', `status` = 'deleted' where `id` = '$deleteID' AND `status` = 'Active' AND `deletes` = '0' ORDER BY `id` ASC");
        if ($deleTeQuery) {
            $result['type'] = '1';
            $result['result'] = 'Draw Deleted Successfully!';
            goto resultGHID;
        } else {
            $result['type'] = '0';
            $result['result'] = 'Draw Delete Failed!';
            goto resultGHID;
        }
    } else {

        $preDraw = mysqli_query($con, "select * from draw where id = (select max(id) from draw where id < $deleteID AND `status` = 'Active' AND `deletes` = '0') AND `status` = 'Active' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1;");
        $query = mysqli_query($con, "select * from draw where id = (select min(id) from draw where id > $deleteID AND `status` = 'Active' AND `deletes` = '0') AND `status` = 'Active' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1;");
        if ($query) {
            $preData = mysqli_fetch_assoc($query);
            $preDataID =  $preData['id'];
            $draw_no = $preData['draw_no'];

            if ($preDataID != '') {

                $dedraw = select_query($con, "draw", "", "`id` = '$deleteID' AND `status` = 'Active' AND `deletes` = '0' ORDER BY `draw`.`result_datetime` DESC LIMIT 1", "", "");
                if ($dedraw['nr'] > 0) {

                    $ticket_start_datetime  = date("Y-m-d H:i:s", strtotime($dedraw['result'][0]['ticket_start_datetime']));
                    $timeUpdat = mysqli_query($con, "UPDATE draw SET `ticket_start_datetime` = '$ticket_start_datetime' where `id` = '$preDataID' AND `status` = 'Active' AND `deletes` = '0' ORDER BY `id` ASC");
                    if ($timeUpdat) {
                        $deleTeQuery = mysqli_query($con, "UPDATE draw SET `deletes` = '1', `status` = 'deleted' where `id` = '$deleteID' AND `status` = 'Active' AND `deletes` = '0' ORDER BY `id` ASC");
                        if ($deleTeQuery) {


                            if ($preDraw) {
                                $preDatas = mysqli_fetch_assoc($preDraw);
                                $preDataID =  $preDatas['id'];
                                $draw_no = $preDatas['draw_no'];
                                $getAllF = select_query($con, "draw", "", "`deletes` = 0 AND `id` > $preDataID and `status` = 'Active' ORDER BY `id` ASC", "", "");

                                if ($getAllF['nr'] > 0) {

                                    foreach ($getAllF['result'] as $key => $value) {
                                        $did = $value['id'];
                                        $newDano = ++$draw_no;
                                        $newName = str_replace(strval($value['draw_no']), strval($newDano), $value['name']);
                                        $deleTeQuery = mysqli_query($con, "UPDATE draw SET `draw_no` = '$newDano', `name` = '$newName' where `id` = '$did' AND `status` = 'Active' AND `deletes` = '0' ORDER BY `id` ASC");
                                    }
                                }
                            }



                            $result['type'] = '1';
                            $result['result'] = 'Draw Delete Successfully!';
                            goto resultGHID;
                        } else {
                            $result['type'] = '0';
                            $result['result'] = 'Draw Delete Failed!';
                            goto resultGHID;
                        }
                    } else {
                        $result['type'] = '0';
                        $result['result'] = 'Draw Delete Failed!';
                        goto resultGHID;
                    }
                } else {
                    $result['type'] = '0';
                    $result['result'] = 'Draw Not Found!';
                    goto resultGHID;
                }
            }
        }
    }


    resultGHID:
    echo json_encode($result);
} else if ($method == 'deleteDraw_preview') {
    $result = [];
    $deleteID = (int)$_POST['id'];
    if ($_SESSION['memid'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Login Required';
        goto resultIOGHID;
    }
    if ($deleteID == '') {
        $result['type'] = '0';
        $result['result'] = 'Delete ID Missing. Kindly Refresh and Try Again!';
        goto resultIOGHID;
    }
    $draw = select_query($con, "draw", "", "`status` = 'Active' AND `deletes` = '0' ORDER BY `draw`.`result_datetime` DESC LIMIT 1", "", "");
    if ($draw['nr'] < 1) {
        $result['type'] = '0';
        $result['result'] = 'Draw Not Found Kindly Create New Draw';
        goto resultIOGHID;
    }

    $lastID = (int)$draw['result'][0]['id'];

    if ($lastID == $deleteID) {
        $result['type'] = '1';
        $result['result'] = 'LAST';
        $result['message'] = 'Are Sure you want to delete last draw?';
        goto resultIOGHID;
    } else {



        $preDraw = mysqli_query($con, "select * from draw where id = (select max(id) from draw where id < $deleteID AND `status` = 'Active' AND `deletes` = '0') AND `status` = 'Active' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1;");
        $query = mysqli_query($con, "select * from draw where id = (select min(id) from draw where id > $deleteID AND `status` = 'Active' AND `deletes` = '0') AND `status` = 'Active' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1;");
        if ($query) {
            $preData = mysqli_fetch_assoc($query);
            $preDataID =  $preData['id'];
            $draw_no = $preData['draw_no'];
            $dedraw = select_query($con, "draw", "", "`id` = '$deleteID' AND `status` = 'Active' AND `deletes` = '0' ORDER BY `draw`.`result_datetime` DESC LIMIT 1", "", "");
            if ($dedraw['nr'] > 0) {
                $ticket_start_datetime  = date("Y-m-d H:i:s", strtotime($dedraw['result'][0]['ticket_start_datetime']));

                $result['type'] = '1';
                $result['result'] = 'MID';
                $result['message'] = 'Are Sure you want to delete ' . $dedraw['result'][0]['name'] . '? <br> Note: ' . $preData['name'] . ' Ticket Sales Start Time ' . $ticket_start_datetime . ' Changed Automatically.';
                goto resultIOGHID;
            }
        }
    }

    resultIOGHID:
    echo json_encode($result);
} else if ($method == 'editDraw_new') {
    $result = [];
    $editID = preg_replace("/[^0-9]/", "", $_POST['id']);
    $newTime = $_POST['newTime'];
    if ($_SESSION['memid'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Login Required';
        goto resultGHIS;
    }
    if ($editID == '') {
        $result['type'] = '0';
        $result['result'] = 'Edit ID Missing. Kindly Refresh and Try Again!';
        goto resultGHIS;
    }
    if ($newTime == '') {
        $result['type'] = '0';
        $result['result'] = 'Changed Time Missing Kindly Refresh and Try Again.';
        goto resultGHIS;
    }
    if (strtotime($newTime) < strtotime($dubaidate_time)) {
        $result['type'] = '0';
        $result['result'] = 'End Date & Time Greater than current time!';
        goto resultGHIS;
    }
    $draw = select_query($con, "draw", "", "`id` = '$editID' AND `status` = 'Active' AND `deletes` = 0 ORDER BY `id` ASC", "", "");
    if ($draw['nr'] < 1) {
        $result['type'] = '0';
        $result['result'] = 'Draw Not Found Kindly Create New Draw';
        goto resultGHIS;
    }


    $nextDraw = mysqli_query($con, "select * from draw where id = (select min(id) from draw where id > $editID AND `status` = 'Active' AND `deletes` = '0') AND `status` = 'Active' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1;");
    $nextDraw_row = mysqli_fetch_assoc($nextDraw);
    if ($nextDraw_row['drawfreq'] != '' && $nextDraw_row['name'] != '') {
        $frq = $nextDraw_row['drawfreq'];
        $drawprefix = preg_replace('/[^A-Za-z]/', '', $nextDraw_row['name']);
    } else {
        $frq = $draw['result'][0]['drawfreq'];
        $drawprefix = preg_replace('/[^A-Za-z]/', '', $draw['result'][0]['name']);
    }

    if ($frq == '') {
        $result['type'] = '0';
        $result['result'] = 'Kindly Select the Draw Frequency';
        goto resultGHIS;
    }
    $draw_frequency = select_query($con, "draw_frequency", "", "`id` = '$frq' AND `deletes` = '0' ORDER BY `id` ASC LIMIT 1", "", "");
    if ($draw_frequency['nr'] < 1) {
        $result['type'] = '0';
        $result['result'] = 'Draw Frequency Not Found!';
        goto resultGHIS;
    }
    $getLastDraw = select_query($con, "draw", "", "`deletes` = 0 AND status = 'Active' ORDER BY `result_datetime` DESC LIMIT 1", "", "");
    if ($draw['nr'] < 1) {
        $result['type'] = '0';
        $result['result'] = 'Draw Not Found Kindly Create New Draw';
        goto resultGHIS;
    }
    $editID = (int)$draw['result'][0]['id'];


    $lastID = (int)$getLastDraw['result'][0]['id'];

    $finalDrawNo =  (int)$getLastDraw['result'][0]['draw_no'];
    $lastDrawEndDate = $getLastDraw['result'][0]['result_datetime'];
    if ($lastID == $editID) {
        $ticket_end_datetime =  date("Y-m-d H:i:s", strtotime($newTime));
        $result_datetime = date("Y-m-d H:i:s", strtotime('+5 minutes', strtotime($newTime)));

        $timeUpdate = mysqli_query($con, "UPDATE draw SET `ticket_end_datetime` = '$ticket_end_datetime', `result_datetime` = '$result_datetime'  where `id` = '$editID' AND `status` = 'Active' AND `deletes` = '0' ORDER BY `id` ASC");
        if ($timeUpdate) {
            $result['type'] = '1';
            $result['result'] = 'Draw Time Updated Successfuly!';
            goto resultGHIS;
        } else {
            $result['type'] = '0';
            $result['result'] = 'Draw Time Update Failed!';
            goto resultGHIS;
        }
    } else {

        $ticket_end_datetime =  date("Y-m-d H:i:s", strtotime($newTime));
        $result_datetime = date("Y-m-d H:i:s", strtotime('+5 minutes', strtotime($newTime)));
        $timeUpdate = mysqli_query($con, "UPDATE draw SET `ticket_end_datetime` = '$ticket_end_datetime', `result_datetime` = '$result_datetime'  where `id` = '$editID' AND `status` = 'Active' AND `deletes` = '0' ORDER BY `id` ASC");
        if ($timeUpdate) {

            $endDate = date('Y-m-d', strtotime('+15 day', strtotime($lastDrawEndDate)));

            $frequency = explode(',', $draw_frequency['result'][0]['frequency']);
            $lastdrawDate = date("Y-m-d H:i:s", strtotime($result_datetime));
            $lastdrawno =  (int)$draw['result'][0]['draw_no'];
            $ticketCloseTime = date("Y-m-d H:i:s", strtotime($ticket_end_datetime));



            if ($lastdrawDate == '' || $lastdrawno == '' || $ticketCloseTime == '') {
                $result['type'] = '0';
                $result['result'] = 'Last Draw Missing Kindly Contact Developer';
                goto resultFHSI;
            }


            $currentDay = date("l", strtotime($lastdrawDate));
            $final = [];
            --$lastdrawno;
            $days = $da->getDayOrder($currentDay, $frequency);
            $drawDates = [];

            $dates_between = $da->displayDates(date('Y-m-d', strtotime($newTime)), date('Y-m-d', strtotime($endDate)));


            foreach ($dates_between as $key => $value) {
                if (strtotime($lastdrawDate) <  strtotime($value)) {

                    $dayC = date("l", strtotime($value));
                    if (in_array($dayC, $days)) {
                        $drawDates[] = $value;
                    }
                }
            }
            $runQuery_C = mysqli_query($con, "select * from `draw` where `id` > '$editID' AND `deletes` = '0' AND `status` = 'Active' ORDER BY `id` DESC");
            $len = mysqli_num_rows($runQuery_C);

            $i = 0;
            foreach ($drawDates as $key => $value) {

                $drawNo = ++$lastdrawno;
                $final[$drawNo]['drawno'] = ++$drawNo;
                $final[$drawNo]['date'] = $value;
                $salestart = date("Y-m-d H:i:s",  strtotime($ticketCloseTime));
                $preNo = intval($drawNo) - 1;

                $preStart = $final[strval($preNo)]['salestart'];
                if ($preStart != NULL) {
                    if ($i == 0) {
                        $salestart = date("Y-m-d H:i:s", strtotime($final[strval($preNo)]['date'] . date("H:i:s", strtotime('-1 second', strtotime($preStart)))));
                    } else {


                        $runQuery = mysqli_query($con, "select * from `draw` where `draw_no` = '$drawNo' AND `deletes` = '0' AND `status` = 'Active' ORDER BY `id` DESC LIMIT 1");
                        $get_Exist_Data = mysqli_fetch_assoc($runQuery);
                        if ($get_Exist_Data['ticket_start_datetime'] != '' && $get_Exist_Data['ticket_start_datetime'] != NULL) {
                            $preStart = $get_Exist_Data['ticket_start_datetime'];
                        }


                        $salestart = date("Y-m-d H:i:s", strtotime($final[strval($preNo)]['date'] . date("H:i:s", strtotime('-1 second', strtotime($preStart)))));
                    }
                } else {

                    $salestart = date("Y-m-d H:i:s",  strtotime($ticketCloseTime));
                }

                $final[$drawNo]['salestart'] = date("Y-m-d H:i:s", strtotime('+1 second', strtotime($salestart)));
                $i++;
            }



            foreach ($final as $key => $value) {
                $drawNo = (int)$key;

                $saleend = '';
                $result_time = '';
                $preend = $final[strval(++$drawNo)]['salestart'];



                if ($preend != NULL) {


                    if ($finalDrawNo == (int)$key) {

                        $runQuery = mysqli_query($con, "select * from `draw` where `draw_no` = '$finalDrawNo' AND `deletes` = '0' AND `status` = 'Active' ORDER BY `id` DESC LIMIT 1");
                        $get_Exist_Data = mysqli_fetch_assoc($runQuery);
                        if ($get_Exist_Data['ticket_end_datetime'] != '' && $get_Exist_Data['ticket_end_datetime'] != NULL) {
                            $preend = date("Y-m-d H:i:s", strtotime('+1 second', strtotime($get_Exist_Data['ticket_end_datetime'])));
                        }
                    }
                    $saleend = date("Y-m-d H:i:s", strtotime('-1 second', strtotime($preend)));
                }


                if ($saleend != '') {
                    $result_time = date("Y-m-d H:i:s", strtotime('+5 minutes', strtotime($saleend)));
                }

                $final[$key]['saleend'] = $saleend;
                $final[$key]['result_time'] =  $result_time;
            }


            foreach ($final as $key => $value) {
                $drawNo = (int)$key;

                $resultTime = $final[strval($drawNo)]['salestart'];

                $salestart = date("Y-m-d H:i:s", strtotime($final[strval($drawNo)]['salestart']));
                $saleend = date("Y-m-d H:i:s", strtotime($final[strval($drawNo)]['saleend']));


                $checkPrev =  select_query($con, "draw", "", "`result_datetime` > '$salestart'  AND `status` = 'Active' AND `deletes`='0' order by `result_datetime` ASC", "", "");

                if ($checkPrev['nr'] > 0) {
                    $final[$key]['between_draw'] = true;
                } else {
                    $final[$key]['between_draw'] = false;
                }
                $final[$key]['frq'] = $frq;
                $final[$key]['day'] = date("l", strtotime($final[strval($drawNo)]['date']));
                $final[$key]['type'] =  $draw_frequency['result'][0]['name'];
                $final[$key]['drawprefix'] = $drawprefix;
                $final[$key]['drawname'] = '#' . str_pad($final[strval($drawNo)]['drawno'], 3, '0', STR_PAD_LEFT);
                $final[$drawNo]['popup'] =   $salestart;


                if ((strtotime($resultTime) > strtotime($lastDrawEndDate)) || (strtotime($final[strval($drawNo)]['saleend']) > strtotime($lastDrawEndDate)) || $resultTime == '') {
                    if ($final[$key]['drawno'] > $finalDrawNo) {
                        unset($final[$key]);
                    }
                }
            }


            foreach ($final as $key => $value) {
                $final[$value['drawno']] = $value;
                $final[$value['drawno']]['day'] = date("l", strtotime($final[strval($value['drawno'])]['result_time']));
            }

            if (count($final) == 0) {
                $result['type'] = '0';
                $result['result'] = 'Could not create draw. Kindly increase End Date';
                goto resultFHSI;
            } else {
                $draw_ins = select_query($con, "draw", "", "`id` > '$editID' AND `status` = 'Active' AND `deletes` = 0 ORDER BY `id` ASC", "", "");
                if ($draw_ins['nr'] > 0) {
                    $deleTeQuery = mysqli_query($con, "UPDATE draw SET `deletes` = '1', `status` = 'deleted' where `id` > '$editID' AND `status` = 'Active' AND `deletes` = '0' ORDER BY `id` ASC");
                    if ($deleTeQuery) {
                        goto GVIJs;
                    }
                } else {
                    goto GVIJs;
                }
                GVIJs:
                $getLostDraw = select_query($con, "draw", "", "`deletes` = 0 ORDER BY `id` DESC LIMIT 1", "", "");
                if ($getLostDraw['nr'] > 0) {
                    $ins = [];
                    foreach ($final as $key => $value) {
                        $popUpHtml = '<h3 style="margin-top: 20px; margin-bottom: 10px; font-size: 24px;"><span style="font-family: Arial;"></span><font face="Arial"><span style="font-weight: 700; font-family: Arial;">Oh, Excited ???</span></font></h3><h3 style="margin-top: 20px; margin-bottom: 10px; font-size: 24px;"><font face="Arial"><span style="font-weight: 700;"><br></span></font></h3><h3 style="margin-top: 20px; margin-bottom: 10px; font-size: 24px;"><font face="Arial"><span style="font-weight: 700; font-family: Arial;">Kindly note that now you are entering into our next ' . (isset($drawfreq) && $drawfreq === 4 ? 'Daily Draw ' : 'Tri-Daily Draw ') . ' No: ' . str_pad($final[$key]['drawno'], 3, '0', STR_PAD_LEFT) . '</span></font></h3><h3 style="margin-top: 20px; margin-bottom: 10px; font-size: 24px;"><br style="color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;"></h3>';
                        $draw_arr = [
                            "draw_no" => $final[$key]['drawno'],
                            "name" =>   $final[$key]['drawprefix'] . ' ' . $final[$key]['drawname'],
                            "ticket_start_datetime" => $final[$key]['salestart'],
                            "ticket_end_datetime" => $final[$key]['saleend'],
                            "result_datetime" => $final[$key]['result_time'],
                            "status" => 'Active',
                            "deletes" => '0',
                            "first" => '',
                            "second" => '',
                            "third_one" => '',
                            "third_two" => '',
                            "third_three" => '',
                            "third_four" => '',
                            "permitno" => $getLostDraw['result'][0]['permitno'],
                            "hprize1" => $getLostDraw['result'][0]['hprize1'],
                            "hprize2" => $getLostDraw['result'][0]['hprize2'],
                            "hprize3" =>  $getLostDraw['result'][0]['hprize3'],
                            "createdon" => $dubaidate_time,
                            "popUpHtml" => $popUpHtml,
                            "drawfreq" => $final[$key]['frq'],
                            "prizeRatio" => $getLostDraw['result'][0]['prizeRatio']
                        ];


                        $draw_ins = insert($con, "draw", "", $draw_arr, "", "", "");
                        if ($draw_ins['id'] != '') {
                            $ins[] = $draw_ins['id'];
                        }
                    }
                }
                if (count($final) != count($ins)) {
                    $result['type'] = '0';
                    $result['result'] = 'Could not create draw. Kindly increase End Date';
                    goto resultGHIS;
                } else {
                    $result['type'] = '1';
                    $result['result'] = 'Draw Updated Successfully';

                    goto resultGHIS;
                }
            }
        } else {
            $result['type'] = '0';
            $result['result'] = 'Draw Time Update Failed!';
            goto resultGHIS;
        }
    }

    resultGHIS:
    echo json_encode($result);
} else if ($method == 'getPreviewsContent') {

    $result = [];
    $editID = preg_replace("/[^0-9]/", "", $_POST['id']);
    $newTime = $_POST['newTime'];
    if ($_SESSION['memid'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Login Required';
        goto resultJKHSI;
    }
    if ($editID == '') {
        $result['type'] = '0';
        $result['result'] = 'Edit ID Missing. Kindly Refresh and Try Again!';
        goto resultJKHSI;
    }
    if ($newTime == '') {
        $result['type'] = '0';
        $result['result'] = 'Changed Time Missing Kindly Refresh and Try Again.';
        goto resultJKHSI;
    }

    $draw = select_query($con, "draw", "", "`id` = '$editID' AND `status` = 'Active' AND `deletes` = 0 ORDER BY `id` ASC", "", "");
    if ($draw['nr'] < 1) {
        $result['type'] = '0';
        $result['result'] = 'Draw Not Found Kindly Create New Draw';
        goto resultJKHSI;
    }



    $result['type'] = '1';
    $result['result'] = 'Ticket Sales End Date and Time: ' . date("d M Y h:i a", strtotime($draw['result'][0]['ticket_end_datetime'])) . '<br>
    Replace To: ' . date("d M Y h:i a", strtotime($newTime));
    $result['title'] = 'Are sure you want to Edit Draw #' . $draw['result'][0]['draw_no'];
    goto resultJKHSI;



    resultJKHSI:
    echo json_encode($result);
} else if ($method == 'getPreContent') {

    $result = [];

    $newTime = $_POST['newTime'];
    $editDraw = preg_replace("/[^0-9]/", "", $_POST['id']);
    $final = $_SESSION['drawlist'];
    if ($_SESSION['memid'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Login Required';
        goto result1KHSI;
    }
    if ($editDraw == '') {
        $result['type'] = '0';
        $result['result'] = 'Edit ID Missing. Kindly Refresh and Try Again!';
        goto result1KHSI;
    }
    if ($newTime == '') {
        $result['type'] = '0';
        $result['result'] = 'Changed Time Missing Kindly Refresh and Try Again.';
        goto result1KHSI;
    }
    if ($final == '') {
        $result['type'] = '0';
        $result['result'] = 'Draw List Missing Kindly Refresh and Try Again.';
        goto result1KHSI;
    }




    $result['type'] = '1';
    $result['result'] = 'Ticket Sales End Date and Time: ' . date("d M Y h:i a", strtotime($final[strval($editDraw)]['saleend'])) . '<br>
    Replace To: ' . date("d M Y h:i a", strtotime($newTime));
    $result['title'] = 'Are sure you want to Edit Draw #' . $final[strval($editDraw)]['drawno'];
    goto result1KHSI;



    result1KHSI:
    echo json_encode($result);
} else if ($method == 'resultPreviews') {
    $result = [];
    $editID = preg_replace("/[^0-9]/", "", $_POST['id']);
    $newTime = $_POST['newTime'];
    if ($_SESSION['memid'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Login Required';
        goto result12HSI;
    }
    if ($editID == '') {
        $result['type'] = '0';
        $result['result'] = 'Edit ID Missing. Kindly Refresh and Try Again!';
        goto result12HSI;
    }
    if ($newTime == '') {
        $result['type'] = '0';
        $result['result'] = 'Changed Time Missing Kindly Refresh and Try Again.';
        goto result12HSI;
    }
    $draw = select_query($con, "draw", "", "`id` = '$editID' AND `status` = 'Active' AND `deletes` = 0 ORDER BY `id` ASC", "", "");
    if ($draw['nr'] < 1) {
        $result['type'] = '0';
        $result['result'] = 'Draw Not Found Kindly Create New Draw';
        goto result12HSI;
    }





    $result['type'] = '1';
    $result['result'] = 'Draw Date and Time: ' . date("d M Y h:i a", strtotime($draw['result'][0]['result_datetime'])) . '<br>
    Replace To: ' . date("d M Y h:i a", strtotime($newTime));
    $result['title'] = 'Are sure you want to Edit Draw #' . $draw['result'][0]['draw_no'];
    goto result12HSI;



    result12HSI:
    echo json_encode($result);
} else if ($method == 'UpdateDrawReusltTime') {
    $result = [];
    $editID = preg_replace("/[^0-9]/", "", $_POST['id']);
    $newTime = $_POST['newTime'];
    if ($_SESSION['memid'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Login Required';
        goto result12ASI;
    }
    if ($editID == '') {
        $result['type'] = '0';
        $result['result'] = 'Edit ID Missing. Kindly Refresh and Try Again!';
        goto result12ASI;
    }
    if ($newTime == '') {
        $result['type'] = '0';
        $result['result'] = 'Changed Time Missing Kindly Refresh and Try Again.';
        goto result12ASI;
    }
    $draw = select_query($con, "draw", "", "`id` = '$editID' AND `status` = 'Active' AND `deletes` = 0 ORDER BY `id` ASC", "", "");
    if ($draw['nr'] < 1) {
        $result['type'] = '0';
        $result['result'] = 'Draw Not Found Kindly Create New Draw';
        goto result12ASI;
    }
    $drawid = $draw['result'][0]['id'];
    $resultTime = date('Y-m-d H:i:s', strtotime($newTime));
    $update = mysqli_query($con, "UPDATE `draw` SET `result_datetime` = '$resultTime' where `id` = '$drawid' AND `status` = 'Active' AND `deletes` = 0 ORDER BY `id` ASC;");
    if ($update) {
        $result['type'] = '1';
        $result['result'] = 'Draw Date and Time Updated Successfully';
        goto result12ASI;
    } else {
        $result["type"] = "0";
        $result["result"] = 'Updated Failed';
        goto result12ASI;
    }


    result12ASI:
    echo json_encode($result);
}

<?php



include 'include/shi-config.php';
include 'include/functions.php';

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

class ldC
{
    public $con;
    public function __construct($con)
    {
        $this->con = $con;
    }

    public function checkRole($id)
    {
        $arr = [1, 2, 3, 4, 'G'];
        if (in_array($id, $arr)) {
            return true;
        } else {
            return false;
        }
    }

    public function getAgentName($userID, $type)
    {
        if ($userID > 0) {
            $user = select_query($this->con, "user_register", "", "`id`= '$userID' ORDER BY `id` DESC LIMIT 1", "", "");
            if ($user['nr'] > 0) {
                return $type . ' (' . ucwords(strtolower($user['result'][0]['name'])) . ')';
            } else {
                return $type . ' (NA)';
            }
        } else {
            return $type . ' (NA)';
        }
    }

    public function emailCheck($email)
    {
        if ($email != "") {
            $emailchack = explode('@', $email);
            if (strtolower($emailchack[1]) != "nationaldraw.ae") {
                return $email;
            } else {
                return 'NA';
            }
        } else {
            return 'NA';
        }
    }
}

try {

    require_once __DIR__ . '/mpdf/vendor/autoload.php';
    $ld = new ldC($con);
    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4-P',
        // 'default_font_size' => 14,
        'default_font' => 'nexafont',

    ]);

    $mpdf->AddPage(
        '', // L - landscape, P - portrait
        '',
        '',
        '',
        '',
        10, // margin_left
        10, // margin right
        20, // margin top
        20, // margin bottom
        5, // margin header
        5 // margin footer
    );
    $grandTotal = 0;
    $winnerid = $_GET['drawid'];
    $pid = $_GET['proid'];
    $content = '';
    $sno = 1;
    if ($winnerid != '' && $pid != '') {

        $draw = select_query($con, "draw", "", "`id`= '$winnerid' AND `deletes` = '0' ", "", "");
        $draw_no = '#' . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT);
        $result_datetime = date('d-M-Y', strtotime($draw['result'][0]['result_datetime']));
        $permitno = $draw['result'][0]['permitno'];
        $ttCol = ($ld->checkRole($pid)) ? '6' : '5';

        $content = '<style media="print">

                        #winnerTable th, #winnerTable tr, #winnerTable td {
                            text-align: center;
                            padding: 3px 22px !important;
                            border: 1px solid black;
                        }

                        #titletb {
                            background-color: #0066cc !important;
                            color: #FFFF00 !important;
                            font-weight: 900 !important;

                        }

                        .subTitle {
                            background-color: #bc8f00 !important;
                            color: white !important;
                            font-weight: 600 !important;
                            font-size: 13px;
                        }

                        .content {
                            font-size: 12px!important;
                        }

                        #headtital th, #headtital tr, #headtital td {
                           text-align:center;
                           padding:0px!important;
                           border: none;
                        }
                        .subTitle-2{
                            font-weight: 900 !important;
                            background-color: #bc8f00 !important;
                            color: white !important;
                            line-height: 23px;
                            text-decoration:3px underline;
                            font-family: "Times New Roman", Times, serif;
                        }

                        #date-table{
                            font-size:15px;
                            padding:0px!important;
                        }

                        .fontCom {
                            font-family: "Times New Roman", Times, serif;
                        }
                    </style>

                    <table  align="center" style="width: 100%;  border-collapse: collapse !important; " id="headtital">
                            <tbody>
                                    <tr  align="center">
                                    <td style="width:20%;  ">
                                    <img src="assets/images/mail/logo.png"  align="center" width="130px">
                                    </td>
                                    <td  style="width:80%;">
                                    <table  align="right"  width="100%">
                                    <tbody>
                                    <tr  align="right">
                                    <td  style="width:35%;  "></td>
                                    <td align="right" style=" padding:0px!important;">

                                    <table  width="470px"  id="date-table"  align="right"  style="   border-collapse: collapse !important; ">
                                            <tbody >
                                                <tr style="border-top:1px solid #000!important; border-left:1px solid #000; border-right:1px solid #000;">
                                                    <td  class="fontCom"  style="border-right:1px solid #000;">Draw Date </td>
                                                    <td  class="fontCom"  style="border-right:1px solid #000; width:50%;">' . $result_datetime . '</td>
                                                </tr style=" border-left:1px solid #000; border-right:1px solid #000;">
                                                    <tr  style="border-right:1px solid #000; border-left:1px solid #000;"><td class="fontCom" >Draw No</td>
                                                    <td  class="fontCom" style="border-left:1px solid #000; width:50%;">' . $draw_no . '</td>
                                                    </tr>
                                                <tr style="  border-left:1px solid #000;  border-right:1px solid #000;" >
                                                    <td  class="fontCom" style="border-right:1px solid #000; width:50%;">DET Raffle Permit Number </td>
                                                    <td class="fontCom"  style="">' . $permitno . '</td>
                                                </tr>';
        if ($ld->checkRole($pid)) {
            $content .= '<tr style="border-bottom: 1px solid #000!important;  border-left:1px solid #000; border-right:1px solid #000;" >
                                                    <td  class="fontCom" style="border-right:1px solid #000; width:50%;">Winning Number/Lucky3Number</td>
                                                    <td class="fontCom"  style="">' . $draw['result'][0]['first'] . '</td>
                                                </tr>';
        }

        $content .= '</tbody>
                                    </table>

                                    </td>


                                    </tr>
                                    <tr>
                                    <td class="subTitle-2"  colspan="4" style="  padding: 0px!important;"><strong>LIST OF WINNERS</strong></td>

                                    </tr>
                                    <tr>
                                    <td  colspan="4" style=" padding: 0px!important; color:#fff;" ></td>

                                    </tr>
                                    </tbody>
                                    </table>
                                    </td>
                                    </tr>
                            </tbody>
                    </table>

                    <table  style="width: 100%;  border-collapse: collapse !important;" id="winnerTable">
                        <thead>
                            <tr>
                                <th colspan="' . $ttCol . '" id="titletb">NATIONAL DRAW BEVERAGES TRADING L.L.C</th>
                            </tr>
                            <tr>
                                <th class="subTitle fontCom"><strong>SL.NO</strong></th>
                                <th class="subTitle fontCom"><strong>NAME</strong></th>
                                <th class="subTitle fontCom"><strong>MOBILE NUMBER</strong></th>
                                <th class="subTitle fontCom"><strong>EMAIL ID</strong></th>
                                <th class="subTitle fontCom"><strong>CASH PRIZE (AED)</strong></th>';
        if ($ld->checkRole($pid)) {
            $content .= ' <th class="subTitle fontCom"><strong>TICKET TYPE</strong></th>';
        }
        $content .= '</tr></thead><tbody>';

        $first = $draw['result'][0]['first'];
        $second = $draw['result'][0]['second'];
        $value_arr = array($draw['result'][0]['third_one'], $draw['result'][0]['third_two'], $draw['result'][0]['third_three'], $draw['result'][0]['third_four']);
        foreach ($value_arr as $value) {
            if ($value != '-') {
                $third[] = $value;
            }
        }

        $drawname = $draw['result'][0]['name'];
        $draw_id = $draw['result'][0]['id'];
        $drawdate = date("d M Y", strtotime($draw['result'][0]['result_datetime']));
        $conP = "";
        if ($ld->checkRole($pid)) {
            if ($pid != 'G') {
                $conP = "AND `product_id` = '$pid'";
                $con2p = "`id`= '$pid' AND";
            }
        }

        $product = select_query($con, "product", "", "$con2p `deletes`='0' ORDER BY `id` ASC", "", "");
        if ($product['nr'] > 0) {

            foreach ($product['result'] as $key => $value) {

                $pdfSubTitle = '<tr><td colspan="6" id="titletb">Category AED ' . (int) $value['rate'] . '</td></tr>';
                $pdfContent = '';

                if ($pid == 0) {
                    $conP = "AND `product_id` = '$value[id]'";
                }
                if ($pid == 'G') {
                    $conP = "AND `product_id` = '$value[id]'";
                }

                if ($first != '' && $first != '-') {
                    $lines1 = select_query($con, "ticket_lines", "", "`draw_id` = '$winnerid' AND `my3number` = '$first' AND `deletes` = '0' $conP", "", "");
                    if ($lines1['nr'] > 0) {

                        for ($i = 0; $i < $lines1['nr']; $i++) {

                            $id_i = $lines1['result'][$i]['id'];
                            $uid = $lines1['result'][$i]['user_id'];
                            $proid = $lines1['result'][$i]['product_id'];
                            $ticketID = $lines1['result'][$i]['type'] . $lines1['result'][$i]['ticket_id'];
                            $user = select_query($con, "user_register", "", "`id`= '$uid' AND `deletes` = '0' ", "", "");
                            $product = select_query($con, "product", "", "`id`= '$proid' AND `deletes`='0' ", "", "");
                            $prize_amount = select_top_name($con, "product", "prize_one", "`id`= '$proid' AND `deletes`='0'", "prize_one", "");
                            $pdfContent .= '<tr>
                                            <td class="content fontCom">' . $sno . '</td>
                                            <td class="content fontCom">' . ucwords(strtolower($user['result'][0]['name'])) . '</td>
                                            <td class="content fontCom">' . $user['result'][0]['mobile'] . '</td>
                                            <td class="content fontCom">' . $ld->emailCheck($user['result'][0]['email']) . '</td>
                                            <td class="content fontCom">' . number_format(floatval($prize_amount), 2) . '</td>';
                            if ($ld->checkRole($pid)) {
                                $pdfContent .= '<td class="content fontCom">' . $ld->getAgentName($lines1['result'][$i]['agent_id'], $lines1['result'][$i]['type']) . '</td>';
                            }
                            $pdfContent .= '</tr>';
                            $grandTotal += $prize_amount;
                            $sno++;
                        }
                    }
                }

                if ($second != '' && $second != '-') {

                    $lines2 = select_query($con, "ticket_lines", "", "`draw_id` = '$winnerid' AND `my3number` = '$second' AND `deletes` = '0' $conP", "", "");

                    if ($lines2['nr'] > 0) {

                        for ($i = 0; $i < $lines2['nr']; $i++) {
                            $id_i = $lines2['result'][$i]['id'];
                            $uid = $lines2['result'][$i]['user_id'];
                            $proid = $lines2['result'][$i]['product_id'];
                            $ticketID = $lines2['result'][$i]['type'] . $lines2['result'][$i]['ticket_id'];
                            $user = select_query($con, "user_register", "", "`id`= '$uid' AND `deletes` = '0' ", "", "");

                            $product = select_query($con, "product", "", "`id`= '$proid' AND `deletes`='0' ", "", "");

                            $prize_amount = select_top_name($con, "product", "prize_two", "`id`= '$proid' AND `deletes`='0'", "prize_two", "");

                            $pdfContent .= '<tr>
                                            <td class="content fontCom">' . $sno . '</td>
                                            <td class="content fontCom">' . ucwords(strtolower($user['result'][0]['name'])) . '</td>
                                            <td class="content fontCom">' . $user['result'][0]['mobile'] . '</td>
                                            <td class="content fontCom">' . $ld->emailCheck($user['result'][0]['email']) . '</td>
                                            <td class="content fontCom">' . number_format(floatval($prize_amount), 2) . '</td>';
                            if ($ld->checkRole($pid)) {
                                $pdfContent .= '<td class="content fontCom">' . $ld->getAgentName($lines2['result'][$i]['agent_id'], $lines2['result'][$i]['type']) . '</td>';
                            }
                            $pdfContent .= '</tr>';
                            $grandTotal += $prize_amount;
                            $sno++;
                        }
                    }
                }

                if (count($third) > 0) {

                    $arr = implode(',', $third);

                    $lines3 = select_query($con, "ticket_lines", "", "`draw_id` = '$winnerid' AND `my3number` IN (" . $arr . ") AND `deletes` = '0' $conP", "", "");

                    if ($lines3['nr'] > 0) {

                        for ($i = 0; $i < $lines3['nr']; $i++) {

                            $id_i = $lines3['result'][$i]['id'];

                            $uid = $lines3['result'][$i]['user_id'];

                            $proid = $lines3['result'][$i]['product_id'];

                            $number_3 = $lines3['result'][$i]['my3number'];
                            $ticketID = $lines3['result'][$i]['type'] . $lines3['result'][$i]['ticket_id'];
                            $user = select_query($con, "user_register", "", "`id`= '$uid' AND `deletes` = '0' ", "", "");

                            $product = select_query($con, "product", "", "`id`= '$proid' AND `deletes`='0' ", "", "");

                            $prize_amount = select_top_name($con, "product", "prize_three", "`id`= '$proid' AND `deletes`='0'", "prize_three", "");

                            $pdfContent .= '<tr>
                                            <td class="content fontCom">' . $sno . '</td>
                                            <td class="content fontCom">' . ucwords(strtolower($user['result'][0]['name'])) . '</td>
                                            <td class="content fontCom">' . $user['result'][0]['mobile'] . '</td>
                                            <td class="content fontCom">' . $ld->emailCheck($user['result'][0]['email']) . '</td>
                                            <td class="content fontCom">' . number_format(floatval($prize_amount), 2) . '</td>';
                            if ($ld->checkRole($pid)) {
                                $pdfContent .= '<td class="content fontCom">' . $ld->getAgentName($lines3['result'][$i]['agent_id'], $lines3['result'][$i]['type']) . '</td>';
                            }
                            $pdfContent .= '</tr>';
                            $grandTotal += $prize_amount;
                            $sno++;
                        }
                    }
                }

                if ($pdfContent != '') {
                    $content .= $pdfSubTitle;
                }

                $content .= $pdfContent;
            }
        }

        $content .= '<tr>
                        <td class="subTitle fontCom"></td>
                        <td class="subTitle fontCom" colspan="3"><strong>GRAND TOTAL CASH PRIZE FOR DRAW ' . $draw_no . '</strong></td>
                        <td class="subTitle fontCom"><strong>' . number_format(floatval($grandTotal), 2) . '</strong></td>';
        if ($ld->checkRole($pid)) {
            $content .= '<td class="subTitle fontCom"></td>';
        }
        $content .= '</tr>';

        $content .= '</tbody></table>';

        $mpdf->WriteHTML($content);
        if ($ld->checkRole($pid)) {
            if ($pid == 'G') {
                $namefff = 'Draw' . $draw_no . 'Winners List-LD';
            } else if ($pid == '1') {
                $namefff = 'Draw' . $draw_no . 'Winners List-Category-10AED LD';
            } else if ($pid == '2') {
                $namefff = 'Draw' . $draw_no . 'Winners List-Category-20AED LD';
            } else if ($pid == '3') {
                $namefff = 'Draw' . $draw_no . 'Winners List-Category-50AED LD';
            } else if ($pid == '4') {
                $namefff = 'Draw' . $draw_no . 'Winners List-Category-100AED LD';
            }
        } else {
            $namefff = 'Draw' . $draw_no . 'Winners List-DET';
        }

        // Saves file on the server as 'filename.pdf'
        $mpdf->Output($namefff . '.pdf', \Mpdf\Output\Destination::DOWNLOAD);
    }
} catch (Exception $e) {
    $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
    $cron_testarr = array("user_id" => 0, "reason" => json_encode($error), "filename" => 'acknowledgement.php', "draw_id" => '', "creadedon" => $dubaidate_time);
    $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
}

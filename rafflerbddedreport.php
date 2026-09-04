<?php

/**
 *      Date            Developer     Changes
 *      23-06-2023      Prashant      Raffle Draw Pre-Winner Pdf Genration
 *      21-07-2023      Prakash       1, 2, 3 Prize order has been changed.
 *      4-9-2023        Prakash       The winners report winners name taken by winnerlist table
 * */

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



require_once __DIR__ . '/mpdf/vendor/autoload.php';
$ld = new ldC($con);
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4-P',
    // 'default_font_size' => 14,
    'default_font' => 'nexafont',

]);

$mpdf->AddPage(
    '',
    '',
    '',
    '',
    '',
    10,
    10,
    20,
    20,
    5,
    5
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
    $ttCol = ($ld->checkRole($pid)) ? '9' : '8';

    $content = '<style media="print">
                        .subTitle,.subTitle-2{background-color:#bc8f00!important;color:#fff!important}.fontCom,.subTitle-2{font-family:"Times New Roman",Times,serif}#winnerTable td,#winnerTable th,#winnerTable tr{text-align:center;padding:3px 22px!important;border:1px solid #000}#titletb{background-color:#06c!important;color:#ff0!important;font-weight:900!important}.subTitle{font-weight:600!important;font-size:13px}.content{font-size:12px!important}#headtital td,#headtital th,#headtital tr{text-align:center;padding:0!important;border:none}.subTitle-2{font-weight:900!important;line-height:23px;text-decoration:3px underline}#date-table{font-size:15px;padding:0!important}
                    </style>

                    <table  align="center" style="width: 100%;  border-collapse: collapse !important; " id="headtital" >
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
                                <th class="subTitle fontCom"><strong>EMAIL ID</strong></th>
                                <th class="subTitle fontCom"><strong>MOBILE NUMBER</strong></th>
                                <th class="subTitle fontCom"><strong>RAFFLE ID</strong></th>
                                <th class="subTitle fontCom"><strong>PRIZE CATEGORY</strong></th>
                                <th class="subTitle fontCom"><strong>CASH PRIZE (AED)</strong></th>
                                ';
    if ($ld->checkRole($pid)) {
        $content .= '<th class="subTitle fontCom"><strong>PARTICIPATED CATEGORY</strong></th>
                                <th class="subTitle fontCom"><strong>TICKET TYPE</strong></th>';
    }
    $content .= '</tr></thead><tbody>';

    $first = $draw['result'][0]['raffleprizefirst'];

    $second = $draw['result'][0]['raffleprizesecond'];
    $third = $draw['result'][0]['raffleprizethird'];


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



    $pdfContent = '';



    if ($first != '' && $first != '-') {

        $lines1 = select_query($con, "ticket_lines", "", "`draw_id` = '$winnerid' AND `raffle_id` = '$first' AND `deletes` = '0'", "", "");
        if ($lines1['nr'] > 0) {

            for ($i = 0; $i < $lines1['nr']; $i++) {

                $id_i = $lines1['result'][$i]['id'];
                $uid = $lines1['result'][$i]['user_id'];
                $proid = $lines1['result'][$i]['product_id'];
                $ticketID = $lines1['result'][$i]['type'] . $lines1['result'][$i]['ticket_id'];

                $userName = '';

                $user = select_query($con, "user_register", "", "`id`= '$uid' AND `deletes` = '0' ", "", "");

                // Take Name To Winners 
                $GetWinneruser = select_query($con, "winnerlist", "", "`userid`= '$uid' AND `raffle_id` = '$first' AND `my3number` IS NULL ORDER BY `id` DESC LIMIT 1", "", "");
                if ($GetWinneruser['nr'] > 0) {
                    $userName = $GetWinneruser['result'][0]['name'];
                } else {
                    $userName = $user['result'][0]['name'];
                }

                $product_rate = select_top_name($con, "product", "rate", "`id`= '$proid' AND `deletes`='0' ", "rate", "");
                $prize_amount = select_top_name($con, "product", "prize_one", "`id`= '$proid' AND `deletes`='0'", "prize_one", "");
                $pdfContent .= '<tr>
                                            <td class="content fontCom">' . $sno . '</td>
                                            <td class="content fontCom">' . ucwords(strtolower($userName)) . '</td>
                                            <td class="content fontCom">' . $user['result'][0]['email'] . '</td>
                                            <td class="content fontCom">' . $ld->emailCheck($user['result'][0]['mobile']) . '</td>
                                            <td class="content fontCom">' . ucwords(strtoupper($first)) . '</td>
                                             <td class="content fontCom">' . ucwords('1<sup>st</sup> Prize', 2) . '</td>
                                            <td class="content fontCom">' . number_format($prize_amount, 2) . '</td>
                                           ';

                if ($ld->checkRole($pid)) {
                    $pdfContent .= '<td class="content fontCom">' . number_format($product_rate, 2) . '</td>
                                            <td class="content fontCom">' . $ld->getAgentName($lines1['result'][$i]['agent_id'], $lines1['result'][$i]['type'])  . '</td>';
                }
                $pdfContent .= '</tr>';
                $grandTotal += $prize_amount;
                $sno++;
            }
        }
    }

    if ($second != '' && $second != '-') {

        $lines2 = select_query($con, "ticket_lines", "", "`draw_id` = '$winnerid' AND `raffle_id` = '$second' AND `deletes` = '0'", "", "");
        if ($lines2['nr'] > 0) {

            for ($i = 0; $i < $lines2['nr']; $i++) {
                $id_i = $lines2['result'][$i]['id'];
                $uid = $lines2['result'][$i]['user_id'];
                $proid = $lines2['result'][$i]['product_id'];
                $ticketID = $lines2['result'][$i]['type'] . $lines2['result'][$i]['ticket_id'];

                $userName = '';
                $user = select_query($con, "user_register", "", "`id`= '$uid' AND `deletes` = '0' ", "", "");

                // Take Name To Winners 
                $GetWinneruser = select_query($con, "winnerlist", "", "`userid`= '$uid' AND `raffle_id` = '$second' AND `my3number` IS NULL ORDER BY `id` DESC LIMIT 1", "", "");
                if ($GetWinneruser['nr'] > 0) {
                    $userName = $GetWinneruser['result'][0]['name'];
                } else {
                    $userName = $user['result'][0]['name'];
                }


                $prize_rate = select_top_name($con, "product", "rate", "`id`= '$proid' AND `deletes`='0' ", "rate", "");

                $prize_amount = select_top_name($con, "product", "prize_two", "`id`= '$proid' AND `deletes`='0'", "prize_two", "");

                $pdfContent .= '<tr>
                                            <td class="content fontCom">' . $sno . '</td>
                                            <td class="content fontCom">' . ucwords(strtolower($userName)) . '</td>
                                            <td class="content fontCom">' . $ld->emailCheck($user['result'][0]['email']) . '</td>
                                            <td class="content fontCom">' . $user['result'][0]['mobile'] . '</td>
                                            <td class="content fontCom">' . ucwords(strtoupper($second)) . '</td>
                                            <td class="content fontCom">' . ucwords('2<sup>nd</sup> Prize', 2) . '</td>
                                            <td class="content fontCom">' . number_format($prize_amount, 2) . '</td>
                                            ';
                if ($ld->checkRole($pid)) {
                    $pdfContent .= '<td class="content fontCom">' . number_format($prize_rate, 2) . '</td>
                                            <td class="content fontCom">' . $ld->getAgentName($lines2['result'][$i]['agent_id'], $lines2['result'][$i]['type']) . '</td>';
                }
                $pdfContent .= '</tr>';
                $grandTotal += $prize_amount;
                $sno++;
            }
        }
    }

    if ($third != '' && $third != '-') {

        $lines3 = select_query($con, "ticket_lines", "", "`draw_id` = '$winnerid' AND `raffle_id` = '$third' AND `deletes` = '0'", "", "");
        if ($lines3['nr'] > 0) {

            for ($i = 0; $i < $lines3['nr']; $i++) {

                $id_i = $lines3['result'][$i]['id'];

                $uid = $lines3['result'][$i]['user_id'];

                $proid = $lines3['result'][$i]['product_id'];

                $number_3 = $lines3['result'][$i]['my3number'];
                $ticketID = $lines3['result'][$i]['type'] . $lines3['result'][$i]['ticket_id'];

                $userName = '';
                $user = select_query($con, "user_register", "", "`id`= '$uid' AND `deletes` = '0' ", "", "");

                // Take Name To Winners 
                $GetWinneruser = select_query($con, "winnerlist", "", "`userid`= '$uid' AND `raffle_id` = '$third' AND `my3number` IS NULL ORDER BY `id` DESC LIMIT 1", "", "");
                if ($GetWinneruser['nr'] > 0) {
                    $userName = $GetWinneruser['result'][0]['name'];
                } else {
                    $userName =  $user['result'][0]['name'];
                }


                $product_rate = select_top_name($con, "product", "rate", "`id`= '$proid' AND `deletes`='0' ", "rate", "");

                $prize_amount = select_top_name($con, "product", "prize_three", "`id`= '$proid' AND `deletes`='0'", "prize_three", "");

                $pdfContent .= '<tr>
                                            <td class="content fontCom">' . $sno . '</td>
                                            <td class="content fontCom">' . ucwords(strtolower($userName)) . '</td>
                                            <td class="content fontCom">' . $ld->emailCheck($user['result'][0]['email']) . '</td>
                                            <td class="content fontCom">' . $user['result'][0]['mobile'] . '</td>
                                            <td class="content fontCom">' . ucwords(strtoupper($third)) . '</td>
                                             <td class="content fontCom">' . ucwords('3<sup>rd</sup> Prize', 2) . '</td>
                                            <td class="content fontCom">' . number_format($prize_amount, 2) . '</td>
                                           ';
                if ($ld->checkRole($pid)) {
                    $pdfContent .= '<td class="content fontCom">' . number_format($product_rate, 2) . '</td>
                                            <td class="content fontCom">' . $ld->getAgentName($lines3['result'][$i]['agent_id'], $lines3['result'][$i]['type'])  . '</td>';
                }
                $pdfContent .= '</tr>';
                $grandTotal += $prize_amount;
                $sno++;
            }
        }
    }



    $content .= $pdfContent;


    $content .= '<tr>
                        <td class="subTitle fontCom"></td>
                        <td class="subTitle fontCom" colspan="5"><strong>GRAND TOTAL CASH PRIZE FOR DRAW ' . $draw_no . '</strong></td>
                        <td class="subTitle fontCom"><strong>' . number_format($grandTotal, 2) . '</strong></td>
                        ';

    if ($ld->checkRole($pid)) {
        $content .= '<td class="subTitle fontCom"></td>';
        $content .= '<td class="subTitle fontCom"></td>';
    }
    $content .= '</tr>';

    $content .= '</tbody></table>';

    // var_dump( $content);die;
    $mpdf->WriteHTML($content);
    if ($ld->checkRole($pid)) {
        if ($pid == 'G') {
            $namefff = 'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . '' . str_replace("Draw", "", $naeme[0]) . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ') ' . ' Just3 raffle' . ' Winners List-LD';
        } else if ($pid == '1') {
            $namefff = 'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . '' . str_replace("Draw", "", $naeme[0]) . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ') ' .  ' Just3 raffle' . ' Winners List-Category-10AED LD';
        } else if ($pid == '2') {
            $namefff = 'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . '' . str_replace("Draw", "", $naeme[0]) . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ') ' . ' Just3 raffle' . ' Winners List-Category-20AED LD';
        } else if ($pid == '3') {
            $namefff = 'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . '' . str_replace("Draw", "", $naeme[0]) . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ') ' . ' Just3 raffle' . ' Winners List-Category-50AED LD';
        } else if ($pid == '4') {
            $namefff = 'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . '' . str_replace("Draw", "", $naeme[0]) . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ') ' . ' Just3 raffle' . ' Winners List-Category-100AED LD';
        }
    } else {
        $namefff = 'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . '' . str_replace("Draw", "", $naeme[0]) . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ') ' . ' Just3 raffle' . ' Winners List ALL DET';
    }


    $mpdf->Output($namefff . '.pdf', \Mpdf\Output\Destination::INLINE);
}

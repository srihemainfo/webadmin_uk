<?php

include 'include/shi-config.php';
include 'include/functions.php';

try {

    $subid2 = $_GET['id'];

    

    require_once __DIR__ . '/mpdf/vendor/autoload.php';

    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4-P',
        'default_font_size' => 14,
        'default_font' => 'dejavusans',

    ]);

    $mpdf->SetHTMLHeader('<img src="assets/images/pdf/national_draw_pdf.png"/>');

    $mpdf->SetHTMLFooter('<img src="assets/images/pdf/footer_pdf.png"/>');

    $mpdf->AddPage(
        '', // L - landscape, P - portrait
        '',
        '',
        '',
        '',
        20, // margin_left
        20, // margin right
        40, // margin top
        20, // margin bottom
        10, // margin header
        10
    ); // margin footer

    // var_dump($subid2);die;

    
    $Ticket_lines = select_query($rcon, "superraffledraw", "", "`draw_no`='$subid2' and `deletes`='0'", "", "");

    if ($Ticket_lines['nr'] > 0) {

        foreach ($Ticket_lines['result'] as $key => $value) {

            $start = $value['ticket_start_datetime'];

            $end = $value['ticket_end_datetime'];

            if ($start != '' && $end != '') {
                $formattedStart = date('Y-m-d H:i:s', strtotime($start));
                $formattedEnd = date('Y-m-d H:i:s', strtotime($end));

                // Create the date filter
                $datefilter1 = "`createdon` BETWEEN '$formattedStart' AND '$formattedEnd' AND";
            } else {

                $datefilter1 = "";
            }
        }
    }

    $getSecondRaffleData = select_query($rcon, "ticket_lines", "", "$datefilter1 `deletes`='0' ORDER BY id", "", "");

    $win_raffle_no = select_top_name($rcon, "superraffledraw", "win_raffle_no", "`draw_no`='$subid2'", "win_raffle_no", "");

    $cusname_id = select_top_name($rcon, "ticket_lines", "user_id", "`raffle_id`='$win_raffle_no'", "user_id", "");


    if ($subid2 != '') {
        $winnerlist = select_query($con, "superraffledraw", "", "`draw_no`='$subid2' AND `win_raffle_no`='$win_raffle_no' AND `win_userid`='$cusname_id' ORDER BY `id` DESC LIMIT 1", "", "");

        // var_dump($winnerlist);die;
        if ($winnerlist['nr'] > 0) {
            $draw_id = $winnerlist['result'][0]['draw_no'];

            
            
            $user_id = $winnerlist['result'][0]['win_userid'];
            $ticket_lines_id = $winnerlist['result'][0]['win_ticket_line_id'];

            if (intval($winnerlist['result'][0]['wonprize']) == 1) {
                $Matched = "1st Raffle number (" . intval($winnerlist['result'][0]['wonprize']) . "<sup>st</sup> Prize)";
            } else if (intval($winnerlist['result'][0]['wonprize']) == 2) {
                $Matched = "2nd Raffle number (" . intval($winnerlist['result'][0]['wonprize']) . "<sup>nd</sup> Prize)";
            } else if (intval($winnerlist['result'][0]['wonprize']) == 3) {
                $Matched = "3rd Raffle number (" . intval($winnerlist['result'][0]['wonprize']) . "<sup>rd</sup> Prize)";
            }
            

            $draw = select_query($con, "superraffledraw", "", "`id`='$subid2' AND `status` != 'Active' ORDER BY `id` DESC LIMIT 1", "", "");

            $draw1 = select_query($con, "superraffledraw", "", "`id`='$subid2' AND `status` != 'Active' ORDER BY `id` DESC LIMIT 1", "", "");
            //var_dump($draw1);die;
            if ($draw1['nr'] > 0) {

                $result_datetime = $draw1['result'][0]['result_datetime'];
                $draw1 = select_query($con, "draw", "", "`result_datetime`='$result_datetime' ORDER BY `id` DESC LIMIT 1", "", "");

                if ($draw1['nr'] > 0) {

                    $draw_name = $draw1['result'][0]['name'];
                    $draw_no = $draw1['result'][0]['draw_no'];
                }

            // var_dump($draw_no);die;

                $ticket_lines = select_query($con, "ticket_lines", "", "`id`='$ticket_lines_id' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
                if ($ticket_lines['nr'] > 0) {
                    $user_register = select_query($con, "user_register", "", "`id`='$user_id' and `deletes`='0' and `roll_id` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
                    if ($user_register['nr'] > 0) {
                        $product_id = $ticket_lines['result'][0]['product_id'];
                        $product_amt = select_top_name($con, "product", "rate", "`id`='$product_id' and `deletes`='0'  order by `id` DESC ", "rate", "");

                        $content = '';

                        $content .= '
                        <style media="print">
                                .f-ter {
                                    background: #fbd510;
                                }

                                .f-ter p {
                                    color: #253781;
                                    margin: 0;
                                    font-weight: 500;

                                }
                                .key{
                                width: 40%;
                                font-size:12px;
                                font-weight: bold;
                                }
                                .colon{
                                    width: 10%;
                                }
                                .value{
                                    width: 40%;
                                    font-size:12px;
                                }
                                p{
                                    font-size:12px;
                                }
                                a{
                                    color: black;
                                    text-decoration: none;
                                    font-weight: bold;
                                }
                                .l3bor{
                                    border: 2px solid black;
                                }
                            </style>
                            </head>
                            <table style="width: 100%;">';
                        // $content .= '<tr>
                        // <td colspan="3"><img src="' . $baseurl . 'assets/images/pdf/national_draw_pdf.png' . '" width="100%" alt=""></td>
                        // </tr>';
                        $drawName = explode(' ', $draw1['result'][0]['name']);
                        if (isset($drawName)) {
                            $drawName = $drawName[0] . ' (' . date('D', strtotime($draw['result'][0]['result_datetime'])) . ')  – Super Raffle '.'#'.str_pad($draw['result'][0]['draw_no'], 3, '0', STR_PAD_LEFT);
                        } else {
                            $drawName = '';
                        }

                        $content .= '<tr>
                            <td colspan="3" style="text-align: center; padding-top: 25px; padding-bottom: 25px;" >

                            <h4>CASH PRIZE RECEIPT FORM</h4>

                            </td>
                            </tr>

                            <tr>
                                <td class="key">Name of Participant</td>
                                <td class="colon">:</td>
                                <td class="value">' . ucwords($user_register['result'][0]['name'].' '.$user_register['result'][0]['lname']) . ' </td>
                            </tr>
                            <tr>
                                <td class="key">Country</td>
                                <td class="colon">:</td>
                                <td class="value">' . ucwords($user_register['result'][0]['nationality']) . '</td>
                            </tr>
                            <tr>
                                <td class="key">Emirates ID/Passport No</td>
                                <td class="colon">:</td>
                                <td class="value">' . strtoupper($user_register['result'][0]['passport']) . '</td>
                            </tr>
                            <tr>
                                <td class="key">Contact Number</td>
                                <td class="colon">:</td>
                                <td class="value">' . intval($user_register['result'][0]['mobile']) . '</td>
                            </tr>
                            <tr>
                                <td class="key">Draw Name</td>
                                <td class="colon">:</td>
                                <td class="value">' . $drawName . '</td>
                            </tr>
                            <tr>
                                <td class="key">Draw No & Date </td>
                                <td class="colon">:</td>
                                <td class="value">#' . str_pad($draw['result'][0]['draw_no'], 3, '0', STR_PAD_LEFT) . ' & ' . date('d.m.Y', strtotime($draw['result'][0]['result_datetime'])) . '</td>
                            </tr>
                            <tr>
                                <td class="key">Ticket ID </td>
                                <td class="colon">:</td>
                                <td class="value">' . $ticket_lines['result'][0]['type'].' '.$ticket_lines['result'][0]['ticket_id'] . '</td>
                            </tr>
                            <tr>
                                <td class="key">Raffle ID</td>
                                <td class="colon">:</td>
                                <td class="value">' . $ticket_lines['result'][0]['raffle_id'] . '</td>
                            </tr>
                            <tr>
                                <td class="key">Winning Raffle ID</td>
                                <td class="colon">:</td>
                                <td class="value">' . $ticket_lines['result'][0]['raffle_id'] . '</td>
                            </tr>
                           
                            <tr>
                                <td class="key">Participated Category</td>
                                <td class="colon">:</td>
                                <td class="value">AED ' . number_format(intval($product_amt), 2) . '/-</td>
                            </tr>

                            <tr>
                            <td class="key">Won Przie</td>
                            <td class="colon">:</td>
                            <td class="value">AED ' . number_format(intval( $draw['result'][0]['wonprize']), 2) . '/-</td>
                           </tr>

                            <tr>
                            <td colspan="3" style="padding-top: 25px;">
                            <p>I hereby confirm that I had participated on the above-mentioned draw and won the cash prize which has
                            been collected from National Draw. Also, I don’t have any objection to use my personal information and
                            Pictures or Videos by National Draw for their further promotional activities.</p>
                            </td>
                            </tr>

                            <tr>
                            <td colspan="3" style="text-align:right; padding-top: 70px;">
                            <p>Participant Signature</p>
                            </td>
                            </tr>';

                        $content .= '</table>';

                        $mpdf->WriteHTML($content);

                        // <tr>
                        //         <td class="key">Won Prize</td>
                        //         <td class="colon">:</td>
                        //         <td class="value">AED ' . number_format($winnerlist['result'][0]['prize_amt']) . '/-</td>
                        //     </tr>
                        // Saves file on the server as 'filename.pdf'
                        $mpdf->Output('filename.pdf', \Mpdf\Output\Destination::INLINE);
                    }
                }
            }
        }
    }
} catch (Exception $e) {
    echo "Error";
}

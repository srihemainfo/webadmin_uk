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

    // var_dump($subid2);
    // die;
    $mpdf->SetHTMLHeader('<div style="text-align: center;"><img src="assets/images/in-logo.png" /></div>');

    // $mpdf->SetHTMLHeader('<img src="assets/images/in-logo.png"/>');

    // $footer = '<div style="text-align:center; padding: 15px; background-color: yellow;">
    // 

    //                         <p>
    //                         <svg xmlns="http://www.w3.org/2000/svg" align="center" width="10" height="10" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
    //                         <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
    //                         </svg>
    //                         <a href="tel:+97143398880">+971 4 33 98880</a>&nbsp;
    //                         <svg xmlns="http://www.w3.org/2000/svg" align="center" width="10" height="10" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
    //                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
    //                         </svg>&nbsp;
    //                        <a href = "mailto: support@littledraw.com">support@littledraw.com</a>&nbsp;
    //                        <svg xmlns="http://www.w3.org/2000/svg" align="center" width="10" height="10" fill="currentColor" class="bi bi-globe2" viewBox="0 0 16 16">
    //                         <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855-.143.268-.276.56-.395.872.705.157 1.472.257 2.282.287V1.077zM4.249 3.539c.142-.384.304-.744.481-1.078a6.7 6.7 0 0 1 .597-.933A7.01 7.01 0 0 0 3.051 3.05c.362.184.763.349 1.198.49zM3.509 7.5c.036-1.07.188-2.087.436-3.008a9.124 9.124 0 0 1-1.565-.667A6.964 6.964 0 0 0 1.018 7.5h2.49zm1.4-2.741a12.344 12.344 0 0 0-.4 2.741H7.5V5.091c-.91-.03-1.783-.145-2.591-.332zM8.5 5.09V7.5h2.99a12.342 12.342 0 0 0-.399-2.741c-.808.187-1.681.301-2.591.332zM4.51 8.5c.035.987.176 1.914.399 2.741A13.612 13.612 0 0 1 7.5 10.91V8.5H4.51zm3.99 0v2.409c.91.03 1.783.145 2.591.332.223-.827.364-1.754.4-2.741H8.5zm-3.282 3.696c.12.312.252.604.395.872.552 1.035 1.218 1.65 1.887 1.855V11.91c-.81.03-1.577.13-2.282.287zm.11 2.276a6.696 6.696 0 0 1-.598-.933 8.853 8.853 0 0 1-.481-1.079 8.38 8.38 0 0 0-1.198.49 7.01 7.01 0 0 0 2.276 1.522zm-1.383-2.964A13.36 13.36 0 0 1 3.508 8.5h-2.49a6.963 6.963 0 0 0 1.362 3.675c.47-.258.995-.482 1.565-.667zm6.728 2.964a7.009 7.009 0 0 0 2.275-1.521 8.376 8.376 0 0 0-1.197-.49 8.853 8.853 0 0 1-.481 1.078 6.688 6.688 0 0 1-.597.933zM8.5 11.909v3.014c.67-.204 1.335-.82 1.887-1.855.143-.268.276-.56.395-.872A12.63 12.63 0 0 0 8.5 11.91zm3.555-.401c.57.185 1.095.409 1.565.667A6.963 6.963 0 0 0 14.982 8.5h-2.49a13.36 13.36 0 0 1-.437 3.008zM14.982 7.5a6.963 6.963 0 0 0-1.362-3.675c-.47.258-.995.482-1.565.667.248.92.4 1.938.437 3.008h2.49zM11.27 2.461c.177.334.339.694.482 1.078a8.368 8.368 0 0 0 1.196-.49 7.01 7.01 0 0 0-2.275-1.52c.218.283.418.597.597.932zm-.488 1.343a7.765 7.765 0 0 0-.395-.872C9.835 1.897 9.17 1.282 8.5 1.077V4.09c.81-.03 1.577-.13 2.282-.287z"/>
    //                         </svg>
    //                         <a href = "' . $baseurl . '">www.littledraw.com</a>
    //                         </p>
    // </div>';

    $mpdf->SetHTMLFooter('<img src="assets/images/nd-f.png"/>');

    $mpdf->AddPage(
        '', // L - landscape, P - portrait
        '',
        '',
        '',
        '',
        20, // margin_left
        20, // margin right
        30, // margin top
        20, // margin bottom
        10, // margin header
        10
    ); // margin footer

    if ($subid2 != '') {
        
        $winnerlist = select_query($con, "winnerlist", "", "`id`='$subid2' ORDER BY `id` DESC LIMIT 1", "", "");
        if ($winnerlist['nr'] > 0) {
            $draw_id = $winnerlist['result'][0]['draw_id'];
            $user_id = $winnerlist['result'][0]['userid'];
            
            $DrawName = $winnerlist['result'][0]['winningDrawName'];
            
            $ticket_lines_id = $winnerlist['result'][0]['ticket_lines_id'];
            
            // var_dump($subid2,$draw_id,$user_id,$ticket_lines_id,$DrawName);die;

            if (intval($winnerlist['result'][0]['prize']) == 1) {
                $Matched = "STRAIGHT (" . intval($winnerlist['result'][0]['prize']) . "<sup>st</sup> Prize)";
            } else if (intval($winnerlist['result'][0]['prize']) == 2) {
                $Matched = "REVERSE (" . intval($winnerlist['result'][0]['prize']) . "<sup>nd</sup> Prize)";
            } else if (intval($winnerlist['result'][0]['prize']) == 3) {
                $Matched = "MIX (" . intval($winnerlist['result'][0]['prize']) . "<sup>rd</sup> Prize)";
            }
            
            $DrawName = $_GET['DrawName'];
            // var_dump($DrawName);die;
           
            if($DrawName =="dailyThrill"){
                
                
                
                $draw = select_query($con, "draw", "", "`id`='$draw_id' AND `dailyThrillName`='Daily Thrill' AND `dailyThirllStatus` = 'Completed' ORDER BY `id` DESC LIMIT 1", "", "");
                
                
            }else if($DrawName =="weeklyBooster"){
               
                $draw = select_query($con, "draw", "", "`id`='$draw_id' AND `weeklyBoosterName`='Weekly Booster' AND `weeklyBoosterStatus` = 'Completed' ORDER BY `id` DESC LIMIT 1", "", "");
              
                
                
            }else if($DrawName =="monthlyBumper"){
                
                
         
                
                $draw = select_query($con, "draw", "", "`id`='$draw_id' AND `monthlyBumperName`='Monthly Bumper' AND `monthlyBumperStatus` = 'Completed' ORDER BY `id` DESC LIMIT 1", "", "");
                
                // var_dump('wwdew');die;
            }
            

            

            
            // $draw = select_query($con, "draw", "", "`id`='$draw_id' AND `status` != 'Active' ORDER BY `id` DESC LIMIT 1", "", "");
            
            if ($draw['nr'] > 0) {
                // $ticket_lines = select_query($con, "ticket_lines", "", "`id`='$ticket_lines_id' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
                // if ($ticket_lines['nr'] > 0) {
                
                    $user_register = select_query($con, "user_register", "", "`id`='$user_id' and `deletes`='0'  ORDER BY `id` DESC LIMIT 1", "", "");
                    
                    if ($user_register['nr'] > 0) {
                        $ticketfef = $winnerlist['result'][0]['ticketReferenceID'];
                        
                        $product_id = $ticket_lines['result'][0]['product_id'];
                        $product_amt = select_top_name($con, "product", "rate", "`id`='$product_id' and `deletes`='0'  order by `id` DESC ", "rate", "");
                        
                        $ticket_NO = select_top_name($con, "ndticket", "ticketNo", "`referenceID`='$ticketfef' and `deletes`='0'  ", "ticketNo", "");
                      

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
                        // <td colspan="3"><img src="' . $baseurl . 'assets/images/pdf/little_draw_pdf.png' . '" width="100%" alt=""></td>
                        // </tr>';
                        $content .= '<tr>
                            <td colspan="3" style="text-align: center; padding-top: 25px; padding-bottom: 25px;" >

                            <h4>GOLD PRIZE RECEIPT FORM</h4>

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
                                <td class="value">' . ucwords($user_register['result'][0]['residinglocation']) . '</td>
                            </tr>
                            <tr>
                                <td class="key">Nationality</td>
                                <td class="colon">:</td>
                                <td class="value">' . ucwords($user_register['result'][0]['nationality']) . '</td>
                            </tr>
                            <tr>
                                <td class="key">Emirates ID(or) Voter ID/Passport No</td>
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
                                <td class="value">' . ucwords($DrawName) . '</td>
                            </tr>
                            <tr>
                                <td class="key">Draw No / Draw Date </td>
                                <td class="colon">:</td>
                                <td class="value">#' . str_pad($draw['result'][0]['dailyDrawNo'], 3, '0', STR_PAD_LEFT) .' - ' . date('d.m.Y', strtotime($draw['result'][0]['resultDate'])) . '</td>
                            </tr>
                           
                            <tr>
                                <td class="key">Raffle ID </td>
                                <td class="colon">:</td>
                                <td class="value">' . $winnerlist['result'][0]['winRaffleId'] . '</td>
                            </tr>
                            <tr>
                                <td class="key">Ticket ID </td>
                                <td class="colon">:</td>
                                <td class="value">' . $ticket_NO . '</td>
                            </tr>
                            
                             <tr>
                                <td class="key">Won Prize in Gold</td>
                                <td class="colon">:</td>
                                <td class="value">' . number_format($winnerlist['result'][0]['prize']) . '  Gram</td>
                            </tr>
                            
                            <tr>
                                <td class="key">Gold Rate on Winning Day</td>
                                <td class="colon">:</td>
                                <td class="value">' . $draw['result'][0]['todayGoldPrize'] . ' (1 Gram)</td>
                            </tr>
                            
                            <tr>
                                <td class="key">Equivalent of Gold Prize</td>
                                <td class="colon">:</td>
                                <td class="value">' . $winnerlist['result'][0]['prize_amt'] . ' (' . number_format($winnerlist['result'][0]['prize']) . '  Gram value)</td>
                            </tr>

                            <tr>
                            <td colspan="3" style="padding-top: 25px;">
                            <p>I hereby confirm that I had participated on the above-mentioned draw and won the Gold Coin/Gold Bar prize which has
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
                        // Saves file on the server as 'filename.pdf'
                        $mpdf->Output(md5(time() . $subid2) . '.pdf', \Mpdf\Output\Destination::INLINE);
                    }
                // } else {
                //     echo 'Ticket Lines Missing!';
                // }
            } else {
                echo 'Draw Missing!';
            }
        }
    }
} catch (Exception $e) {
    $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
    $cron_testarr = array("user_id" => 0, "reason" => json_encode($error), "filename" => 'acknowledgement.php', "draw_id" => '', "creadedon" => $dubaidate_time);
    $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
}

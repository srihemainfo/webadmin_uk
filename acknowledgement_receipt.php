<?php

include 'include/shi-config.php';
include 'include/functions.php';

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

try {

    $subid2 = $_REQUEST['id'];



    require_once __DIR__ . '/mpdf/vendor/autoload.php';

    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4-P',
        'default_font_size' => 14,
        'default_font' => 'dejavusans',

    ]);

    $mpdf->SetHTMLHeader('<img src="assets/images/pdf/national_draw_pdf.png"/>');

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
    //                        <a href = "mailto: support@nationaldraw.com">support@nationaldraw.com</a>&nbsp;
    //                        <svg xmlns="http://www.w3.org/2000/svg" align="center" width="10" height="10" fill="currentColor" class="bi bi-globe2" viewBox="0 0 16 16">
    //                         <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855-.143.268-.276.56-.395.872.705.157 1.472.257 2.282.287V1.077zM4.249 3.539c.142-.384.304-.744.481-1.078a6.7 6.7 0 0 1 .597-.933A7.01 7.01 0 0 0 3.051 3.05c.362.184.763.349 1.198.49zM3.509 7.5c.036-1.07.188-2.087.436-3.008a9.124 9.124 0 0 1-1.565-.667A6.964 6.964 0 0 0 1.018 7.5h2.49zm1.4-2.741a12.344 12.344 0 0 0-.4 2.741H7.5V5.091c-.91-.03-1.783-.145-2.591-.332zM8.5 5.09V7.5h2.99a12.342 12.342 0 0 0-.399-2.741c-.808.187-1.681.301-2.591.332zM4.51 8.5c.035.987.176 1.914.399 2.741A13.612 13.612 0 0 1 7.5 10.91V8.5H4.51zm3.99 0v2.409c.91.03 1.783.145 2.591.332.223-.827.364-1.754.4-2.741H8.5zm-3.282 3.696c.12.312.252.604.395.872.552 1.035 1.218 1.65 1.887 1.855V11.91c-.81.03-1.577.13-2.282.287zm.11 2.276a6.696 6.696 0 0 1-.598-.933 8.853 8.853 0 0 1-.481-1.079 8.38 8.38 0 0 0-1.198.49 7.01 7.01 0 0 0 2.276 1.522zm-1.383-2.964A13.36 13.36 0 0 1 3.508 8.5h-2.49a6.963 6.963 0 0 0 1.362 3.675c.47-.258.995-.482 1.565-.667zm6.728 2.964a7.009 7.009 0 0 0 2.275-1.521 8.376 8.376 0 0 0-1.197-.49 8.853 8.853 0 0 1-.481 1.078 6.688 6.688 0 0 1-.597.933zM8.5 11.909v3.014c.67-.204 1.335-.82 1.887-1.855.143-.268.276-.56.395-.872A12.63 12.63 0 0 0 8.5 11.91zm3.555-.401c.57.185 1.095.409 1.565.667A6.963 6.963 0 0 0 14.982 8.5h-2.49a13.36 13.36 0 0 1-.437 3.008zM14.982 7.5a6.963 6.963 0 0 0-1.362-3.675c-.47.258-.995.482-1.565.667.248.92.4 1.938.437 3.008h2.49zM11.27 2.461c.177.334.339.694.482 1.078a8.368 8.368 0 0 0 1.196-.49 7.01 7.01 0 0 0-2.275-1.52c.218.283.418.597.597.932zm-.488 1.343a7.765 7.765 0 0 0-.395-.872C9.835 1.897 9.17 1.282 8.5 1.077V4.09c.81-.03 1.577-.13 2.282-.287z"/>
    //                         </svg>
    //                         <a href = "' . $baseurl . '">www.nationaldraw.com</a>
    //                         </p>
    // </div>';

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

    if ($subid2 != '') {
        $with_his = select_query($con, "withdraw_request", "", "`id`='$subid2' AND `deletes`='0' ORDER BY `id` DESC", "", "");

        // $draw_id = $winnerlist['result'][0]['draw_id'];
        if ($with_his['nr'] > 0) {

            foreach ($with_his['result'] as $key => $value) {
                $from_name = select_top_name($con, "user_register", "name", "`id`='$value[from_id]' and `deletes`='0'", "name", "");
                $from_mobile = select_top_name($con, "user_register", "mobile", "`id`='$value[from_id]' and `deletes`='0'", "mobile", "");

                $content = '';

                $content .= '
                        <style media="print">

                        table,tr,td {
                            font-family: "Times New Roman", Times, serif;
                          }
                          table tbody,tr,td {
                            font-family: "Times New Roman", Times, serif;
                           
                          }
                          table tbody,tr,td, p {
                            font-family: "Times New Roman", Times, serif;
                          }


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
                                .alignstyle{
                                    padding:0 0 4px 15px!important;
                                }
                            </style>
                            </head>
                            <table style="width: 100%;">';
                // $content .= '<tr>
                // <td colspan="3"><img src="' . $baseurl . 'assets/images/pdf/national_draw_pdf.png' . '" width="100%" alt=""></td>
                // </tr>';
                $content .= '<tr>
                        <td colspan="3" style="text-align: left; padding-top: 30px; padding-bottom: 20px;">
                        <h6><b>To,</b></h6>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Al Ansari Exchange,</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; U.A.E.</p>
                        </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: left; padding-top: 15px; padding-bottom: 15px;font-weight:800;">
                            <p style=" text-decoration: underline; letter-spacing: 1px;"><b>Sub: Request to Transfer Cash Prize of AED <span style="font-size:13px;"> ' . number_format($value['amount']) . '</span> to Mr/MS. <span style="font-size:13px;">' . ucwords($from_name) . ' </span></b></p>
                            </td>
                            </tr>
                            <tr>
                            <td colspan="3" style="text-align: left; padding-top: 10px; padding-bottom: 10px;">
                            <p>  We, National Draw Beverages Trading LLC, conducting a promotional campaign to sell its product through online and store. Customers who are having an interest as they can buy our products and participate in our free weekly draw.</p>
                            </td>
                            </tr>
                            <tr>
                            <td colspan="3" style="text-align: left; padding-top: 10px; padding-bottom: 10px;">
                            <p> The subjected person participated in our free Weekly Raffle Draw <span style=" text-decoration: underline;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> dated on <span style=" text-decoration: underline;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> and won  cash prize. Herewith enclosed copy of ticket for your reference and record. </p>
                            </td>
                            </tr>
                            <tr>
                            <td colspan="3" style="text-align: left; padding-top: 15px; padding-bottom: 15px;">
                            <p> You are requested to transfer the cash prize for the following details.</p>
                            </td>
                            </tr>
                            <tr>
                            <td>
                            <table stle="margin:0px; padding:0px;">
                            <tbody>
                            <tr>
                            <td class="key" style=" text-decoration: underline; ">Bank Details: -</td>

                        </tr>
                        <br>
                        <br>
                        <tr>
                            <td class="key">Account Holder Name</td>
                            <td class="colon">:</td>
                            <td class="value">' . ucwords($value['achname']) . '</td>
                        </tr>
                        <tr>
                        <td class="key">Mobile Number</td>
                        <td class="colon">:</td>
                        <td class="value">' . $from_mobile . '</td>
                        </tr>
                        <tr>
                        <td class="key">Country</td>
                        <td class="colon">:</td>
                        <td class="value">' . ucwords($value['nationality']) . '</td>
                        </tr>
                        <tr>
                            <td class="key">Amount</td>
                            <td class="colon">:</td>
                            <td class="value">' . intval($value['amount']) . '</td>
                        </tr>
                        <tr>
                            <td class="key">Bank Name</td>
                            <td class="colon">:</td>
                            <td class="value">' . ucwords($value['bank_name']) . ' </td>
                        </tr>
                        <tr>
                            <td class="key">Branch Name</td>
                            <td class="colon">:</td>
                            <td class="value">' . ucwords($value['branch_name']) . ' </td>
                        </tr>
                        <tr>
                            <td class="key">Branch Code</td>
                            <td class="colon">:</td>
                            <td class="value">' . ucwords($value['branch_code']) . ' </td>
                        </tr>
                        
                        <tr>
                            <td class="key">Account Type</td>
                            <td class="colon">:</td>
                            <td class="value">' . ucwords($value['acctype']) . '</td>
                        </tr>
                        <tr>
                            <td class="key">Account Number </td>
                            <td class="colon">:</td>
                            <td class="value">' . intval($value['acc_no']) . '</td>
                        </tr>
                        <tr>
                            <td class="key">IBAN/IFSC Code </td>
                            <td class="colon">:</td>
                            <td class="value">' . ucwords($value['iban_code']) . ' </td>
                        </tr>
                        <tr>
                            <td class="key">SWIFT Code</td>
                            <td class="colon">:</td>
                            <td class="value">' . ucwords($value['swiftcode']) . '</td>
                        </tr>
                        <tr>
                            <td class="key">Currency Code</td>
                            <td class="colon">:</td>
                            <td class="value">' . ucwords($value['currencyccode']) . '</td>
                        </tr>
                       

                            </tbody>
                            </table>

                            </td>
                            </tr>

                           
                            <tr>
                            <td colspan="3" style="text-align:right; padding-top: 80px;">
                            <p style="font-size:17px;"><b>Authorized Signature</b></p>
                            </td>
                            </tr>';

                $content .= '</table>';

                $mpdf->WriteHTML($content);
                // Saves file on the server as 'filename.pdf'
                $mpdf->Output('filename.pdf', \Mpdf\Output\Destination::INLINE);
            }
        }
    }
} catch (Exception $e) {
    $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
    $cron_testarr = array("user_id" => 0, "reason" => json_encode($error), "filename" => 'acknowledgement.php', "draw_id" => '', "creadedon" => $dubaidate_time);
    $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
}

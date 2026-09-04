<?php



// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


include 'include/shi-config.php';

include 'include/functions.php';



require_once "xlsx/Classes/PHPExcel.php";


ini_set("pcre.backtrack_limit", "100000000");





class ldC

{

    public $con;

    public function __construct($con)

    {

        $this->con = $con;
    }



    public function checkRole($id)

    {

        $arr = [1, 2, 3, 4, 'G', 'GALL'];

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





    public function ticket_no($tablename, $ticket_id)
    {



        $value =  '';

        $nquery = "SELECT tn.ticket_no as ticket_no FROM $tablename AS tn WHERE tn.id = '$ticket_id' AND tn.deletes = '0' ORDER BY `tn`.`id` DESC LIMIT 1";





        $getQuery  =  mysqli_query($this->con, $nquery);

        $nrows = mysqli_num_rows($getQuery);





        if ($nrows > 0) {

            $userTicket = mysqli_fetch_assoc($getQuery);

            $value =  $userTicket['ticket_no'];
        }

        return $value;
    }
}















if ($_REQUEST['reportType'] == 'pdf') {

    try {



        require_once __DIR__ . '/mpdf/vendor/autoload.php';

        $ld = new ldC($con);

        $mpdf = new \Mpdf\Mpdf([

            'mode' => 'UTF-8',

            'format' => 'A4-P',

            // 'default_font_size' => 14,

            'default_font' => 'nexafont',



        ]);



        $mpdf->AddPage(

            'L', // L - landscape, P - portrait

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

        $grandTotal = '';

        $winnerid = $_GET['drawid'];

        $pid = $_GET['proid'];

        $content = '';

        $sno = 1;



        $grandTotal = '';
        settype($grandTotal, "integer");

        $winnerid = $_GET['drawid'];

        $pid = $_GET['proid'];

        $content = '';

        $sno = 1;

        $result = [];

        $type_Con = '';

        $draw_Con = '';

        $tablename = '';



        $draw_new_id = $_GET['drawid'];

        $ticket_name = $_GET['ticket_name'];

        $now = date('Y-m-d');

        $agdate = $_GET['agdate'];

        $tableMapping = [

            'MT' => 'mticket',

            'AT' => 'aticket',

            'OT' => 'ticket',

            'WT' => ($draw_new_id > 33) ? 'aticket' : 'ticket',

            'FT' => 'fticket',

            'CT' => 'cticket',

            'BP' => 'bpticket',

            'CP' => 'cpticket',
            'KT' => 'kticket',

        ];

        $draw = select_query($con, "draw", "", "`id`= '$winnerid' AND `deletes` = '0' ", "", "");

        $draw_no = '#' . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT);

        $result_datetime = date('d-M-Y', strtotime($draw['result'][0]['result_datetime']));

        $permitno = $draw['result'][0]['permitno'];

        $ttCol = ($ld->checkRole($pid)) ? '9' : '7';



        if ($agdate != '') {

            $newDD = date("Y-m-d", strtotime($agdate));

            $datefilter = "`tl`.`createdon` BETWEEN '$newDD 00:00:00' AND '$newDD 23:59:59' AND";
        } else {

            $datefilter = "";
        }









        if (isset($tableMapping[$ticket_name])) {

            $tablename = $tableMapping[$value['type']];
        }





        if ($draw_new_id != '') {

            $draw_Con = "`tl`.`draw_id` = '$draw_new_id' AND";
        }



        if ($ticket_name != '') {

            $type_Con = "`tl`.`type` = '$ticket_name' AND";
        }











        $queryNew = "SELECT tl.type, tl.id, tl.user_id, tl.product_id, tl.raffle_id, tl.ticket_id, p.rate, p.prize_one, ur.name, ur.mobile, ur.email

                        FROM ticket_lines AS tl

                        JOIN product AS p ON tl.product_id = p.id

                        JOIN user_register AS ur ON tl.user_id = ur.id

                        WHERE $draw_Con $type_Con $datefilter tl.deletes = '0' AND ur.deletes = '0' order by rand();";
        //exit;

        // var_dump($queryNew);
        $result121121 = mysqli_query($con, $queryNew);



        if ($result121121) {

            $numRows = mysqli_num_rows($result121121);

            if ($numRows > 0) {



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

                                        <td  style="width:35%;"></td>

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





                $content .= '</tbody></table></td></tr><tr><td class="subTitle-2"  colspan="4" style="padding: 0px!important;"><strong>LIST OF PARTICIPANTS</strong></td></tr><tr><td  colspan="4" style=" padding: 0px!important; color:#fff;" ></td></tr></tbody></table></td></tr></tbody></table>



                        <table  style="width: 100%;  border-collapse: collapse !important;" id="winnerTable">

                            <thead>';

                // $content .= '<tr><th colspan="' . $ttCol . '" id="titletb">NATIONAL DRAW BEVERAGES TRADING L.L.C</th></tr>';
                $content .= '<tr><th colspan="' . $ttCol . '" id="titletb" >DRAW NUMBER <span class="fontCom"><strong>#</strong></span>' . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' , Dated ' . date('d', strtotime($draw['result'][0]['result_datetime'])) . '<span class="fontCom"><strong>-</strong></span>' . date('M', strtotime($draw['result'][0]['result_datetime'])) .  '<span class="fontCom"><strong>-</strong></span>' . date('Y', strtotime($draw['result'][0]['result_datetime'])) . ' <span class="fontCom"><strong>(</strong></span>' . date('D', strtotime($draw['result'][0]['result_datetime'])) . '<span class="fontCom"><strong>)</strong></span></th></tr>';
                $content .= '<tr>

                                    <th class="subTitle fontCom"><strong>SERIAL NUMBER</strong></th>

                                    <th class="subTitle fontCom"><strong>TICKET ID</strong></th>

                                    <th class="subTitle fontCom"><strong>CUSTOMER NAME</strong></th>

                                    <th class="subTitle fontCom"><strong>MOBILE NUMBER</strong></th>

                                    <th class="subTitle fontCom"><strong>EMAIL ID</strong></th>

                                    <th class="subTitle fontCom"><strong>RAFFLE ID</strong></th>

                                    <th class="subTitle fontCom"><strong>GRAND RAFFLE ID</strong></th>';



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













                while ($value = mysqli_fetch_assoc($result121121)) {



                    if (isset($tableMapping[$value['type']])) {

                        $tablename = $tableMapping[$value['type']];
                    }

                    $pdfContent = '';





                    $id_i = $value['id'];

                    $uid = $value['user_id'];

                    $proid = $value['product_id'];

                    $ticketID = $value['type'] . $value['ticket_id'];

                    $ticket_id = $value['ticket_id'];







                    $product_rate = $value['rate'];

                    $prize_amount =  $value['prize_one'];















                    $content .= '<tr>

                                    <td class="content fontCom">' . $sno . '</td>

                                    <td class="content fontCom">' . $ld->ticket_no($tablename, $ticket_id) . '</td>

                                    <td class="content fontCom">' . ucwords(strtolower($value['name'])) . '</td>

                                    <td class="content fontCom">' . ucwords(strtolower($value['mobile'])) . '</td>

                                    <td class="content fontCom">' . ucwords(strtolower($value['email'])) . '</td>

                                    <td class="content fontCom">' . $value['raffle_id'] . '</td>

                                    <td class="content fontCom">' . $value['raffle_id'] . '</td>';



                    if ($ld->checkRole($pid)) {

                        $content .= '<td class="content fontCom">' . number_format(intval($product_rate), 2) . '</td><td class="content fontCom">' . $value['type'] . '</td>';
                    }



                    $content .= '</tr>';

                    $grandTotal += intval($prize_amount);

                    $sno++;
                }







                $content .= '</tr></tbody></table>';




                $mpdf->WriteHTML($content);
                // var_dump($content);
                // die;

                $naeme = explode("#", $draw['result'][0]['name']);

                if ($ld->checkRole($pid)) {

                    if ($pid == 'G') {
                        $tna =  ($ticket_name != '') ? $ticket_name : 'ALL';
                        $namefff = 'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . ' ' . str_replace("Draw", "", $naeme[0]) . 'Just3 raffle' . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ')' . $tna . '-LD';
                    } else if ($pid == '1') {
                        $tna =  ($ticket_name != '') ? $ticket_name : 'ALL';
                        $namefff = 'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . ' ' . str_replace("Draw", "", $naeme[0]) . ' Just3 raffle' . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ')' . $tna . '-LD Category-10AED';
                    } else if ($pid == '2') {
                        $tna =  ($ticket_name != '') ? $ticket_name : 'ALL';
                        $namefff = 'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . ' ' . str_replace("Draw", "", $naeme[0]) . ' Just3 raffle' . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ')' . $tna . '-LD Category-20AED';
                    } else if ($pid == '3') {
                        $tna =  ($ticket_name != '') ? $ticket_name : 'ALL';
                        $namefff = 'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . ' ' . str_replace("Draw", "", $naeme[0]) . 'Just3 raffle ' . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ')' . $tna . '-LD Category-50AED';
                    } else if ($pid == '4') {
                        $tna =  ($ticket_name != '') ? $ticket_name : 'ALL';
                        $namefff = 'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . ' ' . str_replace("Draw", "", $naeme[0]) . 'Just3 raffle ' . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ')' . $tna . '-LD Category-100AED';
                    }
                } else {
                    $txts =  ($_REQUEST['pre'] == "yes") ? 'Preliminary Winner List ' : '';
                    $tna =  ($ticket_name != '') ? $ticket_name : 'ALL';
                    $namefff =  'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . ' ' . str_replace("Draw", "", $naeme[0]) . ' Just3 raffle' . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ') ' . $t . $tna . '-DET';
                    // $namefff = 'Draw' . $draw_no . 'Winners List-DET';
                }



                // Saves file on the server as 'filename.pdf'

                $mpdf->Output($namefff . '.pdf', \Mpdf\Output\Destination::INLINE);
            } else {
                echo 'Track Not Found';
            }
        }
    } catch (Exception $e) {
        return $e->getMessage();
    }
} else if ($_REQUEST['reportType'] == 'excel') {





    $ld = new ldC($con);

    $objPHPExcel = new PHPExcel();

    $objPHPExcel->setActiveSheetIndex(0);



    $grandTotal = '';

    $winnerid = $_GET['drawid'];

    $pid = $_GET['proid'];

    $content = '';



    $grandTotal = '';

    $winnerid = $_GET['drawid'];

    $pid = $_GET['proid'];

    $content = '';

    $sno = 1;

    $result = [];

    $type_Con = '';

    $draw_Con = '';

    $tablename = '';



    $draw_new_id = $_GET['drawid'];

    $ticket_name = $_GET['ticket_name'];

    $now = date('Y-m-d');

    $agdate = $_GET['agdate'];



    $draw = select_query($con, "draw", "", "`id`= '$winnerid' AND `deletes` = '0' AND `raffle_status` != 'Pending' ", "", "");

    if ($draw['nr'] > 0) {





        $draw_no = '#' . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT);

        $result_datetime = date('d-M-Y', strtotime($draw['result'][0]['result_datetime']));

        $permitno = $draw['result'][0]['permitno'];

        $ttCol = ($ld->checkRole($pid)) ? '8' : '8';



        if ($agdate != '') {

            $newDD = date("Y-m-d", strtotime($agdate));

            $datefilter = "`createdon` BETWEEN '$newDD 00:00:00' AND '$newDD 23:59:59' AND";
        } else {

            $datefilter = "";
        }



        if ($ticket_name == 'MT') {

            $tablename = 'mticket';
        } else if ($ticket_name == 'AT') {

            $tablename = 'aticket';
        } else if ($ticket_name == 'OT') {

            $tablename = 'ticket';
        } else if ($ticket_name == 'WT') {

            if ($draw_new_id > 33) {

                $ticket_name = 'WT';

                $tablename = 'aticket';
            } else {

                $ticket_name = 'OT';

                $tablename = 'ticket';
            }
        } else if ($ticket_name == 'FT') {

            $tablename = 'fticket';
        } else if ($ticket_name == 'CT') {

            $tablename = 'cticket';
        } else if ($ticket_name == 'BP') {

            $tablename = 'bpticket';
        } else if ($ticket_name == 'CP') {

            $tablename = 'cpticket';
        }







        if ($draw_new_id != '') {

            $draw_Con = "`draw_id` = '$draw_new_id' AND";
        }



        if ($ticket_name != '') {

            $type_Con = "`type` = '$ticket_name' AND";
        }

        $whereWin = [];
        if ($draw['result'][0]['raffleprizefirst'] != null && $draw['result'][0]['raffleprizefirst'] != '') {
            $whereWin[] = "'" . strval($draw['result'][0]['raffleprizefirst']) . "'";
        }


        if ($draw['result'][0]['raffleprizesecond'] != null && $draw['result'][0]['raffleprizesecond'] != '') {
            $whereWin[] = "'" . strval($draw['result'][0]['raffleprizesecond']) . "'";
        }

        if ($draw['result'][0]['raffleprizethird'] != null && $draw['result'][0]['raffleprizethird'] != '') {
            $whereWin[] = "'" . strval($draw['result'][0]['raffleprizethird']) . "'";
        }


        $ranCon =   'ORDER BY RAND()';
        $prizCon = '';

        if ($_REQUEST['pre'] == "yes") {

            // if (count($whereWin) > 0) {
            $ranCon = "ORDER BY 
  CASE raffle_id 
    WHEN " .  $whereWin[0] . " THEN 1
    WHEN " .  $whereWin[1] . " THEN 2
    WHEN " . $whereWin[2] . " THEN 3
    ELSE 4 
  END";
            // }

            if (count($whereWin) > 0) {
                $conDval = trim(implode(',', $whereWin), '"');
                $prizCon = '`raffle_id` in (' . $conDval . ') AND';
            }
        }

        $Ticket_lines = select_query($con, "ticket_lines", "", "$draw_Con $type_Con $datefilter $prizCon `deletes`='0' $ranCon", "", "");




        foreach (range('B', 'I') as $columnID) {

            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        if ($Ticket_lines['nr'] > 0) {

            $style = array(

                'alignment' => array(

                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

                )

            );

            // $objPHPExcel->getActiveSheet()->getStyle("A1:G1")->applyFromArray($style);

            if ($ld->checkRole($pid)) {

                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:N1');
            } else {

                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:G1');
            }



            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'NATIONAL DRAW BEVERAGES TRADING L.L.C');

            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);

            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);



            $objPHPExcel->getActiveSheet()->setCellValue('A4', 'SL.NO');

            $objPHPExcel->getActiveSheet()->setCellValue('B4', 'TICKET ID');

            $objPHPExcel->getActiveSheet()->setCellValue('C4', 'CUSTOMER NAME');

            $objPHPExcel->getActiveSheet()->setCellValue('D4', 'MOBILE NUMBER');

            $objPHPExcel->getActiveSheet()->setCellValue('E4', 'EMAIL');

            $objPHPExcel->getActiveSheet()->setCellValue('F4', 'RAFFLE ID');

            $objPHPExcel->getActiveSheet()->setCellValue('G4', 'GRAND RAFFLE ID');
            if ($_REQUEST['prizeINC'] == "yes") {
                $objPHPExcel->getActiveSheet()->setCellValue('H4', 'CASH PRIZE (AED)');
            }


            if ($ld->checkRole($pid)) {

                $objPHPExcel->getActiveSheet()->setCellValue('H4', 'COUNTRY');

                $objPHPExcel->getActiveSheet()->setCellValue('I4', 'DEVICE');

                $objPHPExcel->getActiveSheet()->setCellValue('J4', 'AMOUNT');

                $objPHPExcel->getActiveSheet()->setCellValue('K4', 'MY3NUMBER');

                $objPHPExcel->getActiveSheet()->setCellValue('L4', 'TRANSACTION ID');

                // $objPHPExcel->getActiveSheet()->setCellValue('M4', 'AGENT NAME');
                $objPHPExcel->getActiveSheet()->setCellValue('M4', 'TICKET TYPE');

                $objPHPExcel->getActiveSheet()->setCellValue('N4', 'PURCHASE DATE & TIME');
            }

            $col = 5;

            foreach ($Ticket_lines['result'] as $key => $value) {



                if ($value['type'] == 'MT') {

                    $tablename = 'mticket';
                } else if ($value['type'] == 'AT') {

                    $tablename = 'aticket';
                } else if ($value['type'] == 'OT') {

                    $tablename = 'ticket';
                } else if ($value['type'] == 'WT') {

                    if ($draw_new_id > 33) {

                        $value['type'] = 'WT';

                        $tablename = 'aticket';
                    } else {

                        $value['type'] = 'OT';

                        $tablename = 'ticket';
                    }
                } else if ($value['type'] == 'FT') {

                    $tablename = 'fticket';
                } else if ($value['type'] == 'CT') {

                    $tablename = 'cticket';
                } else if ($value['type'] == 'BP') {

                    $tablename = 'bpticket';
                } else if ($value['type'] == 'CP') {

                    $tablename = 'cpticket';
                }



                $id_i = $value['id'];

                $uid = $value['user_id'];

                $proid = $value['product_id'];

                $ticket_id = $value['ticket_id'];

                $ticketID = $value['type'] . $value['ticket_id'];



                $ticket_no = select_top_name($con, $tablename, "ticket_no", "`id`='$ticket_id' and `deletes`='0'", "ticket_no", "");





                if ($value['type'] == 'AT') {

                    $getTicketData = select_query($con, 'aticket', "", "`id`='$ticket_id' and `deletes`='0'", "", "");

                    $agentId = $getTicketData['result'][0]['agent_id'];

                    $agent_name = select_top_name($con, "user_register", "name", "`id`= '$agentId'", "name", "");
                } else {

                    $agent_name = '';
                }


                $userName = '';
                $user = select_query($con, "user_register", "", "`id`= '$uid' AND `deletes` = '0' ", "", "");

                // Take Name To Winners 
                $GetWinneruser = select_query($con, "winnerlist", "", "`userid`= '$uid' AND `my3number` IS NULL ORDER BY `id` DESC LIMIT 1", "", "");
                if ($GetWinneruser['nr'] > 0) {
                    $userName = $GetWinneruser['result'][0]['name'];
                } else {
                    $userName = $user['result'][0]['name'];
                }

                $product_rate = select_top_name($con, "product", "rate", "`id`= '$proid' AND `deletes`='0' ", "rate", "");



                $prize_amount = select_top_name($con, "product", "prize_one", "`id`= '$proid' AND `deletes`='0'", "prize_one", "");

                $transcation_id = select_top_name($con, "payment_history", "transaction_id", "`id`= '$value[orders]'", "transaction_id", "");



                $objPHPExcel->getActiveSheet()->setCellValue('A' . $col, $sno);

                $objPHPExcel->getActiveSheet()->getStyle('A' . $col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



                $objPHPExcel->getActiveSheet()->setCellValue('B' . $col, $ticket_no);

                $objPHPExcel->getActiveSheet()->setCellValue('C' . $col, ucwords(strtolower($userName)));

                $objPHPExcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

                $objPHPExcel->getActiveSheet()->setCellValue('D' . $col, ucwords(strtolower($user['result'][0]['mobile'])));

                $objPHPExcel->getActiveSheet()->setCellValue('E' . $col, ucwords(strtolower($user['result'][0]['email'])));

                $objPHPExcel->getActiveSheet()->setCellValue('F' . $col, $value['raffle_id']);

                $objPHPExcel->getActiveSheet()->setCellValue('G' . $col, $value['raffle_id']);

                if ($_REQUEST['prizeINC'] == "yes") {

                    $prizeAMT = '';
                    if ($draw['result'][0]['raffleprizefirst'] == $value['raffle_id']) {
                        $getPro = mysqli_query($con, "SELECT prize_one FROM `product` WHERE `id` = '$value[product_id]' ORDER BY `id` DESC LIMIT 1;");
                        $ro = mysqli_fetch_assoc($getPro);
                        $prizeAMT = $ro['prize_one'];
                    }

                    if ($draw['result'][0]['raffleprizesecond'] == $value['raffle_id']) {
                        $getPro = mysqli_query($con, "SELECT prize_two FROM `product` WHERE `id` = '$value[product_id]' ORDER BY `id` DESC LIMIT 1;");
                        $ro = mysqli_fetch_assoc($getPro);
                        $prizeAMT = $ro['prize_two'];
                    }


                    if ($draw['result'][0]['raffleprizethird'] == $value['raffle_id']) {
                        $getPro = mysqli_query($con, "SELECT prize_three FROM `product` WHERE `id` = '$value[product_id]' ORDER BY `id` DESC LIMIT 1;");
                        $ro = mysqli_fetch_assoc($getPro);
                        $prizeAMT = $ro['prize_three'];
                    }


                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $col, $prizeAMT);
                }

                if ($ld->checkRole($pid)) {
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $col, ucwords(strtolower($user['result'][0]['nationality'])));

                    $objPHPExcel->getActiveSheet()->setCellValue('I' . $col, $user['result'][0]['deviceType']);

                    $objPHPExcel->getActiveSheet()->setCellValue('J' . $col, $product_rate);

                    $objPHPExcel->getActiveSheet()->setCellValue('K' . $col, $value['my3number']);

                    $objPHPExcel->getActiveSheet()->setCellValue('L' . $col, $transcation_id);

                    // $objPHPExcel->getActiveSheet()->setCellValue('M' . $col, $agent_name);
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . $col, $value['type']);


                    $objPHPExcel->getActiveSheet()->setCellValue('N' . $col, $value['createdon']);
                }





                $sno++;

                $col++;
            }
        }



        $txts =  ($_REQUEST['pre'] == "yes") ? ' Winner List ' : '';
        $naeme = explode("#", $draw['result'][0]['name']);
        if ($ld->checkRole($pid)) {

            if ($pid == 'G') {
                $tna =  ($ticket_name != '') ? $ticket_name : 'ALL';
                $namefff = 'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . ' ' . str_replace("Draw", "", $naeme[0])  . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ') ' . ' Just3 raffle ' . $txts . $tna .  '-LD';
            } else if ($pid == '1') {
                $tna =  ($ticket_name != '') ? $ticket_name : 'ALL';
                $namefff = 'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . ' ' . str_replace("Draw", "", $naeme[0])  . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ') ' . ' Just3 raffle ' . $txts . $tna . '-LD Category-10AED';
            } else if ($pid == '2') {
                $tna =  ($ticket_name != '') ? $ticket_name : 'ALL';

                $namefff = 'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . ' ' . str_replace("Draw", "", $naeme[0]) . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ') ' . ' Just3 raffle ' . $txts . $tna . '-LD Category-20AED';
            } else if ($pid == '3') {
                $tna =  ($ticket_name != '') ? $ticket_name : 'ALL';
                $namefff = 'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . ' ' . str_replace("Draw", "", $naeme[0]) . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ') ' . ' Just3 raffle ' . $txts . $tna . '-LD Category-50AED';
            } else if ($pid == '4') {
                $tna =  ($ticket_name != '') ? $ticket_name : 'ALL';
                $namefff = 'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . ' ' . str_replace("Draw", "", $naeme[0])  . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ') ' . ' Just3 raffle ' . $txts . $tna . '-LD Category-100AED';
            }
        } else {

            // $namefff = 'Draw' . $draw_no . 'User List-DED';
            $txts =  ($_REQUEST['pre'] == "yes") ? ' Winner List ' : '';
            $tna =  ($ticket_name != '') ? $ticket_name : 'ALL';
            $namefff =  'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . ' ' . str_replace("Draw", "", $naeme[0])  . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ') ' . ' Just3 raffle ' . $txts . $tna . '-DET';
        }

        $filename = $namefff;


        if ($_REQUEST['protect'] == 'yes') {
        }



        header("Content-Type: application/vnd.ms-excel");

        header("Content-Disposition: attachment; filename=" . $filename . ".xls");

        header("Pragma: no-cache");

        header("Expires: 0");

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        $objWriter->save("php://output");
    }
}

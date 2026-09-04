<?php

/**

 *      Date            Developer     Changes

 *      26-06-2023      Prashant      Raffle Draw Pre-Winner Pdf Genration

 * */





include 'include/shi-config.php';

include 'include/functions.php';

require_once "xlsx/Classes/PHPExcel.php";


ini_set("pcre.backtrack_limit", "100000000");
// ini_set('display_errors', 1);

// ini_set('display_startup_errors', 1);

// error_reporting(E_ALL);



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

        ];

        $draw = select_query($con, "draw", "", "`draw_no`= '$winnerid' AND `deletes` = '0' ", "", "");

        $draw_no = '#' . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT);

        $result_datetime = date('d-M-Y', strtotime($draw['result'][0]['result_datetime']));

        $permitno = $draw['result'][0]['permitno'];

        $ttCol = ($ld->checkRole($pid)) ? '9' : '9';



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

                    $content .= '<th class="subTitle fontCom"><strong>PARTICIPATED CATEGORY</strong></th><th class="subTitle fontCom"><strong>Ticket Type</strong></th>';
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
                    $tna =  ($ticket_name != '') ? $ticket_name : 'ALL';
                    $namefff =  'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . ' ' . str_replace("Draw", "", $naeme[0]) . ' Just3 raffle' . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ') ' . $tna . '-DET';
                    // $namefff = 'Draw' . $draw_no . 'Winners List-DET';
                }



                // Saves file on the server as 'filename.pdf'

                $mpdf->Output($namefff . '.pdf', \Mpdf\Output\Destination::INLINE);
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



    $draw = select_query($con, "draw", "", "`id`= '$winnerid' AND `deletes` = '0' ", "", "");
    // $pdraw = select_query($con, "draw", "", "`draw_no`= '$winnerid' AND `deletes` = '0' ", "", "");

    $draw_no = '#' . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT);

    $result_datetime = date('d-M-Y', strtotime($draw['result'][0]['result_datetime']));

    $permitno = $draw['result'][0]['permitno'];
    // $draw_new_id = $pdraw['result'][0]['id'];

    $ttCol = ($ld->checkRole($pid)) ? '8' : '8';

    $totPrizeAmt = 0;

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

        $ranCon = '';
        if (count($whereWin) > 0) {
            $conDval = trim(implode(',', $whereWin), '"');
            $prizCon = 'and `raffle_id` in (' . $conDval . ') ORDER BY field(raffle_id, ' . $conDval . ');';
        }
    }

    $Ticket_lines = select_query($con, "ticket_lines", "", "$draw_Con $type_Con $datefilter `deletes`='0' $prizCon ", "", "");

//exit;


    foreach (range('B', 'I') as $columnID) {

        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }

    if ($Ticket_lines['nr'] > 0) {

        $style = array(

            'alignment' => [

                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

            ]

        );


        $styleArrayF1 = [
            'font'  => [
                'bold'  => true,
                'color' => ['rgb' => '000000'],
                'size'  => 14,
                'name'  => 'Book Antiqua'
            ],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ]
        ];

        $styleArrayT1 = [
            'font'  => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size'  => 20,
                'name'  => 'Book Antiqua',
                'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
            ],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => 'BC9000']
            ]
        ];


        $styleArrayTT1 = [
            'font'  => [
                'bold'  => true,
                'color' => ['rgb' => 'FFC715'],
                'size'  => 16,
                'name'  => 'Nexa Rust Slab Black Shadow 01',
                // 'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
            ],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => '0066CC']
            ],
            'borders' => [
                'allborders' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        ];

        $styleArrayTH1 = [
            'font'  => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size'  => 12,
                'name'  => 'Book Antiqua',
                // 'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
            ],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => 'BC9000']
            ],
            'borders' =>
            [
                'allborders' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        ];

        $styleArrayTNH1 = [
            'font'  => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size'  => 16,
                'name'  => 'Book Antiqua',
                // 'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
            ],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => 'BC9000']
            ],
            'borders' =>
            [
                'allborders' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        ];

        $styleArrayTB1 = [
            'font'  => [
                'bold'  => false,
                'color' => ['rgb' => '000000'],
                'size'  => 16,
                'name'  => 'Book Antiqua',
                // 'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
            ],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'borders' =>
            [
                'allborders' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        ];

        // $objPHPExcel->getActiveSheet()->getStyle("A1:G1")->applyFromArray($style);

        if ($ld->checkRole($pid)) {

            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:N1');
        } else {

            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A4:I4');
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:I7');
        }

        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Draw Date')->getStyle('G1')->applyFromArray($styleArrayF1);
        $objPHPExcel->getActiveSheet()->setCellValue('I1', $result_datetime)->getStyle('I1')->applyFromArray($styleArrayF1);


        $objPHPExcel->getActiveSheet()->setCellValue('G2', 'Draw No')->getStyle('G2')->applyFromArray($styleArrayF1);
        $objPHPExcel->getActiveSheet()->setCellValue('I2', $draw_no)->getStyle('I2')->applyFromArray($styleArrayF1);

        $objPHPExcel->getActiveSheet()->setCellValue('G3', 'DED Raffle Permit Number')->getStyle('G3')->applyFromArray($styleArrayF1);
        $objPHPExcel->getActiveSheet()->setCellValue('I3', $permitno)->getStyle('I3')->applyFromArray($styleArrayF1);

        $objPHPExcel->getActiveSheet()->setCellValue('A4', 'LIST OF WINNERS')->getStyle('A4')->applyFromArray($styleArrayT1);
        // $objPHPExcel->getActiveSheet()->setCellValue('A4', 'NATIONAL DRAW BEVERAGES TRADING L.L.C')->getStyle('A4')->applyFromArray($styleArrayT1);
        // $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
        // $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->getActiveSheet()->setCellValue('A7', 'NATIONAL DRAW BEVERAGES TRADING L.L.C')->getStyle('A7')->applyFromArray($styleArrayTT1);
        $objPHPExcel->getActiveSheet()->setCellValue('B7', '')->getStyle('B7')->applyFromArray($styleArrayTT1);
        $objPHPExcel->getActiveSheet()->setCellValue('C7', '')->getStyle('C7')->applyFromArray($styleArrayTT1);
        $objPHPExcel->getActiveSheet()->setCellValue('D7', '')->getStyle('D7')->applyFromArray($styleArrayTT1);
        $objPHPExcel->getActiveSheet()->setCellValue('E7', '')->getStyle('E7')->applyFromArray($styleArrayTT1);
        $objPHPExcel->getActiveSheet()->setCellValue('F7', '')->getStyle('F7')->applyFromArray($styleArrayTT1);
        $objPHPExcel->getActiveSheet()->setCellValue('G7', '')->getStyle('G7')->applyFromArray($styleArrayTT1);
        if ($_REQUEST['prizeINC'] == "yes") {
            $objPHPExcel->getActiveSheet()->setCellValue('H7', '')->getStyle('H7')->applyFromArray($styleArrayTT1);
            $objPHPExcel->getActiveSheet()->setCellValue('I7', '')->getStyle('I7')->applyFromArray($styleArrayTT1);
        }
        if ($ld->checkRole($pid)) {
            $objPHPExcel->getActiveSheet()->setCellValue('H7', '')->getStyle('H7')->applyFromArray($styleArrayTT1);
            $objPHPExcel->getActiveSheet()->setCellValue('I7', '')->getStyle('I7')->applyFromArray($styleArrayTT1);
            $objPHPExcel->getActiveSheet()->setCellValue('J7', '')->getStyle('J7')->applyFromArray($styleArrayTT1);
            $objPHPExcel->getActiveSheet()->setCellValue('K7', '')->getStyle('K7')->applyFromArray($styleArrayTT1);
            $objPHPExcel->getActiveSheet()->setCellValue('L7', '')->getStyle('L7')->applyFromArray($styleArrayTT1);
            $objPHPExcel->getActiveSheet()->setCellValue('M7', '')->getStyle('M7')->applyFromArray($styleArrayTT1);
            $objPHPExcel->getActiveSheet()->setCellValue('N7', '')->getStyle('N7')->applyFromArray($styleArrayTT1);
        }
        $objPHPExcel->getActiveSheet()->getRowDimension(6)->setRowHeight(30);
        $objPHPExcel->getActiveSheet()->getRowDimension(7)->setRowHeight(50);









        $objPHPExcel->getActiveSheet()->setCellValue('A8', 'SL.NO')->getStyle('A8')->applyFromArray($styleArrayTH1);

        $objPHPExcel->getActiveSheet()->setCellValue('B8', strtoupper('Ticket ID'))->getStyle('B8')->applyFromArray($styleArrayTH1);

        $objPHPExcel->getActiveSheet()->setCellValue('C8', strtoupper('Customer Name'))->getStyle('C8')->applyFromArray($styleArrayTH1);

        $objPHPExcel->getActiveSheet()->setCellValue('D8', strtoupper('Mobile number'))->getStyle('D8')->applyFromArray($styleArrayTH1);

        $objPHPExcel->getActiveSheet()->setCellValue('E8', strtoupper('Email'))->getStyle('E8')->applyFromArray($styleArrayTH1);

        $objPHPExcel->getActiveSheet()->setCellValue('F8', strtoupper('RAFFLE ID'))->getStyle('F8')->applyFromArray($styleArrayTH1);

        $objPHPExcel->getActiveSheet()->setCellValue('G8', strtoupper('Grand RAFFLE ID'))->getStyle('G8')->applyFromArray($styleArrayTH1);

        if ($_REQUEST['prizeINC'] == "yes") {

            $objPHPExcel->getActiveSheet()->setCellValue('H8', strtoupper('PRIZE CATEGORY'))->getStyle('H8')->applyFromArray($styleArrayTH1);
            $objPHPExcel->getActiveSheet()->setCellValue('I8', strtoupper('CASH PRIZE (AED)'))->getStyle('I8')->applyFromArray($styleArrayTH1);
        }


        if ($ld->checkRole($pid)) {

            $objPHPExcel->getActiveSheet()->setCellValue('H8', strtoupper('COUNTRY'))->getStyle('J8')->applyFromArray($styleArrayTH1);

            $objPHPExcel->getActiveSheet()->setCellValue('I8', strtoupper('DEVICE'))->getStyle('I8')->applyFromArray($styleArrayTH1);

            $objPHPExcel->getActiveSheet()->setCellValue('J8', strtoupper('AMOUNT'))->getStyle('J8')->applyFromArray($styleArrayTH1);

            $objPHPExcel->getActiveSheet()->setCellValue('K8', strtoupper('MY3NUMBER'))->getStyle('K8')->applyFromArray($styleArrayTH1);

            $objPHPExcel->getActiveSheet()->setCellValue('L8', strtoupper('TRANSACTION ID'))->getStyle('L8')->applyFromArray($styleArrayTH1);

            // $objPHPExcel->getActiveSheet()->setCellValue('M4', 'AGENT NAME');
            $objPHPExcel->getActiveSheet()->setCellValue('M8', strtoupper('TICKET TYPE'))->getStyle('M8')->applyFromArray($styleArrayTH1);

            $objPHPExcel->getActiveSheet()->setCellValue('N8', strtoupper('PURCHASE DATE & TIME'))->getStyle('N8')->applyFromArray($styleArrayTH1);
        }
        $objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(40);
        $col = 9;

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

                             $GetWinneruser = select_query($con, "winnerlist", "", "`userid`= '$uid' AND `my3number` IS NULL ORDER BY `id` DESC LIMIT 1", "", "");
                            if($GetWinneruser['nr'] > 0) {
                                $userName = $GetWinneruser['result'][0]['name'];
                            } else {
                                $userName = $user['result'][0]['name'];
                            }


            $product_rate = select_top_name($con, "product", "rate", "`id`= '$proid' AND `deletes`='0' ", "rate", "");



            $prize_amount = select_top_name($con, "product", "prize_one", "`id`= '$proid' AND `deletes`='0'", "prize_one", "");

            $transcation_id = select_top_name($con, "payment_history", "transaction_id", "`id`= '$value[orders]'", "transaction_id", "");



            $objPHPExcel->getActiveSheet()->setCellValue('A' . $col, $sno)->getStyle('A' . $col)->applyFromArray($styleArrayTB1);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



            $objPHPExcel->getActiveSheet()->setCellValue('B' . $col, $ticket_no)->getStyle('B' . $col)->applyFromArray($styleArrayTB1);

            $objPHPExcel->getActiveSheet()->setCellValue('C' . $col, ucwords(strtolower($userName)))->getStyle('C' . $col)->applyFromArray($styleArrayTB1);

            $objPHPExcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

            $objPHPExcel->getActiveSheet()->setCellValue('D' . $col, ucwords(strtolower($user['result'][0]['mobile'])))->getStyle('D' . $col)->applyFromArray($styleArrayTB1);

            $objPHPExcel->getActiveSheet()->setCellValue('E' . $col, ucwords(strtolower($user['result'][0]['email'])))->getStyle('E' . $col)->applyFromArray($styleArrayTB1);

            $objPHPExcel->getActiveSheet()->setCellValue('F' . $col, $value['raffle_id'])->getStyle('F' . $col)->applyFromArray($styleArrayTB1);

            $objPHPExcel->getActiveSheet()->setCellValue('G' . $col, $value['raffle_id'])->getStyle('G' . $col)->applyFromArray($styleArrayTB1);



            if ($_REQUEST['prizeINC'] == "yes") {

                $prizeCat = '';
                $prizeAMT = '';
                
              // echo "aaaaaaaaaaaaaaaaaa".strtoupper($value['raffle_id']);
               // echo "bbbbbbbbbbbbbbbbbbbbb".$draw['result'][0]['raffleprizefirst'];
                 
                // exit;
                if (strtoupper($draw['result'][0]['raffleprizefirst']) == strtoupper($value['raffle_id'])) {
                    $getPro = mysqli_query($con, "SELECT prize_one FROM `product` WHERE `id` = '$value[product_id]' ORDER BY `id` DESC LIMIT 1;");
                    
                 // echo "SELECT prize_one FROM `product` WHERE `id` = '$value[product_id]' ORDER BY `id` DESC LIMIT 1;";
                 // exit;
                    $ro = mysqli_fetch_assoc($getPro);
                    $prizeAMT = $ro['prize_one'];
                    $prizeCat = '1st Prize';
                }

                if (strtoupper($draw['result'][0]['raffleprizesecond']) == strtoupper($value['raffle_id'])) {
                    $getPro = mysqli_query($con, "SELECT prize_two FROM `product` WHERE `id` = '$value[product_id]' ORDER BY `id` DESC LIMIT 1;");
                    $ro = mysqli_fetch_assoc($getPro);
                    $prizeAMT = $ro['prize_two'];
                    $prizeCat = '2nd Prize';
                }


                if (strtoupper($draw['result'][0]['raffleprizethird']) == strtoupper($value['raffle_id'])) {
                    $getPro = mysqli_query($con, "SELECT prize_three FROM `product` WHERE `id` = '$value[product_id]' ORDER BY `id` DESC LIMIT 1;");
                    $ro = mysqli_fetch_assoc($getPro);
                    $prizeAMT = $ro['prize_three'];
                    $prizeCat = '3rd Prize';
                }

                $totPrizeAmt += $prizeAMT;
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $col, $prizeCat)->getStyle('H' . $col)->applyFromArray($styleArrayTB1);
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $col, $prizeAMT)->getStyle('I' . $col)->applyFromArray($styleArrayTB1);
            }

            if ($ld->checkRole($pid)) {
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $col, ucwords(strtolower($user['result'][0]['nationality'])))->getStyle('H' . $col)->applyFromArray($styleArrayTB1);

                $objPHPExcel->getActiveSheet()->setCellValue('I' . $col, $user['result'][0]['deviceType'])->getStyle('I' . $col)->applyFromArray($styleArrayTB1);

                $objPHPExcel->getActiveSheet()->setCellValue('J' . $col, $product_rate)->getStyle('J' . $col)->applyFromArray($styleArrayTB1);

                $objPHPExcel->getActiveSheet()->setCellValue('K' . $col, $value['my3number'])->getStyle('K' . $col)->applyFromArray($styleArrayTB1);

                $objPHPExcel->getActiveSheet()->setCellValue('L' . $col, $transcation_id)->getStyle('L' . $col)->applyFromArray($styleArrayTB1);

                // $objPHPExcel->getActiveSheet()->setCellValue('M' . $col, $agent_name);
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $col, $value['type'])->getStyle('M' . $col)->applyFromArray($styleArrayTB1);


                $objPHPExcel->getActiveSheet()->setCellValue('N' . $col, $value['createdon'])->getStyle('N' . $col)->applyFromArray($styleArrayTB1);
            }



            $objPHPExcel->getActiveSheet()->getRowDimension($col)->setRowHeight(40);

            $sno++;

            $col++;
        }

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $col, '')->getStyle('A' . $col)->applyFromArray($styleArrayTH1);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $col, '')->getStyle('B' . $col)->applyFromArray($styleArrayTH1);
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $col, '')->getStyle('C' . $col)->applyFromArray($styleArrayTH1);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $col, '')->getStyle('D' . $col)->applyFromArray($styleArrayTH1);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $col, '')->getStyle('E' . $col)->applyFromArray($styleArrayTH1);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $col, '')->getStyle('F' . $col)->applyFromArray($styleArrayTH1);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $col, '')->getStyle('G' . $col)->applyFromArray($styleArrayTH1);

        if ($_REQUEST['prizeINC'] == "yes") {
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $col, '')->getStyle('H' . $col)->applyFromArray($styleArrayTH1);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $col, $totPrizeAmt)->getStyle('I' . $col)->applyFromArray($styleArrayTNH1);
        }

        if ($ld->checkRole($pid)) {
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $col, '')->getStyle('H' . $col)->applyFromArray($styleArrayTH1);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $col, '')->getStyle('I' . $col)->applyFromArray($styleArrayTH1);
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $col, '')->getStyle('J' . $col)->applyFromArray($styleArrayTH1);
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $col, '')->getStyle('K' . $col)->applyFromArray($styleArrayTH1);
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $col, '')->getStyle('L' . $col)->applyFromArray($styleArrayTH1);
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $col, '')->getStyle('M' . $col)->applyFromArray($styleArrayTH1);
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $col, '')->getStyle('N' . $col)->applyFromArray($styleArrayTH1);
        }

        $objPHPExcel->getActiveSheet()->setCellValue('C' . $col, 'GRAND TOTAL CASH PRIZE FOR DRAW# ' . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT))->getStyle('C' . $col)->applyFromArray($styleArrayTNH1);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C' . $col . ':E' . $col);
        $objPHPExcel->getActiveSheet()->getRowDimension($col)->setRowHeight(40);
    }





    $txts =  ($_REQUEST['pre'] == "yes") ? 'Winner List ' : '';
    $naeme = explode("#", $draw['result'][0]['name']);
    if ($ld->checkRole($pid)) {

        if ($pid == 'G') {
            $tna =  ($ticket_name != '') ? $ticket_name : 'ALL';
            $namefff = 'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . '' . str_replace("Draw", "", $naeme[0]) . 'Just3 raffle ' . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ') ' . $txts . $tna .  '-LD';
        } else if ($pid == '1') {
            $tna =  ($ticket_name != '') ? $ticket_name : 'ALL';
            $namefff = 'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . '' . str_replace("Draw", "", $naeme[0]) . 'Just3 raffle ' . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ') ' . $txts . $tna . '-LD Category-10AED';
        } else if ($pid == '2') {
            $tna =  ($ticket_name != '') ? $ticket_name : 'ALL';

            $namefff = 'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . '' . str_replace("Draw", "", $naeme[0]) . 'Just3 raffle ' . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ') ' . $txts . $tna . '-LD Category-20AED';
        } else if ($pid == '3') {
            $tna =  ($ticket_name != '') ? $ticket_name : 'ALL';
            $namefff = 'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . '' . str_replace("Draw", "", $naeme[0]) . 'Just3 raffle ' . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ') ' . $txts . $tna . '-LD Category-50AED';
        } else if ($pid == '4') {
            $tna =  ($ticket_name != '') ? $ticket_name : 'ALL';
            $namefff = 'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . '' . str_replace("Draw", "", $naeme[0]) . 'Just3 raffle ' . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ') ' . $txts . $tna . '-LD Category-100AED';
        }
    } else {

        // $namefff = 'Draw' . $draw_no . 'User List-DED';
        $tna =  ($ticket_name != '') ? $ticket_name : 'ALL';
        $namefff =  'Draw No #'  . str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($draw['result'][0]['result_datetime'])) . '' . str_replace("Draw", "", $naeme[0]) . 'Just3 raffle ' . ' (' .  date("D", strtotime($draw['result'][0]['result_datetime'])) . ') ' . $txts . $tna . '-DET';
    }

    $filename = $namefff;


    // Add a drawing to the header
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setPath('assets/images/logo-xl.png');
    $objDrawing->setOffsetX(0);
    $objDrawing->setOffsetY(0);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', '');
    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());


  

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');


    $objWriter->save("php://output");
}

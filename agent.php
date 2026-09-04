<?php
   //   error_reporting(E_ALL);
   // ini_set('display_errors', 1);
   //hello
   $DIR = dirname(__DIR__);
   set_include_path($DIR);
   
   require_once "xlsx/Classes/PHPExcel.php";
   if (isset($_POST['down_form']) && $_POST['method'] == 'download_all_user') {
   	$objPHPExcel = new PHPExcel();
   	$objPHPExcel->setActiveSheetIndex(0);
   	$filename = 'User List.xlsx';
   	$user_register = select_query($con, "user_register", "`id`, `name`, `t_point`, `mobile`, `passport`, `email`, `created_at`", "`deletes` = '0' AND `roll_id` = '0'", "", "");
   
   	if ($user_register['nr'] > 0) {
   		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'CUSTOMER ID');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'NAME');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'POINTS');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'PHONE NO');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'EMIRATE / PASSPORT ID');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'E-MAIL');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'CREATED ON');
   
   		$col = 2;
   
   		foreach ($user_register['result'] as $key => $value) {
   
   			$objPHPExcel->getActiveSheet()->setCellValue('A' . $col, $value['id']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('B' . $col, $value['name']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('C' . $col, $value['t_point']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('D' . $col, $value['mobile']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('E' . $col, $value['passport']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('F' . $col, $value['email']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('G' . $col, date("d-M-Y g:i a", strtotime($value['created_at'])));
   
   			$col++;
   		}
   	}
   
   	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
   
   	$loc = $DIR . '/xlsx/upload/generate/';
   	// var_dump($loc);die;
   	$objWriter->save($loc . $filename);
   
   	$xslurl = $adminurl . 'xlsx/upload/generate/' . $filename;
   
   	$cron_testarr = array("reason" => $xslurl, "filename" => 'agent.php', "draw_id" => '0', "creadedon" => $dubaidate_time);
   	$cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
   
   	divert($xslurl);
   } else if (isset($_POST['down_form971']) && $_POST['method'] == 'download_all_user971') {
   	$objPHPExcel = new PHPExcel();
   	$objPHPExcel->setActiveSheetIndex(0);
   	$filename = 'All UAE Customers List.xlsx';
   	$user_register = select_query($con, "user_register", "`id`, `name`, `t_point`, `mobile`, `passport`, `email`, `created_at`", "`deletes` = '0' AND `roll_id` = '0' AND mobile LIKE '971%' AND LENGTH(mobile) =12 AND mobile NOT LIKE '9710%'", "", "");
   
   	if ($user_register['nr'] > 0) {
   		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'CUSTOMER ID');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'NAME');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'POINTS');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'PHONE NO');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'EMIRATE / PASSPORT ID');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'E-MAIL');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'CREATED ON');
   
   		$col = 2;
   
   		foreach ($user_register['result'] as $key => $value) {
   
   			$objPHPExcel->getActiveSheet()->setCellValue('A' . $col, $value['id']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('B' . $col, $value['name']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('C' . $col, $value['t_point']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('D' . $col, $value['mobile']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('E' . $col, $value['passport']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('F' . $col, $value['email']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('G' . $col, date("d-M-Y g:i a", strtotime($value['created_at'])));
   
   			$col++;
   		}
   	}
   
   	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
   
   	$loc = $DIR . '/xlsx/upload/generate/';
   	// var_dump($loc);die;
   	$objWriter->save($loc . $filename);
   
   	$xslurl = $adminurl . 'xlsx/upload/generate/' . $filename;
   
   	$cron_testarr = array("reason" => $xslurl, "filename" => 'agent.php', "draw_id" => '0', "creadedon" => $dubaidate_time);
   	$cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
   
   	divert($xslurl);
   } else if (isset($_POST['down_formnon']) && $_POST['method'] == 'download_all_usernon') {
   	$objPHPExcel = new PHPExcel();
   	$objPHPExcel->setActiveSheetIndex(0);
   	$filename = 'All Non UAE Customers List.xlsx';
   	$user_register = select_query($con, "user_register", "`id`, `name`, `t_point`, `mobile`, `passport`, `email`, `created_at`", "`deletes` = '0' AND `roll_id` = '0' AND mobile NOT LIKE '971%' AND LENGTH(mobile) =12 AND mobile NOT LIKE '9710%'", "", "");
   
   	if ($user_register['nr'] > 0) {
   		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'CUSTOMER ID');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'NAME');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'POINTS');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'PHONE NO');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'EMIRATE / PASSPORT ID');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'E-MAIL');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'CREATED ON');
   
   		$col = 2;
   
   		foreach ($user_register['result'] as $key => $value) {
   
   			$objPHPExcel->getActiveSheet()->setCellValue('A' . $col, $value['id']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('B' . $col, $value['name']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('C' . $col, $value['t_point']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('D' . $col, $value['mobile']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('E' . $col, $value['passport']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('F' . $col, $value['email']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('G' . $col, date("d-M-Y g:i a", strtotime($value['created_at'])));
   
   			$col++;
   		}
   	}
   
   	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
   
   	$loc = $DIR . '/xlsx/upload/generate/';
   	// var_dump($loc);die;
   	$objWriter->save($loc . $filename);
   
   	$xslurl = $adminurl . 'xlsx/upload/generate/' . $filename;
   
   	$cron_testarr = array("reason" => $xslurl, "filename" => 'agent.php', "draw_id" => '0', "creadedon" => $dubaidate_time);
   	$cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
   
   	divert($xslurl);
   } else if (isset($_POST['down_form971non']) && $_POST['method'] == 'download_all_user971non') {
   	$objPHPExcel = new PHPExcel();
   	$objPHPExcel->setActiveSheetIndex(0);
   	$filename = 'All Non Participate UAE Customers List .xlsx';
   	$draw = select_query($con, "draw", "", " (`deletes` = 0 AND `status` = 'Active' AND `id` IN ( SELECT `id` FROM ( SELECT `id` FROM `draw` WHERE `deletes` = 0 AND `status` = 'Active' ORDER BY `id` ASC LIMIT 1 ) t )) ORDER BY `id` DESC;", "", "");
   	$drawid_curr = $draw['result'][0]['id'];
   	$user_register = mysqli_query($con, "SELECT * from user_register WHERE id NOT IN (SELECT user_id FROM ticket_lines WHERE draw_id = $drawid_curr  GROUP BY user_id) AND deletes= '0' AND roll_id='0' AND mobile LIKE '971%' AND LENGTH(mobile) =12 AND mobile NOT LIKE '9710%' AND lastlogin BETWEEN (NOW() - INTERVAL 6 MONTH) AND NOW()");
   	// echo "user_register == ".mysqli_num_rows($user_register);
   	// if ($user_register['nr'] > 0) {
   	if (mysqli_num_rows($user_register) > 0) {
   		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'CUSTOMER ID');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'NAME');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'POINTS');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'PHONE NO');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'EMIRATE / PASSPORT ID');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'E-MAIL');
   
   		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'CREATED ON');
   
   		$col = 2;
   
   		// foreach ($user_register['result'] as $key => $value) {
   		while ($value = mysqli_fetch_assoc($user_register)) {
   
   			$objPHPExcel->getActiveSheet()->setCellValue('A' . $col, $value['id']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('B' . $col, $value['name']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('C' . $col, $value['t_point']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('D' . $col, $value['mobile']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('E' . $col, $value['passport']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('F' . $col, $value['email']);
   
   			$objPHPExcel->getActiveSheet()->setCellValue('G' . $col, date("d-M-Y g:i a", strtotime($value['created_at'])));
   
   			$col++;
   		}
   	}
   
   	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
   
   	$loc = $DIR . '/xlsx/upload/generate/';
   
   	$objWriter->save($loc . $filename);
   
   	$xslurl = $adminurl . 'xlsx/upload/generate/' . $filename;
   
   	$cron_testarr = array("reason" => $xslurl, "filename" => 'agent.php', "draw_id" => '0', "creadedon" => $dubaidate_time);
   	$cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
   
   	divert($xslurl);
   }
   
   $agent_ID_N = $_SESSION['memid'];
   $sql = "SELECT * FROM `user_register` WHERE `id` = '$agent_ID_N'";
   $run = mysqli_query($con, $sql);
   if (mysqli_num_rows($run) > 0) {
   	$row = $run->fetch_assoc();
   }
   
   
   $tabID = $subid2;
   
   if ($tabID == 'customer') {
   
   	$title = 'My Customer';
   
   	$idname = 'Customer ID';
   
   	$addName = 'Customer';
   } else if ($tabID == 'agents') {
   
   	$title = 'Agents List';
   
   	$idname = 'Agent ID';
   
   	$addName = 'Agent';
   } else if ($tabID == 'affiliate') {
   
   	$title = 'Affiliate List';
   
   	$idname = 'Affiliate ID';
   
   	$addName = 'Affiliate';
   } else if ($tabID == 'kioskcreater') {
   
   	$title = 'kiosk List';
   
   	$idname = 'kiosk ID';
   
   	$addName = 'Kiosk';
   } else if ($tabID == 'support') {
   
   
   
   	$title = 'Customer Support';
   
   
   
   	$idname = 'Customer Support ID';
   
   
   
   	$addName = 'Customer Support';
   } else {
   
   	$title = 'My Staffs';
   
   	$idname = 'Staff ID';
   
   	$addName = 'Staff';
   }
   
   ?>
<head>
   <style>
      img.swal2-image,
      textarea {
      width: 100%
      }
      .intl-tel-input.allow-dropdown .flag-container:hover,
      .intl-tel-input.iti-container:hover,
      button:hover {
      cursor: pointer
      }
      .bootstrap-select .dropdown-toggle .filter-option {
      height: auto
      }
      .bootstrap-select>.dropdown-toggle {
      background: #fff
      }
      .bootstrap-select .dropdown-menu li a,
      .dropdown-item.active {
      padding: 3px 0 24px 22px !important
      }
      .inner.show {
      min-height: 135px !important;
      max-height: 134px !important
      }
      .wrap-modal-slider {
      padding: 0 30px;
      opacity: 0;
      transition: .3s
      }
      .wrap-modal-slider.open {
      opacity: 1
      }
      .slick-next:before,
      .slick-prev:before {
      color: red
      }
      .modal-body1 {
      padding: 30px !important;
      text-align: center;
      background: radial-gradient(circle, rgb(3 111 190) 0, rgb(13 65 114) 100%)
      }
      .modal-body1 p {
      line-height: 23px;
      padding-top: 12px;
      font-size: 15px;
      color: #fff
      }
      .modal-body1 h3 {
      color: #fff
      }
      .modal-headers {
      background-image: -webkit-linear-gradient(137deg, #e9cc02 0, #ffee0c 100%);
      border-radius: inherit;
      border-bottom: 1px solid #036bb7;
      display: flex;
      -ms-flex-align: start;
      align-items: flex-start;
      -ms-flex-pack: justify;
      justify-content: space-between;
      padding: 12px
      }
      button.close,
      h5.modal-title {
      color: #141414
      }
      .swal-wide {
      background: 0 0 !important;
      width: 1000px !important
      }
      .hide,
      .intl-tel-input .hide,
      .swal2-select {
      display: none
      }
      .intl-tel-input,
      pre {
      display: inline-block
      }
      .swal2-close:focus {
      outline: 0;
      box-shadow: none !important
      }
      .swal2-close {
      margin: 18px -11px -161px 0 !important
      }
      textarea {
      height: 150px;
      padding: 12px 20px;
      box-sizing: border-box;
      border: 2px solid #ccc;
      border-radius: 4px;
      background-color: #f8f8f8;
      font-size: 16px;
      resize: none
      }
      .intl-tel-input {
      position: relative
      }
      *,
      .intl-tel-input * {
      box-sizing: border-box;
      -moz-box-sizing: border-box
      }
      .intl-tel-input .v-hide {
      visibility: hidden
      }
      .intl-tel-input input,
      .intl-tel-input input[type=tel],
      .intl-tel-input input[type=text] {
      position: relative;
      z-index: 0;
      margin-top: 0 !important;
      margin-bottom: 0 !important;
      padding-right: 36px;
      margin-right: 0
      }
      .intl-tel-input .flag-container {
      position: absolute;
      top: 0;
      bottom: 0;
      right: 0;
      padding: 1px
      }
      .intl-tel-input .selected-flag {
      z-index: 1;
      position: relative;
      width: 36px;
      height: 100%;
      padding: 0 0 0 8px
      }
      .intl-tel-input .selected-flag .iti-flag {
      position: absolute;
      top: 0;
      bottom: 0;
      margin: auto
      }
      .intl-tel-input .selected-flag .iti-arrow {
      position: absolute;
      top: 50%;
      margin-top: -2px;
      right: 6px;
      width: 0;
      height: 0;
      border-left: 3px solid transparent;
      border-right: 3px solid transparent;
      border-top: 4px solid #555
      }
      .intl-tel-input .selected-flag .iti-arrow.up {
      border-top: none;
      border-bottom: 4px solid #555
      }
      .intl-tel-input input#phone {
      width: 100%;
      height: 44px;
      border-radius: 5px;
      border: 1px solid #ecf0fa
      }
      .intl-tel-input .country-list {
      position: absolute;
      z-index: 2;
      list-style: none;
      text-align: left;
      padding: 0;
      margin: 0 0 0 -1px;
      box-shadow: 1px 1px 4px rgba(0, 0, 0, .2);
      background-color: #fff;
      border: 1px solid #ccc;
      white-space: nowrap;
      max-height: 200px;
      overflow-y: scroll
      }
      .intl-tel-input.allow-dropdown input[disabled]+.flag-container:hover .selected-flag,
      .intl-tel-input.allow-dropdown input[readonly]+.flag-container:hover .selected-flag,
      .iti-flag.np {
      background-color: transparent
      }
      .intl-tel-input .country-list.dropup {
      bottom: 100%;
      margin-bottom: -1px
      }
      .intl-tel-input .country-list .flag-box {
      display: inline-block;
      width: 20px
      }
      @media (max-width:500px) {
      .intl-tel-input .country-list {
      white-space: normal
      }
      }
      .intl-tel-input .country-list .divider {
      padding-bottom: 5px;
      margin-bottom: 5px;
      border-bottom: 1px solid #ccc
      }
      .intl-tel-input .country-list .country {
      padding: 5px 10px
      }
      .intl-tel-input .country-list .country .dial-code {
      color: #999
      }
      .intl-tel-input .country-list .country.highlight,
      .intl-tel-input.allow-dropdown .flag-container:hover .selected-flag {
      background-color: rgba(0, 0, 0, .05)
      }
      .intl-tel-input .country-list .country-name,
      .intl-tel-input .country-list .dial-code,
      .intl-tel-input .country-list .flag-box {
      vertical-align: middle
      }
      .intl-tel-input .country-list .country-name,
      .intl-tel-input .country-list .flag-box {
      margin-right: 6px
      }
      .intl-tel-input.allow-dropdown input,
      .intl-tel-input.allow-dropdown input[type=tel],
      .intl-tel-input.allow-dropdown input[type=text],
      .intl-tel-input.separate-dial-code input,
      .intl-tel-input.separate-dial-code input[type=tel],
      .intl-tel-input.separate-dial-code input[type=text] {
      padding-right: 6px;
      padding-left: 52px;
      margin-left: 0
      }
      .intl-tel-input.allow-dropdown .flag-container,
      .intl-tel-input.separate-dial-code .flag-container {
      right: auto;
      left: 0
      }
      .intl-tel-input.allow-dropdown .selected-flag,
      .intl-tel-input.separate-dial-code .selected-flag {
      width: 46px
      }
      .intl-tel-input.allow-dropdown input[disabled]+.flag-container:hover,
      .intl-tel-input.allow-dropdown input[readonly]+.flag-container:hover {
      cursor: default
      }
      .intl-tel-input.separate-dial-code .selected-flag {
      background-color: rgba(0, 0, 0, .05);
      display: table
      }
      .intl-tel-input.separate-dial-code .selected-dial-code {
      display: table-cell;
      vertical-align: middle;
      padding-left: 28px
      }
      .intl-tel-input.separate-dial-code.iti-sdc-2 input,
      .intl-tel-input.separate-dial-code.iti-sdc-2 input[type=tel],
      .intl-tel-input.separate-dial-code.iti-sdc-2 input[type=text] {
      padding-left: 66px
      }
      .intl-tel-input.separate-dial-code.iti-sdc-2 .selected-flag {
      width: 60px
      }
      .intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-2 input,
      .intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-2 input[type=tel],
      .intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-2 input[type=text] {
      padding-left: 76px
      }
      .intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-2 .selected-flag {
      width: 70px
      }
      .intl-tel-input.separate-dial-code.iti-sdc-3 input,
      .intl-tel-input.separate-dial-code.iti-sdc-3 input[type=tel],
      .intl-tel-input.separate-dial-code.iti-sdc-3 input[type=text] {
      padding-left: 74px
      }
      .intl-tel-input.separate-dial-code.iti-sdc-3 .selected-flag {
      width: 68px
      }
      .intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-3 input,
      .intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-3 input[type=tel],
      .intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-3 input[type=text] {
      padding-left: 84px
      }
      .intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-3 .selected-flag {
      width: 78px
      }
      .intl-tel-input.separate-dial-code.iti-sdc-4 input,
      .intl-tel-input.separate-dial-code.iti-sdc-4 input[type=tel],
      .intl-tel-input.separate-dial-code.iti-sdc-4 input[type=text] {
      padding-left: 82px
      }
      .intl-tel-input.separate-dial-code.iti-sdc-4 .selected-flag {
      width: 76px
      }
      .intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-4 input,
      .intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-4 input[type=tel],
      .intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-4 input[type=text] {
      padding-left: 92px
      }
      .intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-4 .selected-flag {
      width: 86px
      }
      .intl-tel-input.separate-dial-code.iti-sdc-5 input,
      .intl-tel-input.separate-dial-code.iti-sdc-5 input[type=tel],
      .intl-tel-input.separate-dial-code.iti-sdc-5 input[type=text] {
      padding-left: 90px
      }
      .intl-tel-input.separate-dial-code.iti-sdc-5 .selected-flag {
      width: 84px
      }
      .intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-5 input,
      .intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-5 input[type=tel],
      .intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-5 input[type=text] {
      padding-left: 100px
      }
      .intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-5 .selected-flag {
      width: 94px
      }
      .intl-tel-input.iti-container {
      position: absolute;
      top: -1000px;
      left: -1000px;
      z-index: 1060;
      padding: 1px
      }
      .iti-mobile .intl-tel-input.iti-container {
      top: 30px;
      bottom: 30px;
      left: 30px;
      right: 30px;
      position: fixed
      }
      .iti-mobile .intl-tel-input .country-list {
      max-height: 100%;
      width: 100%
      }
      .iti-mobile .intl-tel-input .country-list .country {
      padding: 10px;
      line-height: 1.5em
      }
      .iti-flag.be,
      .iti-flag.ne {
      width: 18px
      }
      .iti-flag.ch {
      width: 15px
      }
      .iti-flag.mc {
      width: 19px
      }
      .iti-flag.ac {
      height: 10px;
      background-position: 0 0
      }
      .iti-flag.ad {
      height: 14px;
      background-position: -22px 0
      }
      .iti-flag.ae {
      height: 10px;
      background-position: -44px 0
      }
      .iti-flag.af {
      height: 14px;
      background-position: -66px 0
      }
      .iti-flag.ag {
      height: 14px;
      background-position: -88px 0
      }
      .iti-flag.ai {
      height: 10px;
      background-position: -110px 0
      }
      .iti-flag.al {
      height: 15px;
      background-position: -132px 0
      }
      .iti-flag.am {
      height: 10px;
      background-position: -154px 0
      }
      .iti-flag.ao {
      height: 14px;
      background-position: -176px 0
      }
      .iti-flag.aq {
      height: 14px;
      background-position: -198px 0
      }
      .iti-flag.ar {
      height: 13px;
      background-position: -220px 0
      }
      .iti-flag.as {
      height: 10px;
      background-position: -242px 0
      }
      .iti-flag.at {
      height: 14px;
      background-position: -264px 0
      }
      .iti-flag.au {
      height: 10px;
      background-position: -286px 0
      }
      .iti-flag.aw {
      height: 14px;
      background-position: -308px 0
      }
      .iti-flag.ax {
      height: 13px;
      background-position: -330px 0
      }
      .iti-flag.az {
      height: 10px;
      background-position: -352px 0
      }
      .iti-flag.ba {
      height: 10px;
      background-position: -374px 0
      }
      .iti-flag.bb {
      height: 14px;
      background-position: -396px 0
      }
      .iti-flag.bd {
      height: 12px;
      background-position: -418px 0
      }
      .iti-flag.be {
      height: 15px;
      background-position: -440px 0
      }
      .iti-flag.bf {
      height: 14px;
      background-position: -460px 0
      }
      .iti-flag.bg {
      height: 12px;
      background-position: -482px 0
      }
      .iti-flag.bh {
      height: 12px;
      background-position: -504px 0
      }
      .iti-flag.bi {
      height: 12px;
      background-position: -526px 0
      }
      .iti-flag.bj {
      height: 14px;
      background-position: -548px 0
      }
      .iti-flag.bl {
      height: 14px;
      background-position: -570px 0
      }
      .iti-flag.bm {
      height: 10px;
      background-position: -592px 0
      }
      .iti-flag.bn {
      height: 10px;
      background-position: -614px 0
      }
      .iti-flag.bo {
      height: 14px;
      background-position: -636px 0
      }
      .iti-flag.bq {
      height: 14px;
      background-position: -658px 0
      }
      .iti-flag.br {
      height: 14px;
      background-position: -680px 0
      }
      .iti-flag.bs {
      height: 10px;
      background-position: -702px 0
      }
      .iti-flag.bt {
      height: 14px;
      background-position: -724px 0
      }
      .iti-flag.bv {
      height: 15px;
      background-position: -746px 0
      }
      .iti-flag.bw {
      height: 14px;
      background-position: -768px 0
      }
      .iti-flag.by {
      height: 10px;
      background-position: -790px 0
      }
      .iti-flag.bz {
      height: 14px;
      background-position: -812px 0
      }
      .iti-flag.ca {
      height: 10px;
      background-position: -834px 0
      }
      .iti-flag.cc {
      height: 10px;
      background-position: -856px 0
      }
      .iti-flag.cd {
      height: 15px;
      background-position: -878px 0
      }
      .iti-flag.cf {
      height: 14px;
      background-position: -900px 0
      }
      .iti-flag.cg {
      height: 14px;
      background-position: -922px 0
      }
      .iti-flag.ch {
      height: 15px;
      background-position: -944px 0
      }
      .iti-flag.ci {
      height: 14px;
      background-position: -961px 0
      }
      .iti-flag.ck {
      height: 10px;
      background-position: -983px 0
      }
      .iti-flag.cl {
      height: 14px;
      background-position: -1005px 0
      }
      .iti-flag.cm {
      height: 14px;
      background-position: -1027px 0
      }
      .iti-flag.cn {
      height: 14px;
      background-position: -1049px 0
      }
      .iti-flag.co {
      height: 14px;
      background-position: -1071px 0
      }
      .iti-flag.cp {
      height: 14px;
      background-position: -1093px 0
      }
      .iti-flag.cr {
      height: 12px;
      background-position: -1115px 0
      }
      .iti-flag.cu {
      height: 10px;
      background-position: -1137px 0
      }
      .iti-flag.cv {
      height: 12px;
      background-position: -1159px 0
      }
      .iti-flag.cw {
      height: 14px;
      background-position: -1181px 0
      }
      .iti-flag.cx {
      height: 10px;
      background-position: -1203px 0
      }
      .iti-flag.cy {
      height: 13px;
      background-position: -1225px 0
      }
      .iti-flag.cz {
      height: 14px;
      background-position: -1247px 0
      }
      .iti-flag.de {
      height: 12px;
      background-position: -1269px 0
      }
      .iti-flag.dg {
      height: 10px;
      background-position: -1291px 0
      }
      .iti-flag.dj {
      height: 14px;
      background-position: -1313px 0
      }
      .iti-flag.dk {
      height: 15px;
      background-position: -1335px 0
      }
      .iti-flag.dm {
      height: 10px;
      background-position: -1357px 0
      }
      .iti-flag.do {
      height: 13px;
      background-position: -1379px 0
      }
      .iti-flag.dz {
      height: 14px;
      background-position: -1401px 0
      }
      .iti-flag.ea {
      height: 14px;
      background-position: -1423px 0
      }
      .iti-flag.ec {
      height: 14px;
      background-position: -1445px 0
      }
      .iti-flag.ee {
      height: 13px;
      background-position: -1467px 0
      }
      .iti-flag.eg {
      height: 14px;
      background-position: -1489px 0
      }
      .iti-flag.eh {
      height: 10px;
      background-position: -1511px 0
      }
      .iti-flag.er {
      height: 10px;
      background-position: -1533px 0
      }
      .iti-flag.es {
      height: 14px;
      background-position: -1555px 0
      }
      .iti-flag.et {
      height: 10px;
      background-position: -1577px 0
      }
      .iti-flag.eu {
      height: 14px;
      background-position: -1599px 0
      }
      .iti-flag.fi {
      height: 12px;
      background-position: -1621px 0
      }
      .iti-flag.fj {
      height: 10px;
      background-position: -1643px 0
      }
      .iti-flag.fk {
      height: 10px;
      background-position: -1665px 0
      }
      .iti-flag.fm {
      height: 11px;
      background-position: -1687px 0
      }
      .iti-flag.fo {
      height: 15px;
      background-position: -1709px 0
      }
      .iti-flag.fr {
      height: 14px;
      background-position: -1731px 0
      }
      .iti-flag.ga {
      height: 15px;
      background-position: -1753px 0
      }
      .iti-flag.gb {
      height: 10px;
      background-position: -1775px 0
      }
      .iti-flag.gd {
      height: 12px;
      background-position: -1797px 0
      }
      .iti-flag.ge {
      height: 14px;
      background-position: -1819px 0
      }
      .iti-flag.gf {
      height: 14px;
      background-position: -1841px 0
      }
      .iti-flag.gg {
      height: 14px;
      background-position: -1863px 0
      }
      .iti-flag.gh {
      height: 14px;
      background-position: -1885px 0
      }
      .iti-flag.gi {
      height: 10px;
      background-position: -1907px 0
      }
      .iti-flag.gl {
      height: 14px;
      background-position: -1929px 0
      }
      .iti-flag.gm {
      height: 14px;
      background-position: -1951px 0
      }
      .iti-flag.gn {
      height: 14px;
      background-position: -1973px 0
      }
      .iti-flag.gp {
      height: 14px;
      background-position: -1995px 0
      }
      .iti-flag.gq {
      height: 14px;
      background-position: -2017px 0
      }
      .iti-flag.gr {
      height: 14px;
      background-position: -2039px 0
      }
      .iti-flag.gs {
      height: 10px;
      background-position: -2061px 0
      }
      .iti-flag.gt {
      height: 13px;
      background-position: -2083px 0
      }
      .iti-flag.gu {
      height: 11px;
      background-position: -2105px 0
      }
      .iti-flag.gw {
      height: 10px;
      background-position: -2127px 0
      }
      .iti-flag.gy {
      height: 12px;
      background-position: -2149px 0
      }
      .iti-flag.hk {
      height: 14px;
      background-position: -2171px 0
      }
      .iti-flag.hm {
      height: 10px;
      background-position: -2193px 0
      }
      .iti-flag.hn {
      height: 10px;
      background-position: -2215px 0
      }
      .iti-flag.hr {
      height: 10px;
      background-position: -2237px 0
      }
      .iti-flag.ht {
      height: 12px;
      background-position: -2259px 0
      }
      .iti-flag.hu {
      height: 10px;
      background-position: -2281px 0
      }
      .iti-flag.ic {
      height: 14px;
      background-position: -2303px 0
      }
      .iti-flag.id {
      height: 14px;
      background-position: -2325px 0
      }
      .iti-flag.ie {
      height: 10px;
      background-position: -2347px 0
      }
      .iti-flag.il {
      height: 15px;
      background-position: -2369px 0
      }
      .iti-flag.im {
      height: 10px;
      background-position: -2391px 0
      }
      .iti-flag.in {
      height: 14px;
      background-position: -2413px 0
      }
      .iti-flag.io {
      height: 10px;
      background-position: -2435px 0
      }
      .iti-flag.iq {
      height: 14px;
      background-position: -2457px 0
      }
      .iti-flag.ir {
      height: 12px;
      background-position: -2479px 0
      }
      .iti-flag.is {
      height: 15px;
      background-position: -2501px 0
      }
      .iti-flag.it {
      height: 14px;
      background-position: -2523px 0
      }
      .iti-flag.je {
      height: 12px;
      background-position: -2545px 0
      }
      .iti-flag.jm {
      height: 10px;
      background-position: -2567px 0
      }
      .iti-flag.jo {
      height: 10px;
      background-position: -2589px 0
      }
      .iti-flag.jp {
      height: 14px;
      background-position: -2611px 0
      }
      .iti-flag.ke {
      height: 14px;
      background-position: -2633px 0
      }
      .iti-flag.kg {
      height: 12px;
      background-position: -2655px 0
      }
      .iti-flag.kh {
      height: 13px;
      background-position: -2677px 0
      }
      .iti-flag.ki {
      height: 10px;
      background-position: -2699px 0
      }
      .iti-flag.km {
      height: 12px;
      background-position: -2721px 0
      }
      .iti-flag.kn {
      height: 14px;
      background-position: -2743px 0
      }
      .iti-flag.kp {
      height: 10px;
      background-position: -2765px 0
      }
      .iti-flag.kr {
      height: 14px;
      background-position: -2787px 0
      }
      .iti-flag.kw {
      height: 10px;
      background-position: -2809px 0
      }
      .iti-flag.ky {
      height: 10px;
      background-position: -2831px 0
      }
      .iti-flag.kz {
      height: 10px;
      background-position: -2853px 0
      }
      .iti-flag.la {
      height: 14px;
      background-position: -2875px 0
      }
      .iti-flag.lb {
      height: 14px;
      background-position: -2897px 0
      }
      .iti-flag.lc {
      height: 10px;
      background-position: -2919px 0
      }
      .iti-flag.li {
      height: 12px;
      background-position: -2941px 0
      }
      .iti-flag.lk {
      height: 10px;
      background-position: -2963px 0
      }
      .iti-flag.lr {
      height: 11px;
      background-position: -2985px 0
      }
      .iti-flag.ls {
      height: 14px;
      background-position: -3007px 0
      }
      .iti-flag.lt {
      height: 12px;
      background-position: -3029px 0
      }
      .iti-flag.lu {
      height: 12px;
      background-position: -3051px 0
      }
      .iti-flag.lv {
      height: 10px;
      background-position: -3073px 0
      }
      .iti-flag.ly {
      height: 10px;
      background-position: -3095px 0
      }
      .iti-flag.ma {
      height: 14px;
      background-position: -3117px 0
      }
      .iti-flag.mc {
      height: 15px;
      background-position: -3139px 0
      }
      .iti-flag.md {
      height: 10px;
      background-position: -3160px 0
      }
      .iti-flag.me {
      height: 10px;
      background-position: -3182px 0
      }
      .iti-flag.mf {
      height: 14px;
      background-position: -3204px 0
      }
      .iti-flag.mg {
      height: 14px;
      background-position: -3226px 0
      }
      .iti-flag.mh {
      height: 11px;
      background-position: -3248px 0
      }
      .iti-flag.mk {
      height: 10px;
      background-position: -3270px 0
      }
      .iti-flag.ml {
      height: 14px;
      background-position: -3292px 0
      }
      .iti-flag.mm {
      height: 14px;
      background-position: -3314px 0
      }
      .iti-flag.mn {
      height: 10px;
      background-position: -3336px 0
      }
      .iti-flag.mo {
      height: 14px;
      background-position: -3358px 0
      }
      .iti-flag.mp {
      height: 10px;
      background-position: -3380px 0
      }
      .iti-flag.mq {
      height: 14px;
      background-position: -3402px 0
      }
      .iti-flag.mr {
      height: 14px;
      background-position: -3424px 0
      }
      .iti-flag.ms {
      height: 10px;
      background-position: -3446px 0
      }
      .iti-flag.mt {
      height: 14px;
      background-position: -3468px 0
      }
      .iti-flag.mu {
      height: 14px;
      background-position: -3490px 0
      }
      .iti-flag.mv {
      height: 14px;
      background-position: -3512px 0
      }
      .iti-flag.mw {
      height: 14px;
      background-position: -3534px 0
      }
      .iti-flag.mx {
      height: 12px;
      background-position: -3556px 0
      }
      .iti-flag.my {
      height: 10px;
      background-position: -3578px 0
      }
      .iti-flag.mz {
      height: 14px;
      background-position: -3600px 0
      }
      .iti-flag.na {
      height: 14px;
      background-position: -3622px 0
      }
      .iti-flag.nc {
      height: 10px;
      background-position: -3644px 0
      }
      .iti-flag.ne {
      height: 15px;
      background-position: -3666px 0
      }
      .iti-flag.nf {
      height: 10px;
      background-position: -3686px 0
      }
      .iti-flag.ng {
      height: 10px;
      background-position: -3708px 0
      }
      .iti-flag.ni {
      height: 12px;
      background-position: -3730px 0
      }
      .iti-flag.nl {
      height: 14px;
      background-position: -3752px 0
      }
      .iti-flag.no {
      height: 15px;
      background-position: -3774px 0
      }
      .iti-flag.np {
      width: 13px;
      height: 15px;
      background-position: -3796px 0
      }
      .iti-flag.nr {
      height: 10px;
      background-position: -3811px 0
      }
      .iti-flag.nu {
      height: 10px;
      background-position: -3833px 0
      }
      .iti-flag.nz {
      height: 10px;
      background-position: -3855px 0
      }
      .iti-flag.om {
      height: 10px;
      background-position: -3877px 0
      }
      .iti-flag.pa {
      height: 14px;
      background-position: -3899px 0
      }
      .iti-flag.pe {
      height: 14px;
      background-position: -3921px 0
      }
      .iti-flag.pf {
      height: 14px;
      background-position: -3943px 0
      }
      .iti-flag.pg {
      height: 15px;
      background-position: -3965px 0
      }
      .iti-flag.ph {
      height: 10px;
      background-position: -3987px 0
      }
      .iti-flag.pk {
      height: 14px;
      background-position: -4009px 0
      }
      .iti-flag.pl {
      height: 13px;
      background-position: -4031px 0
      }
      .iti-flag.pm {
      height: 14px;
      background-position: -4053px 0
      }
      .iti-flag.pn {
      height: 10px;
      background-position: -4075px 0
      }
      .iti-flag.pr {
      height: 14px;
      background-position: -4097px 0
      }
      .iti-flag.ps {
      height: 10px;
      background-position: -4119px 0
      }
      .iti-flag.pt {
      height: 14px;
      background-position: -4141px 0
      }
      .iti-flag.pw {
      height: 13px;
      background-position: -4163px 0
      }
      .iti-flag.py {
      height: 11px;
      background-position: -4185px 0
      }
      .iti-flag.qa {
      height: 8px;
      background-position: -4207px 0
      }
      .iti-flag.re {
      height: 14px;
      background-position: -4229px 0
      }
      .iti-flag.ro {
      height: 14px;
      background-position: -4251px 0
      }
      .iti-flag.rs {
      height: 14px;
      background-position: -4273px 0
      }
      .iti-flag.ru {
      height: 14px;
      background-position: -4295px 0
      }
      .iti-flag.rw {
      height: 14px;
      background-position: -4317px 0
      }
      .iti-flag.sa {
      height: 14px;
      background-position: -4339px 0
      }
      .iti-flag.sb {
      height: 10px;
      background-position: -4361px 0
      }
      .iti-flag.sc {
      height: 10px;
      background-position: -4383px 0
      }
      .iti-flag.sd {
      height: 10px;
      background-position: -4405px 0
      }
      .iti-flag.se {
      height: 13px;
      background-position: -4427px 0
      }
      .iti-flag.sg {
      height: 14px;
      background-position: -4449px 0
      }
      .iti-flag.sh {
      height: 10px;
      background-position: -4471px 0
      }
      .iti-flag.si {
      height: 10px;
      background-position: -4493px 0
      }
      .iti-flag.sj {
      height: 15px;
      background-position: -4515px 0
      }
      .iti-flag.sk {
      height: 14px;
      background-position: -4537px 0
      }
      .iti-flag.sl {
      height: 14px;
      background-position: -4559px 0
      }
      .iti-flag.sm {
      height: 15px;
      background-position: -4581px 0
      }
      .iti-flag.sn {
      height: 14px;
      background-position: -4603px 0
      }
      .iti-flag.so {
      height: 14px;
      background-position: -4625px 0
      }
      .iti-flag.sr {
      height: 14px;
      background-position: -4647px 0
      }
      .iti-flag.ss {
      height: 10px;
      background-position: -4669px 0
      }
      .iti-flag.st {
      height: 10px;
      background-position: -4691px 0
      }
      .iti-flag.sv {
      height: 12px;
      background-position: -4713px 0
      }
      .iti-flag.sx {
      height: 14px;
      background-position: -4735px 0
      }
      .iti-flag.sy {
      height: 14px;
      background-position: -4757px 0
      }
      .iti-flag.sz {
      height: 14px;
      background-position: -4779px 0
      }
      .iti-flag.ta {
      height: 10px;
      background-position: -4801px 0
      }
      .iti-flag.tc {
      height: 10px;
      background-position: -4823px 0
      }
      .iti-flag.td {
      height: 14px;
      background-position: -4845px 0
      }
      .iti-flag.tf {
      height: 14px;
      background-position: -4867px 0
      }
      .iti-flag.tg {
      height: 13px;
      background-position: -4889px 0
      }
      .iti-flag.th {
      height: 14px;
      background-position: -4911px 0
      }
      .iti-flag.tj {
      height: 10px;
      background-position: -4933px 0
      }
      .iti-flag.tk {
      height: 10px;
      background-position: -4955px 0
      }
      .iti-flag.tl {
      height: 10px;
      background-position: -4977px 0
      }
      .iti-flag.tm {
      height: 14px;
      background-position: -4999px 0
      }
      .iti-flag.tn {
      height: 14px;
      background-position: -5021px 0
      }
      .iti-flag.to {
      height: 10px;
      background-position: -5043px 0
      }
      .iti-flag.tr {
      height: 14px;
      background-position: -5065px 0
      }
      .iti-flag.tt {
      height: 12px;
      background-position: -5087px 0
      }
      .iti-flag.tv {
      height: 10px;
      background-position: -5109px 0
      }
      .iti-flag.tw {
      height: 14px;
      background-position: -5131px 0
      }
      .iti-flag.tz {
      height: 14px;
      background-position: -5153px 0
      }
      .iti-flag.ua {
      height: 14px;
      background-position: -5175px 0
      }
      .iti-flag.ug {
      height: 14px;
      background-position: -5197px 0
      }
      .iti-flag.um {
      height: 11px;
      background-position: -5219px 0
      }
      .iti-flag.us {
      height: 11px;
      background-position: -5241px 0
      }
      .iti-flag.uy {
      height: 14px;
      background-position: -5263px 0
      }
      .iti-flag.uz {
      height: 10px;
      background-position: -5285px 0
      }
      .iti-flag.va {
      width: 15px;
      height: 15px;
      background-position: -5307px 0
      }
      .iti-flag.vc {
      height: 14px;
      background-position: -5324px 0
      }
      .iti-flag.ve {
      height: 14px;
      background-position: -5346px 0
      }
      .iti-flag.vg {
      height: 10px;
      background-position: -5368px 0
      }
      .iti-flag.vi {
      height: 14px;
      background-position: -5390px 0
      }
      .iti-flag.vn {
      height: 14px;
      background-position: -5412px 0
      }
      .iti-flag.vu {
      height: 12px;
      background-position: -5434px 0
      }
      .iti-flag.wf {
      height: 14px;
      background-position: -5456px 0
      }
      .iti-flag.ws {
      height: 10px;
      background-position: -5478px 0
      }
      .iti-flag.xk {
      height: 15px;
      background-position: -5500px 0
      }
      .iti-flag.ye {
      height: 14px;
      background-position: -5522px 0
      }
      .iti-flag.yt {
      height: 14px;
      background-position: -5544px 0
      }
      .iti-flag.za {
      height: 14px;
      background-position: -5566px 0
      }
      .iti-flag.zm {
      height: 14px;
      background-position: -5588px 0
      }
      .iti-flag.zw {
      height: 10px;
      background-position: -5610px 0
      }
      .iti-flag {
      width: 20px;
      height: 15px;
      box-shadow: 0 0 1px 0 #888;
      background-image: url("https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/img/flags.png");
      background-repeat: no-repeat;
      background-color: #dbdbdb;
      background-position: 20px 0
      }
      @media only screen and (-webkit-min-device-pixel-ratio:2),
      only screen and (min--moz-device-pixel-ratio:2),
      only screen and (-o-min-device-pixel-ratio:2 / 1),
      only screen and (min-device-pixel-ratio:2),
      only screen and (min-resolution:192dpi),
      only screen and (min-resolution:2dppx) {
      .iti-flag {
      background-size: 5630px 15px;
      background-image: url("https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/img/flags@2x.png")
      }
      }
      body {
      margin: 20px;
      font-size: 14px;
      font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
      color: #555
      }
      pre {
      margin: 0 !important
      }
      .language-css .token.string,
      .style .token.string,
      .token.entity,
      .token.operator,
      .token.url,
      .token.variable {
      background: 0 0
      }
      button,
      input {
      height: 35px;
      margin: 0;
      padding: 6px 12px;
      border-radius: 2px;
      font-family: inherit;
      font-size: 100%;
      color: inherit
      }
      button[disabled],
      input[disabled] {
      background-color: #eee
      }
      input,
      select {
      border: 1px solid #ccc;
      width: 250px
      }
      ::-webkit-input-placeholder {
      color: #bbb
      }
      ::-moz-placeholder {
      color: #bbb;
      opacity: 1
      }
      :-ms-input-placeholder {
      color: #bbb
      }
      button {
      color: #fff;
      background-color: #428bca;
      border: 1px solid #357ebd
      }
      button:hover {
      background-color: #3276b1;
      border-color: #285e8e
      }
      #result {
      margin-bottom: 100px
      }
      .back-arrow-btn i {
      background: #fff;
      font-size: 16px;
      padding: 2px 3px;
      border-radius: 50px;
      border: 2px solid #6c6e70;
      color: #6c6e70;
      margin-right: 15px;
      width: 24px;
      height: 24px
      }
      #showerroralert,
      #showsuccessalert {
      display: none
      }
   </style>
   <script>
      window.console = window.console || function(e) {}, document.location.search.match(/type=embed/gi) && window.parent.postMessage("resize", "*");
      var page_origin = window.location.origin;
      let anchor = document.getElementById("anchor");
      anchor.href = page_origin;
   </script>
   <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/css/intlTelInput.css" rel="stylesheet" media="screen">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js" integrity="sha512-QEAheCz+x/VkKtxeGoDq6nsGyzTx/0LMINTgQjqZ0h3+NjP+bCsPYz3hn0HnBkGmkIFSr7QcEZT+KyEM7lbLPQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"></script>
</head>
<input type="hidden" id="tabID" value="<?= $tabID; ?>">
<div class="main-content app-content mt-0">
   <div class="side-app">
      <div class="main-container container-fluid">
         <div class="page-header">
            <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a><?= $title; ?></h1>
            <div>
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?= $title; ?></li>
               </ol>
            </div>
         </div>
         <div class="row row-sm">
            <div class="col-lg-12">
               <div class="card overflow-hidden">
                  <div class="card-body">
                     <div class="">
                        <div class="mt-2">
                           <?php if ($tabID == 'customer') { ?>
                           <!--<div class="row mb-2">-->
                           <!--	<div class="col-md-6 mb-2">-->
                           <!--		<form method="POST">-->
                           <!--			<input name="method" type="hidden" value="download_all_user">-->
                           <!--			<button type="submit" name="down_form" class="btn btn-secondary" style="background: #1170e4 !important;">Download All<i class="fa fa-arrow-down" aria-hidden="true" style="padding: 6px;"></i></button>-->
                           <!--		</form>-->
                           <!--	</div>-->
                           <!--	<div class="col-md-6 mb-2">-->
                           <!--		<form method="POST">-->
                           <!--			<input name="method" type="hidden" value="download_all_user971">-->
                           <!--			<button type="submit" name="down_form971" class="btn btn-secondary" style="background: #1170e4 !important;">Download All UAE Customers<i class="fa fa-arrow-down" aria-hidden="true" style="padding: 6px;"></i></button>-->
                           <!--		</form>-->
                           <!--	</div>-->
                           <!--	<div class="col-md-6 mb-2">-->
                           <!--		<form method="POST">-->
                           <!--			<input name="method" type="hidden" value="download_all_usernon">-->
                           <!--			<button type="submit" name="down_formnon" class="btn btn-secondary" style="background: #1170e4 !important;">Download All Non UAE Customers<i class="fa fa-arrow-down" aria-hidden="true" style="padding: 6px;"></i></button>-->
                           <!--		</form>-->
                           <!--	</div>-->
                           <!--	<div class="col-md-6 mb-2">-->
                           <!--		<form method="POST">-->
                           <!--			<input name="method" type="hidden" value="download_all_user971non">-->
                           <!--			<button type="submit" name="down_form971non" class="btn btn-secondary" style="background: #1170e4 !important;">Download All Non Participate UAE Customers<i class="fa fa-arrow-down" aria-hidden="true" style="padding: 6px;"></i></button>-->
                           <!--		</form>-->
                           <!--	</div>-->
                           <!--</div>-->
                           <?php } ?>
                           <div class="row">
                              <!--<div class="col-md-6">-->
                              <!--   <span>From Date</span>-->
                              <!--   <input type="date" name="datefrom" id="datefrom" value="" class="form-select" max="<?php echo date("Y-m-d"); ?>">-->
                              <!--</div>-->
                              <!--<div class="col-md-6">-->
                              <!--   <span>To Date</span>-->
                              <!--   <input type="date" name="datefill" id="datefill" value="" class="form-select" max="<?php echo date("Y-m-d"); ?>">-->
                              <!--</div>-->
                               <div class="col-md-4">
                                 <span>Select Date</span> &nbsp; <span style="color:red;">*</span>
                                 <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                      <i class="fa fa-calendar"></i>&nbsp;
                                      <span></span> <i class="fa fa-caret-down"></i>
                                 </div>
                              </div>
                              
                              <br>
                              <div class="col-md-4">
                                 <span>Search</span>
                                 <input type="text" name="fieldname" id="fieldname" value="" class="form-control" placeholder="Search by Phone No">
                              </div>
                              <?php if ($tabID != 'support') { ?>
                              <div class="col-md-4">
                                 <span>Deleted Status</span>
                                 <select id="deletedstatus" class="form-select">
                                    <option value="">Seleted Status</option>
                                    <option value="0">Active <?= $addName; ?></option>
                                    <option value="1">Deleted <?= $addName; ?></option>
                                 </select>
                              </div>
                           </div>
                           <?php } ?>
                           <div class="row">
                              <div class="col-md-4">
                                 <button style="margin-top: 22px;" onclick="viewtable()">GO</button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="card">
                  <div class="card-header">
                     <h3 class="card-title"><?= $title; ?></h3>
                     <div class="col-sm">
                        <!--<button type="button" id="smallmodal" data-bs-effect="effect-scale" data-bs-toggle="modal" data-bs-target="#addagent" style="float: right;" class="btn btn-info"><i class="fa fa-user-plus me-2"></i>Add <?= $addName; ?></button>-->
                     </div>
                  </div>
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="exampleTable" style="width:100%;">
                           <thead>
                              <tr>
                                 <th class="wd-15p border-bottom-0"><?= $idname; ?></th>
                                 <?php if ($tabID == 'agents' || $tabID == 'kioskcreater') { ?>
                                 <th class="wd-25p border-bottom-0">Type</th>
                                 <?php } ?>
                                 <th class="wd-15p border-bottom-0">Full Name</th>
                                 <!-- <th class="wd-15p border-bottom-0">Last Name</th> -->
                                 <?php if ($tabID != 'support') { ?>
                                 <!--<th class="wd-20p border-bottom-0">Points</th>-->
                                 <?php } ?>
                                 <th class="wd-15p border-bottom-0">Phone No.</th>
                                 <th class="wd-10p border-bottom-0">Emirate / Passport ID</th>
                                 <th class="wd-25p border-bottom-0">E-mail</th>
                                 <?php if ($tabID == 'agents') { ?>
                                 <th class="wd-25p border-bottom-0"><?= ($tabID == 'affiliate') ? 'Company/Shop Name' : 'Building Name'; ?></th>
                                 <th class="wd-25p border-bottom-0">Location</th>
                                 <th class="wd-25p border-bottom-0">City</th>
                                 <th class="wd-25p border-bottom-0">Country</th>
                                 <?php } ?>
                                 <th class="wd-25p border-bottom-0">Nationality</th>
                                 <th class="wd-25p border-bottom-0">Residing Location</th>
                                 <th class="wd-25p border-bottom-0">Date of Birth</th>
                                 <th class="wd-25p border-bottom-0">Created On</th>
                                 <th class="wd-25p border-bottom-0">Action</th>
                              </tr>
                           </thead>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="addagent">
   <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content modal-content-demo">
         <div class="modal-header">
            <h6 class="modal-title">Add <?= $addName; ?></h6>
            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="cls_form_div">
            <form class="login100-form validate-form cls_booking_form">
               <div class="modal-body">
                  <div class="row">
                     <div class="col-sm-12">
                        <div id="createformerror"></div>
                        <div class="alert alert-danger" role="alert" id="showerroralert">
                        </div>
                        <div class="alert alert-success" role="alert" id="showsuccessalert">
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-sm-12">
                        <label class="lb-text">Full Name&nbsp;<sup style="color: red;weight: bolder;">*</sup></label><br>
                        <div class="wrap-input100 validate-input input-group">
                           <a href="javascript:void(0)" class="input-group-text bg-white text-muted"><i class="mdi mdi-account" aria-hidden="true"></i></a>
                           <input class="input100 border-start-0 ms-0 form-control req-empty" id="name" name="name" oninput="this.value = this.value.replace(/[^A-Za-z. ]/g, '');" type="text" placeholder="Full Name" required>
                        </div>
                        <label class="lb-text">Last Name&nbsp;<sup style="color: red; weight: bolder;">*</sup></label><br>
                        <div class="wrap-input100 validate-input input-group">
                           <a href="javascript:void(0)" class="input-group-text bg-white text-muted"><i class="mdi mdi-account" aria-hidden="true"></i></a>
                           <input class="input100 border-start-0 ms-0 form-control req-empty" name="lname" id="lname" oninput="this.value = this.value.replace(/[^A-Za-z. ]/g, '');" type="text" placeholder="Last Name" required>
                        </div>
                        <label class="lb-text">Mobile No&nbsp;<sup style="color: red;weight: bolder;">*</sup></label><br>
                        <div class="wrap-input100 validate-input input-group">
                           <input id="phone" type="tel" oninput="mobile_number_validation($(this).val())" onfocusout="Email_check()" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="15">
                           <span id="valid-msg" class="hide">Valid</span>
                           <span id="error-msg" class="hide">Invalid number</span>
                        </div>
                        <label class="lb-text">Email&nbsp;<sup style="color: red;weight: bolder;">*</sup></label><br>
                        <div class="wrap-input100 validate-input input-group">
                           <a href="javascript:void(0)" class="input-group-text bg-white text-muted"><i class="zmdi zmdi-email" aria-hidden="true"></i></a>
                           <input class="input100 border-start-0 ms-0 form-control req-empty" id="email" name="email" type="email" placeholder="Email" required>
                        </div>
                        <label class="lb-text"><?= ($tabID == 'affiliate') ? 'Company/Shop Name' : 'Building Name'; ?></label><br>
                        <div class="wrap-input100 validate-input input-group">
                           <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                           <i class="fa fa-hospital-o" aria-hidden="true"></i>
                           </a>
                           <input type="text" value="<?= $row['building_name']; ?>" oninput="this.value = this.value.replace(/[^A-Za-z0-9#,. `]/g, '');" name="bulidingname" class="form-control" id="bulidingname" placeholder="Building Name ">
                        </div>
                        <div class="wrap-input100 validate-input input-group">
                           <div class="form-group" style="width: 100%;" id="gfhdjkdk">
                              <label class="lb-text">Country&nbsp;<sup style="color: red;weight: bolder;">*</sup></label>
                              <div class="disc-pot">
                                 <select class="form-control form-select selectpicker" data-live-search="true" id="nationlaity" name="nationlaity" onchange="getState($(this).val())" required>
                                    <option value="">Select Country</option>
                                    <?php
                                       echo("SELECT `countries`.`id` AS `id`, `countries`.`name` AS `name`, COUNT(states.id) AS `statecount` FROM `countries` INNER JOIN `states` ON `countries`.`id` = `states`.`country_id` WHERE `countries`.`flag` = '1' GROUP BY countries.id HAVING `statecount` > 0 ORDER BY name ASC;");
                                       $countries = mysqli_query($con, "SELECT `countries`.`id` AS `id`, `countries`.`name` AS `name`, COUNT(states.id) AS `statecount` FROM `countries` INNER JOIN `states` ON `countries`.`id` = `states`.`country_id` WHERE `countries`.`flag` = '1' GROUP BY countries.id HAVING `statecount` > 0 ORDER BY name ASC;");
                                       while ($value = mysqli_fetch_array($countries)) : ?>
                                    <option value="<?= $value['id']; ?>" <?= (strtolower(utf8_encode($value['name'])) == strtolower(utf8_encode($row['nationality']))) ? 'selected' : ''; ?>><?= utf8_encode($value['name']); ?></option>
                                    <?php endwhile; ?>
                                 </select>
                              </div>
                           </div>
                        </div>
                        <div class="wrap-input100 validate-input input-group">
                           <div style="width: 100%;" class="form-group">
                              <label class="lb-text">State / Emirates&nbsp;<sup style="color: red;weight: bolder;">*</sup></label>
                              <select class="form-control  form-select selectpicker" data-live-search="true" id="billing_address" name="billing_address" onchange="getCity($(this).val())" required>
                                 <option value="">Select State / Emirates</option>
                              </select>
                              <p style="color:#18ff36; font-weight:bold; font-size:12px;" id="billingerrorinfo"></p>
                           </div>
                        </div>
                        <div class="wrap-input100 validate-input input-group">
                           <div style="width: 100%;" class="form-group">
                              <label class="lb-text">Area / District&nbsp;<sup style="color: red;weight: bolder;">*</sup></label>
                              <select class="form-control  form-select selectpicker" data-live-search="true" id="billing_city" name="billing_city" required>
                                 <option value="">Select Area / District </option>
                              </select>
                              <p style="color:#18ff36; font-weight:bold; font-size:12px;" id="billingcityerrorinfo"></p>
                           </div>
                        </div>
                        <!-- <div class="wrap-input100 validate-input input-group">
                           <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                           	<i class="fa fa-address-book-o" aria-hidden="true"></i>
                           </a>
                           <input type="text" value="<?= $row['address']; ?>" oninput="this.value = this.value.replace(/[^A-Za-z0-9,./ ]/g, '');" name="buildinglocation" class="form-control" id="buildinglocation" placeholder="Location ">
                           </div>
                           
                           <div class="wrap-input100 validate-input input-group">
                           <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                           	<i class="fa fa-map-marker" aria-hidden="true"></i>
                           </a>
                           <input type="text" value="<?= $row['city']; ?>" name="City" oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '');" class="form-control" id="City" placeholder="City ">
                           </div>
                           
                           <div class="wrap-input100 validate-input input-group">
                           <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                           	<i class="fa fa-globe" aria-hidden="true"></i>
                           </a>
                           <input type="text" value="<?= $row['nationality']; ?>" name="Country" oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '');" class="form-control" id="Country" placeholder="Country ">
                           </div> -->
                        <?php if ($tabID != 'customer') { ?>
                        <label class="lb-text">Password&nbsp;<sup style="color: red; font-weight: bolder;">*</sup></label><br>
                        <div class="wrap-input100 validate-input input-group">
                           <a href="javascript:void(0)" class="input-group-text bg-white text-muted toggle-password">
                           <i class="fa fa-eye" toggle="#password" aria-hidden="true"></i>
                           </a>
                           <input class="input100 border-start-0 ms-0 form-control" id="password" name="Password" type="password" placeholder="Password" minlength="6" maxlength="20">&nbsp;
                           <i onclick="generatePassword(7)" style="font-size: 20px!important;color: lime;cursor: pointer;margin: auto 0;" class="fa fa-refresh" aria-hidden="true"></i>
                        </div>
                        <?php } ?>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <div id="rebtn">
                     <button class="btn ripple btn-success" id="smallmodal" type="button" onclick="sendotp()">Create <?= $addName; ?></button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="otp">
   <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content modal-content-demo">
         <div id="otperror"></div>
         <div class="modal-header">
            <h6 class="modal-title">OTP</h6>
            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="container">
               <form action="#" class="otp_form">
                  <div class="form-group">
                     <div class="model-text">
                        <h3 class="text-center"><b id="title"></b> verification</h3>
                        <p class="text-center">Enter the code we just send on your <b id="title"></b> <b id="mno"></b></p>
                        <br>
                     </div>
                     <div class="row">
                        <div class="col-3">
                           <input type="text" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" onkeyup="onKeyUpEvent(1, event)" onfocus="onFocusEvent(1)" class="form-control" id="otp1" name="otp1">
                        </div>
                        <div class="col-3">
                           <input type="text" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" onkeyup="onKeyUpEvent(2, event)" onfocus="onFocusEvent(2)" class="form-control" id="otp2" name="otp2">
                        </div>
                        <div class="col-3">
                           <input type="text" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" onkeyup="onKeyUpEvent(3, event)" onfocus="onFocusEvent(3)" class="form-control" id="otp3" name="otp3">
                        </div>
                        <div class="col-3">
                           <input type="text" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" onkeyup="onKeyUpEvent(4, event)" onfocus="onFocusEvent(4)" class="form-control" id="otp4" name="otp4">
                        </div>
                     </div>
                  </div>
               </form>
               <br>
            </div>
         </div>
         <div class="modal-footer" id="otpbtn">
            <!-- <button class="btn ripple btn-success" onclick="saveformNew()" type="button">Submit</button>
               <button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button> -->
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="sussessmodal">
   <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content modal-content-demo">
         <div class="modal-header">
            <h6 class="modal-title">Success</h6>
            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div id="successerror">
            </div>
         </div>
         <div class="modal-footer">
            <button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="delete">
   <div class="modal-dialog modal-dialog-centered text-center" role="document">
      <div class="modal-content tx-size-sm">
         <div class="modal-body text-center p-4 pb-5">
            <button aria-label="Close" class="btn-close position-absolute" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            <i class="icon icon-close fs-70 text-danger lh-1 my-5 d-inline-block"></i>
            <h4 class="text-danger">Are you sure you want to delete page</h4>
            <button aria-label="Close" class="btn btn-primary pd-x-25" data-bs-dismiss="modal">Yes</button>
            <button aria-label="Close" class="btn btn-danger pd-x-25" data-bs-dismiss="modal">No</button>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="showimgmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Preview</h5>
            <button type="button" class="close" onclick="closemodal('showimgmodal')">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body" id="imagecontent">
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="deleteinfo" tabindex="-1" role="dialog" aria-labelledby="customModalLabel" aria-modal="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="customModalLabel">Delete</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
         </div>
         <div class="modal-body">
            <div class="text-center">
               <p class="mb-3 modalques">Are you sure to Delete Truck Fare?</p>
            </div>
         </div>
         <div class="modal-footer custom">
            <div class="left-side">
               <button aria-label="Close" class="btn btn-primary pd-x-25" data-bs-dismiss="modal">No</button>
            </div>
            <div class="divider"></div>
            <div class="right-side">
               <button aria-label="Close" class="btn btn-danger pd-x-25 success" data-bs-dismiss="modal">Yes</button>
            </div>
         </div>
      </div>
   </div>
</div>
<script id="rendered-js">
   var state = '<?= strtolower($row['address']); ?>';
   var city = '<?= strtolower($row['city']); ?>';
   var origin = window.location.origin;
   
   var url = origin + "/ajax/service/datatable_services.php";
   
   var agenturl = origin + "/ajax/service/agent_services.php";
   
    //   $(function() {
       
    // });
   
   $(function() {
   
   	var searchValue = $(location).attr('href').split('?')[1];
   	if (searchValue != '' && searchValue != undefined) {
   		$('#fieldname').val(unescape(searchValue));
   		viewtable();
   	}
   
   	getState($('#nationlaity').val());
   	
   	
    var start = moment().subtract(1, 'days');
    var end = moment();
    
    function cb(start, end) {
        $('#reportrange span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY'));
    }
    
    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
    
    cb(start, end);

   	
   	
   });
   
   
   
   function viewtable() {
   
   	let tabID = $('#tabID').val();
   
   	// let datefrom = $('#datefrom').val();
   
   	// let datefill = $('#datefill').val();
   	
   	 var datefrom = $('#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
             var datefill = $('#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
   
   	let fieldname = $('#fieldname').val();
   
   	let deletedstatus = $('#deletedstatus').val();
   	let act = deletedstatus;
   
   
   	if (datefrom == '' || datefrom != '' || datefill != '' || datefill == '' || fieldname != '' || deletedstatus != '' || deletedstatus == '') {
   
   		var table = $('#exampleTable').DataTable();
   
   		table.destroy();
   
   		if (tabID == 'agents') {
   			if ((datefrom != '' || datefill != '') && (deletedstatus == 1 && deletedstatus != "")) {
   				var excelTitle = 'My Agent List Reports : (' + fieldname + ' ' + "Deleted <?= $addName; ?>" + ' ' + datefrom + ' to ' + datefill + ')';
   			} else if ((datefrom != '' || datefill != '') && (deletedstatus == 0 && deletedstatus != "")) {
   				var excelTitle = 'My Agent List Reports : (' + fieldname + ' ' + "Active <?= $addName; ?>" + ' ' + datefrom + ' to ' + datefill + ')';
   			} else if (datefrom != '' || datefill != '') {
   				var excelTitle = 'My Agent List Reports : (' + fieldname + ' ' + datefrom + ' to ' + datefill + ')';
   			} else if (deletedstatus == 1) {
   				var excelTitle = 'My Agent List Reports : (' + fieldname + ' ' + "Deleted <?= $addName; ?>" + ')';
   
   			} else if (deletedstatus == "") {
   				var excelTitle = 'My Agent List Reports : (all data)';
   
   			} else if (deletedstatus == 0) {
   				var excelTitle = 'My Agent List Reports : (' + fieldname + ' ' + "Active <?= $addName; ?>" + ')';
   
   			}
   
   			table = $("#exampleTable").DataTable({
   
   				pageLength: 10,
   
   				order: [
   					[11, 'desc']
   				],
   				columnDefs: [{
   						type: 'date',
   						targets: [11]
   					} // Assuming the date column is at index 2
   				],
   
   
   				paging: true,
   
   				searching: true,
   
   				info: true,
   
   				ajax: {
   
   					url: url,
   
   					method: "POST",
   
   					dataSrc: "",
   
   					data: {
   
   						method: 'list_agent',
   
   						role: '2,3,4,5',
   
   						type: 'agent',
   
   						datefill: datefill,
   
   						fieldname: fieldname,
   
   						datefrom: datefrom,
   
   						deletedstatus: deletedstatus
   
   					}
   
   				},
   
   				dom: 'Bfrtip',
   
   				lengthMenu: [
   
   					[10, 25, 50, 100, 200, 500, -1],
   
   					[10, 25, 50, 100, 200, 500, 'All'],
   
   				],
   
   				buttons: [
   
   					'pageLength',
   
   					{
   
   						extend: 'copyHtml5',
   
   						title: excelTitle
   
   					},
   
   					{
   
   						extend: 'csvHtml5',
   
   						title: excelTitle
   
   					},
   
   					{
   
   						extend: 'excelHtml5',
   
   						title: excelTitle
   
   					},
   
   					{
   
   						extend: 'pdfHtml5',
   
   						orientation: 'landscape',
   
   						pageSize: 'LEGAL',
   
   						title: excelTitle
   
   					},
   
   					{
   
   						extend: 'print',
   
   						title: excelTitle
   
   					},
   
   				],
   
   				columns: [{
   
   						data: "nid"
   
   					},
   
   
   
   					{
   
   						data: "rollType"
   
   					},
   
   
   
   					{
   
   						data: "name"
   
   					},
   					// {
   
   					// data: "lname"
   
   					// },
   
   
   			// 		{
   
   			// 			data: "t_point"
   
   			// 		},
   
   					{
   
   						data: "mobile"
   
   					},
   
   					{
   
   						data: "passport"
   
   					},
   
   					{
   
   						data: "email"
   
   					},
   					{
   
   						data: "bulidingname"
   
   					},
   					{
   
   						data: "buildinglocation"
   
   					},
   					{
   
   						data: "City"
   
   					},
   					{
   
   						data: "Country"
   
   					},
   					{
   						data: null,
   						render: function(data, type, row, meta) {
   							return moment(data.createdat).format("DD MMM YYYY hh:mm a")
   						}
   					},
   
   					{
   
   						data: "action"
   
   					}
   
   				]
   
   			});
   
   
   
   		} else if (tabID == 'customer') {
   		    
   		    
   
   			if ((datefrom != '' || datefill != '') && (deletedstatus == 1 && deletedstatus != "")) {
   				var excelTitle = 'My Customer List Reports : (' + fieldname + ' ' + "Deleted <?= $addName; ?>" + ' ' + datefrom + ' to ' + datefill + ')';
   			} else if ((datefrom != '' || datefill != '') && (deletedstatus == 0 && deletedstatus != "")) {
   				var excelTitle = 'My Customer List Reports : (' + fieldname + ' ' + "Active <?= $addName; ?>" + ' ' + datefrom + ' to ' + datefill + ')';
   			} else if (datefrom != '' || datefill != '') {
   				var excelTitle = 'My Customer List Reports : (' + datefrom + ' to ' + datefill + ')';
   			} else if (deletedstatus == 1) {
   				var excelTitle = 'My Customer List Reports : (' + fieldname + ' ' + "Deleted <?= $addName; ?>" + ')';
   
   			} else if (deletedstatus == "") {
   				var excelTitle = 'My Customer List Reports : (all data)';
   
   			} else if (deletedstatus == 0) {
   				var excelTitle = 'My Customer List Reports: (' + fieldname + ' ' + "Active <?= $addName; ?>" + ')';
   
   			}
   			
   	// 		var datefrom = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
    //         var datefill = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");
            
            
   			table = $("#exampleTable").DataTable({
   
   				pageLength: 10,
   
   				order: [
   					[6, 'desc']
   				],
   				columnDefs: [{
   						type: 'date',
   						targets: [6]
   					} // Assuming the date column is at index 2
   				],
   
   				paging: true,
   
   				searching: true,
   
   				info: true,
   
   				ajax: {
   
   					url: url,
   
   					method: "POST",
   
   					dataSrc: "",
   
   					data: {
   
   						method: 'list_agent',
   
   						role: '0',
   
   						datefill: datefill,
   
   						fieldname: fieldname,
   
   						datefrom: datefrom,
   
   						deletedstatus: deletedstatus
   
   					}
   
   				},
   
   				dom: 'Bfrtip',
   
   				lengthMenu: [
   
   					[10, 25, 50, 100, 200, 500, -1],
   
   					[10, 25, 50, 100, 200, 500, 'All'],
   
   				],
   
   				buttons: [
   
   					'pageLength',
   
   		
   					{
   
   						extend: 'excelHtml5',
   
   						title: excelTitle
   
   					},
   
   				],
   
   				columns: [{
   
   						data: "nid"
   
   					},
   
   					{
   
   						data: "name"
   
   					},
   					// 						{
   
   					// data: "lname"
   
   					// },
   
   			// 		{
   
   			// 			data: "t_point"
   
   			// 		},
   
   					{
   
   						data: "mobile"
   
   					},
   
   					{
   
   						data: "passport"
   
   					},
   
   					{
   
   						data: "email"
   
   					},
   						{
   
   						data: "Country"
   
   					},
   						{
   
   						data: "residinglocation"
   
   					},
   						{
   
   						data: "dob"
   
   					},
   
   					// {
   
   					// 	data: "createdat"
   
   					// },
   					{
   						data: null,
   						render: function(data, type, row, meta) {
   							return moment(data.createdat).format("DD MMM YYYY hh:mm a")
   						}
   					},
   					{
   
   						data: "action"
   
   					}
   
   				]
   
   			});
   
   		} else if (tabID == 'support') {
   
   
   
   			if ((datefrom != '' || datefill != '') && (deletedstatus == 1 && deletedstatus != "")) {
   				var excelTitle = 'Customer support List Reports : (' + fieldname + ' ' + "Deleted <?= $addName; ?>" + ' ' + datefrom + ' to ' + datefill + ')';
   			} else if ((datefrom != '' || datefill != '') && (deletedstatus == 0 && deletedstatus != "")) {
   				var excelTitle = 'Customer support List Reports : (' + fieldname + ' ' + "Active <?= $addName; ?>" + ' ' + datefrom + ' to ' + datefill + ')';
   			} else if (datefrom != '' || datefill != '') {
   				var excelTitle = 'Customer support List Reports : (' + fieldname + ' ' + datefrom + ' to ' + datefill + ')';
   			} else if (deletedstatus == 1) {
   				var excelTitle = 'Customer support List Reports : (' + fieldname + ' ' + "Deleted <?= $addName; ?>" + ')';
   
   			} else if (deletedstatus == "") {
   				var excelTitle = 'Customer support List Reports : (all data)';
   
   			} else if (deletedstatus == 0) {
   				var excelTitle = 'Customer support List Reports : (' + fieldname + ' ' + "Active <?= $addName; ?>" + ')';
   
   			}
   
   			// 	if(datefrom != '' || datefill != '' ){
   			// 	var excelTitle = 'Customer support List Reports : (' + fieldname + ' ' + deletedstatus + ' ' + datefrom + ' to ' + datefill + ')';
   			//    }
   			//    else if(fieldname != '' || deletedstatus != '' ){
   			// 	var excelTitle = 'Customer support List Reports : (' + fieldname + ' ' + deletedstatus + ')';
   
   			//    }else{
   			// 	var excelTitle = 'Customer support List Reports : (all data)';
   
   			//    }
   
   
   
   			// var excelTitle = 'Customer support List Reports : (' + fieldname + ' ' + datefrom + '  ' + datefill + ')';
   
   
   
   			table = $("#exampleTable").DataTable({
   
   
   
   				pageLength: 10,
   
   
   
   				order: [
   
   					[0, 'desc']
   
   				],
   
   				columnDefs: [{
   
   						type: 'date',
   
   						targets: [6]
   
   					} // Assuming the date column is at index 2
   
   				],
   
   
   
   				paging: true,
   
   
   
   				searching: true,
   
   
   
   				info: true,
   
   
   
   				ajax: {
   
   
   
   					url: url,
   
   
   
   					method: "POST",
   
   
   
   					dataSrc: "",
   
   
   
   					data: {
   
   
   
   						method: 'list_agent',
   
   
   
   						role: '11',
   
   
   
   						datefill: datefill,
   
   
   
   						fieldname: fieldname,
   
   
   
   						datefrom: datefrom,
   
   
   
   						deletedstatus: deletedstatus
   
   
   
   					}
   
   
   
   				},
   
   
   
   				dom: 'Bfrtip',
   
   
   
   				llengthMenu: [
   
   
   
   					[10, 25, 50, 100, 200, 500, -1],
   
   
   
   					[10, 25, 50, 100, 200, 500, 'All'],
   
   
   
   				],
   
   
   
   				buttons: [
   
   
   
   					'pageLength',
   
   
   
   					{
   
   
   
   						extend: 'copyHtml5',
   
   
   
   						title: excelTitle
   
   
   
   					},
   
   
   
   					{
   
   
   
   						extend: 'csvHtml5',
   
   
   
   						title: excelTitle
   
   
   
   					},
   
   
   
   					{
   
   
   
   						extend: 'excelHtml5',
   
   
   
   						title: excelTitle
   
   
   
   					},
   
   
   
   					{
   
   
   
   						extend: 'pdfHtml5',
   
   
   
   						orientation: 'landscape',
   
   
   
   						pageSize: 'LEGAL',
   
   
   
   						title: excelTitle
   
   
   
   					},
   
   
   
   					{
   
   
   
   						extend: 'print',
   
   
   
   						title: excelTitle
   
   
   
   					},
   
   
   
   				],
   
   
   
   				columns: [{
   
   
   
   						data: "nid"
   
   
   
   					},
   
   
   
   					{
   
   
   
   						data: "name"
   
   
   
   					},
   					// 						{
   
   					// data: "lname"
   
   					// },
   
   
   
   
   
   
   
   					{
   
   
   
   						data: "mobile"
   
   
   
   					},
   
   
   
   					{
   
   
   
   						data: "passport"
   
   
   
   					},
   
   
   
   					{
   
   
   
   						data: "email"
   
   
   
   					},
   
   
   
   					// {
   
   
   
   					// 	data: "createdat"
   
   
   
   					// },
   
   					{
   
   						data: null,
   
   						render: function(data, type, row, meta) {
   
   							return moment(data.createdat).format("DD MMM YYYY hh:mm a")
   
   						}
   
   					},
   
   					{
   
   
   
   						data: "action"
   
   
   
   					}
   
   
   
   				]
   
   
   
   			});
   
   
   
   		} else if (tabID == 'kioskcreater') {
   
   			if ((datefrom != '' || datefill != '') && (deletedstatus == 1 && deletedstatus != "")) {
   				var excelTitle = 'My kiosk List Reports : (' + fieldname + ' ' + "Deleted <?= $addName; ?>" + ' ' + datefrom + ' to ' + datefill + ')';
   			} else if ((datefrom != '' || datefill != '') && (deletedstatus == 0 && deletedstatus != "")) {
   				var excelTitle = 'My kiosk List Reports : (' + fieldname + ' ' + "Active <?= $addName; ?>" + ' ' + datefrom + ' to ' + datefill + ')';
   			} else if (datefrom != '' || datefill != '') {
   				var excelTitle = 'My kiosk List Reports : (' + fieldname + ' ' + datefrom + ' to ' + datefill + ')';
   			} else if (deletedstatus == 1) {
   				var excelTitle = 'My kiosk List Reports : (' + fieldname + ' ' + "Deleted <?= $addName; ?>" + ')';
   
   			} else if (deletedstatus == "") {
   				var excelTitle = 'My kiosk List Reports : (all data)';
   
   			} else if (deletedstatus == 0) {
   				var excelTitle = 'My kiosk List Reports : (' + fieldname + ' ' + "Active <?= $addName; ?>" + ')';
   
   			}
   
   			// 	if(datefrom != '' || datefill != '' ){
   			// 	var excelTitle = 'My kiosk List Reports : (' + fieldname + ' ' + deletedstatus + ' ' + datefrom + ' to ' + datefill + ')';
   			//    }
   			//    else if(fieldname != '' || deletedstatus != '' ){
   			// 	var excelTitle = 'My kiosk List Reports : (' + fieldname + ' ' + deletedstatus + ')';
   
   			//    }else{
   			// 	var excelTitle = 'My kiosk List Reports : (all data)';
   
   			//    }
   
   
   			// var excelTitle = 'My kiosk List Reports : (' + fieldname + ' ' + deletedstatus + ' ' + datefrom + ' to ' + datefill + ')';
   			table = $("#exampleTable").DataTable({
   
   				pageLength: 10,
   
   				order: [
   					[6, 'desc']
   				],
   				columnDefs: [{
   						type: 'date',
   						targets: [6]
   					} // Assuming the date column is at index 2
   				],
   
   				paging: true,
   
   				searching: true,
   
   				info: true,
   
   				ajax: {
   
   					url: url,
   
   					method: "POST",
   
   					dataSrc: "",
   
   					data: {
   
   						method: 'list_agent',
   
   						role: '9',
   
   						datefill: datefill,
   
   						fieldname: fieldname,
   
   						datefrom: datefrom,
   
   						deletedstatus: deletedstatus
   
   					}
   
   				},
   
   				dom: 'Bfrtip',
   
   				lengthMenu: [
   
   					[10, 25, 50, 100, 200, 500, -1],
   
   					[10, 25, 50, 100, 200, 500, 'All'],
   
   				],
   
   				buttons: [
   
   					'pageLength',
   
   					{
   
   						extend: 'copyHtml5',
   
   						title: excelTitle
   
   					},
   
   					{
   
   						extend: 'csvHtml5',
   
   						title: excelTitle
   
   					},
   
   					{
   
   						extend: 'excelHtml5',
   
   						title: excelTitle
   
   					},
   
   					{
   
   						extend: 'pdfHtml5',
   
   						orientation: 'landscape',
   
   						pageSize: 'LEGAL',
   
   						title: excelTitle
   
   					},
   
   					{
   
   						extend: 'print',
   
   						title: excelTitle
   
   					},
   
   				],
   
   				columns: [{
   
   						data: "nid"
   
   					},
   
   					{
   
   						data: "rollType"
   
   					},
   					{
   
   						data: "name"
   
   					},
   					// {
   
   					// data: "lname"
   
   					// },
   					{
   
   						data: "t_point"
   
   					},
   
   					{
   
   						data: "mobile"
   
   					},
   
   					{
   
   						data: "passport"
   
   					},
   
   					{
   
   						data: "email"
   
   					},
   
   					// {
   
   					// 	data: "createdat"
   
   					// },
   					{
   						data: null,
   						render: function(data, type, row, meta) {
   							return moment(data.createdat).format("DD MMM YYYY hh:mm a")
   						}
   					},
   					{
   
   						data: "action"
   
   					}
   
   				]
   
   			});
   
   		} else if (tabID == 'staffs') {
   
   			if ((datefrom != '' || datefill != '') && (deletedstatus == 1 && deletedstatus != "")) {
   				var excelTitle = 'My Staffs List Reports : (' + fieldname + ' ' + "Deleted <?= $addName; ?>" + ' ' + datefrom + ' to ' + datefill + ')';
   			} else if ((datefrom != '' || datefill != '') && (deletedstatus == 0 && deletedstatus != "")) {
   				var excelTitle = 'My Staffs List Reports : (' + fieldname + ' ' + "Active <?= $addName; ?>" + ' ' + datefrom + ' to ' + datefill + ')';
   			} else if (datefrom != '' || datefill != '') {
   				var excelTitle = 'My Staffs List Reports : (' + fieldname + ' ' + datefrom + ' to ' + datefill + ')';
   			} else if (deletedstatus == 1) {
   				var excelTitle = 'My Staffs List Reports : (' + fieldname + ' ' + "Deleted <?= $addName; ?>" + ')';
   
   			} else if (deletedstatus == "") {
   				var excelTitle = 'My Staffs List Reports : (all data)';
   
   			} else if (deletedstatus == 0) {
   				var excelTitle = 'My Staffs List Reports : (' + fieldname + ' ' + "Active <?= $addName; ?>" + ')';
   
   			}
   
   			// 	if(datefrom != '' || datefill != '' ){
   			// 	var excelTitle = 'My Staffs List Reports : (' + fieldname + ' ' + deletedstatus + ' ' + datefrom + ' to ' + datefill + ')';
   			//    }
   			//    else if(fieldname != '' || deletedstatus != '' ){
   			// 	var excelTitle = 'My Staffs List Reports : (' + fieldname + ' ' + deletedstatus + ')';
   
   			//    }else{
   			// 	var excelTitle = 'My Staffs List Reports : (all data)';
   
   			//    }
   
   			// /var excelTitle = 'My Staffs List Reports : (' + fieldname + ' ' + deletedstatus + ' ' + datefrom + ' to ' + datefill + ')';
   
   			table = $("#exampleTable").DataTable({
   
   				pageLength: 10,
   
   				order: [
   					[6, 'desc']
   				],
   				columnDefs: [{
   						type: 'date',
   						targets: [6]
   					} // Assuming the date column is at index 2
   				],
   
   				paging: true,
   
   				searching: true,
   
   				info: true,
   
   				ajax: {
   
   					url: url,
   
   					method: "POST",
   
   					dataSrc: "",
   
   					data: {
   
   						method: 'list_agent',
   
   						role: '6',
   
   						datefill: datefill,
   
   						fieldname: fieldname,
   
   						datefrom: datefrom,
   
   						deletedstatus: deletedstatus
   
   					}
   
   				},
   
   				dom: 'Bfrtip',
   
   				llengthMenu: [
   
   					[10, 25, 50, 100, 200, 500, -1],
   
   					[10, 25, 50, 100, 200, 500, 'All'],
   
   				],
   
   				buttons: [
   
   					'pageLength',
   
   					{
   
   						extend: 'copyHtml5',
   
   						title: excelTitle
   
   					},
   
   					{
   
   						extend: 'csvHtml5',
   
   						title: excelTitle
   
   					},
   
   					{
   
   						extend: 'excelHtml5',
   
   						title: excelTitle
   
   					},
   
   					{
   
   						extend: 'pdfHtml5',
   
   						orientation: 'landscape',
   
   						pageSize: 'LEGAL',
   
   						title: excelTitle
   
   					},
   
   					{
   
   						extend: 'print',
   
   						title: excelTitle
   
   					},
   
   				],
   
   				columns: [{
   
   						data: "nid"
   
   					},
   
   					{
   
   						data: "name"
   
   					},
   					// 						{
   
   					// data: "lname"
   
   					// },
   
   			// 		{
   
   			// 			data: "t_point"
   
   			// 		},
   
   					{
   
   						data: "mobile"
   
   					},
   
   					{
   
   						data: "passport"
   
   					},
   
   					{
   
   						data: "email"
   
   					},
   
   					// {
   
   					// 	data: "createdat"
   
   					// },
   					{
   						data: null,
   						render: function(data, type, row, meta) {
   							return moment(data.createdat).format("DD MMM YYYY hh:mm a")
   						}
   					},
   					{
   
   						data: "action"
   
   					}
   
   				]
   
   			});
   
   		} else if (tabID == 'affiliate') {
   
   			if ((datefrom != '' || datefill != '') && (deletedstatus == 1 && deletedstatus != "")) {
   				var excelTitle = 'Affiliate User List : (' + fieldname + ' ' + "Deleted <?= $addName; ?>" + ' ' + datefrom + ' to ' + datefill + ')';
   			} else if ((datefrom != '' || datefill != '') && (deletedstatus == 0 && deletedstatus != "")) {
   				var excelTitle = 'Affiliate User List : (' + fieldname + ' ' + "Active <?= $addName; ?>" + ' ' + datefrom + ' to ' + datefill + ')';
   			} else if (datefrom != '' || datefill != '') {
   				var excelTitle = 'Affiliate User List : (' + fieldname + ' ' + datefrom + ' to ' + datefill + ')';
   			} else if (deletedstatus == 1) {
   				var excelTitle = 'Affiliate User List : (' + fieldname + ' ' + "Deleted <?= $addName; ?>" + ')';
   
   			} else if (deletedstatus == "") {
   				var excelTitle = 'Affiliate User List : (all data)';
   
   			} else if (deletedstatus == 0) {
   				var excelTitle = 'Affiliate User List : (' + fieldname + ' ' + "Active <?= $addName; ?>" + ')';
   
   			}
   
   			// 	if(datefrom != '' || datefill != '' ){
   			// 	var excelTitle = 'Affiliate User List : (' + fieldname + ' ' + deletedstatus + ' ' + datefrom + ' to ' + datefill + ')';
   			//    }
   			//    else if(fieldname != '' || deletedstatus != '' ){
   			// 	var excelTitle = 'Affiliate User List : (' + fieldname + ' ' + deletedstatus + ')';
   
   			//    }else{
   			// 	var excelTitle = 'Affiliate User List : (all data)';
   
   			//    }
   
   			// var excelTitle = 'Affiliate User List : (' + fieldname + ' ' + deletedstatus + ' ' + datefrom + ' to ' + datefill + ')';
   
   			table = $("#exampleTable").DataTable({
   
   				pageLength: 10,
   
   				order: [
   					[6, 'desc']
   				],
   				columnDefs: [{
   						type: 'date',
   						targets: [6]
   					} // Assuming the date column is at index 2
   				],
   
   				paging: true,
   
   				searching: true,
   
   				info: true,
   
   				ajax: {
   
   					url: url,
   
   					method: "POST",
   
   					dataSrc: "",
   
   					data: {
   
   						method: 'list_agent',
   
   						role: '7',
   
   						datefill: datefill,
   
   						fieldname: fieldname,
   
   						datefrom: datefrom,
   
   						deletedstatus: deletedstatus
   
   					}
   
   				},
   
   				dom: 'Bfrtip',
   
   				llengthMenu: [
   
   					[10, 25, 50, 100, 200, 500, -1],
   
   					[10, 25, 50, 100, 200, 500, 'All'],
   
   				],
   
   				buttons: [
   
   					'pageLength',
   
   					{
   
   						extend: 'copyHtml5',
   
   						title: excelTitle
   
   					},
   
   					{
   
   						extend: 'csvHtml5',
   
   						title: excelTitle
   
   					},
   
   					{
   
   						extend: 'excelHtml5',
   
   						title: excelTitle
   
   					},
   
   					{
   
   						extend: 'pdfHtml5',
   
   						orientation: 'landscape',
   
   						pageSize: 'LEGAL',
   
   						title: excelTitle
   
   					},
   
   					{
   
   						extend: 'print',
   
   						title: excelTitle
   
   					},
   
   				],
   
   				columns: [{
   
   						data: "nid"
   
   					},
   
   					{
   
   						data: "name"
   
   					},
   					// 						{
   
   					// data: "lname"
   
   					// },
   
   					{
   
   						data: "t_point"
   
   					},
   
   					{
   
   						data: "mobile"
   
   					},
   
   					{
   
   						data: "passport"
   
   					},
   
   					{
   
   						data: "email"
   
   					},
   					{
   						data: null,
   						render: function(data, type, row, meta) {
   							return moment(data.createdat).format("DD MMM YYYY hh:mm a")
   						}
   					},
   					// {
   
   					// 	data: "createdat"
   
   					// },
   
   					{
   
   						data: "action"
   
   					}
   
   				]
   
   			});
   
   		}
   	} else {
   
   		toast('error', 'Please Fill Any One');
   
   	}
   
   }
   
   
   
   function sendotp() {
   	if ($('#email').val().trim() == '') {
   		Email_check();
   		return false;
   	}
   
   
   	document.getElementById('createformerror').innerHTML = '';
   
   	let name = $('#name').val();
   	let lname = $('#lname').val();
   	let tabID = $('#tabID').val();
   
   	let pass = $('#password').val();
   
   	let c = '';
   
   	if (tabID == 'customer') {
   
   		c = true;
   
   	} else {
   
   		c = (pass.length >= 6 && tabID != 'customer');
   
   	}
   
   	// function checkLength(pass) {
   
   	if (name != '') {
   
   		if (lname == '' || lname == null || lname == undefined) {
   			document.getElementById('createformerror').innerHTML = '<div class="alert alert-danger" role="alert">Enter Last name</div>';
   			return false;
   		}
   
   
   		let countrycode = $('.selected-dial-code').text()
   
   		let number = $.trim(telInput.val());
   		var test = number;
   		var trim_number = parseInt(test.charAt(0));
   
   		if (trim_number == 0) {
   			number = number.slice(1);
   
   
   		}
   		let email = $('#email').val();
   
   		if ((email != '') && (validateEmail(email))) {
   
   
   
   			if (number != '') {
   
   				if ($('#bulidingname').val() == '') {
   					let address = "<?= $row['building_name']; ?>";
   					if (address != '') {
   						document.getElementById('bulidingname').value = address;
   					} else {
   						document.getElementById('createformerror').innerHTML = '<div class="alert alert-danger" role="alert">Field Required - Building Name</div>';
   					}
   					return false;
   				}
   
   
   				if ($('#nationlaity').val() == '') {
   					// toast('warning', 'Field Required - Country');
   					// let nationality = "<?= $row['nationality']; ?>";
   					// if (nationality != '') {
   					// 	document.getElementById('Country').value = nationality;
   					// } else {
   					document.getElementById('createformerror').innerHTML = '<div class="alert alert-danger" role="alert">Field Required - Country</div>';
   					// }
   					return false;
   				}
   
   
   				if ($('#billing_address').val() == '') {
   					// let address = "<?= $row['address']; ?>";
   					// if (address != '') {
   					// 	document.getElementById('buildinglocation').value = address;
   					// } else {
   					document.getElementById('createformerror').innerHTML = '<div class="alert alert-danger" role="alert">Field Required - State / Emirates</div>';
   					// }
   					return false;
   				}
   
   				if ($('#billing_city').val() == '') {
   					// let city = "<?= $row['city']; ?>";
   					// if (city != '') {
   					// 	document.getElementById('City').value = city;
   					// } else {
   					document.getElementById('createformerror').innerHTML = '<div class="alert alert-danger" role="alert">Field Required - State / Emirates</div>';
   					// }
   
   					return false;
   				}
   
   
   
   				if (c) {
   
   
   
   
   
   					document.getElementById('rebtn').innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';
   
   
   
   					let country = countrycode.substring(1);
   
   					let mobile = country + number;
   
   					var formdata = $('.cls_booking_form').serializeArray();
   
   					formdata.push({
   
   						name: 'method',
   
   						value: "send_otp"
   
   					});
   
   					formdata.push({
   
   						name: 'mobile',
   
   						value: mobile
   
   					});
   					formdata.push({
   						name: 'countrycode',
   						value: countrycode
   					});
   					var post_data = formdata;
   
   					var onsuccess = function(data) {
   
   						var response = JSON.parse(data);
   
   						if (response != "") {
   
   							if (response.type == 1) {
   
   								document.getElementById('otperror').innerHTML = '<div class="alert alert-success" role="alert">' + response.result + '</div>';
   
   								$('#addagent').modal('hide');
   
   								document.getElementById('rebtn').innerHTML = '<button class="btn ripple btn-success" id="smallmodal" type="button" onclick="sendotp()">Create <?= $addName; ?></button>';
   
   								document.getElementById('mno').innerText = response.Newmobile;
   
   								document.getElementById('title').innerText = response.title;
   								document.getElementById('otpbtn').innerHTML = response.otpbtn;
   								$('#otp').modal('show');
   
   							} else {
   
   								document.getElementById('createformerror').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';
   
   
   
   								document.getElementById('rebtn').innerHTML = '<button class="btn ripple btn-success" id="smallmodal" type="button" onclick="sendotp()">Create <?= $addName; ?></button>';
   
   
   
   							}
   
   
   
   						}
   
   					}
   
   					do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/customer_services.php");
   
   
   
   
   
   				} else {
   
   					document.getElementById('createformerror').innerHTML = '<div class="alert alert-danger" role="alert">Password - Length must be min 6 characters</div>';
   
   
   
   				}
   
   
   
   			} else {
   
   				document.getElementById('createformerror').innerHTML = '<div class="alert alert-danger" role="alert">Enter the mobile no!</div>';
   
   				// alert("length must be exactly 6 characters")
   
   			}
   
   		} else {
   
   			document.getElementById('createformerror').innerHTML = '<div class="alert alert-danger" role="alert">Enter the email!</div>';
   
   			// alert("length must be exactly 6 characters")
   
   		}
   
   	} else {
   
   		document.getElementById('createformerror').innerHTML = '<div class="alert alert-danger" role="alert">Enter name</div>';
   
   	}
   
   
   
   
   
   	// }
   
   }
   
   
   
   function saveformNew(insert_id) {
   	if (insert_id != '') {
   		let tabID = $('#tabID').val();
   
   		var formdata = $('.otp_form').serializeArray();
   
   		formdata.push({
   
   			name: 'method',
   
   			value: "add_agent_new"
   
   		});
   
   		formdata.push({
   
   			name: 'tabID',
   
   			value: tabID
   
   		});
   		formdata.push({
   			name: 'insert_id',
   			value: insert_id
   		});
   		var post_data = formdata;
   
   		var onsuccess = function(data) {
   
   			var response = JSON.parse(data);
   
   			if (response != "") {
   
   				if (response.type == 1) {
   
   					document.getElementById('otperror').innerHTML = '<div class="alert alert-success" role="alert">' + response.result + '</div>';
   
   					$('#otp').modal('hide');
   
   					document.getElementById('successerror').innerHTML = '<div class="alert alert-success" role="alert">' + response.result + '</div>';
   
   					$('#sussessmodal').modal('show');
   
   
   
   					// var table = $('#exampleTable').DataTable();
   
   					var table = $('#exampleTable').DataTable();
   
   					table.destroy();
   
   					viewtable();
   
   					// table.row.add({
   
   					// 	"id": response.id,
   
   					// 	"rollType": response.rollType,
   
   					// 	"name": response.name,
   
   					// 	"t_point": response.t_point,
   
   					// 	"mobile": response.mobile,
   
   					// 	"passport": response.passport,
   
   					// 	"email": response.email,
   
   					// 	"": '<a onclick="viewform(1)" style="cursor: pointer;" data-bs-effect="effect-scale" data-bs-toggle="modal" data-bs-target="#addagent"  data-bs-original-title="Edit"><span class="fe fe-edit fs-14"></span></a><a class="btn text-danger btn-sm" data-bs-target="#deleteinfo" data-bs-toggle="modal" data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-trash-2 fs-14" onclick=deletelist(' + data.id + ')></span></a>'
   
   					// }).draw();
   
   
   
   
   
   				} else {
   
   					document.getElementById('otperror').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';
   
   				}
   
   			}
   
   		}
   
   		do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/customer_services.php");
   	}
   }
   
   
   
   function saveform() {
   
   
   
   	var formdata = $('.cls_booking_form').serializeArray();
   
   
   
   	formdata.push({
   
   		name: 'method',
   
   		value: "add_agent"
   
   	});
   
   	var post_data = formdata;
   
   
   
   	var onsuccess = function(data) {
   
   		var response = JSON.parse(data);
   
   		if (response != "") {
   
   
   
   			if (response.type == 0) {
   
   				$("#showsuccessalert").hide();
   
   				$("#showerroralert").show();
   
   				$("#showerroralert").html(response.result);
   
   
   
   			} else {
   
   				$("#showerroralert").hide();
   
   				$("#showsuccessalert").show();
   
   				$("#showsuccessalert").html(response.result);
   
   			}
   
   
   
   		}
   
   	}
   
   
   
   	do_ajax_call(post_data, onsuccess);
   
   
   
   }
   
   
   
   function deletelist(str) {
   
   
   	var tabID = $('#tabID').val();
   	var heading = "Delete Agent";
   	var Question = "Are you sure to Delete Agent?";
   
   	if (tabID == 'customer') {
   		heading = 'Delete Customer';
   		Question = "Are you sure to Delete Customer?";
   	}
   
   	var post_data = {
   
   		deleteid: str,
   
   		method: 'deleteagent'
   
   	};
   
   	var onsuccess = function(data) {
   
   		var response = JSON.parse(data);
   
   
   
   		$("#deleteinfo").modal("hide");
   
   
   
   		// ('#exampleTable').dataTable({
   
   		// 	"bServerSide": true,
   
   		// 	paging: false,
   
   		// 	searching: false,
   
   		// 	"bDestroy": true
   
   		// });
   
   		// viewtable();
   
   		var table = $('#exampleTable').DataTable();
   
   		table.destroy();
   
   		viewtable();
   
   	}
   
   
   
   	var del_ok = function(event) {
   
   		do_ajax_call(post_data, onsuccess);
   
   	};
   
   
   
   	confirmdelete(heading, Question, del_ok);
   
   }
   
   
   
   function jump(field, autoMove) {
   
   	if (field.value.length >= field.maxLength) {
   
   		document.getElementById(autoMove).focus();
   
   	}
   
   }
   
   
   
   function suspendagent(str) {
   
   	swal.fire({
   
   		title: 'Suspended Reason',
   
   		input: 'textarea',
   
   		showCancelButton: true,
   
   		allowOutsideClick: false
   
   	}).then(function(result) {
   
   		if (result.isConfirmed) {
   
   			if (result.value != '') {
   
   				var formdata = [];
   
   				formdata.push({
   
   					name: 'method',
   
   					value: "suspended_agent"
   
   				});
   
   				formdata.push({
   
   					name: 'userid',
   
   					value: str
   
   				});
   
   				formdata.push({
   
   					name: 'reason',
   
   					value: result.value
   
   				});
   
   				var post_data = formdata;
   
   				var onsuccess = function(data) {
   
   					var response = JSON.parse(data);
   
   					if (response != "") {
   
   						if (response.type == 1) {
   
   							paymentSuccess('success', response.result);
   
   							viewtable();
   
   						} else {
   
   							paymentSuccess('error', response.result);
   
   						}
   
   					}
   
   				}
   
   				do_ajax_call(post_data, onsuccess, agenturl);
   
   				// console.log(result.value);
   
   				// paymentSuccess('success', 's');
   
   			} else {
   
   				toast('error', 'Please Fill the Reason');
   
   			}
   
   		}
   
   	})
   
   }
   
   function closemodal(id) {
   	$('#' + id).modal('hide');
   }
   
   
   function unsuspendagent(str) {
   
   	swal.fire({
   
   		title: 'Suspended Reason',
   
   		input: 'textarea',
   
   		showCancelButton: true,
   
   		allowOutsideClick: false
   
   	}).then(function(result) {
   
   		if (result.isConfirmed) {
   
   			if (result.value != '') {
   
   				var formdata = [];
   
   				formdata.push({
   
   					name: 'method',
   
   					value: "unsuspendagent_agent"
   
   				});
   
   				formdata.push({
   
   					name: 'userid',
   
   					value: str
   
   				});
   
   				formdata.push({
   
   					name: 'reason',
   
   					value: result.value
   
   				});
   
   				var post_data = formdata;
   
   				var onsuccess = function(data) {
   
   					var response = JSON.parse(data);
   
   					if (response != "") {
   
   						if (response.type == 1) {
   
   							paymentSuccess('success', response.result);
   
   							viewtable();
   
   						} else {
   
   							paymentSuccess('error', response.result);
   
   						}
   
   					}
   
   				}
   
   				do_ajax_call(post_data, onsuccess, agenturl);
   
   				// console.log(result.value);
   
   				// paymentSuccess('success', 's');
   
   			} else {
   
   				toast('error', 'Please Fill the Reason');
   
   			}
   
   		}
   
   	})
   
   }
   
   
   
   function paymentSuccess(icon, titlestr) {
   
   	Swal.fire({
   
   		title: titlestr,
   
   		icon: icon,
   
   		confirmButtonColor: '#3085d6',
   
   		confirmButtonText: 'OKAY',
   
   		allowOutsideClick: false
   
   	}).then((result) => {
   
   		if (result.isConfirmed) {
   
   			viewtable();
   
   		}
   
   	})
   
   }
   
   
   
   function toast(icon, message) {
   
   	const Toast = Swal.mixin({
   
   		toast: true,
   
   		position: 'top-end',
   
   		showConfirmButton: false,
   
   		timer: 5000,
   
   		timerProgressBar: true,
   
   		didOpen: (toast) => {
   
   			toast.addEventListener('mouseenter', Swal.stopTimer)
   
   			toast.addEventListener('mouseleave', Swal.resumeTimer)
   
   		}
   
   	})
   
   
   
   	Toast.fire({
   
   		icon: icon,
   
   		title: message
   
   	})
   
   
   
   }
   
   
   
   
   
   function agentdelete(id) {
   
   	swal.fire({
   
   		title: 'Delete Reason',
   
   		input: 'textarea',
   
   		showCancelButton: true,
   
   		allowOutsideClick: false
   
   	}).then(function(result) {
   
   		if (result.isConfirmed) {
   
   			if (result.value != '') {
   
   				var formdata = [];
   
   				formdata.push({
   
   					name: 'method',
   
   					value: "deleted_agent"
   
   				});
   
   				formdata.push({
   
   					name: 'userid',
   
   					value: id
   
   				});
   
   				formdata.push({
   
   					name: 'reason',
   
   					value: result.value
   
   				});
   
   				var post_data = formdata;
   
   				var onsuccess = function(data) {
   
   					var response = JSON.parse(data);
   
   					if (response != "") {
   
   						if (response.type == 1) {
   
   							paymentSuccess('success', response.result);
   
   							viewtable();
   
   						} else {
   
   							paymentSuccess('error', response.result);
   
   						}
   
   					}
   
   				}
   
   				do_ajax_call(post_data, onsuccess, agenturl);
   
   			} else {
   
   				toast('error', 'Please Fill the Reason');
   
   			}
   
   		}
   
   	})
   
   }
   
   
   
   function previewp(id) {
   	var formdata = [];
   
   	formdata.push({
   
   		name: 'method',
   
   		value: "show_image"
   
   	});
   
   	formdata.push({
   
   		name: 'id',
   
   		value: id
   
   	});
   
   
   	var post_data = formdata;
   
   	var onsuccess = function(data) {
   
   		var response = JSON.parse(data);
   
   		if (response != "") {
   
   			if (response.type == 1) {
   
   
   				document.getElementById('imagecontent').innerHTML = response.result;
   
   				$('#showimgmodal').modal('show');
   
   			}
   		}
   
   	}
   
   	do_ajax_call(post_data, onsuccess, url);
   
   }
   
   
   var telInput = $("#phone"),
   
   	errorMsg = $("#error-msg"),
   
   	validMsg = $("#valid-msg");
   
   
   
   // initialise plugin
   
   telInput.intlTelInput({
   
   
   
   	allowExtensions: true,
   
   	formatOnDisplay: true,
   
   	autoFormat: true,
   
   	autoHideDialCode: true,
   
   	autoPlaceholder: true,
   
   	defaultCountry: "auto",
   
   	ipinfoToken: "yolo",
   
   
   
   
   
   	nationalMode: false,
   
   	numberType: "MOBILE",
   
   	//onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
   
   	preferredCountries: ['ae', 'sa', 'qa', 'om', 'bh', 'kw', 'ma'],
   
   	preventInvalidNumbers: true,
   
   	separateDialCode: true,
   
   	initialCountry: "ae",
   
   	geoIpLookup: function(callback) {
   
   		$.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
   
   			var countryCode = resp && resp.country ? resp.country : "";
   
   			callback(countryCode);
   
   		});
   
   	},
   
   	utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"
   
   });
   
   
   
   var reset = function() {
   
   	telInput.removeClass("error");
   
   	errorMsg.addClass("hide");
   
   	validMsg.addClass("hide");
   
   };
   
   
   
   // on blur: validate
   
   telInput.blur(function() {
   
   	reset();
   
   	if ($.trim(telInput.val())) {
   
   		if (telInput.intlTelInput("isValidNumber")) {
   
   			validMsg.removeClass("hide");
   
   		} else {
   
   			telInput.addClass("error");
   
   			errorMsg.removeClass("hide");
   
   		}
   
   	}
   
   });
   
   
   
   // on keyup / change flag: reset
   
   telInput.on("keyup change", reset);
   
   //# sourceURL=pen.js
   
   
   
   // 		var iti = intlTelInput(input, {
   
   //   initialCountry: "ae"
   
   // });
   
   
   
   function getCodeBoxElement(index) {
   
   	return document.getElementById('otp' + index);
   
   }
   
   
   
   function onKeyUpEvent(index, event) {
   
   	const eventCode = event.which || event.keyCode;
   
   	if (getCodeBoxElement(index).value.length === 1) {
   
   		if (index !== 4) {
   
   			getCodeBoxElement(index + 1).focus();
   
   		} else {
   
   			getCodeBoxElement(index).blur();
   
   			// Submit code
   
   			console.log('submit code ');
   
   		}
   
   	}
   
   	if (eventCode === 8 && index !== 1) {
   
   		getCodeBoxElement(index - 1).focus();
   
   	}
   
   }
   
   
   
   function onFocusEvent(index) {
   
   	for (item = 1; item < index; item++) {
   
   		if (window.CP.shouldStopExecution(0)) break;
   
   		const currentElement = getCodeBoxElement(item);
   
   		if (!currentElement.value) {
   
   			currentElement.focus();
   
   			break;
   
   		}
   
   	}
   
   	window.CP.exitedLoop(0);
   
   }
   
   
   
   function checkLength(el) {
   
   	if (el.value.length < 6) {
   
   		document.getElementById('createformerror').innerHTML = '<div class="alert alert-danger" role="alert">length must be exactly 6 characters</div>';
   
   		// alert("length must be exactly 6 characters")
   
   	}
   
   }
   
   
   
   function validateEmail(email) {
   
   	var reg = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
   
   	if (reg.test(email)) {
   
   		return true;
   
   	}
   
   	return false;
   
   }
   
   
   
   // eye
   
   $(".toggle-password").click(function() {
   	$(this).find('i').toggleClass("fa-eye fa-eye-slash");
   	var input = $($(this).find('i').attr("toggle"));
   	if (input.attr("type") == "password") {
   		input.attr("type", "text");
   	} else {
   		input.attr("type", "password");
   	}
   });
   
   
   
   // $(".toggle-password").click(function() {
   
   
   
   // 	$(this).toggleClass("fa-eye fa-eye-slash");
   
   // 	var input = $($(this).attr("toggle"));
   
   // 	if (input.attr("type") == "password") {
   
   // 		input.attr("type", "text");
   
   // 	} else {
   
   // 		input.attr("type", "password");
   
   // 	}
   
   // });
   
   
   
   
   
   // Eye end
   
   
   function Email_check() {
   	let countrycode = $('.selected-dial-code').text();
   	let country = countrycode.substring(1);
   	let number = $.trim(telInput.val());
   	var test = number;
   
   	var trim_number = parseInt(test.charAt(0));
   	if (trim_number == 0) {
   		number = number.slice(1);
   	}
   	if (number != '') {
   		if (country == 971) {
   			let mobile = country + number;
   			var email = $('#email').val().trim();
   			let position = email.search('@littledraw.ae');
   			if (email == '' || position >= 0) {
   				document.getElementById('email').value = mobile + '@littledraw.ae';
   				document.getElementById('createformerror').innerHTML = '';
   			}
   		} else {
   			document.getElementById('email').value = '';
   			document.getElementById('createformerror').innerHTML = '<div class="alert alert-danger" role="alert">Field Required - Email</div>';
   		}
   	}
   }
   
   function getState(id) {
   	if (id != '') {
   
   		var formdata = [];
   
   		formdata.push({
   			name: 'method',
   			value: "getState"
   		});
   
   		formdata.push({
   			name: 'id',
   			value: id
   		});
   
   		var post_data = formdata;
   
   		var onsuccess = function(data) {
   			var response = JSON.parse(data);
   			if (response != "") {
   				if (response.type == 1) {
   					$('#billing_address').empty();
   					let len = response.result.length;
   					$('#billing_address').append(`<option value="">Select State / Emirates</option>`);
   					for (let i = 0; i < len; i++) {
   						optionText = response.result[i]['name'];
   						optionValue = response.result[i]['id'];
   						let svalue = (state.toLowerCase() == optionText.toLowerCase()) ? 'selected' : '';
   						$('#billing_address').append(`<option value="${optionValue}" ${svalue}>${optionText}</option>`);
   					}
   					getCity($('#billing_address').val());
   				} else {
   					toast('error', response.result);
   				}
   				$('#billing_address').selectpicker('refresh');
   			}
   		}
   		do_ajax_call(post_data, onsuccess);
   	} else {
   		toast('error', 'Kindly Select Country')
   	}
   }
   
   
   function getCity(id) {
   	if (id != '') {
   
   		var formdata = [];
   
   		formdata.push({
   			name: 'method',
   			value: "getCity"
   		});
   
   		formdata.push({
   			name: 'id',
   			value: id
   		});
   
   		var post_data = formdata;
   
   		var onsuccess = function(data) {
   			var response = JSON.parse(data);
   			if (response != "") {
   				if (response.type == 1) {
   
   					$('#billing_city').empty();
   					let len = response.result.length;
   					$('#billing_city').append(`<option value="">Select Area / District</option>`);
   					for (let i = 0; i < len; i++) {
   						optionText = response.result[i]['name'];
   						optionValue = response.result[i]['id'];
   						let svalue = (city.toLowerCase() == optionText.toLowerCase()) ? 'selected' : '';
   						$('#billing_city').append(`<option value="${optionValue}" ${svalue}>${optionText}</option>`);
   					}
   
   				} else {
   					toast('error', response.result);
   				}
   				$('#billing_city').selectpicker('refresh');
   			}
   		}
   		do_ajax_call(post_data, onsuccess);
   
   	} else {
   		toast('error', 'Kindly Select State')
   	}
   }
   
   
   // Moblie Validation Added
   function mobile_number_validation(mobilenumber) {
   	var pattern = /[^0-9]/g;
   	$("#createformerror").html("");
   	if (mobilenumber != '') {
   		let firstChar = parseInt(mobilenumber.charAt(0));
   		if (firstChar != 0) {
   			if (pattern.test(mobilenumber)) {
   				var res = mobilenumber.charAt(mobilenumber.length - 1);
   				errorThrow('The Characters are Not Allowed');
   			} else {
   				var count = country_Mobile_count(parseInt($('.selected-dial-code').text()));
   				if (count != '') {
   					if (mobilenumber.length < count) {} else {
   						let len = parseInt(mobilenumber.length);
   						$("#phone").attr("maxlength", count);
   						$("#createformerror").html("");
   					}
   
   				} else {
   					$("#phone").removeAttr("maxlength");
   				}
   			}
   		} else {
   			document.getElementById('phone').value = '';
   			errorThrow('Mobile Number Should Not Start With Zero');
   		}
   	} else {
   		errorThrow('Field Required - Enter Mobile');
   	}
   
   }
   
   function errorThrow(err) {
   	document.getElementById('createformerror').innerHTML = '<div class="alert alert-danger" role="alert">' + err + '</div>';
   }
   
   function country_Mobile_count(dialCode) {
   	var result = '';
   	if (dialCode == 971 || dialCode == 61 || dialCode == 966 || dialCode == 33 || dialCode == 61 || dialCode == 31) {
   		result = 9;
   	} else if (dialCode == 91 || dialCode == 63 || dialCode == 1 || dialCode == 44 || dialCode == 49 || dialCode == 81 || dialCode == 60) {
   		result = 10;
   	} else if (dialCode == 973 || dialCode == 65 || dialCode == 852 || dialCode == 965 || dialCode == 974 || dialCode == 968 || dialCode == 45) {
   		result = 8;
   	} else {
   		result = '';
   	}
   	return result;
   
   }
   
   telInput.on("countrychange", function() {
   	document.getElementById('phone').value = "";
   	$("#phone").removeAttr("maxlength");
   });
   
   
   
   
   
   
   // New
   function affiliatedelete(id) {
   
   	swal.fire({
   
   		title: 'Delete Reason',
   
   		input: 'textarea',
   
   		showCancelButton: true,
   
   		allowOutsideClick: false
   
   	}).then(function(result) {
   
   		if (result.isConfirmed) {
   
   			if (result.value == '') {
   				toast('error', 'Please Fill the Reason');
   				return false;
   			}
   			var formdata = [];
   
   			formdata.push({
   
   				name: 'method',
   
   				value: "deleted_affiliate"
   
   			}, {
   
   				name: 'userid',
   
   				value: id
   
   			}, {
   
   				name: 'reason',
   
   				value: result.value
   
   			});
   
   
   			var post_data = formdata;
   
   			var onsuccess = function(data) {
   
   				var response = JSON.parse(data);
   
   				if (response != "") {
   
   					if (response.type == 1) {
   
   						paymentSuccess('success', response.result);
   
   						viewtable();
   
   					} else {
   
   						paymentSuccess('error', response.result);
   
   					}
   
   				}
   
   			}
   
   			do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/affiliate_services.php");
   
   
   
   		}
   
   	})
   
   }
   
   function suspendaffiliate(str) {
   
   	swal.fire({
   
   		title: 'Suspended Reason',
   
   		input: 'textarea',
   
   		showCancelButton: true,
   
   		allowOutsideClick: false
   
   	}).then(function(result) {
   
   		if (result.isConfirmed) {
   			if (result.value == '') {
   				toast('error', 'Please Fill the Reason');
   				return false;
   			}
   
   
   			var formdata = [];
   
   			formdata.push({
   
   				name: 'method',
   
   				value: "suspended_affiliate"
   
   			}, {
   
   				name: 'userid',
   
   				value: str
   
   			}, {
   
   				name: 'reason',
   
   				value: result.value
   
   			});
   
   
   
   			var post_data = formdata;
   
   			var onsuccess = function(data) {
   
   				var response = JSON.parse(data);
   
   				if (response != "") {
   
   					if (response.type == 1) {
   
   						paymentSuccess('success', response.result);
   
   						viewtable();
   
   					} else {
   
   						paymentSuccess('error', response.result);
   
   					}
   
   				}
   
   			}
   
   			do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/affiliate_services.php");
   		}
   
   	});
   
   }
   
   
   function unsuspendaffiliate(str) {
   
   	swal.fire({
   
   		title: 'Unsuspended Reason',
   
   		input: 'textarea',
   
   		showCancelButton: true,
   
   		allowOutsideClick: false
   
   	}).then(function(result) {
   
   		if (result.isConfirmed) {
   
   			if (result.value == '') {
   				toast('error', 'Please Fill the Reason');
   				return false;
   			}
   
   			var formdata = [];
   
   			formdata.push({
   
   				name: 'method',
   
   				value: "unsuspendagent_affiliate"
   
   			}, {
   
   				name: 'userid',
   
   				value: str
   
   			}, {
   
   				name: 'reason',
   
   				value: result.value
   
   			});
   
   
   
   
   
   			var post_data = formdata;
   
   			var onsuccess = function(data) {
   
   				var response = JSON.parse(data);
   
   				if (response != "") {
   
   					if (response.type == 1) {
   
   						paymentSuccess('success', response.result);
   
   						viewtable();
   
   					} else {
   
   						paymentSuccess('error', response.result);
   
   					}
   
   				}
   
   			}
   
   			do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/affiliate_services.php");
   
   
   
   
   		}
   
   	})
   
   }
   
   
   
   function generatePassword(length = 6) {
   	var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789$#@!&*",
   		retVal = "";
   	for (var i = 0, n = charset.length; i < length; ++i) {
   		retVal += charset.charAt(Math.floor(Math.random() * n));
   	}
   	// return retVal;
   	$('#password').val(retVal);
   }
</script>
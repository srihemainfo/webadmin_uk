<?php

/**
 * Date               Developer                   Modification
 * 18/8/23            divya                        kiosk acess and services created and kioskid
 */



$draw_id = '';
$draw_query1 = select_query($con, "draw", "", " `ticket_start_datetime` < '$dubaidate_time' and `ticket_end_datetime` > '$dubaidate_time'  AND `status` = 'Active' and `deletes`='0' order by `result_datetime` ASC", "", "");

if ($draw_query1['nr'] > 0) {

	$draw_id = $draw_query1['result'][0]['id'];
}

?>







<head>







	<style>
		.bootstrap-select .dropdown-toggle .filter-option {



			height: auto;



		}







		.bootstrap-select>.dropdown-toggle {



			background: #fff;



		}







		.dropdown-item.active {



			padding: 3px 0px 24px 22px !important;



		}







		.bootstrap-select .dropdown-menu li a {







			padding: 3px 0px 24px 22px !important;







		}







		.inner.show {



			min-height: 135px !important;



			max-height: 134px !important;



		}







		textarea {



			/* width: 100%;



			height: 150px; */



			padding: 12px 20px;



			box-sizing: border-box;



			border: 2px solid #ccc;



			border-radius: 4px;



			background-color: #f8f8f8;



			font-size: 16px;



			resize: none;



		}







		/* h2#swal2-title {



			color: red !important;



		} */







		/*the container must be positioned relative:*/



		.autocomplete {



			position: relative;



			display: inline-block;



		}







		input {



			border: 1px solid transparent;



			background-color: #f1f1f1;



			padding: 10px;



			font-size: 16px;



		}







		input[type=text] {



			background-color: #f1f1f1;



			width: 100%;



		}







		input[type=submit] {



			background-color: DodgerBlue;



			color: #fff;



			cursor: pointer;



		}







		.autocomplete-items {



			position: absolute;



			border: 1px solid #d4d4d4;



			border-bottom: none;



			border-top: none;



			z-index: 99;



			/*position the autocomplete items to be the same width as the container:*/



			top: 100%;



			left: 0;



			right: 0;



		}







		.autocomplete-items div {



			padding: 10px;



			cursor: pointer;



			background-color: #fff;



			border-bottom: 1px solid #d4d4d4;



		}







		/*when hovering an item:*/



		.autocomplete-items div:hover {



			background-color: #e9e9e9;



		}







		/*when navigating through the items using the arrow keys:*/



		.autocomplete-active {



			background-color: DodgerBlue !important;



			color: #ffffff;



		}







		.dt-buttons.btn-group.flex-wrap {



			position: initial;



			float: left;



		}







		.intl-tel-input {



			position: relative;



			display: inline-block;



		}







		.intl-tel-input * {



			box-sizing: border-box;



			-moz-box-sizing: border-box;



		}







		.intl-tel-input .hide {



			display: none;



		}







		.intl-tel-input .v-hide {



			visibility: hidden;



		}







		.intl-tel-input input,



		.intl-tel-input input[type=text],



		.intl-tel-input input[type=tel] {



			position: relative;



			z-index: 0;



			margin-top: 0 !important;



			margin-bottom: 0 !important;



			padding-right: 36px;



			margin-right: 0;



		}







		.intl-tel-input .flag-container {



			position: absolute;



			top: 0;



			bottom: 0;



			right: 0;



			padding: 1px;



		}







		.intl-tel-input .selected-flag {



			z-index: 1;



			position: relative;



			width: 36px;



			height: 100%;



			padding: 0 0 0 8px;



		}







		.intl-tel-input .selected-flag .iti-flag {



			position: absolute;



			top: 0;



			bottom: 0;



			margin: auto;



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



			border-top: 4px solid #555;



		}







		.intl-tel-input .selected-flag .iti-arrow.up {



			border-top: none;



			border-bottom: 4px solid #555;



		}







		.intl-tel-input .country-list {



			position: absolute;



			z-index: 2;



			list-style: none;



			text-align: left;



			padding: 0;



			margin: 0 0 0 -1px;



			box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.2);



			background-color: white;



			border: 1px solid #CCC;



			white-space: nowrap;



			max-height: 200px;



			overflow-y: scroll;



		}







		.intl-tel-input .country-list.dropup {



			bottom: 100%;



			margin-bottom: -1px;



		}







		.intl-tel-input .country-list .flag-box {



			display: inline-block;



			width: 20px;



		}







		.intl-tel-input input#phone {



			width: 100%;



			height: 44px;



			border-radius: 5px;



			border: 1px solid #ecf0fa;



		}







		button.btn.btn-danger.edit {



			background-color: transparent !important;



			border: 0px solod #000;



			border: 0px solid #000 !important;



			color: #000 !important;



		}







		@media (max-width: 500px) {



			.intl-tel-input .country-list {



				white-space: normal;



			}



		}







		.intl-tel-input .country-list .divider {



			padding-bottom: 5px;



			margin-bottom: 5px;



			border-bottom: 1px solid #CCC;



		}







		.intl-tel-input .country-list .country {



			padding: 5px 10px;



		}







		.intl-tel-input .country-list .country .dial-code {



			color: #999;



		}







		.intl-tel-input .country-list .country.highlight {



			background-color: rgba(0, 0, 0, 0.05);



		}







		.intl-tel-input .country-list .flag-box,



		.intl-tel-input .country-list .country-name,



		.intl-tel-input .country-list .dial-code {



			vertical-align: middle;



		}







		.intl-tel-input .country-list .flag-box,



		.intl-tel-input .country-list .country-name {



			margin-right: 6px;



		}







		.intl-tel-input.allow-dropdown input,



		.intl-tel-input.allow-dropdown input[type=text],



		.intl-tel-input.allow-dropdown input[type=tel],



		.intl-tel-input.separate-dial-code input,



		.intl-tel-input.separate-dial-code input[type=text],



		.intl-tel-input.separate-dial-code input[type=tel] {



			padding-right: 6px;



			padding-left: 52px;



			margin-left: 0;



		}







		.intl-tel-input.allow-dropdown .flag-container,



		.intl-tel-input.separate-dial-code .flag-container {



			right: auto;



			left: 0;



		}







		.intl-tel-input.allow-dropdown .selected-flag,



		.intl-tel-input.separate-dial-code .selected-flag {



			width: 46px;



		}







		.intl-tel-input.allow-dropdown .flag-container:hover {



			cursor: pointer;



		}







		.intl-tel-input.allow-dropdown .flag-container:hover .selected-flag {



			background-color: rgba(0, 0, 0, 0.05);



		}







		.intl-tel-input.allow-dropdown input[disabled]+.flag-container:hover,



		.intl-tel-input.allow-dropdown input[readonly]+.flag-container:hover {



			cursor: default;



		}







		.intl-tel-input.allow-dropdown input[disabled]+.flag-container:hover .selected-flag,



		.intl-tel-input.allow-dropdown input[readonly]+.flag-container:hover .selected-flag {



			background-color: transparent;



		}







		.intl-tel-input.separate-dial-code .selected-flag {



			background-color: rgba(0, 0, 0, 0.05);



			display: table;



		}







		.intl-tel-input.separate-dial-code .selected-dial-code {



			display: table-cell;



			vertical-align: middle;



			padding-left: 28px;



		}







		.intl-tel-input.separate-dial-code.iti-sdc-2 input,



		.intl-tel-input.separate-dial-code.iti-sdc-2 input[type=text],



		.intl-tel-input.separate-dial-code.iti-sdc-2 input[type=tel] {



			padding-left: 66px;



		}







		.intl-tel-input.separate-dial-code.iti-sdc-2 .selected-flag {



			width: 60px;



		}







		.intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-2 input,



		.intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-2 input[type=text],



		.intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-2 input[type=tel] {



			padding-left: 76px;



		}







		.intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-2 .selected-flag {



			width: 70px;



		}







		.intl-tel-input.separate-dial-code.iti-sdc-3 input,



		.intl-tel-input.separate-dial-code.iti-sdc-3 input[type=text],



		.intl-tel-input.separate-dial-code.iti-sdc-3 input[type=tel] {



			padding-left: 74px;



		}







		.intl-tel-input.separate-dial-code.iti-sdc-3 .selected-flag {



			width: 68px;



		}







		.intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-3 input,



		.intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-3 input[type=text],



		.intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-3 input[type=tel] {



			padding-left: 84px;



		}







		.intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-3 .selected-flag {



			width: 78px;



		}







		.intl-tel-input.separate-dial-code.iti-sdc-4 input,



		.intl-tel-input.separate-dial-code.iti-sdc-4 input[type=text],



		.intl-tel-input.separate-dial-code.iti-sdc-4 input[type=tel] {



			padding-left: 82px;



		}







		.intl-tel-input.separate-dial-code.iti-sdc-4 .selected-flag {



			width: 76px;



		}







		.intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-4 input,



		.intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-4 input[type=text],



		.intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-4 input[type=tel] {



			padding-left: 92px;



		}







		.intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-4 .selected-flag {



			width: 86px;



		}







		.intl-tel-input.separate-dial-code.iti-sdc-5 input,



		.intl-tel-input.separate-dial-code.iti-sdc-5 input[type=text],



		.intl-tel-input.separate-dial-code.iti-sdc-5 input[type=tel] {



			padding-left: 90px;



		}







		.intl-tel-input.separate-dial-code.iti-sdc-5 .selected-flag {



			width: 84px;



		}







		.intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-5 input,



		.intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-5 input[type=text],



		.intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-5 input[type=tel] {



			padding-left: 100px;



		}







		.intl-tel-input.separate-dial-code.allow-dropdown.iti-sdc-5 .selected-flag {



			width: 94px;



		}







		.intl-tel-input.iti-container {



			position: absolute;



			top: -1000px;



			left: -1000px;



			z-index: 1060;



			padding: 1px;



		}







		.intl-tel-input.iti-container:hover {



			cursor: pointer;



		}







		.iti-mobile .intl-tel-input.iti-container {



			top: 30px;



			bottom: 30px;



			left: 30px;



			right: 30px;



			position: fixed;



		}







		.iti-mobile .intl-tel-input .country-list {



			max-height: 100%;



			width: 100%;



		}







		.iti-mobile .intl-tel-input .country-list .country {



			padding: 10px 10px;



			line-height: 1.5em;



		}







		.iti-flag {



			width: 20px;



		}







		.iti-flag.be {



			width: 18px;



		}







		.iti-flag.ch {



			width: 15px;



		}







		.iti-flag.mc {



			width: 19px;



		}







		.iti-flag.ne {



			width: 18px;



		}







		.iti-flag.np {



			width: 13px;



		}







		.iti-flag.va {



			width: 15px;



		}







		@media only screen and (-webkit-min-device-pixel-ratio: 2),



		only screen and (min--moz-device-pixel-ratio: 2),



		only screen and (-o-min-device-pixel-ratio: 2 / 1),



		only screen and (min-device-pixel-ratio: 2),



		only screen and (min-resolution: 192dpi),



		only screen and (min-resolution: 2dppx) {



			.iti-flag {



				background-size: 5630px 15px;



			}



		}







		.iti-flag.ac {



			height: 10px;



			background-position: 0px 0px;



		}







		.iti-flag.ad {



			height: 14px;



			background-position: -22px 0px;



		}







		.iti-flag.ae {



			height: 10px;



			background-position: -44px 0px;



		}







		.iti-flag.af {



			height: 14px;



			background-position: -66px 0px;



		}







		.iti-flag.ag {



			height: 14px;



			background-position: -88px 0px;



		}







		.iti-flag.ai {



			height: 10px;



			background-position: -110px 0px;



		}







		.iti-flag.al {



			height: 15px;



			background-position: -132px 0px;



		}







		.iti-flag.am {



			height: 10px;



			background-position: -154px 0px;



		}







		.iti-flag.ao {



			height: 14px;



			background-position: -176px 0px;



		}







		.iti-flag.aq {



			height: 14px;



			background-position: -198px 0px;



		}







		.iti-flag.ar {



			height: 13px;



			background-position: -220px 0px;



		}







		.iti-flag.as {



			height: 10px;



			background-position: -242px 0px;



		}







		.iti-flag.at {



			height: 14px;



			background-position: -264px 0px;



		}







		.iti-flag.au {



			height: 10px;



			background-position: -286px 0px;



		}







		.iti-flag.aw {



			height: 14px;



			background-position: -308px 0px;



		}







		.iti-flag.ax {



			height: 13px;



			background-position: -330px 0px;



		}







		.iti-flag.az {



			height: 10px;



			background-position: -352px 0px;



		}







		.iti-flag.ba {



			height: 10px;



			background-position: -374px 0px;



		}







		.iti-flag.bb {



			height: 14px;



			background-position: -396px 0px;



		}







		.iti-flag.bd {



			height: 12px;



			background-position: -418px 0px;



		}







		.iti-flag.be {



			height: 15px;



			background-position: -440px 0px;



		}







		.iti-flag.bf {



			height: 14px;



			background-position: -460px 0px;



		}







		.iti-flag.bg {



			height: 12px;



			background-position: -482px 0px;



		}







		.iti-flag.bh {



			height: 12px;



			background-position: -504px 0px;



		}







		.iti-flag.bi {



			height: 12px;



			background-position: -526px 0px;



		}







		.iti-flag.bj {



			height: 14px;



			background-position: -548px 0px;



		}







		.iti-flag.bl {



			height: 14px;



			background-position: -570px 0px;



		}







		.iti-flag.bm {



			height: 10px;



			background-position: -592px 0px;



		}







		.iti-flag.bn {



			height: 10px;



			background-position: -614px 0px;



		}







		.iti-flag.bo {



			height: 14px;



			background-position: -636px 0px;



		}







		.iti-flag.bq {



			height: 14px;



			background-position: -658px 0px;



		}







		.iti-flag.br {



			height: 14px;



			background-position: -680px 0px;



		}







		.iti-flag.bs {



			height: 10px;



			background-position: -702px 0px;



		}







		.iti-flag.bt {



			height: 14px;



			background-position: -724px 0px;



		}







		.iti-flag.bv {



			height: 15px;



			background-position: -746px 0px;



		}







		.iti-flag.bw {



			height: 14px;



			background-position: -768px 0px;



		}







		.iti-flag.by {



			height: 10px;



			background-position: -790px 0px;



		}







		.iti-flag.bz {



			height: 14px;



			background-position: -812px 0px;



		}







		.iti-flag.ca {



			height: 10px;



			background-position: -834px 0px;



		}







		.iti-flag.cc {



			height: 10px;



			background-position: -856px 0px;



		}







		.iti-flag.cd {



			height: 15px;



			background-position: -878px 0px;



		}







		.iti-flag.cf {



			height: 14px;



			background-position: -900px 0px;



		}







		.iti-flag.cg {



			height: 14px;



			background-position: -922px 0px;



		}







		.iti-flag.ch {



			height: 15px;



			background-position: -944px 0px;



		}







		.iti-flag.ci {



			height: 14px;



			background-position: -961px 0px;



		}







		.iti-flag.ck {



			height: 10px;



			background-position: -983px 0px;



		}







		.iti-flag.cl {



			height: 14px;



			background-position: -1005px 0px;



		}







		.iti-flag.cm {



			height: 14px;



			background-position: -1027px 0px;



		}







		.iti-flag.cn {



			height: 14px;



			background-position: -1049px 0px;



		}







		.iti-flag.co {



			height: 14px;



			background-position: -1071px 0px;



		}







		.iti-flag.cp {



			height: 14px;



			background-position: -1093px 0px;



		}







		.iti-flag.cr {



			height: 12px;



			background-position: -1115px 0px;



		}







		.iti-flag.cu {



			height: 10px;



			background-position: -1137px 0px;



		}







		.iti-flag.cv {



			height: 12px;



			background-position: -1159px 0px;



		}







		.iti-flag.cw {



			height: 14px;



			background-position: -1181px 0px;



		}







		.iti-flag.cx {



			height: 10px;



			background-position: -1203px 0px;



		}







		.iti-flag.cy {



			height: 13px;



			background-position: -1225px 0px;



		}







		.iti-flag.cz {



			height: 14px;



			background-position: -1247px 0px;



		}







		.iti-flag.de {



			height: 12px;



			background-position: -1269px 0px;



		}







		.iti-flag.dg {



			height: 10px;



			background-position: -1291px 0px;



		}







		.iti-flag.dj {



			height: 14px;



			background-position: -1313px 0px;



		}







		.iti-flag.dk {



			height: 15px;



			background-position: -1335px 0px;



		}







		.iti-flag.dm {



			height: 10px;



			background-position: -1357px 0px;



		}







		.iti-flag.do {



			height: 13px;



			background-position: -1379px 0px;



		}







		.iti-flag.dz {



			height: 14px;



			background-position: -1401px 0px;



		}







		.iti-flag.ea {



			height: 14px;



			background-position: -1423px 0px;



		}







		.iti-flag.ec {



			height: 14px;



			background-position: -1445px 0px;



		}







		.iti-flag.ee {



			height: 13px;



			background-position: -1467px 0px;



		}







		.iti-flag.eg {



			height: 14px;



			background-position: -1489px 0px;



		}







		.iti-flag.eh {



			height: 10px;



			background-position: -1511px 0px;



		}







		.iti-flag.er {



			height: 10px;



			background-position: -1533px 0px;



		}







		.iti-flag.es {



			height: 14px;



			background-position: -1555px 0px;



		}







		.iti-flag.et {



			height: 10px;



			background-position: -1577px 0px;



		}







		.iti-flag.eu {



			height: 14px;



			background-position: -1599px 0px;



		}







		.iti-flag.fi {



			height: 12px;



			background-position: -1621px 0px;



		}







		.iti-flag.fj {



			height: 10px;



			background-position: -1643px 0px;



		}







		.iti-flag.fk {



			height: 10px;



			background-position: -1665px 0px;



		}







		.iti-flag.fm {



			height: 11px;



			background-position: -1687px 0px;



		}







		.iti-flag.fo {



			height: 15px;



			background-position: -1709px 0px;



		}







		.iti-flag.fr {



			height: 14px;



			background-position: -1731px 0px;



		}







		.iti-flag.ga {



			height: 15px;



			background-position: -1753px 0px;



		}







		.iti-flag.gb {



			height: 10px;



			background-position: -1775px 0px;



		}







		.iti-flag.gd {



			height: 12px;



			background-position: -1797px 0px;



		}







		.iti-flag.ge {



			height: 14px;



			background-position: -1819px 0px;



		}







		.iti-flag.gf {



			height: 14px;



			background-position: -1841px 0px;



		}







		.iti-flag.gg {



			height: 14px;



			background-position: -1863px 0px;



		}







		.iti-flag.gh {



			height: 14px;



			background-position: -1885px 0px;



		}







		.iti-flag.gi {



			height: 10px;



			background-position: -1907px 0px;



		}







		.iti-flag.gl {



			height: 14px;



			background-position: -1929px 0px;



		}







		.iti-flag.gm {



			height: 14px;



			background-position: -1951px 0px;



		}







		.iti-flag.gn {



			height: 14px;



			background-position: -1973px 0px;



		}







		.iti-flag.gp {



			height: 14px;



			background-position: -1995px 0px;



		}







		.iti-flag.gq {



			height: 14px;



			background-position: -2017px 0px;



		}







		.iti-flag.gr {



			height: 14px;



			background-position: -2039px 0px;



		}







		.iti-flag.gs {



			height: 10px;



			background-position: -2061px 0px;



		}







		.iti-flag.gt {



			height: 13px;



			background-position: -2083px 0px;



		}







		.iti-flag.gu {



			height: 11px;



			background-position: -2105px 0px;



		}







		.iti-flag.gw {



			height: 10px;



			background-position: -2127px 0px;



		}







		.iti-flag.gy {



			height: 12px;



			background-position: -2149px 0px;



		}







		.iti-flag.hk {



			height: 14px;



			background-position: -2171px 0px;



		}







		.iti-flag.hm {



			height: 10px;



			background-position: -2193px 0px;



		}







		.iti-flag.hn {



			height: 10px;



			background-position: -2215px 0px;



		}







		.iti-flag.hr {



			height: 10px;



			background-position: -2237px 0px;



		}







		.iti-flag.ht {



			height: 12px;



			background-position: -2259px 0px;



		}







		.iti-flag.hu {



			height: 10px;



			background-position: -2281px 0px;



		}







		.iti-flag.ic {



			height: 14px;



			background-position: -2303px 0px;



		}







		.iti-flag.id {



			height: 14px;



			background-position: -2325px 0px;



		}







		.iti-flag.ie {



			height: 10px;



			background-position: -2347px 0px;



		}







		.iti-flag.il {



			height: 15px;



			background-position: -2369px 0px;



		}







		.iti-flag.im {



			height: 10px;



			background-position: -2391px 0px;



		}







		.iti-flag.in {



			height: 14px;



			background-position: -2413px 0px;



		}







		.iti-flag.io {



			height: 10px;



			background-position: -2435px 0px;



		}







		.iti-flag.iq {



			height: 14px;



			background-position: -2457px 0px;



		}







		.iti-flag.ir {



			height: 12px;



			background-position: -2479px 0px;



		}







		.iti-flag.is {



			height: 15px;



			background-position: -2501px 0px;



		}







		.iti-flag.it {



			height: 14px;



			background-position: -2523px 0px;



		}







		.iti-flag.je {



			height: 12px;



			background-position: -2545px 0px;



		}







		.iti-flag.jm {



			height: 10px;



			background-position: -2567px 0px;



		}







		.iti-flag.jo {



			height: 10px;



			background-position: -2589px 0px;



		}







		.iti-flag.jp {



			height: 14px;



			background-position: -2611px 0px;



		}







		.iti-flag.ke {



			height: 14px;



			background-position: -2633px 0px;



		}







		.iti-flag.kg {



			height: 12px;



			background-position: -2655px 0px;



		}







		.iti-flag.kh {



			height: 13px;



			background-position: -2677px 0px;



		}







		.iti-flag.ki {



			height: 10px;



			background-position: -2699px 0px;



		}







		.iti-flag.km {



			height: 12px;



			background-position: -2721px 0px;



		}







		.iti-flag.kn {



			height: 14px;



			background-position: -2743px 0px;



		}







		.iti-flag.kp {



			height: 10px;



			background-position: -2765px 0px;



		}







		.iti-flag.kr {



			height: 14px;



			background-position: -2787px 0px;



		}







		.iti-flag.kw {



			height: 10px;



			background-position: -2809px 0px;



		}







		.iti-flag.ky {



			height: 10px;



			background-position: -2831px 0px;



		}







		.iti-flag.kz {



			height: 10px;



			background-position: -2853px 0px;



		}







		.iti-flag.la {



			height: 14px;



			background-position: -2875px 0px;



		}







		.iti-flag.lb {



			height: 14px;



			background-position: -2897px 0px;



		}







		.iti-flag.lc {



			height: 10px;



			background-position: -2919px 0px;



		}







		.iti-flag.li {



			height: 12px;



			background-position: -2941px 0px;



		}







		.iti-flag.lk {



			height: 10px;



			background-position: -2963px 0px;



		}







		.iti-flag.lr {



			height: 11px;



			background-position: -2985px 0px;



		}







		.iti-flag.ls {



			height: 14px;



			background-position: -3007px 0px;



		}







		.iti-flag.lt {



			height: 12px;



			background-position: -3029px 0px;



		}







		.iti-flag.lu {



			height: 12px;



			background-position: -3051px 0px;



		}







		.iti-flag.lv {



			height: 10px;



			background-position: -3073px 0px;



		}







		.iti-flag.ly {



			height: 10px;



			background-position: -3095px 0px;



		}







		.iti-flag.ma {



			height: 14px;



			background-position: -3117px 0px;



		}







		.iti-flag.mc {



			height: 15px;



			background-position: -3139px 0px;



		}







		.iti-flag.md {



			height: 10px;



			background-position: -3160px 0px;



		}







		.iti-flag.me {



			height: 10px;



			background-position: -3182px 0px;



		}







		.iti-flag.mf {



			height: 14px;



			background-position: -3204px 0px;



		}







		.iti-flag.mg {



			height: 14px;



			background-position: -3226px 0px;



		}







		.iti-flag.mh {



			height: 11px;



			background-position: -3248px 0px;



		}







		.iti-flag.mk {



			height: 10px;



			background-position: -3270px 0px;



		}







		.iti-flag.ml {



			height: 14px;



			background-position: -3292px 0px;



		}







		.iti-flag.mm {



			height: 14px;



			background-position: -3314px 0px;



		}







		.iti-flag.mn {



			height: 10px;



			background-position: -3336px 0px;



		}







		.iti-flag.mo {



			height: 14px;



			background-position: -3358px 0px;



		}







		.iti-flag.mp {



			height: 10px;



			background-position: -3380px 0px;



		}







		.iti-flag.mq {



			height: 14px;



			background-position: -3402px 0px;



		}







		.iti-flag.mr {



			height: 14px;



			background-position: -3424px 0px;



		}







		.iti-flag.ms {



			height: 10px;



			background-position: -3446px 0px;



		}







		.iti-flag.mt {



			height: 14px;



			background-position: -3468px 0px;



		}







		.iti-flag.mu {



			height: 14px;



			background-position: -3490px 0px;



		}







		.iti-flag.mv {



			height: 14px;



			background-position: -3512px 0px;



		}







		.iti-flag.mw {



			height: 14px;



			background-position: -3534px 0px;



		}







		.iti-flag.mx {



			height: 12px;



			background-position: -3556px 0px;



		}







		.iti-flag.my {



			height: 10px;



			background-position: -3578px 0px;



		}







		.iti-flag.mz {



			height: 14px;



			background-position: -3600px 0px;



		}







		.iti-flag.na {



			height: 14px;



			background-position: -3622px 0px;



		}







		.iti-flag.nc {



			height: 10px;



			background-position: -3644px 0px;



		}







		.iti-flag.ne {



			height: 15px;



			background-position: -3666px 0px;



		}







		.iti-flag.nf {



			height: 10px;



			background-position: -3686px 0px;



		}







		.iti-flag.ng {



			height: 10px;



			background-position: -3708px 0px;



		}







		.iti-flag.ni {



			height: 12px;



			background-position: -3730px 0px;



		}







		.iti-flag.nl {



			height: 14px;



			background-position: -3752px 0px;



		}







		.iti-flag.no {



			height: 15px;



			background-position: -3774px 0px;



		}







		.iti-flag.np {



			height: 15px;



			background-position: -3796px 0px;



		}







		.iti-flag.nr {



			height: 10px;



			background-position: -3811px 0px;



		}







		.iti-flag.nu {



			height: 10px;



			background-position: -3833px 0px;



		}







		.iti-flag.nz {



			height: 10px;



			background-position: -3855px 0px;



		}







		.iti-flag.om {



			height: 10px;



			background-position: -3877px 0px;



		}







		.iti-flag.pa {



			height: 14px;



			background-position: -3899px 0px;



		}







		.iti-flag.pe {



			height: 14px;



			background-position: -3921px 0px;



		}







		.iti-flag.pf {



			height: 14px;



			background-position: -3943px 0px;



		}







		.iti-flag.pg {



			height: 15px;



			background-position: -3965px 0px;



		}







		.iti-flag.ph {



			height: 10px;



			background-position: -3987px 0px;



		}







		.iti-flag.pk {



			height: 14px;



			background-position: -4009px 0px;



		}







		.iti-flag.pl {



			height: 13px;



			background-position: -4031px 0px;



		}







		.iti-flag.pm {



			height: 14px;



			background-position: -4053px 0px;



		}







		.iti-flag.pn {



			height: 10px;



			background-position: -4075px 0px;



		}







		.iti-flag.pr {



			height: 14px;



			background-position: -4097px 0px;



		}







		.iti-flag.ps {



			height: 10px;



			background-position: -4119px 0px;



		}







		.iti-flag.pt {



			height: 14px;



			background-position: -4141px 0px;



		}







		.iti-flag.pw {



			height: 13px;



			background-position: -4163px 0px;



		}







		.iti-flag.py {



			height: 11px;



			background-position: -4185px 0px;



		}







		.iti-flag.qa {



			height: 8px;



			background-position: -4207px 0px;



		}







		.iti-flag.re {



			height: 14px;



			background-position: -4229px 0px;



		}







		.iti-flag.ro {



			height: 14px;



			background-position: -4251px 0px;



		}







		.iti-flag.rs {



			height: 14px;



			background-position: -4273px 0px;



		}







		.iti-flag.ru {



			height: 14px;



			background-position: -4295px 0px;



		}







		.iti-flag.rw {



			height: 14px;



			background-position: -4317px 0px;



		}







		.iti-flag.sa {



			height: 14px;



			background-position: -4339px 0px;



		}







		.iti-flag.sb {



			height: 10px;



			background-position: -4361px 0px;



		}







		.iti-flag.sc {



			height: 10px;



			background-position: -4383px 0px;



		}







		.iti-flag.sd {



			height: 10px;



			background-position: -4405px 0px;



		}







		.iti-flag.se {



			height: 13px;



			background-position: -4427px 0px;



		}







		.iti-flag.sg {



			height: 14px;



			background-position: -4449px 0px;



		}







		.iti-flag.sh {



			height: 10px;



			background-position: -4471px 0px;



		}







		.iti-flag.si {



			height: 10px;



			background-position: -4493px 0px;



		}







		.iti-flag.sj {



			height: 15px;



			background-position: -4515px 0px;



		}







		.iti-flag.sk {



			height: 14px;



			background-position: -4537px 0px;



		}







		.iti-flag.sl {



			height: 14px;



			background-position: -4559px 0px;



		}







		.iti-flag.sm {



			height: 15px;



			background-position: -4581px 0px;



		}







		.iti-flag.sn {



			height: 14px;



			background-position: -4603px 0px;



		}







		.iti-flag.so {



			height: 14px;



			background-position: -4625px 0px;



		}







		.iti-flag.sr {



			height: 14px;



			background-position: -4647px 0px;



		}







		.iti-flag.ss {



			height: 10px;



			background-position: -4669px 0px;



		}







		.iti-flag.st {



			height: 10px;



			background-position: -4691px 0px;



		}







		.iti-flag.sv {



			height: 12px;



			background-position: -4713px 0px;



		}







		.iti-flag.sx {



			height: 14px;



			background-position: -4735px 0px;



		}







		.iti-flag.sy {



			height: 14px;



			background-position: -4757px 0px;



		}







		.iti-flag.sz {



			height: 14px;



			background-position: -4779px 0px;



		}







		.iti-flag.ta {



			height: 10px;



			background-position: -4801px 0px;



		}







		.iti-flag.tc {



			height: 10px;



			background-position: -4823px 0px;



		}







		.iti-flag.td {



			height: 14px;



			background-position: -4845px 0px;



		}







		.iti-flag.tf {



			height: 14px;



			background-position: -4867px 0px;



		}







		.iti-flag.tg {



			height: 13px;



			background-position: -4889px 0px;



		}







		.iti-flag.th {



			height: 14px;



			background-position: -4911px 0px;



		}







		.iti-flag.tj {



			height: 10px;



			background-position: -4933px 0px;



		}







		.iti-flag.tk {



			height: 10px;



			background-position: -4955px 0px;



		}







		.iti-flag.tl {



			height: 10px;



			background-position: -4977px 0px;



		}







		.iti-flag.tm {



			height: 14px;



			background-position: -4999px 0px;



		}







		.iti-flag.tn {



			height: 14px;



			background-position: -5021px 0px;



		}







		.iti-flag.to {



			height: 10px;



			background-position: -5043px 0px;



		}







		.iti-flag.tr {



			height: 14px;



			background-position: -5065px 0px;



		}







		.iti-flag.tt {



			height: 12px;



			background-position: -5087px 0px;



		}







		.iti-flag.tv {



			height: 10px;



			background-position: -5109px 0px;



		}







		.iti-flag.tw {



			height: 14px;



			background-position: -5131px 0px;



		}







		.iti-flag.tz {



			height: 14px;



			background-position: -5153px 0px;



		}







		.iti-flag.ua {



			height: 14px;



			background-position: -5175px 0px;



		}







		.iti-flag.ug {



			height: 14px;



			background-position: -5197px 0px;



		}







		.iti-flag.um {



			height: 11px;



			background-position: -5219px 0px;



		}







		.iti-flag.us {



			height: 11px;



			background-position: -5241px 0px;



		}







		.iti-flag.uy {



			height: 14px;



			background-position: -5263px 0px;



		}







		.iti-flag.uz {



			height: 10px;



			background-position: -5285px 0px;



		}







		.iti-flag.va {



			height: 15px;



			background-position: -5307px 0px;



		}







		.iti-flag.vc {



			height: 14px;



			background-position: -5324px 0px;



		}







		.iti-flag.ve {



			height: 14px;



			background-position: -5346px 0px;



		}







		.iti-flag.vg {



			height: 10px;



			background-position: -5368px 0px;



		}







		.iti-flag.vi {



			height: 14px;



			background-position: -5390px 0px;



		}







		.iti-flag.vn {



			height: 14px;



			background-position: -5412px 0px;



		}







		.iti-flag.vu {



			height: 12px;



			background-position: -5434px 0px;



		}







		.iti-flag.wf {



			height: 14px;



			background-position: -5456px 0px;



		}







		.iti-flag.ws {



			height: 10px;



			background-position: -5478px 0px;



		}







		.iti-flag.xk {



			height: 15px;



			background-position: -5500px 0px;



		}







		.iti-flag.ye {



			height: 14px;



			background-position: -5522px 0px;



		}







		.iti-flag.yt {



			height: 14px;



			background-position: -5544px 0px;



		}







		.iti-flag.za {



			height: 14px;



			background-position: -5566px 0px;



		}







		.iti-flag.zm {



			height: 14px;



			background-position: -5588px 0px;



		}







		.iti-flag.zw {



			height: 10px;



			background-position: -5610px 0px;



		}







		.iti-flag {



			width: 20px;



			height: 15px;



			box-shadow: 0px 0px 1px 0px #888;



			background-image: url("https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/img/flags.png");



			background-repeat: no-repeat;



			background-color: #DBDBDB;



			background-position: 20px 0;



		}







		@media only screen and (-webkit-min-device-pixel-ratio: 2),



		only screen and (min--moz-device-pixel-ratio: 2),



		only screen and (-o-min-device-pixel-ratio: 2 / 1),



		only screen and (min-device-pixel-ratio: 2),



		only screen and (min-resolution: 192dpi),



		only screen and (min-resolution: 2dppx) {



			.iti-flag {



				background-image: url("https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/img/flags@2x.png");



			}



		}







		.iti-flag.np {



			background-color: transparent;



		}







		* {



			box-sizing: border-box;



			-moz-box-sizing: border-box;



		}







		body {



			margin: 20px;



			font-size: 14px;



			font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;



			color: #555;



		}







		.hide {



			display: none;



		}







		pre {



			margin: 0 !important;



			display: inline-block;



		}







		.token.operator,



		.token.entity,



		.token.url,



		.language-css .token.string,



		.style .token.string,



		.token.variable {



			background: none;



		}







		input,



		button {



			height: 35px;



			margin: 0;



			padding: 6px 12px;



			border-radius: 2px;



			font-family: inherit;



			font-size: 100%;



			color: inherit;



		}







		input[disabled],



		button[disabled] {



			background-color: #eee;



		}







		input,



		select {



			border: 1px solid #CCC;



			/*width: 250px;*/



		}







		::-webkit-input-placeholder {



			color: #BBB;



		}







		::-moz-placeholder {



			/* Firefox 19+ */



			color: #BBB;



			opacity: 1;



		}







		:-ms-input-placeholder {



			color: #BBB;



		}







		button {



			color: #FFF;



			background-color: #428BCA;



			border: 1px solid #357EBD;



		}







		button:hover {



			background-color: #3276B1;



			border-color: #285E8E;



			cursor: pointer;



		}







		#result {



			margin-bottom: 100px;



		}
	</style>







	<script>
		window.console = window.console || function(t) {};







		if (document.location.search.match(/type=embed/gi)) {



			window.parent.postMessage("resize", "*");



		}
	</script>



	<!-- 
	<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/css/intlTelInput.css" rel="stylesheet" media="screen">



	<script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js" integrity="sha512-QEAheCz+x/VkKtxeGoDq6nsGyzTx/0LMINTgQjqZ0h3+NjP+bCsPYz3hn0HnBkGmkIFSr7QcEZT+KyEM7lbLPQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>



	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>



	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.js"></script>



	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.min.js"></script>



	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"></script>
 -->






</head>







<style>
	[class^=swal2] {



		-webkit-tap-highlight-color: transparent;



		z-index: 10000 !important;



	}







	#showsuccessalert {



		display: none;



	}







	#showerroralert {



		display: none;



	}







	form.datefilter {



		float: right;



	}







	.back-arrow-btn i {



		background: #ffffff;



		font-size: 16px;



		padding: 2px 3px;



		border-radius: 50px;



		border: 2px solid #6c6e70;



		color: #6c6e70;



		margin-right: 15px;



		width: 24px;



		height: 24px;



	}







	.pd-20 {



		padding: 0 37px 0 0;



	}







	button.btn-style {



		background: #1170e4;



		padding: 4px;



		height: 38px;



		border: navajowhite;



		color: #fff;



		border-radius: 7px;



		width: 38px;



		margin: 38px 0 0 5px;



	}







	button.btn-style1 {



		background: #1170e4;



		padding: 2px;



		height: 29px;



		border: navajowhite;



		color: #fff;



		border-radius: 7px;



		width: 42px;



		margin: 5px 5px 0 4px;



	}







	div#enternewticketlines select {



		padding: 0.475rem 0.75rem;



		font-size: 0.875rem;



		border-radius: 7px;



		margin-top: 9px;



	}
</style>











<script>
	window.onload = function() {







		var page_origin = window.location.origin;







		let anchor = document.getElementById("anchor");







		anchor.href = page_origin;







	}
</script>











<input type="hidden" id="tabID" value="<?= $tabID; ?>">



<div class="main-content app-content mt-0">







	<div class="side-app">







		<div class="main-container container-fluid">



			<div class="page-header">



				<h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>New Sign-UP</h1>



				<div>



					<ol class="breadcrumb">



						<li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>



						<li class="breadcrumb-item active" aria-current="page">New Sign-UP</li>



					</ol>



				</div>



			</div>







			<?php if ($roll_id == 3 || $roll_id == 4 || $roll_id == 5) { ?>



				<!-- <div class="card col-sm-12">



					<div class="card-header">



						<h3 class="card-title">Search &amp; Tickets</h3>



					</div>



					<div class="card-body pt-0">



						<div class="form-group">



							<label class="form-label">Search Clients</label>



							<div class="row g-3">



								<div class="col-sm-7">



									<div class="input-group mb-2" class="autocomplete">



								



										<input id="myInput" type="text" required oninput="this.value=this.value.replace(/[^0-9]/g,'');" onkeyup="showResult(this.value)" name="myUser" placeholder="Searching.....">



										<span class="input-group-text btn btn-info" onclick="getuserdata()">Get User</span>



									</div>



								</div>



								<div class="col-sm">



									<button type="button" onclick="addcts()" id="smallmodal" data-bs-effect="effect-scale" data-bs-toggle="modal" data-bs-target="#addagent" style="float: right;" class="btn btn-info"><i class="fa fa-user-plus me-2"></i>Add <?= $addName; ?></button>



								</div>



							</div>



						</div>



						<div class="col-sm-6 ticketidhide">



							<div class="row">



								<div class="form-group p-0">



									<div id="userdetails">



									</div>







								</div>



							</div>



						</div>



						<div class="col-sm-12">



							<div class="row" id="enterTicket">







							</div>











							<div class="row" id="increment">







							</div>







							<form id="aticket">



								<div id="enternewticketlines">



								</div>



							</form>



						</div>



					</div>











					<div class="card-footer text-end">



						<div id="total">







						</div>







						<div id="buyerror"></div>



					

						<button onclick="preview($('#totallines').val())" id="previewticket" class="btn btn-info">Preview</button>



					</div>



				</div> -->



			<?php



			} ?>







		</div>











		<div class="row row-sm">



			<div class="">



				<div class="card">



					<div class="card-header row">




						<div class="col-md-12  col-sm-12  col-lg-12">



							<h3 class="card-title"><strong>User List</strong></h3>







						</div>










						<div class="row align-items-center">








							<!-- <div class="col-lg-3 col-sm-6 mb-2">

								<span>Select Draw</span>&nbsp;<span style="color: red;">*</span>
								<select id="draw_new_id" class="form-select">
									<option value="">Select Draw</option>
									<?php
									$draw = select_query($con, "draw", "", "(`deletes` = 0 AND `status` = 'Completed') OR (`deletes` = 0 AND `status` = 'Active' AND `id` IN ( SELECT `id` FROM ( SELECT `id` FROM `draw` WHERE `deletes` = 0 AND `status` = 'Active' ORDER BY `id` ASC LIMIT 2 ) t )) ORDER BY `id` DESC", "", "");
									if ($draw['nr'] > 0) {

										foreach ($draw['result'] as $key => $value) {
											$naeme = explode("#", $value['name']);
									?>
											<option value="<?= $value['id']; ?>" <?= ($draw_id === $value['id']) ? 'selected' : ''; ?>><?= 'Draw No #'  . str_pad($value['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($value['result_datetime'])) . ' ' . str_replace("Draw", "", $naeme[0]) . ' (' .  date("D", strtotime($value['result_datetime'])) . ')'; ?></option>
									<?php
										}
									}
									?>
								</select>

							</div> -->



							<div class="col-lg-4 col-md-8 mb-2">
								<!-- <div class="d-lg-flex align-items-center"> -->
								<!-- <input type="text" class="form-control" placeholder="Search Kiosk ID" maxlength="30" id="searchTxt">
									<p class="mt-1 mb-0 px-3"><b>OR</b></p> -->
								<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
									<i class="fa fa-calendar"></i>&nbsp;
									<span></span> <i class="fa fa-caret-down"></i>
								</div>

								<!-- </div> -->
							</div>



							<div class="col-lg-4 col-md-2 mb-2">

								<!-- <br> -->

								<button onclick="viewtable()">GO</button>

							</div>
						</div>



						<!-- </div> -->



					</div>



					<div class="card-body">



						<div class="table-responsive">



							<table class="table table-bordered text-nowrap border-bottom" id="agenttickettable" style="width:100%;">



								<thead>



									<tr>

									<th class="wd-15p border-bottom-0">Customer Name</th>

									<th class="wd-15p border-bottom-0">Email</th>

									<th class="wd-15p border-bottom-0">Mobile</th>

									<th class="wd-15p border-bottom-0">Created At</th>
										<!-- <th class="wd-15p border-bottom-0">Ticket ID</th>



										<th class="wd-15p border-bottom-0">Customer Name</th>



										<th class="wd-15p border-bottom-0">Mobile Number</th>



										<th class="wd-20p border-bottom-0">Email ID</th>



										<th class="wd-25p border-bottom-0">My3Numbers</th>



										<th class="wd-20p border-bottom-0">Raffle ID</th>



										<th class="wd-20p border-bottom-0">Product Amount (AED)</th>



										<th class="wd-25p border-bottom-0">Purchase Date & Timing</th>



										<th class="wd-25p border-bottom-0">KIOSK ID</th>

										<th class="wd-25p border-bottom-0">Draw</th>

										<th class="wd-15p border-bottom-0">Action</th> -->



									</tr>



								</thead>



								<tbody>



								</tbody>



								<!-- <tfoot>



									<tr>



										<th colspan="5"></th>



										<th>Total</th>



										<th></th>



										<th colspan="3"></th>



									</tr>



								</tfoot> -->



							</table>



						</div>



					</div>



				</div>



			</div>



		</div>











	</div>







</div>











<div class="modal fade" id="previewModal">



	<div class="modal-dialog modal-sm" role="document">



		<div class="modal-content modal-content-demo" style="width: 400px !important;">



			<div class="modal-header">



				<h6 class="modal-title">Ticket Preview</h6>



				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">



					<span aria-hidden="true">×</span>



				</button>



			</div>



			<div class="modal-body">



				<div class="row">







					<div class="col-sm-12">



						<div id="ticketview">







						</div>



					</div>



				</div>



			</div>



			<div class="modal-footer">



				<button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Cancel</button>



				<div id="rebtn">



					<button class="btn btn-success my-1" type="button" id="buybtn" onclick="registeroffline($('#totallines').val())">Confirm</button>



				</div>



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



















<div class="modal fade" id="deleteModal">



	<div class="modal-dialog modal-sm" role="document">



		<div class="modal-content modal-content-demo">



			<div class="modal-header">



				<h6 class="modal-title">Confirm</h6>



				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">



					<span aria-hidden="true">×</span>



				</button>



			</div>



			<div class="modal-body">



				<div id="">



					<input type="hidden" id="transid">



					<textarea id="deletemessage" cols="30" rows="10" placeholder="Reason"></textarea>



				</div>



			</div>



			<div class="modal-footer">



				<button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button>



				<div id="delete_btn">



					<button class="btn ripple btn-danger" onclick="confirmDelete($('#transid').val(), $('#deletemessage').val())" type="button">Submit</button>



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



									<input class="input100 border-start-0 ms-0 form-control req-empty" name="name" id="name" oninput="this.value = this.value.replace(/[^A-Za-z. ]/g, '');" type="text" placeholder="Name" required>



								</div>

								<label class="lb-text">Mobile No&nbsp;<sup style="color: red;weight: bolder;">*</sup></label><br>

								<div class="wrap-input100 validate-input input-group">

									<input id="phone" type="tel" oninput="mobile_number_validation($(this).val())" onfocusout="Email_check()" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="15">

									<span id="valid-msg" class="hide">Valid</span>

									<span id="error-msg" class="hide">Invalid number</span>
									<input type="checkbox" id="is_whatsapp" name="is_whatsapp">
									<label for="is_whatsapp">Is whatsapp</label>


								</div>



								<label class="lb-text">Email&nbsp;<sup style="color: red;weight: bolder;">*</sup></label><br>

								<div class="wrap-input100 validate-input input-group">



									<a href="javascript:void(0)" class="input-group-text bg-white text-muted"><i class="zmdi zmdi-email" aria-hidden="true"></i></a>



									<input class="input100 border-start-0 ms-0 form-control req-empty" name="email" type="email" oninput="this.value = this.value.replace(/[^A-Za-z0-9@._]/g, '');" id="email" placeholder="Email" required>



								</div>





								<label class="lb-text">Building Name&nbsp;<sup style="color: red;weight: bolder;">*</sup></label><br>

								<div class="wrap-input100 validate-input input-group">



									<a href="javascript:void(0)" class="input-group-text bg-white text-muted"><i class="fa fa-hospital-o" aria-hidden="true"></i>

									</a>



									<input type="text" value="<?= $row['building_name']; ?>" oninput="this.value = this.value.replace(/[^A-Za-z0-9#,. `]/g, '');" name="bulidingname" class="form-control" id="bulidingname" placeholder="Building Name">



								</div>







								<div class="col-md-12">



									<div class="form-group" id="gfhdjkdk">



										<label class="lb-text">Country&nbsp;<sup style="color: red;weight: bolder;">*</sup></label>



										<div class="disc-pot">



											<select class="form-control form-select selectpicker" data-live-search="true" id="nationlaity" name="nationlaity" onchange="getState($(this).val())" required>



												<option value="">Select Country</option>



												<?php



												$countries = mysqli_query($con, "SELECT `countries`.`id` AS `id`, `countries`.`name` AS `name`, COUNT(states.id) AS `statecount` FROM `countries` INNER JOIN `states` ON `countries`.`id` = `states`.`country_id` WHERE `countries`.`flag` = '1' GROUP BY countries.id HAVING `statecount` > 0 ORDER BY name ASC;");



												while ($value = mysqli_fetch_array($countries)) : ?>



													<option value="<?= $value['id']; ?>" <?= (strtolower(utf8_encode($value['name'])) == strtolower(utf8_encode($row['nationality']))) ? 'selected' : ''; ?>><?= utf8_encode($value['name']); ?></option>



												<?php endwhile; ?>



											</select>



										</div>



									</div>



								</div>







								<div class="col-md-12">



									<div class="form-group">



										<label class="lb-text">State / Emirates&nbsp;<sup style="color: red;weight: bolder;">*</sup></label>







										<select class="form-control form-select selectpicker" data-live-search="true" id="billing_address" name="billing_address" onchange="getCity($(this).val())" required>



											<option value="">Select State / Emirates</option>



										</select>







										<p style="color:#18ff36; font-weight:bold; font-size:12px;" id="billingerrorinfo"></p>



									</div>



								</div>







								<div class="col-md-12">



									<div class="form-group">



										<label class="lb-text">Area / District&nbsp;<sup style="color: red;weight: bolder;">*</sup></label>



										<select class="form-control form-select selectpicker" data-live-search="true" id="billing_city" name="billing_city" required>



											<option value="">Select Area / District </option>



										</select>



										<p style="color:#18ff36; font-weight:bold; font-size:12px;" id="billingcityerrorinfo"></p>



									</div>



								</div>




							</div>



						</div>



					</div>



					<div class="modal-footer" id="addAgentbtn">



						<button class="btn ripple btn-success" id="smallmodal" type="button" onclick="sendotp()">Create <?= $addName; ?></button>








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







								<h3 class="text-center"><b id="title"></b> verification



								</h3>



								<p class="text-center">Enter the code we just send on your <b id="title"></b> <b id="mno"></b></p><br>



							</div>







							<div class="row">



								<div class="col-lg-3 col-md-3 col-sm-3">



									<input type="text" id="otp1" name="otp1" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" onkeyup="onKeyUpEvent(1, event)" onfocus="onFocusEvent(1)" class="form-control">



								</div>



								<div class="col-lg-3 col-md-3 col-sm-3">



									<input type="text" id="otp2" name="otp2" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" onkeyup="onKeyUpEvent(2, event)" onfocus="onFocusEvent(2)" class="form-control">



								</div>



								<div class="col-lg-3 col-md-3 col-sm-3">



									<input type="text" id="otp3" name="otp3" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" onkeyup="onKeyUpEvent(3, event)" onfocus="onFocusEvent(3)" class="form-control">



								</div>



								<div class="col-lg-3 col-md-3 col-sm-3">



									<input type="text" id="otp4" name="otp4" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" onkeyup="onKeyUpEvent(4, event)" onfocus="onFocusEvent(4)" class="form-control">



								</div>



							</div>







						</div>







					</form>



					<br>



				</div>



			</div>



			<div class="modal-footer" id="otpbtn">







			</div>



		</div>



	</div>



</div>























<div class="modal fade" id="otp">



	<div class="modal-dialog modal-sm" role="document">



		<div class="modal-content modal-content-demo">



			<div class="modal-header">



				<h6 class="modal-title">OTP</h6>



				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">



					<span aria-hidden="true">×</span>



				</button>



			</div>



			<div class="modal-body">



				<div class="container">



					<form action="#">



						<div class="form-group">



							<div class="model-text">



								<h3 class="text-center">Mobile phone verification



								</h3>



								<p class="text-center">Enter the code we just send on your mobile phone +1 9876543210</p><br>



							</div>







							<div class="row">



								<div class="col-md-3">



									<input type="text" class="form-control" id="" name="">



								</div>



								<div class="col-md-3">



									<input type="text" class="form-control" id="" name="">



								</div>



								<div class="col-md-3">



									<input type="text" class="form-control" id="" name="">



								</div>



								<div class="col-md-3">



									<input type="text" class="form-control" id="" name="">



								</div>



							</div>







						</div>







					</form>



					<br>



				</div>



			</div>



			<div class="modal-footer">



				<button class="btn ripple btn-success" type="button">Submit</button>



				<button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button>



			</div>



		</div>



	</div>



</div>







<script id="rendered-js">
	var origin = window.location.origin;

	let baseurl = '<?= $baseurl; ?>';

	var url = origin + "/ajax/service/kiosk_services.php";



	var datatableurl = origin + "/ajax/service/kiosk_data_services.php";



	var onlineticketurl = origin + "/ajax/service/kioskot_services.php";



	$(function() {
		createDatePricket('reportrange');
		viewtable();
	});




	function createDatePricket(id) {

		var start = moment();

		var end = moment();



		function cb(start, end) {

			$('#' + id + ' span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY'));

		}

		$('#' + id).daterangepicker({

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

	}




	function viewtable() {



		var table = $('#agenttickettable').DataTable();



		table.destroy();

		var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");

		var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");



		// let fieldname = $('#fieldname').val();


		let fileName = 'New Sign Up List';



		table = $("#agenttickettable").DataTable({



			pageLength: 10,



			order: [],



			paging: true,



			searching: true,



			info: true,



			ajax: {



				url: datatableurl,



				method: "POST",



				dataSrc: "",



				data: {

					method: 'new_sign_up_list',
					formdate: formdate,
					todate: todate,
					// searchTxt: $('#searchTxt').val(),
					draw_new_id: $("#draw_new_id").val()
				}



			},



			dom: 'Bfrtip',



			buttons: [



				'pageLength',



				// {



				// 	extend: 'copyHtml5',



				// 	title: fileName


				// },



				// {



				// 	extend: 'csvHtml5',



				// 	title: fileName



				// },







				{



					extend: 'excelHtml5',



					title: fileName



				},



				// {



				// 	extend: 'pdfHtml5',



				// 	orientation: 'landscape',



				// 	pageSize: 'LEGAL',



				// 	title: fileName



				// }, 
				// 'print',







			],



			columns: [

				{
					data: null,
					render: function(data, type, row, meta) {
						return data.name
					}
				},






				{
					data: null,
					render: function(data, type, row, meta) {
						return data.email
					}
				},



				{
					data: null,
					render: function(data, type, row, meta) {
						return data.mobile
					}
				},



				{
					data: null,
					render: function(data, type, row, meta) {
			
						return moment(data.created_at).format("DD MMM YYYY hh:mm a")
					}
				},


				// {
				// 	data: null,
				// 	render: function(data, type, row, meta) {
				// 		return `<textarea readonly="">${data.my3number}</textarea>`
				// 	}
				// },



				// {
				// 	data: null,
				// 	render: function(data, type, row, meta) {
				// 		return `<textarea readonly="">${data.raffle_id}</textarea>`
				// 	}
				// },





				// {
				// 	data: null,
				// 	render: function(data, type, row, meta) {
				// 		return parseInt(data.net_total)
				// 	}
				// },







				// {
				// 	data: null,
				// 	render: function(data, type, row, meta) {
				// 		return moment(data.createdon).format("DD MMM YYYY hh:mm a")
				// 	}
				// },

				// {
				// 	data: null,
				// 	render: function(data, type, row, meta) {
				// 		return data.kiosk_id
				// 	}
				// },
				// {
				// 	data: null,
				// 	render: function(data, type, row, meta) {
				// 		return data.drawname
				// 	}
				// },



				// {
				// 	data: null,
				// 	render: function(data, type, row, meta) {
				// 		return `<a target="_blank" href="${baseurl}ticket-view/${data.transaction_id}" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span class="fa fa-files-o" style="font-size: 18px;"></span></a>`
				// 	}
				// },


			],



			// footerCallback: function(row, data, start, end, display) {







			// 	var api = this.api();















			// 	// Remove the formatting to get integer data for summation







			// 	var intVal = function(i) {







			// 		return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;







			// 	};















			// 	// Total over all pages







			// 	total = api







			// 		.column(6)







			// 		.data()







			// 		.reduce(function(a, b) {







			// 			let x = intVal(a) + intVal(b.net_total);







			// 			return x.toFixed(2);







			// 		}, 0);















			// 	// Total over this page







			// 	pageTotal = api







			// 		.column(6, {







			// 			page: 'current'







			// 		})







			// 		.data()







			// 		.reduce(function(a, b) {







			// 			return intVal(a) + intVal(b.net_total);







			// 		}, 0);















			// 	// Update footer







			// 	$(api.column(6).footer()).html('' + pageTotal + ' ( ' + total + ' total)');







			// }







		});



	}


	function searchviewtable(tableName, tickettype) {

		var table = $('#agenttickettable').DataTable();
		table.destroy();

		let formdate = $('#formdate').val();
		let todate = $('#todate').val();
		let fieldname = $('#fieldname').val();

		table = $("#agenttickettable").DataTable({
			pageLength: 10,
			order: [],
			paging: true,
			searching: true,
			info: true,
			ajax: {
				url: datatableurl,
				method: "POST",
				dataSrc: "",
				data: {
					method: 'list_search_ticket',
					fieldname: fieldname,
					tableName: tableName,
					formdate: formdate,
					type: tickettype,
					todate: todate
				}
			},
			dom: 'Bfrtip',

			buttons: [
				'pageLength',
				{
					extend: 'copyHtml5',
					title: 'Kiosk Tickets Report : (' + formdate + ' to ' + todate + ')'
				},
				{
					extend: 'csvHtml5',
					title: 'Kiosk Tickets Report : (' + formdate + ' to ' + todate + ')'
				},
				{
					extend: 'excelHtml5',
					title: 'Kiosk Tickets Report : (' + formdate + ' to ' + todate + ')'
				},
				{
					extend: 'pdfHtml5',
					orientation: 'landscape',
					pageSize: 'LEGAL',
					title: 'Kiosk Tickets Report : ( ' + formdate + ' to ' + todate + ')'
				}, 'print',
			],

			columns: [{
					data: 'ticketno'
				},
				{
					data: 'cusname'
				},
				{
					data: 'mobile'
				},
				{
					data: 'email'
				},
				{
					data: 'My3Numbers'
				},
				{
					data: 'RaffleID'
				},
				{
					data: 'proamt'
				},
				{
					data: 'purdate'
				},
				{
					data: 'kioskid'
				},
				{
					data: 'action'
				}
			],
			footerCallback: function(row, data, start, end, display) {
				var api = this.api();
				var intVal = function(i) {
					return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
				};

				total = api
					.column(6)
					.data()
					.reduce(function(a, b) {
						let x = intVal(a) + intVal(b);
						return x.toFixed(2);

					}, 0);
				pageTotal = api
					.column(6, {
						page: 'current'
					})
					.data()
					.reduce(function(a, b) {
						return intVal(a) + intVal(b);
					}, 0);

				// Update footer

				$(api.column(6).footer()).html('' + pageTotal + ' ( ' + total + ' total)');
			}
		});
	}
















	function deleteticket(trans_id) {







		document.getElementById('transid').value = trans_id;



		$('#deleteModal').modal('show');







	}







	function confirmDelete(id, Dmessage) {







		document.getElementById('delete_btn').innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';



		let formdata = [];



		formdata.push({



			name: 'method',



			value: "delete_Ticket"



		});



		formdata.push({



			name: 'transid',



			value: id



		});



		formdata.push({



			name: 'message',



			value: Dmessage



		});



		var post_data = formdata;







		var onsuccess = function(data) {



			var response = JSON.parse(data);



			if (response != "") {



				if (response.type == 1) {



					document.getElementById('delete_btn').innerHTML = '<button class="btn ripple btn-danger" onclick="confirmDelete($(' + "'#transid'" + ').val(), $(' + "'#deletemessage'" + ').val())" type="button">Submit</button>';







					var table = $('#agenttickettable').DataTable();



					table.destroy();



					viewtable();



					$('#deleteModal').modal('hide');



					document.getElementById('successerror').innerHTML = '<div class="alert alert-success" role="alert">' + response.result + '</div>';



					$('#sussessmodal').modal('show');



				} else {



					document.getElementById('successerror').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';



					$('#sussessmodal').modal('show');



				}



			}



		}



		do_ajax_call(post_data, onsuccess);















	}







	function deleteagticket(id) {





		swal.fire({



			title: 'Deleted Reason',



			input: 'textarea',



			showCancelButton: true,



			allowOutsideClick: false,



			confirmButtonText: 'Delete Ticket',



			cancelButtonText: 'Close',



		}).then(function(result) {



			if (result.isConfirmed) {



				if (result.value != '') {



					var formdata = [];



					formdata.push({



						name: 'method',



						value: "delete_agent_ticket"



					});



					formdata.push({



						name: 'transid',



						value: id



					});



					formdata.push({



						name: 'message',



						value: result.value



					});



					var post_data = formdata;



					var onsuccess = function(data) {



						var response = JSON.parse(data);



						if (response != "") {



							if (response.type == 1) {



								toast('success', response.result);



								viewtable();



							} else {



								toast('error', response.result);



							}



						}



					}



					do_ajax_call(post_data, onsuccess, url);



				} else {



					toast('error', 'Please Fill the Reason');



				}



			}



		});



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
</script>
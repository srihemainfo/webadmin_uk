<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

if (isset($_REQUEST['menuper'])) {
   mysqli_query($con, "Delete  from `menu_permission` where `userid`='$subid4'");
   $menuval = $_REQUEST['other'];
   $menuid = $_REQUEST['otherid'];
   $menu = mysqli_query($con, "SELECT * FROM  `orm_menu`");
   $val = mysqli_num_rows($menu);
   for ($i = 1; $i <= 300; $i++) {
      if ($menuval[$i] != "") {
         mysqli_query($con, "INSERT INTO `menu_permission`(`userid`,`menu`) VALUES ('$subid4','$menuid[$i]')");
      }
   }
}

?>
<?php
if ($subid3 != 'permission') {
   if ($subid3 != '') {
      $userid = $subid3;
   } else {
      $userid = $_SESSION['memid'];
   }

   $sql = "SELECT ur.*, kd.type FROM `user_register` ur LEFT JOIN kyc_details kd ON kd.user_id = ur.id WHERE ur.`id` = '$userid'";
   $run = mysqli_query($con, $sql);
   if (mysqli_num_rows($run) > 0) {
      $row = $run->fetch_assoc();

   }
    //  var_dump($row);die;
 
   // var_dump($row['company_name']);die;

   $roll_id =  select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'", "roll_id", "");

   $acc_type = ['savings', 'current'];

   $bank_list = [
      'First Abu Dhabi Bank (FAB)',
      'Emirates NBD',
      'Abu Dhabi Commercial Bank',
      'Dubai Islamic Bank',
      'MashreqBank',
      'Abu Dhabi Islamic Bank (ADIB)',
      'HSBC Bank Middle East - UAE Operations',
      'Union National Bank',
      'Commercial Bank of Dubai (CBD)',
      'Emirates Islamic Bank',
      'National Bank of Ras Al Khaimah (RAKBANK)',
      'Al Hilal Bank',
      'Noor Bank',
      'Sharjah Islamic Bank',
      'National Bank of Fujairah',
      'Others'
   ];

   $isocode = array(

      'ARE' => 'United Arab Emirates',

      'ABW' => 'Aruba',

      'AFG' => 'Afghanistan',

      'AGO' => 'Angola',

      'AIA' => 'Anguilla',

      'ALA' => 'Åland Islands',

      'ALB' => 'Albania',

      'AND' => 'Andorra',

      'ARG' => 'Argentina',

      'ARM' => 'Armenia',

      'ASM' => 'American Samoa',

      'ATA' => 'Antarctica',

      'ATF' => 'French Southern Territories',

      'ATG' => 'Antigua and Barbuda',

      'AUS' => 'Australia',

      'AUT' => 'Austria',

      'AZE' => 'Azerbaijan',

      'BDI' => 'Burundi',

      'BEL' => 'Belgium',

      'BEN' => 'Benin',

      'BES' => 'Bonaire, Sint Eustatius and Saba',

      'BFA' => 'Burkina Faso',

      'BGD' => 'Bangladesh',

      'BGR' => 'Bulgaria',

      'BHR' => 'Bahrain',

      'BHS' => 'Bahamas',

      'BIH' => 'Bosnia and Herzegovina',

      'BLM' => 'Saint Barthélemy',

      'BLR' => 'Belarus',

      'BLZ' => 'Belize',

      'BMU' => 'Bermuda',

      'BOL' => 'Bolivia, Plurinational State of',

      'BRA' => 'Brazil',

      'BRB' => 'Barbados',

      'BRN' => 'Brunei Darussalam',

      'BTN' => 'Bhutan',

      'BVT' => 'Bouvet Island',

      'BWA' => 'Botswana',

      'CAF' => 'Central African Republic',

      'CAN' => 'Canada',

      'CCK' => 'Cocos (Keeling) Islands',

      'CHE' => 'Switzerland',

      'CHL' => 'Chile',

      'CHN' => 'China',

      'CIV' => 'Côte d\'Ivoire',

      'CMR' => 'Cameroon',

      'COD' => 'Congo, the Democratic Republic of the',

      'COG' => 'Congo',

      'COK' => 'Cook Islands',

      'COL' => 'Colombia',

      'COM' => 'Comoros',

      'CPV' => 'Cape Verde',

      'CRI' => 'Costa Rica',

      'CUB' => 'Cuba',

      'CUW' => 'Curaçao',

      'CXR' => 'Christmas Island',

      'CYM' => 'Cayman Islands',

      'CYP' => 'Cyprus',

      'CZE' => 'Czech Republic',

      'DEU' => 'Germany',

      'DJI' => 'Djibouti',

      'DMA' => 'Dominica',

      'DNK' => 'Denmark',

      'DOM' => 'Dominican Republic',

      'DZA' => 'Algeria',

      'ECU' => 'Ecuador',

      'EGY' => 'Egypt',

      'ERI' => 'Eritrea',

      'ESH' => 'Western Sahara',

      'ESP' => 'Spain',

      'EST' => 'Estonia',

      'ETH' => 'Ethiopia',

      'FIN' => 'Finland',

      'FJI' => 'Fiji',

      'FLK' => 'Falkland Islands (Malvinas)',

      'FRA' => 'France',

      'FRO' => 'Faroe Islands',

      'FSM' => 'Micronesia, Federated States of',

      'GAB' => 'Gabon',

      'GBR' => 'United Kingdom',

      'GEO' => 'Georgia',

      'GGY' => 'Guernsey',

      'GHA' => 'Ghana',

      'GIB' => 'Gibraltar',

      'GIN' => 'Guinea',

      'GLP' => 'Guadeloupe',

      'GMB' => 'Gambia',

      'GNB' => 'Guinea-Bissau',

      'GNQ' => 'Equatorial Guinea',

      'GRC' => 'Greece',

      'GRD' => 'Grenada',

      'GRL' => 'Greenland',

      'GTM' => 'Guatemala',

      'GUF' => 'French Guiana',

      'GUM' => 'Guam',

      'GUY' => 'Guyana',

      'HKG' => 'Hong Kong',

      'HMD' => 'Heard Island and McDonald Islands',

      'HND' => 'Honduras',

      'HRV' => 'Croatia',

      'HTI' => 'Haiti',

      'HUN' => 'Hungary',

      'IDN' => 'Indonesia',

      'IMN' => 'Isle of Man',

      'IND' => 'India',

      'IOT' => 'British Indian Ocean Territory',

      'IRL' => 'Ireland',

      'IRN' => 'Iran, Islamic Republic of',

      'IRQ' => 'Iraq',

      'ISL' => 'Iceland',

      'ISR' => 'Israel',

      'ITA' => 'Italy',

      'JAM' => 'Jamaica',

      'JEY' => 'Jersey',

      'JOR' => 'Jordan',

      'JPN' => 'Japan',

      'KAZ' => 'Kazakhstan',

      'KEN' => 'Kenya',

      'KGZ' => 'Kyrgyzstan',

      'KHM' => 'Cambodia',

      'KIR' => 'Kiribati',

      'KNA' => 'Saint Kitts and Nevis',

      'KOR' => 'Korea, Republic of',

      'KWT' => 'Kuwait',

      'LAO' => 'Lao People\'s Democratic Republic',

      'LBN' => 'Lebanon',

      'LBR' => 'Liberia',

      'LBY' => 'Libya',

      'LCA' => 'Saint Lucia',

      'LIE' => 'Liechtenstein',

      'LKA' => 'Sri Lanka',

      'LSO' => 'Lesotho',

      'LTU' => 'Lithuania',

      'LUX' => 'Luxembourg',

      'LVA' => 'Latvia',

      'MAC' => 'Macao',

      'MAF' => 'Saint Martin (French part)',

      'MAR' => 'Morocco',

      'MCO' => 'Monaco',

      'MDA' => 'Moldova, Republic of',

      'MDG' => 'Madagascar',

      'MDV' => 'Maldives',

      'MEX' => 'Mexico',

      'MHL' => 'Marshall Islands',

      'MKD' => 'Macedonia, the former Yugoslav Republic of',

      'MLI' => 'Mali',

      'MLT' => 'Malta',

      'MMR' => 'Myanmar',

      'MNE' => 'Montenegro',

      'MNG' => 'Mongolia',

      'MNP' => 'Northern Mariana Islands',

      'MOZ' => 'Mozambique',

      'MRT' => 'Mauritania',

      'MSR' => 'Montserrat',

      'MTQ' => 'Martinique',

      'MUS' => 'Mauritius',

      'MWI' => 'Malawi',

      'MYS' => 'Malaysia',

      'MYT' => 'Mayotte',

      'NAM' => 'Namibia',

      'NCL' => 'New Caledonia',

      'NER' => 'Niger',

      'NFK' => 'Norfolk Island',

      'NGA' => 'Nigeria',

      'NIC' => 'Nicaragua',
      'NIU' => 'Niue',
      'NLD' => 'Netherlands',
      'NOR' => 'Norway',
      'NPL' => 'Nepal',
      'NRU' => 'Nauru',
      'NZL' => 'New Zealand',
      'OMN' => 'Oman',
      'PAK' => 'Pakistan',
      'PAN' => 'Panama',
      'PCN' => 'Pitcairn',
      'PER' => 'Peru',
      'PHL' => 'Philippines',
      'PLW' => 'Palau',
      'PNG' => 'Papua New Guinea',
      'POL' => 'Poland',
      'PRI' => 'Puerto Rico',
      'PRK' => 'Korea, Democratic People\'s Republic of',
      'PRT' => 'Portugal',
      'PRY' => 'Paraguay',
      'PSE' => 'Palestinian Territory, Occupied',
      'PYF' => 'French Polynesia',
      'QAT' => 'Qatar',
      'REU' => 'Réunion',
      'ROU' => 'Romania',
      'RUS' => 'Russian Federation',
      'RWA' => 'Rwanda',
      'SAU' => 'Saudi Arabia',
      'SDN' => 'Sudan',
      'SEN' => 'Senegal',
      'SGP' => 'Singapore',
      'SGS' => 'South Georgia and the South Sandwich Islands',
      'SHN' => 'Saint Helena, Ascension and Tristan da Cunha',
      'SJM' => 'Svalbard and Jan Mayen',
      'SLB' => 'Solomon Islands',
      'SLE' => 'Sierra Leone',
      'SLV' => 'El Salvador',
      'SMR' => 'San Marino',
      'SOM' => 'Somalia',
      'SPM' => 'Saint Pierre and Miquelon',
      'SRB' => 'Serbia',
      'SSD' => 'South Sudan',
      'STP' => 'Sao Tome and Principe',
      'SUR' => 'Suriname',
      'SVK' => 'Slovakia',
      'SVN' => 'Slovenia',
      'SWE' => 'Sweden',
      'SWZ' => 'Swaziland',
      'SXM' => 'Sint Maarten (Dutch part)',
      'SYC' => 'Seychelles',
      'SYR' => 'Syrian Arab Republic',
      'TCA' => 'Turks and Caicos Islands',
      'TCD' => 'Chad',
      'TGO' => 'Togo',
      'THA' => 'Thailand',
      'TJK' => 'Tajikistan',
      'TKL' => 'Tokelau',
      'TKM' => 'Turkmenistan',
      'TLS' => 'Timor-Leste',
      'TON' => 'Tonga',
      'TTO' => 'Trinidad and Tobago',
      'TUN' => 'Tunisia',
      'TUR' => 'Turkey',
      'TUV' => 'Tuvalu',
      'TWN' => 'Taiwan, Province of China',
      'TZA' => 'Tanzania, United Republic of',
      'UGA' => 'Uganda',
      'UKR' => 'Ukraine',
      'UMI' => 'United States Minor Outlying Islands',
      'URY' => 'Uruguay',
      'USA' => 'United States',
      'UZB' => 'Uzbekistan',
      'VAT' => 'Holy See (Vatican City State)',
      'VCT' => 'Saint Vincent and the Grenadines',
      'VEN' => 'Venezuela, Bolivarian Republic of',
      'VGB' => 'Virgin Islands, British',
      'VIR' => 'Virgin Islands, U.S.',
      'VNM' => 'Viet Nam',
      'VUT' => 'Vanuatu',
      'WLF' => 'Wallis and Futuna',
      'WSM' => 'Samoa',
      'YEM' => 'Yemen',
      'ZAF' => 'South Africa',
      'ZMB' => 'Zambia',
      'ZWE' => 'Zimbabwe',
   );

   $bank_check = '';
   if ($row['bank_name'] != '') {
      if (in_array($row['bank_name'], $bank_list)) {
         $bank_check = '1';
      } else {
         $bank_check = '0';
      }
   }
?>
   <style>
      .bootstrap-select>.dropdown-toggle {
         background: #fff
      }

      .brround {
         height: 80px;
         width: 81px;
      }

      .bootstrap-select .dropdown-menu li a,
      .dropdown-item.active {
         padding: 3px 0 24px 22px !important
      }

      .inner.show {
         min-height: 135px !important;
         max-height: 134px !important
      }

      .dropdown-menu.show {
         width: 100%
      }

      .bootstrap-select .dropdown-toggle .filter-option {
         height: auto
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

      textarea {
         height: 100px;
         padding: 12px 20px;
         box-sizing: border-box;
         border: 2px solid #ccc;
         border-radius: 4px;
         background-color: #f8f8f8;
         font-size: 16px;
         resize: none;
         text-align: center;
      }
   </style>
   <div class="main-content mt-0 edit-profile1 app-content">
      <div class="side-app">
         <!-- CONTAINER -->
         <div class="main-container container-fluid">
            <!-- PAGE-HEADER -->
            <div class="page-header">
               <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Edit Profile</h1>
               <div>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="javascript:void(0)">Pages</a></li>
                     <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
                  </ol>
               </div>
            </div>
            <!-- PAGE-HEADER END -->
            <!-- ROW-1 OPEN -->
            <div class="row">
               <div class="col-xl-4">
                  <div class="card">
                     <div class="card-header">
                        <div class="card-title">My Profile</div>
                     </div>
                     <div class="card-body">
                        <div class="text-center chat-image mb-5">
                           <div class="avatar avatar-xxl chat-profile mb-3 brround">
                              <?php if ($row['img_url'] != '') { ?>
                                 <a class="" href="javascript:void(0);">
                                    <img alt="avatar" src="https://nationalasset.blr1.digitaloceanspaces.com/<?php echo $row['img_url']; ?>" class="brround">
                                 </a>
                              <?php } else { ?>
                                 <a class="" href="javascript:void(0);">
                                    <img alt="avatar" src="assets/images/users/2.jpg" class="brround">
                                 </a>
                              <?php } ?>
                           </div>
                           <div class="main-chat-msg-name">
                              <?php if ($roll_id  != 9) { ?>
                                 <!-- <a href="agentview"> -->
                                 <h5 class="mb-1 text-dark fw-semibold">Go Ride</h5>
                                 </a>
                              <?php } ?>
                              <?php if ($roll_id  == 9) { ?>
                                 <p class="mt-0 mb-0 pt-0 fs-15">User-
                                    <span class="font-weight-500"><?= $row['name']; ?></span>
                                 </p>
                              <?php } ?>
                              <p class="text-muted mt-0 mb-0 pt-0 fs-13">ID - <?= $row['id']; ?></p>
                              <?php if ($roll_id  != 9) { ?>
                                 <!--<p class=" mt-0 mb-0 pt-0 fs-15">Balance --->
                                 <!--   <span class="font-weight-500"><?= ($row['walletBalance'] != 0) ? $row['walletBalance'] : "Nil"; ?></span>-->
                                 <!--</p>-->
                                 <p class="mt-0 mb-0 pt-0 fs-15">Last Login -
                                    <span class="font-weight-500"><?= $row['lastlogin']; ?></span>
                                 </p>
                              <?php } ?>
                              <?php if ($roll_id  == 9) { ?>
                                 <p class="mt-0 mb-0 pt-0 fs-15">Created On -
                                    <span class="font-weight-500"><?= $row['created_at']; ?></span>
                                 </p>
                              <?php } ?>
                           </div>
                        </div>
                        <!--OLD -->
                        <!--<div class="main-chat-msg-name">-->
                        <!--    <a href="agentview">-->
                        <!--        <h5 class="mb-1 text-dark fw-semibold">National Draw</h5>-->
                        <!--    </a>-->
                        <!--    <p class="text-muted mt-0 mb-0 pt-0 fs-13">ID - <?= $row['id']; ?></p>-->
                        <!--    <p class=" mt-0 mb-0 pt-0 fs-15">Balance --->
                        <!--        <span class="font-weight-500"><?= ($row['t_earning'] != 0) ? $row['t_earning'] : "Nill"; ?></span>-->
                        <!--    </p>-->
                        <!--    <p class="mt-0 mb-0 pt-0 fs-15">Last Login --->
                        <!--        <span class="font-weight-500"><?= $row['lastlogin']; ?></span>-->
                        <!--    </p>-->
                        <!--</div>-->
                     </div>
                     <ul class="list-group no-margin">
                        <li class="list-group-item d-flex ps-3">
                           <div class="social social-profile-buttons me-2">
                              <a class="social-icon text-primary" href=""><i class="fe fe-mail"></i></a>
                           </div>
                           <a href="javascript:void(0)" class="my-auto"><?= $row['email']; ?></a>
                        </li>
                        <!--<li class="list-group-item d-flex ps-3">-->
                        <!--   <div class="social social-profile-buttons me-2">-->
                        <!--      <a class="social-icon text-primary" href=""><i class="fa fa-address-card-o"></i></a>-->
                        <!--   </div>-->
                        <!--   <a href="javascript:void(0)" class="my-auto"><?= $row['passport']; ?></a>-->
                        <!--</li>-->
                        <li class="list-group-item d-flex ps-3">
                           <div class="social social-profile-buttons me-2">
                              <a class="social-icon text-primary" href=""><i class="fe fe-phone"></i></a>
                           </div>
                           <a href="javascript:void(0)" class="my-auto"><?= $row['mobile']; ?></a>
                        </li>
                     </ul>
                  </div>
               </div>
               <div class="col-xl-8">
                  <form id="editprofileform">
                     <input type="hidden" id="updateuserid" name="updateuserid" value="<?= $userid; ?>">
                     <input type="hidden" id="typeOTP" name="typeOTP" value="<?php
                                                                              if ($subid3 != '') {
                                                                                 echo 'send';
                                                                              }
                                                                              ?>">
                     <div class="card ">
                        <div class="card-header">
                           <h3 class="card-title">Edit Profile</h3>
                        </div>
                        <div class="card-body">
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="exampleInputname">First Name</label>
                                    <input type="text" value="<?= $row['name']; ?>" name="name" class="form-control" id="exampleInputname" oninput="this.value = this.value.replace(/[^A-Za-z. ]/g, '');" placeholder="First Name">
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="exampleInputname">Last Name</label>
                                    <input type="text" value="<?= $row['lname']; ?>" name="lname" class="form-control" id="exampleInputname" oninput="this.value = this.value.replace(/[^A-Za-z. ]/g, '');" placeholder="Last Name">
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="exampleInputnumber">Mobile Number</label>
                                    <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '');" value="<?= $row['mobile']; ?>" name="mobile" class="form-control" id="exampleInputnumber" placeholder="Contact number" <?= ($roll_id  != 1) ? 'readonly' : ''; ?>>
                                 </div>
                              </div>
                              
                              <?php
                              if (in_array($row['roll_id'], [3])) {
                              ?>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label for="exampleInputnumber">WhatsApp No</label>
                                       <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '');" value="<?= $row['whatsAppNo']; ?>" name="whatsAppNo" class="form-control" id="exampleInputnumber" placeholder="Contact number" <?= ($roll_id  != 1) ? 'readonly' : ''; ?>>
                                    </div>
                                 </div>
                              <?php
                              }
                              ?>


                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="exampleInputEmail1">Email ID</label>
                                    <input type="email" value="<?= $row['email']; ?>" oninput="this.value = this.value.replace(/[^A-Za-z0-9@._]/g, '');" name="email" class="form-control" id="exampleInputEmail1" placeholder="Email address" <?= ($roll_id  != 1) ? 'readonly' : ''; ?>>
                                 </div>
                              </div>
                              <!--<div class="col-md-4">-->
                              <!--   <div class="form-group">-->
                              <!--      <label for="bulidingname"><?= (in_array($row['roll_id'], [7])) ? 'Company/Shop Name' : 'Building Name'; ?></label>-->
                              <!--      <input type="text" value="<?= $row['building_name']; ?>" oninput="this.value = this.value.replace(/[^A-Za-z0-9#,. `]/g, '');" name="bulidingname" class="form-control" id="bulidingname" placeholder="Building Name ">-->
                              <!--   </div>-->
                              <!--</div>-->
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label class="lb-text">State</label>
                                    <select class="form-control form-select selectpicker" data-live-search="true" id="billing_address" name="billing_address" required>
                                       <option value="">Select State</option>
                                        <?php
                                       $query = mysqli_query($con,"SELECT id, name FROM states WHERE country_code = 'IN' ORDER BY id ASC");
                                        while($rows = mysqli_fetch_assoc($query)){

                                             $selected = ($rows['name'] == $row['state']) ? 'selected' : '';
                                             echo "<option value='{$rows['id']}' $selected >{$rows['name']}</option>";
                                          }
                                       ?>
                                    </select>
                                    <p style="color:#18ff36; font-weight:bold; font-size:12px;" id="billingerrorinfo"></p>
                                 </div>
                              </div>
                              <!--<div class="col-md-4">-->
                              <!--   <div class="form-group">-->
                              <!--      <label class="lb-text">Area / District</label>-->
                              <!--      <select class="form-control form-select selectpicker" data-live-search="true" id="billing_city" name="billing_city"  required>-->
                              <!--         <option value="">Select Area / District </option>-->
                                       
                              <!--      </select>-->
                              <!--      <p style="color:#18ff36; font-weight:bold; font-size:12px;" id="billingcityerrorinfo"></p>-->
                              <!--   </div>-->
                              <!--</div>-->
                               <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="exampleInputname">UPI ID</label>
                                    <input type="text" value="<?= $row['upiID']; ?>" name="upi_id" class="form-control" id="exampleUpi" oninput="this.value = this.value.replace(/[^A-Za-z. ]/g, '');"  placeholder="UPI ID">
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="exampleInputname">Address</label>
                                    <input type="text" value="<?= $row['address']; ?>" name="address_us" class="form-control" id="address_us" oninput="this.value = this.value.replace(/[^A-Za-z.-,/@ ]/g, '');" placeholder="Address">
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="exampleInputname">User Role Type</label>
                                    <select name="user_role" id="user_role" class="form-control form-select">
                                        <option value="">Select User Role</option>
                                        <option value="driver" <?= ($row['type'] == 'Driver') ? 'selected' : '' ?>>Driver</option>
                                        <option value="owner" <?= ($row['type'] == 'Owner') ? 'selected' : '' ?>>Owner</option>
                                    </select>
                        
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="exampleInputname" id="com_label" style="display:none;">Company Name</label>
                                    <input type="text" value="<?= $row['company_name']; ?>" name="company_name" class="form-control" id="exampleCompany"  style="display:none;" oninput="this.value = this.value.replace(/[^A-Za-z. ]/g, '');" placeholder="Company Name">
                                 </div>
                              </div>

                           </div>
                        </div>
                        <div class="card-footer text-end">
                           <div id="editmessage2"></div>
                           <?php if ($subid3 != '' && $roll_id != 1 && $roll_id != 6) { ?>
                              <button type="button" onclick="updateverification()" id="newupotp" class="btn btn-success bg-success-gradient my-1">Send OTP</button>
                           <?php   } else { ?>
                              <button type="button" onclick="updateDetails()" class="btn btn-success bg-success-gradient my-1">Update</button>
                           <?php  } ?>
                           <button type="button" onclick="updateDetails()" id="userup" class="btn btn-success bg-success-gradient my-1">Update</button>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="modal fade" id="otp">
      <div class="modal-dialog modal-sm" role="document">
         <div class="modal-content modal-content-demo">
            <div id="otperror2"></div>
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
                           <h3 class="text-center">User verification
                           </h3>
                           <p class="text-center">Enter the code we just send on your <?= $row['email']; ?> / <?= $row['mobile']; ?> </p>
                           <br>
                        </div>
                        <div class="row">
                           <div class="col-md-3">
                              <input type="text" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" onkeyup="onKeyUpEvent(1, event)" onfocus="onFocusEvent(1)" class="form-control" id="otp1" name="otp1">
                           </div>
                           <div class="col-md-3">
                              <input type="text" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" onkeyup="onKeyUpEvent(2, event)" onfocus="onFocusEvent(2)" class="form-control" id="otp2" name="otp2">
                           </div>
                           <div class="col-md-3">
                              <input type="text" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" onkeyup="onKeyUpEvent(3, event)" onfocus="onFocusEvent(3)" class="form-control" id="otp3" name="otp3">
                           </div>
                           <div class="col-md-3">
                              <input type="text" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" onkeyup="onKeyUpEvent(4, event)" onfocus="onFocusEvent(4)" class="form-control" id="otp4" name="otp4">
                           </div>
                        </div>
                     </div>
                  </form>
                  <br>
               </div>
            </div>
            <div class="modal-footer">
               <button class="btn ripple btn-success" onclick="updateverification()" type="button">Submit</button>
               <button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button>
            </div>
         </div>
      </div>
   </div>
   <style>
      input,
      select {
         border: 1px solid #ccc
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

      .min-height-profile-dtls {
         height: 455px !important;
         overflow-x: scroll !important
      }

      .edit-profile1.app-content {
         min-height: calc(92vh - 50px)
      }

      .jexcel>tbody>tr>td.readonly {
         color: #000 !important
      }

      .nav.product-sale {
         position: unset;
         top: -3rem;
         right: 5px;
         margin: 12px 0;
      }

      table.table.table-bordered {
         text-align: center;
      }

      div#shtext {
         text-align: center;
         cursor: pointer;
      }

      #spreedcontent {
         text-align: center;
         margin-top: 10px;
      }

      .show-more {
         text-align: center;
         cursor: pointer;
      }
   </style>
   <?php
   if ($subid3 != '') {
      $userid = $subid3;
   } else {
      $userid = $_SESSION['memid'];
   }

   $sql = "SELECT * FROM `user_register` WHERE `id` = '$userid'";
   $run = mysqli_query($con, $sql);
   if (mysqli_num_rows($run) > 0) {
      $row = $run->fetch_assoc();
   }

   $roll_id = select_top_name($con, 'user_register', "roll_id", "`id`='$_SESSION[memid]'", "roll_id", "");

   if ($roll_id == 1 || $roll_id == 2 || $roll_id == 6  && ($row['id'] != $_SESSION['memid'])  &&  $row['roll_id']  != 3 && $row['roll_id']  != 4  &&  $row['roll_id']  != 5) {
   // var_dump($row['company_name']);die;
   ?>
      <div class="main-content app-content mt-0">
         <div class="side-app">
            <input type="hidden" id="tabID" value="agents">
            <!-- CONTAINER -->
            <div class="main-container container-fluid">
               <!-- Bootstrap Tab  -->
               <div class="row row-sm">
                  <div class="col-lg-12">
                     <div class="card">
                        <div class="card-body pt-4">
                           <div class="grid-margin">
                              <div class="panel panel-primary">
                                 <div class="tab-menu-heading border-0 p-0">
                                    <div class="tabs-menu1">
                                       <!-- Tabs -->
                                       <ul class="nav panel-tabs product-sale">
                                          <!--<li><a href="#tab1" class=" text-dark active" data-bs-toggle="tab" onclick="Customer_Ticket()"><?= ucwords('Ticket Wise Reports'); ?></a></li>-->
                                          <li><a href="#tab2" data-bs-toggle="tab" class="text-dark active" onclick="transaction()">Transaction History</a></li>
                                          <!--<li><a href="#tab3" data-bs-toggle="tab" class="text-dark " onclick="withdrawal()">Withdrawal History</a></li>-->
                                          <!--<li><a href="#tab4" data-bs-toggle="tab" class="text-dark " onclick="getCashBonusReport()">Cash & Bonus Report</a></li>-->
                                          <!--<li><a href="#tab5" data-bs-toggle="tab" class="text-dark " onclick="balance_summary()">Balance Sheet</a></li>-->
                                          <!--<li><a href="#tab6" data-bs-toggle="tab" class="text-dark " onclick="new_balance_summary()">New Balance Sheet</a></li>-->
                                          <!--<li><a href="#tab7" data-bs-toggle="tab" class="text-dark " onclick="n_balance_summary()">CB Balance Sheet</a></li>-->
                                       </ul>
                                    </div>
                                 </div>
                                 <div class="panel-body tabs-menu-body border-0 pt-0">
                                    <div class="tab-content">
                                       <!-- Customer Ticket Line Wise Reports  -->
                                       <div class="tab-pane " id="tab1">
                                          <div class="row">
                                             <!-- <div class="col-lg-5 col-md-7 mb-2">
                                          <label for="">Purchase Date & Time</label>
                                          <div id="ticketctime" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"><i class="fa fa-calendar"></i>&nbsp;<span></span> <i class="fa fa-caret-down"></i></div>
                                          </div> -->
                                             <div class="col-md-3  mb-2">
                                                <!-- <button class="btn btn-info" onclick="Customer_Ticket()">Load Tickets</button> -->
                                             </div>
                                          </div>
                                          <div class="table-responsive">
                                             <table class="table table-bordered text-nowrap border-bottom" id="customer_ticket" style="width:100%;">
                                                <thead>
                                                   <tr>
                                                      <th class="wd-15p border-bottom-0">S.No</th>
                                                      <th class="wd-15p border-bottom-0">Ticket ID</th>
                                                      <th class="wd-20p border-bottom-0">Raffle Id</th>
                                                      <th class="wd-20p border-bottom-0">Net Total (AED)</th>
                                                      <th class="wd-20p border-bottom-0">Shipping Amount (AED)</th>
                                                      <th class="wd-20p border-bottom-0">Grand Total (AED)</th>
                                                      <th class="wd-25p border-bottom-0">Purchase Date & Timing</th>
                                                      <th class="wd-25p border-bottom-0">End Date</th>
                                                      <th class="wd-25p border-bottom-0">Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <!--<tfoot>-->
                                                <!--   <tr>-->
                                                <!--      <th></th>-->
                                                <!--      <th></th>-->
                                                <!--      <th></th>-->
                                                <!--      <th style="text-align:right">Total:</th>-->
                                                <!--      <th> </th>-->
                                                <!--      <th></th>-->
                                                <!--   </tr>-->
                                                <!--</tfoot>-->
                                             </table>
                                          </div>
                                       </div>
                                       <!-- Transaction History  -->
                                       <div class="tab-pane active" id="tab2">
                                          <div class="row">
                                             <div class="col-lg-5 col-md-7 mb-2">
                                                <div id="transhctime" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"><i class="fa fa-calendar"></i>&nbsp;<span></span> <i class="fa fa-caret-down"></i></div>
                                             </div>
                                             <div class="col-md-3  mb-2">
                                                <button class="btn btn-info" onclick="transaction()">Go</button>
                                             </div>
                                          </div>
                                          <div class="table-responsive">
                                             <table id="transaction" class="table table-bordered text-nowrap border-bottom " style="width:100%;">
                                                <thead>
                                                   <tr>
                                                      <!--<th class="wd-15p border-bottom-0">ID</th>-->
                                                      <th class="wd-15p border-bottom-0">Date &amp; Time</th>
                                                      <th class="wd-15p border-bottom-0">Purchase Type</th>
                                                      <th class="wd-15p border-bottom-0">Plan Type</th>
                                                      <th class="wd-15p border-bottom-0">Payment Gatway</th>
                                                      <th class="wd-15p border-bottom-0">Payment Status</th>
                                                      <th class="wd-15p border-bottom-0">Transaction ID</th>
                                                      <th class="wd-15p border-bottom-0"> Amount (AED)</th>
                                                      <!--<th class="wd-15p border-bottom-0">Total Balance</th>-->
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                </tfoot>
                                             </table>
                                          </div>
                                       </div>
                                       <!-- Withdrawal History  -->
                                       <div class="tab-pane" id="tab3">
                                          <div class="row">
                                             <div class="col-lg-5 col-md-7 mb-2">
                                                <div id="withhctime" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"><i class="fa fa-calendar"></i>&nbsp;<span></span> <i class="fa fa-caret-down"></i></div>
                                             </div>
                                             <div class="col-md-3  mb-2">
                                                <button class="btn btn-info" onclick="withdrawal()">Go</button>
                                             </div>
                                          </div>
                                          <div class="table-responsive">
                                             <table id="withdrawal_space" class="table table-bordered text-nowrap border-bottom" style="width:100%">
                                                <thead>
                                                   <tr>
                                                      <th class="wd-15p border-bottom-0">Requested Date</th>
                                                      <th class="wd-15p border-bottom-0">To</th>
                                                      <th class="wd-15p border-bottom-0">Amount</th>
                                                      <th class="wd-15p border-bottom-0">wallet Balance</th>
                                                      <th class="wd-15p border-bottom-0">Status</th>
                                                      <th class="wd-15p border-bottom-0">Transaction Mode</th>
                                                      <th class="wd-15p border-bottom-0">Request ID</th>
                                                      <th class="wd-15p border-bottom-0">Transaction Date</th>
                                                      <th class="wd-15p border-bottom-0">Transaction ID</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                </tfoot>
                                             </table>
                                          </div>
                                       </div>
                                       <!-- Cash & Bonus Report  -->
                                       <div class="tab-pane" id="tab4">
                                          <div class="row">
                                             <div class="col-lg-5 col-md-7 mb-2">
                                                <div id="cahsbonustime" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"><i class="fa fa-calendar"></i>&nbsp;<span></span> <i class="fa fa-caret-down"></i></div>
                                             </div>
                                             <div class="col-md-3  mb-2">
                                                <button class="btn btn-info" onclick="getCashBonusReport()">Go</button>
                                             </div>
                                          </div>
                                          <div class="table-responsive">
                                             <table class="table table-bordered display" id="cash_bonus_report" style="width:100%;">
                                                <thead>
                                                   <tr>
                                                      <th class="wd-15p border-bottom-0">ID</th>
                                                      <th class="wd-15p border-bottom-0">Point Type</th>
                                                      <th class="wd-20p border-bottom-0">Opening Balance (AED)</th>
                                                      <th class="wd-25p border-bottom-0">Total Amount (AED)</th>
                                                      <th class="wd-20p border-bottom-0">Closeing Balance (AED)</th>
                                                      <th class="wd-15p border-bottom-0">Mobile</th>
                                                      <th class="wd-20p border-bottom-0">Email</th>
                                                      <th class="wd-25p border-bottom-0">Transaction Type</th>
                                                      <th class="wd-25p border-bottom-0">Reward Type</th>
                                                      <th class="wd-25p border-bottom-0">IP</th>
                                                      <th class="wd-25p border-bottom-0">Date & Time</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                   <tr>
                                                      <th></th>
                                                      <th></th>
                                                      <th></th>
                                                      <th></th>
                                                      <th></th>
                                                      <th></th>
                                                      <th></th>
                                                      <th></th>
                                                      <th></th>
                                                      <th></th>
                                                      <th></th>
                                                   </tr>
                                                </tfoot>
                                             </table>
                                          </div>
                                       </div>
                                       <!-- Balance sheet for customer  -->
                                       <div class="tab-pane" id="tab5">
                                          <div class="table-responsive">
                                             <div class="col-12">
                                                <h4 style="text-align: center;">Balance Sheet Abstract</h4>
                                                <table class="table table-bordered">
                                                   <thead>
                                                      <tr>
                                                         <th scope="col">Summary</th>
                                                         <th scope="col">Winning</th>
                                                         <th scope="col">Add Credit</th>
                                                         <th scope="col">Cash</th>
                                                         <th scope="col">Bonus</th>
                                                         <th scope="col">Withdraw</th>
                                                         <th scope="col">Wallet Ticket</th>
                                                         <th scope="col">Cash Ticket</th>
                                                         <th scope="col">Bonus Ticket</th>
                                                      </tr>
                                                   </thead>
                                                   <tbody>
                                                      <tr>
                                                         <td><strong>Sub Total</strong></td>
                                                         <td id="winning_amt" style="color: green; font-weight: bolder;"></td>
                                                         <td id="addcredit_amt" style="color: green; font-weight: bolder;"></td>
                                                         <td id="cash_amt" style="color: blue; font-weight: bolder;"></td>
                                                         <td id="bonus_amt" style="color: blue; font-weight: bolder;"></td>
                                                         <td id="withdraw_amt" style="color: red; font-weight: bolder;"></td>
                                                         <td id="wt_amt" style="color: red; font-weight: bolder;"></td>
                                                         <td id="cahst_amt" style="color: red; font-weight: bolder;"></td>
                                                         <td id="bonust_amt" style="color: red; font-weight: bolder;"></td>
                                                      </tr>
                                                      <tr>
                                                         <td><strong>Balance</strong></td>
                                                         <td></td>
                                                         <td></td>
                                                         <td colspan="2" id="bal_cp" style="font-weight: bolder;"></td>
                                                         <td colspan="4" id="spent_balance" style="font-weight: bolder;"></td>
                                                      </tr>
                                                      <tr>
                                                         <td><strong>Total</strong></td>
                                                         <td colspan="2" id="wa_total" style="font-weight: bolder;"></td>
                                                         <td colspan="2" id="cb_total" style="font-weight: bolder;"></td>
                                                         <td colspan="4" id="spent_total" style="font-weight: bolder;"></td>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                             </div>
                                             <div class="col-12">
                                                <div class="show-more" onclick="show_more()"><strong id="shtext">(Show More)</strong></div>
                                             </div>
                                             <div class="col-12" id="spreedcontent">
                                                <h4 style="text-align: center;">Detail View</h4>
                                                <div id="spreadsheet"></div>
                                             </div>
                                          </div>
                                       </div>
                                       <!-- new Balance sheet for customer  -->
                                       <div class="tab-pane" id="tab6">
                                          <div class="table-responsive">
                                             <div class="col-12">
                                                <h4 style="text-align: center;">New Balance Sheet Abstract</h4>
                                                <table class="table table-bordered">
                                                   <thead>
                                                      <tr>
                                                         <th scope="col">Summary</th>
                                                         <th scope="col">Winning Balance</th>
                                                         <th scope="col">Add Credit</th>
                                                         <th scope="col" colspan="3">Total Points</th>
                                                         <th scope="col">Withdrawal</th>
                                                      </tr>
                                                      <tr>
                                                         <th scope="col"></th>
                                                         <th scope="col"></th>
                                                         <th scope="col"></th>
                                                         <th scope="col">Cash</th>
                                                         <th scope="col">Bonus</th>
                                                         <th scope="col">Wallet</th>
                                                         <th scope="col"></th>
                                                      </tr>
                                                   </thead>
                                                   <tbody>
                                                      <tr>
                                                         <td><strong>Cumulative</strong></td>
                                                         <td id="winning_amt_new" style="font-weight: bolder;"></td>
                                                         <td id="addcredit_amt_new" style="font-weight: bolder;"></td>
                                                         <td id="cash_amt_new" style="font-weight: bolder;"></td>
                                                         <td id="bonus_amt_new" style="font-weight: bolder;"></td>
                                                         <td id="withdraw_amt_new" style="font-weight: bolder;"></td>
                                                         <td id="wt_amt_new" style="font-weight: bolder;"></td>
                                                      </tr>
                                                      <tr style="background-color: #f8acac;">
                                                         <td><strong>Used/Withdrawal</strong></td>
                                                         <td id="withDraw_data" style="font-weight: bolder;"></td>
                                                         <td id="addc_data" style="font-weight: bolder;"></td>
                                                         <td id="cash_data_r" style="font-weight: bolder;"></td>
                                                         <td id="bonus_data_r" style="font-weight: bolder;"></td>
                                                         <td id="withdraw_data_r" style="font-weight: bolder;"></td>
                                                         <td id="wallet_data_r" style="font-weight: bolder;"></td>
                                                      </tr>
                                                      <tr style="background-color: #add3ac;">
                                                         <td><strong>Balance</strong></td>
                                                         <td id="withDraw_data_f" style="font-weight: bolder;"></td>
                                                         <td id="addc_data_f" style="font-weight: bolder;"></td>
                                                         <td id="cash_data_f" style="font-weight: bolder;"></td>
                                                         <td id="bonus_data_f" style="font-weight: bolder;"></td>
                                                         <td id="withdraw_data_f" style="font-weight: bolder;"></td>
                                                         <td id="wallet_data_f" style="font-weight: bolder;"></td>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                             </div>
                                             <div class="col-12">
                                                <div class="show-more" onclick="new_show_more()"><strong id="new_shtext">(Show More)</strong></div>
                                             </div>
                                             <div class="col-12" id="new_spreedcontent">
                                                <h4 style="text-align: center;">Detail View</h4>
                                                <div id="new_spreadsheet"></div>
                                             </div>
                                          </div>
                                       </div>
                                       <!-- CB Balance sheet for customer  -->
                                       <div class="tab-pane" id="tab7">
                                          <div class="table-responsive">
                                             <div class="col-12">
                                                <h4 style="text-align: center;">CB Balance Sheet Abstract</h4>
                                                <table class="table table-bordered">
                                                   <thead>
                                                      <tr>
                                                         <th scope="col">Summary</th>
                                                         <!-- <th scope="col">Network</th>
                                                      <th scope="col">CCAvenue</th> -->
                                                         <th scope="col" colspan="2">Ticket</th>
                                                         <th scope="col" colspan="2">Balance</th>
                                                      </tr>
                                                      <tr>
                                                         <th scope="col"></th>
                                                         <!-- <th scope="col"></th>
                                                      <th scope="col"></th> -->
                                                         <th scope="col">BP</th>
                                                         <th scope="col">CP</th>
                                                         <th scope="col">BP</th>
                                                         <th scope="col">CP</th>
                                                      </tr>
                                                   </thead>
                                                   <tbody>
                                                      <!-- <tr>
                                                   <td><strong>Cumulative</strong></td>
                                                   <td id="winning_amt_new" style="font-weight: bolder;"></td>
                                                   <td id="addcredit_amt_new" style="font-weight: bolder;"></td>
                                                   <td id="cash_amt_new" style="font-weight: bolder;"></td>
                                                   <td id="bonus_amt_new" style="font-weight: bolder;"></td>
                                                   <td id="withdraw_amt_new" style="font-weight: bolder;"></td>
                                                   <td id="wt_amt_new" style="font-weight: bolder;"></td>
                                                   
                                                   
                                                   </tr> -->
                                                      <tr style="background-color: #fff;">
                                                         <td><strong>Bonus Total</strong></td>
                                                         <!-- <td id="withDraw_data_r" style="font-weight: bolder;"></td>
                                                      <td id="addc_data_r" style="font-weight: bolder;"></td> -->
                                                         <td id="cash_data_r1" style="color: #f10000;font-weight: bolder;"></td>
                                                         <td id="bonus_data_r1" style="color: #f10000;font-weight: bolder;"></td>
                                                         <td id="withdraw_data_r1" style="color: green;font-weight: bolder;"></td>
                                                         <td id="wallet_data_r1" style="color: green;font-weight: bolder;"></td>
                                                      </tr>
                                                      <tr style="background-color: #fff;">
                                                         <td><strong>Cash Total</strong></td>
                                                         <!-- <td id="withDraw_data_f" style="font-weight: bolder;"></td>
                                                      <td id="addc_data_f" style="font-weight: bolder;"></td> -->
                                                         <td id="cash_data_f1" style="color: #f10000;font-weight: bolder;"></td>
                                                         <td id="bonus_data_f1" style="color: #f10000;font-weight: bolder;"></td>
                                                         <td id="withdraw_data_f1" style="color: green;font-weight: bolder;"></td>
                                                         <td id="wallet_data_f1" style="color: green;font-weight: bolder;"></td>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                             </div>
                                             <div class="col-12">
                                                <div class="show-more" onclick="n_show_more()"><strong id="n_shtext">(Show More)</strong></div>
                                             </div>
                                             <div class="col-12" id="n_spreedcontent">
                                                <h4 style="text-align: center;">Detail View</h4>
                                                <div id="n_spreadsheet"></div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- ROW-4 END -->
         </div>
         <!-- CONTAINER END -->
      </div>
      <script>
         var spreadsheet;
         var new_spreadsheet;
         var n_spreadsheet;

         document.getElementById('user_role').addEventListener('change', function () {
         var type = $('#user_role').val();
         // console.log(type);
         
         const companyDiv = document.getElementById('exampleCompany');
         const compLabel = document.getElementById('com_label');

         if (type == 'owner') {
            
            companyDiv.style.display = 'block';
            compLabel.style.display = 'block';
         } else {
            companyDiv.style.display = 'none';
            compLabel.style.display = 'none';
         }
         });

         $(function() {
            $('#spreedcontent').hide();
            $('#new_spreedcontent').hide();
            $('#n_spreedcontent').hide();


            // createDatePricket('ticketctime');
            createDatePricket('transhctime');
            createDatePricket('withhctime');
            createDatePricket('cahsbonustime');
            // Customer_Ticket();
            transaction();
            new_balance_customer_sheet();
            n_balance_customer_sheet();
         });


         function createDatePricket(id) {

            var start = moment();

            var end = moment();



            function cb(start, end) {

               $('#' + id + ' span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY'));

            }

            $('#' + id).daterangepicker({
               startDate: start.set({
                  hour: 0,
                  minute: 0,
                  second: 0,
                  millisecond: 0
               }),
               endDate: end.set({
                  hour: 23,
                  minute: 59,
                  second: 59,
                  millisecond: 59
               }),

               timePicker: true,
               timePicker24Hour: true,
               timePickerSeconds: true,
               maxSpan: {
                  days: 365
               },
               autoUpdateInput: true,

               // minYear: moment().format("YYYY"),
               // maxYear: moment().add(1, 'years').format("YYYY"),
               maxDate: moment().add(1, 'days').toDate(),

               ranges: {

                  'Today': [moment().set({
                     hour: 0,
                     minute: 0,
                     second: 0,
                     millisecond: 0
                  }), moment().set({
                     hour: 23,
                     minute: 59,
                     second: 59,
                     millisecond: 59
                  })],

                  'Yesterday': [moment().subtract(1, 'days').set({
                     hour: 0,
                     minute: 0,
                     second: 0,
                     millisecond: 0
                  }), moment().subtract(1, 'days').set({
                     hour: 23,
                     minute: 59,
                     second: 59,
                     millisecond: 59
                  })],

                  'Last 7 Days': [moment().subtract(6, 'days').set({
                     hour: 0,
                     minute: 0,
                     second: 0,
                     millisecond: 0
                  }), moment().set({
                     hour: 23,
                     minute: 59,
                     second: 59,
                     millisecond: 59
                  })],

                  'Last 30 Days': [moment().subtract(29, 'days').set({
                     hour: 0,
                     minute: 0,
                     second: 0,
                     millisecond: 0
                  }), moment().set({
                     hour: 23,
                     minute: 59,
                     second: 59,
                     millisecond: 59
                  })],

                  'This Month': [moment().startOf('month').set({
                     hour: 0,
                     minute: 0,
                     second: 0,
                     millisecond: 0
                  }), moment().endOf('month').set({
                     hour: 23,
                     minute: 59,
                     second: 59,
                     millisecond: 59
                  })],

                  'Last Month': [moment().subtract(1, 'month').startOf('month').set({
                     hour: 0,
                     minute: 0,
                     second: 0,
                     millisecond: 0
                  }), moment().subtract(1, 'month').endOf('month').set({
                     hour: 23,
                     minute: 59,
                     second: 59,
                     millisecond: 59
                  })],

                  'This Year': [moment().startOf('year').set({
                     hour: 0,
                     minute: 0,
                     second: 0,
                     millisecond: 0
                  }), moment().endOf('year').set({
                     hour: 23,
                     minute: 59,
                     second: 59,
                     millisecond: 59
                  })],

                  'Last Year': [moment().subtract(1, 'year').startOf('year').set({
                     hour: 0,
                     minute: 0,
                     second: 0,
                     millisecond: 0
                  }), moment().subtract(1, 'year').endOf('year').set({
                     hour: 23,
                     minute: 59,
                     second: 59,
                     millisecond: 59
                  })]

               }

            }, cb);

            cb(start, end);



         }

         const Customer_Ticket = () => {


            try {



               let updateuserid = $('#updateuserid').val();

               // var formdate = moment($('#ticketctime').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
               // var todate = moment($('#ticketctime').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");

               if (updateuserid == '') {
                  toast('error', 'User ID Missing!');
                  return false;
               }


               // if (formdate == '' || todate == '') {
               //    toast('error', 'Kindly select the date');
               //    return false;
               // }


               var table = $("#customer_ticket").DataTable({
                  destroy: true,
                  pageLength: 10,

                  order: [
                     [6, 'desc']
                  ],
                  columnDefs: [{
                     type: 'date',
                     targets: [6]
                  }],
                  paging: true,

                  searching: true,

                  info: true,

                  ajax: {

                     url: window.location.origin + "/ajax/service/customer_ticket_services.php",

                     method: "POST",
                     dataSrc: "",
                     data: {
                        method: 'Customer_Ticket',
                        user_id: updateuserid,
                        // formdate: formdate,
                        // todate: todate
                     }

                  },

                  dom: 'Bfrtip',

                  buttons: [

                     'pageLength',

                     'copy',
                     {
                        extend: 'csvHtml5',
                        title: 'Customer Ticket Reports - ( ' + updateuserid + ' )'
                     },
                     {
                        extend: 'excelHtml5',
                        title: 'Customer Ticket Reports - ( ' + updateuserid + ' )'
                     },
                     {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        title: 'Customer Ticket Reports - ( ' + updateuserid + ' )'
                     }, 'print',

                  ],

                  columns: [{
                        targets: 0, // Target the first column
                        render: function(data, type, row, meta) {
                           return meta.row + 1; // Add 1 to start from 1 instead of 0
                        }
                     },
                     {
                        data: null,
                        render: function(data, type, row, meta) {
                           return data.ticketNo; // Add 1 to start from 1 instead of 0
                        }
                     },
                     {
                        data: null,
                        render: function(data, type, row, meta) {
                           const raffleids = JSON.parse(data.raffleIds);
                           return `<textarea readonly="">${raffleids.join(", ")}</textarea>`;

                        }
                     },
                     {
                        data: null,
                        render: function(data, type, row, meta) {
                           return data.netTotal;
                        }
                     },
                     {
                        data: null,
                        render: function(data, type, row, meta) {
                           return data.shipamount;
                        }
                     },
                     {
                        data: null,
                        render: function(data, type, row, meta) {
                           return data.grandtotal;
                        }
                     },
                     {
                        data: null,
                        render: function(data, type, row, meta) {
                           return moment(data.purchaseDatetime).format("DD MMM YYYY hh:mm A");
                        }
                     },
                     {
                        data: null,
                        render: function(data, type, row, meta) {
                           return moment(data.endDate).format("DD MMM YYYY");
                        }
                     },
                     {
                        data: null,
                        render: function(data, type, row, meta) {
                           var invoiceLink = data.invoiceNo ? `<a target="_blank" href="<?= $baseurl; ?>invoice/${data.referenceID}" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span style="font-size: 18px;color: #40411f !important;font-weight: 500;" class="fa fa-file-text-o">&nbsp;Invoice</span></a>` : '';
                           return `<a target="_blank" href="<?= $baseurl; ?>ticket-view/${data.referenceID}" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span style="font-size: 18px;color: green !important;font-weight: 500;" class="fa fa-files-o">&nbsp;Ticket</span></a>&nbsp;
                     ${invoiceLink}
                             `;
                        }
                     },

                     //   {
                     //       data: "raffleid"
                     //   },
                     //       {
                     //      data: null,
                     //      render: function(data, type, row, meta) {
                     //          // Check if the data is already an array (no need to parse)
                     //          if (Array.isArray(data.raffleid)) {
                     //              return formatMatrix(data.raffleid);
                     //          }
                     //          try {
                     //              // Attempt to parse the JSON data
                     //              var parsedData = JSON.parse(data.raffleid);
                     //              if (Array.isArray(parsedData)) {
                     //                  return formatMatrix(parsedData);
                     //              } else {
                     //                  // Handle unexpected JSON format
                     //                  console.error("Unexpected JSON format:", parsedData);
                     //                  return "Error: Invalid data format";
                     //              }
                     //          } catch (error) {
                     //              // Handle JSON parsing error
                     //              console.error("Error parsing JSON:", error);
                     //              return "Error: Invalid JSON";
                     //          }
                     //      }
                     //  },

                     // {
                     //    data: "proamt"
                     // },
                     // {
                     //    data: "purdate"
                     // },
                     // {
                     //    data: "endDate"
                     // }
                  ],

                  //   footerCallback: function(row, data, start, end, display) {

                  //       var api = this.api();





                  //       var intVal = function(i) {

                  //           return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;

                  //       };





                  //       total = api

                  //           .column(4)

                  //           .data()

                  //           .reduce(function(a, b) {

                  //               return intVal(a) + intVal(b);

                  //           }, 0);





                  //       pageTotal = api

                  //           .column(4, {

                  //               page: 'current'

                  //           })

                  //           .data()

                  //           .reduce(function(a, b) {

                  //               return intVal(a) + intVal(b);

                  //           }, 0);





                  //       $(api.column(4).footer()).html('' + pageTotal + ' ( ' + total + ' total)');

                  //   },

               });

            } catch (e) {
               console.log('Error: ' + e);
            }



         }
        
        function transaction() {
                var table = $('#transaction').DataTable();
                table.destroy();
                let updateuserid = $('#updateuserid').val();
                var formdate = moment($('#transhctime').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
                var todate = moment($('#transhctime').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");
                if (updateuserid == '') {
                    toast('error', 'User ID Missing!');
                    return false;
                }
                if (formdate == '' || todate == '') {
                    toast('error', 'Kindly select the date');
                    return false;
                }
                table = $("#transaction").DataTable({
                    pageLength: 10,
                    order: [
                        [
                            0, 'desc'
                        ]
                    ],
                    columnDefs: [{
                        type: 'date',
                        targets: [1]
                    }],
                    paging: true,
                    searching: true,
                    info: true,
                    ajax: {
                        url: window.location.origin + "/ajax/service/customer_ticket_services.php",
                        method: "POST",
                        dataSrc: "",
                        data: {
                            method: 'transaction_table',
                            user_id: updateuserid,
                            formdate: formdate,
                            todate: todate
                        }
                    },
                    dom: 'Bfrtip',
                    buttons: [
                        'pageLength',
                        'copy',
                       
                        {
                            extend: 'excelHtml5',
                            title: 'Customer Transaction - ( ' + updateuserid + ' )'
                        },
                       
                    ],
                    columns: [
                        {
                            data: "date"
                        },
                        {
                            data: "purchaseType",
                            render: function (data, type, row) {
                       
                                 if (row.purchaseType) {
                                      return row.purchaseType; 
                                 } else {
                                      return 'NA';
                                 }
                              }
                            
                        },
                        {
                            data: "planType",
                            render: function (data, type, row) {
                       
                                 if (row.planType) {
                                      return row.planType; 
                                 } else {
                                      return 'NA';
                                 }
                              }
                            
                        },
                        {
                            data: "gateway",
                            render: function (data, type, row) {

                                 if (row.gateway) {
                                       return row.gateway; 
                                 } else {
                                       return 'NA';
                                 }
                              }
                        },
                        {
                            data: "paymentStatus",
                            render: function (data, type, row) {
                       
                                 if (row.paymentStatus == "Paid" || row.paymentStatus == "SUCCESS" ) {
                                       return 'SUCCESS'; 
                                 } else if(row.paymentStatus == "Pending" || row.paymentStatus == "Failed"  ){
                                    return 'FAILED';

                                 } else {
                                       return 'NA';
                                 }
                              }
                        },
                        {
                            data: "ticketReferenceID",
                            render: function (data, type, row) {
                       
                                 if (row.ticketReferenceID !== ""  ) {
                                       return row.ticketReferenceID; 
                                 }else {
                                       return 'NA';
                                 }
                              }
                        },
                        {
                            data: "Amount"
                        }
                    ],
                    footerCallback: function(row, data, start, end, display) {
                        var api = this.api();
                        var intVal = function(i) {
                            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ?
                                i : 0;
                        };
                        total = api
                            .column(5)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        pageTotal = api
                            .column(5, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        $(api.column(5).footer()).html('' + pageTotal + ' ( ' + total + ' total)');
                    },
                });
            }

        
        
         function formatMatrix(raffleIds) {
            var result = '';
            for (var i = 0; i < raffleIds.length; i += 4) {
               for (var j = i; j < i + 4 && j < raffleIds.length; j++) {
                  result += raffleIds[j];
                  if (j < raffleIds.length - 1) {
                     result += ', ';
                  } else {
                     // result += ' end';
                  }
               }
               result += '<br>';
            }
            return result;
         }

      
      </script>
   <?php
   }
   ?>
   <script>
      var state = "<?= strtolower($row['address']); ?>";
      var city = "<?= strtolower($row['city']); ?>";
      $(function() {
         $('#userup').hide();
         if($('#nationlaity').val() != ''){
            getState($('#nationlaity').val());
         }
         bank_check(<?= $bank_check; ?>);
      });




      function updateDetails() {
         var formdata = $('#editprofileform').serializeArray();
         // var district = $('#billing_city').val();
         // console.log(district);
         formdata.push({
            name: 'method',
            value: "updateDetails",
            // district_val: district
         });
         var post_data = formdata;
         var onsuccess = function(data) {
            var response = JSON.parse(data);
            if (response != "") {
               if (response.type == 1) {
                  toast('success', response.result);
               } else {
                  toast('error', response.result);
               }
            }
         }
         do_ajax_call(post_data, onsuccess);
      }

      function updateverification() {
         let mobile = $('#exampleInputnumber').val();
         let updateuserid = $('#updateuserid').val();
         let typeOTP = $('#typeOTP').val();
         let formdata = [];

         if (typeOTP == 'send') {
            formdata.push({
               name: 'mobile',
               value: mobile
            });
            formdata.push({
               name: 'updateuserid',
               value: updateuserid
            });
         } else if (typeOTP == 'check') {
            formdata = $('.otp_form').serializeArray();
            formdata.push({
               name: 'updateuserid',
               value: updateuserid
            });
         }
         formdata.push({
            name: 'method',
            value: "updateverification"
         });
         formdata.push({
            name: 'type',
            value: typeOTP
         });
         var post_data = formdata;
         var onsuccess = function(data) {
            var response = JSON.parse(data);
            if (response != "") {
               if (response.type == 1) {
                  document.getElementById('typeOTP').value = 'check';
                  document.getElementById('otperror2').innerHTML = '<div class="alert alert-success" role="alert">' + response.result + '</div>';
                  $('#otp').modal('show');
               } else if (response.type == 0 || response.type == 2) {
                  document.getElementById('otperror2').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';
                  $('#otp').modal('show');
               } else {
                  document.getElementById('typeOTP').value = 'send';
                  $('#newupotp').hide();
                  $('#userup').show();
                  document.getElementById('otperror2').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';
                  $('#otp').modal('hide');
               }
            }
         }
         do_ajax_call(post_data, onsuccess);

      }

      function jump(field, autoMove) {
         if (field.value.length >= field.maxLength) {
            document.getElementById(autoMove).focus();
         }
      }

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

      function toast(icon, message) {
         const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
               toast.addEventListener('mouseenter', Swal.stopTimer);
               toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
         });

         Toast.fire({
            icon: icon,
            title: message
         });
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
                     // toast('error', response.result);
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

                        $('#billing_city').append(`<option value="${optionText}" ${svalue}>${optionText}</option>`);

                     // $('#billing_city').val(res.result.name);
                     }

                  } else {
                     // toast('error', response.result);
                  }
                  $('#billing_city').selectpicker('refresh');
               }
            }
            do_ajax_call(post_data, onsuccess);

         } else {
            // toast('error', 'Kindly Select State')
         }
      }


      $("#bank_name1").change(function() {
         var bank_name = $(this).val();
         $('#bank_name').prop('type', 'hidden');
         if (bank_name == '') {} else if (bank_name == 'Others') {
            $('#bank_name').prop('type', 'text');
         } else {
            document.getElementById('bank_name').value = bank_name;
         }
      });

      function bank_check(bank_check) {
         if (bank_check == '0') {
            $('#bank_name1').val('Others');
            $('#bank_name').prop('type', 'text');
         }
      }

      function age_validate() {
         $("#expirydateerror").html("");

         let chooshedYear = parseInt(moment($("#passport_expiry").val(), "YYYY").format('Y'));
         if (chooshedYear < 1900) {
            toast('error', 'Invalid Year!');
            return false;
         } else if (chooshedYear > 2200) {
            toast('error', 'Invalid Year!');
            return false;
         } else {
            if (calAge('passport_expiry') < 18) {
               toast('error', 'You age under 18 not qualified to participate to try your luck.');
               return false;
            }
            return true;
         }
      }



      function calAge(id) {
         let date1 = new Date(document.getElementById(id).value);
         let date2 = new Date();
         let yearsDiff = date2.getFullYear() - date1.getFullYear();
         return parseInt(yearsDiff);
      }
   </script>
<?php
} else {



   if ($subid4 != '') {
      $userid = $subid4;
   }



   $sql = "SELECT * FROM `user_register` WHERE `id` = '$userid'";
   $run = mysqli_query($con, $sql);
   if (mysqli_num_rows($run) > 0) {
      $row = $run->fetch_assoc();
   }

?>
   <style>
      .blue-button {
         color: #FFF;
         background-color: #428BCA;
         border: 1px solid #357EBD;
      }
   </style>
   <div class="main-content app-content mt-0">
      <div class="side-app">
         <!-- CONTAINER -->
         <div class="main-container container-fluid">
            <!-- PAGE-HEADER -->
            <div class="page-header">
               <h1 class="page-title">Menu Permissions</h1>
               <div>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="javascript:void(0)">Pages</a></li>
                     <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
                  </ol>
               </div>
            </div>
            <!-- PAGE-HEADER END -->
            <!-- ROW-1 OPEN -->
            <div class="row">
               <div class="col-xl-4">
                  <div class="card">
                     <div class="card-header">
                        <div class="card-title">My Profile</div>
                     </div>
                     <div class="card-body">
                        <div class="text-center chat-image mb-5">
                           <div class="avatar avatar-xxl chat-profile mb-3 brround">
                              <a class="" href="agentview"><img alt="avatar" src="assets/images/users/2.jpg" class="brround"></a>
                           </div>
                           <div class="main-chat-msg-name">
                              <!-- <a href="agentview"> -->
                              <h5 class="mb-1 text-dark fw-semibold">National Draw</h5>
                              </a>
                              <p class="text-muted mt-0 mb-0 pt-0 fs-13">ID - <?= $row['id']; ?></p>
                           </div>
                        </div>
                        <ul class="list-group no-margin">
                           <li class="list-group-item d-flex ps-3">
                              <div class="social social-profile-buttons me-2">
                                 <a class="social-icon text-primary" href=""><i class="fe fe-mail"></i></a>
                              </div>
                              <a href="javascript:void(0)" class="my-auto"><?= $row['email']; ?></a>
                           </li>
                           <li class="list-group-item d-flex ps-3">
                              <div class="social social-profile-buttons me-2">
                                 <a class="social-icon text-primary" href=""><i class="fa fa-address-card-o"></i></a>
                              </div>
                              <a href="javascript:void(0)" class="my-auto"><?= $row['passport']; ?></a>
                           </li>
                           <li class="list-group-item d-flex ps-3">
                              <div class="social social-profile-buttons me-2">
                                 <a class="social-icon text-primary" href=""><i class="fe fe-phone"></i></a>
                              </div>
                              <a href="javascript:void(0)" class="my-auto"><?= $row['mobile']; ?></a>
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
               <div class="col-xl-8">
                  <?php
                  if ($subid3 == 'permission') {
                  ?>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="card">
                              <div class="card-header">
                                 <div class="card-title">Menu Permission</div>
                              </div>
                              <div class="card-body">
                                 <form action="" method="post">
                                    <?php
                                    $menupermission = mysqli_query($con, "SELECT * FROM  `orm_menu`  where `main`='0' AND `deletes`='0' ORDER BY `id` ASC");
                                    while ($menupermission1 = mysqli_fetch_array($menupermission)) {
                                       $userper = mysqli_query($con, "select * FROM  `menu_permission` where `userid`='$subid4' and `menu`='$menupermission1[id]' ");
                                       $row = mysqli_fetch_array($userper);
                                       $numrow = mysqli_num_rows($userper);
                                       $row['id'];
                                    ?>
                                       <div class="form-group">
                                          <label class="custom-switch form-switch mb-0">
                                             <input type="checkbox" name="other[<?php echo $menupermission1['id']; ?>]" id="other<?php echo $menupermission1['id']; ?>" value="<?php echo  $menupermission1['name']; ?>" <?php if ($row) { ?> checked="checked" <?php } ?> class="custom-switch-input">
                                             <input type="hidden" name="otherid[<?php echo $menupermission1['id']; ?>]" id="otherid<?php echo $menupermission1['id']; ?>" style="width:25px;" value="<?php echo  $menupermission1['id']; ?>">
                                             <span class="custom-switch-indicator"></span>
                                             <span class="custom-switch-description"><?php echo $menupermission1['name']; ?></span>
                                          </label>
                                          <div class="row">
                                             <?php
                                             $submenupermission = mysqli_query($con, "SELECT * FROM  `orm_menu`  where `main`='$menupermission1[id]' ORDER BY `order` ASC;");
                                             while ($submenupermission1 = mysqli_fetch_array($submenupermission)) {
                                                $subper = mysqli_query($con, "select * FROM  `menu_permission` where `userid`='$subid4' and `menu`='$submenupermission1[id]'");
                                                $row1 = mysqli_fetch_array($subper);
                                             ?>
                                                <div class="col-md-1"></div>
                                                <div class="col-md-8">
                                                   <label class="custom-switch form-switch mb-0">
                                                      <input type="checkbox" name="other[<?php echo $submenupermission1['id']; ?>]" id="other<?php echo $submenupermission1['id']; ?>" value="<?php echo  $submenupermission1['id']; ?>" <?php if ($row1) { ?> checked <?php } ?> class="custom-switch-input">
                                                      <input type="hidden" name="otherid[<?php echo $submenupermission1['id']; ?>]" id="otherid<?php echo $submenupermission1['id']; ?>" style="width:25px;" value="<?php echo  $submenupermission1['id']; ?>">
                                                      <span class="custom-switch-indicator"></span>
                                                      <span class="custom-switch-description"><?php echo $submenupermission1['name']; ?></span>
                                                   </label>
                                                </div>
                                                <div class="row">
                                                   <?php
                                                   $seconmenupermission = mysqli_query($con, "SELECT * FROM  `orm_menu`  where `main`='$submenupermission1[id]' ORDER BY `order` ASC;");

                                                   while ($seconmenupermission1 = mysqli_fetch_array($seconmenupermission)) {
                                                      $secondper = mysqli_query($con, "select * FROM  `menu_permission` where `userid`='$subid4' and `menu`='$seconmenupermission1[id]'");

                                                      $secondper1 = mysqli_fetch_array($secondper);
                                                   ?>
                                                      <div class="col-md-2"></div>
                                                      <div class="col-md-8">
                                                         <label class="custom-switch form-switch mb-0">
                                                            <input type="checkbox" name="other[<?php echo $seconmenupermission1['id']; ?>]" id="other<?php echo $seconmenupermission1['id']; ?>" value="<?php echo  $seconmenupermission1['id']; ?>" <?php if ($secondper1) { ?> checked <?php } ?> class="custom-switch-input">
                                                            <input type="hidden" name="otherid[<?php echo $seconmenupermission1['id']; ?>]" id="otherid<?php echo $submenupermission1['id']; ?>" style="width:25px;" value="<?php echo  $seconmenupermission1['id']; ?>">
                                                            <span class="custom-switch-indicator"></span>
                                                            <span class="custom-switch-description"><?php echo $seconmenupermission1['name']; ?></span>
                                                         </label>
                                                      </div>
                                                      <div class="col-md-2"></div>
                                                   <?php } ?>
                                                </div>
                                             <?php } ?>
                                          </div>
                                       </div>
                                    <?php } ?>
                                    <div class="form-group">
                                       <input type="submit" name="menuper" id="menuper" value="Submit" style="margin-left: 0px;" class="btn blue-button">
                                    </div>
                                 </form>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- <div class="card">
      <div class="row">
          <div class="col-lg-6">
              <div class="card-box">
                  <div class="table-responsive">
                      <form action="" method="post">
      
      
      
      
      
      
      
                          <table width="326" id="inbox_box" class="table table-actions-bar">
                              <tr>
      
                                  <td colspan="3">
                                      <input type="hidden" name="teid" value="<?= $subid3 ?>">
                                      <h4> Menu Permissions</h4>
                                  </td>
                              </tr>
                              <td width="26">
                                  <ul> <?php
                                       $menupermission = mysqli_query($con, "SELECT * FROM  `orm_menu`  where `main`='0' ");

                                       while ($menupermission1 = mysqli_fetch_array($menupermission)) {







                                       ?>
      
                                          <ul style="list-style:none;">
                                              <tr>
      
                                                  <td height="27" class="black" colspan="2">
      
                                                      <?php
                                                      $userper = mysqli_query($con, "select * FROM  `menu_permission` where `userid`='$subid4' and `menu`='$menupermission1[id]'");

                                                      $row = mysqli_fetch_array($userper);

                                                      $numrow = mysqli_num_rows($userper);

                                                      $row['id'];

                                                      ?>
      
                                                      <li style="list-style:none;">
      
                                                          <input type="checkbox" name="other[<?php echo $menupermission1['id']; ?>]" id="other<?php echo $menupermission1['id']; ?>" value="<?php echo  $menupermission1['name']; ?>" <?php if ($row) { ?> checked="checked" <?php } ?>>
      
                                                          <input type="hidden" name="otherid[<?php echo $menupermission1['id']; ?>]" id="otherid<?php echo $menupermission1['id']; ?>" style="width:25px;" value="<?php echo  $menupermission1['id']; ?>">
      
                                                          <?php echo $menupermission1['name']; ?>
      
      
      
      
      
      
      
                                                          <ul style="list-style:none;text-indent: 22px;margin-top: 5px;">
      
                                                              <?php
                                                               $submenupermission = mysqli_query($con, "SELECT * FROM  `orm_menu`  where `main`='$menupermission1[id]' ");

                                                               while ($submenupermission1 = mysqli_fetch_array($submenupermission)) { ?>
      
                                                                  <li>
      
                                                                      <?php
                                                                        $subper = mysqli_query($con, "select * FROM  `menu_permission` where `userid`='$subid4' and `menu`='$submenupermission1[id]'");

                                                                        $row1 = mysqli_fetch_array($subper); ?>
      
                                                                      <input type="checkbox" name="other[<?php echo $submenupermission1['id']; ?>]" id="other<?php echo $submenupermission1['id']; ?>" value="<?php echo  $submenupermission1['id']; ?>" <?php if ($row1) { ?> checked <?php } ?>>
      
      
      
                                                                      <input type="hidden" name="otherid[<?php echo $submenupermission1['id']; ?>]" id="otherid<?php echo $submenupermission1['id']; ?>" style="width:25px;" value="<?php echo  $submenupermission1['id']; ?>">
      
                                                                      <?php echo $submenupermission1['name']; ?>
      
      
      
      
                                                                      <ul style="list-style:none;text-indent: 22px;margin-top: 5px;">
      
                                                                          <?php
                                                                           $seconmenupermission = mysqli_query($con, "SELECT * FROM  `orm_menu`  where `main`='$submenupermission1[id]' ");

                                                                           while ($seconmenupermission1 = mysqli_fetch_array($seconmenupermission)) { ?>
      
                                                                              <li>
      
                                                                                  <?php
                                                                                    $secondper = mysqli_query($con, "select * FROM  `menu_permission` where `userid`='$subid4' and `menu`='$seconmenupermission1[id]'");

                                                                                    $secondper1 = mysqli_fetch_array($secondper); ?>
      
                                                                                  <input type="checkbox" name="other[<?php echo $seconmenupermission1['id']; ?>]" id="other<?php echo $seconmenupermission1['id']; ?>" value="<?php echo  $seconmenupermission1['id']; ?>" <?php if ($secondper1) { ?> checked <?php } ?>>
      
      
      
                                                                                  <input type="hidden" name="otherid[<?php echo $seconmenupermission1['id']; ?>]" id="otherid<?php echo $submenupermission1['id']; ?>" style="width:25px;" value="<?php echo  $seconmenupermission1['id']; ?>">
      
                                                                                  <?php echo $seconmenupermission1['name']; ?>
                                                                              </li>
      
                                                                          <?php } ?>
      
                                                                      </ul>
      
                                                                  </li>
      
                                                              <?php } ?>
      
                                                          </ul>
      
                                                      </li>
      
                                                  </td>
      
                                              </tr>
      
                                          <?php } ?>
                                          </ul>
      
                                          <tr>
      
                                              <td></td>
      
                                              <td width="288"><input type="submit" name="menuper" id="menuper" value="Submit" style="margin-left: 0px;" class="blue_button"></td>
      
                                          </tr>
      
                          </table>
      
      
      
                      </form>
      
                  </div>
      
              </div>
      
      
      
      
      
      
      
      
      
      
      
          </div>
      
      
      
      
      </div>
      
      </div> -->
                  <?php
                  } ?>
               </div>
            <?php } ?>


<?php

if($row['state'] !== "") { ?>

<script>
   $(document).ready(function(){
   getCity(<?= $row['state']; ?>);
   })
</script>

<?php

}

?>
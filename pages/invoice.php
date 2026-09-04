<?php include('assets/include/header.php'); ?>

<body class="app sidebar-mini ltr light-mode">
	
    <div id="global-loader">
        <img src="assets/images/loader.svg" class="loader-img" alt="Loader">
	</div>
    <div class="page">
        <div class="page-main">
			<?php include('pageheader.php'); ?>		
    	<?php include('pagesidemenu.php'); ?>	
			

            <div class="main-content app-content mt-0">
                <div class="side-app ">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Invoice</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Pages</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Invoice</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- ROW-1 OPEN -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <a class="header-brand" href="index.html">
                                                    <img src="assets/images/brand/logo-3.png" class="header-brand-img logo-3" alt="National Draw Logo">

                                                </a>
                                                <div>
                                                   
                                                </div>
                                            </div>
                                            <div class="col-lg-6 text-end border-bottom border-lg-0">
                                                <h3>#INV-526</h3>
                                                <h5>Date Issued: 14-05-2022</h5>
                      
                                            </div>
                                        </div>
										
										
										<div class="row">
										<div class="col-md-12">
										<h2 class="head-title">TAX INVOICE</h2>
										</div>	</div>
										
										
                                        <div class="row pt-5">
                                            <div class="col-lg-6">
                                                <p class="h3">Invoice To:</p>
                                                <p class="fs-18 fw-semibold mb-0">Ashruf</p>
                                                <address>
                                                        Street Address, State, City<br>
                                                        State, City<br>
                                                        Region, Postal Code<br>
                                                        ashruf@gmail.com
                                                    </address>
                                            </div>
                                            <div class="col-lg-6 text-end">
											    <address>
                                                <p class="h4 fw-semibold">Payment Details:</p>
                                                <p class="h4 fw-semibold">Total : AED 789.00</p>
                                                <!--<p class="mb-1">Bank Name: Union Bank 0456</p>
                                                <p class="mb-1">IBAN: 543218769</p>
                                                <p>Country: UAE</p>  -->         
												Payment Type:<span class="bt-text"> Online</span><br>
												Payment Status: <span class="bt-text"> Paid</span></address>
                                            </div>
                                        </div>
                                        <div class="table-responsive push">
                                            <table class="table table-bordered table-hover mb-0 text-nowrap">
                                                <tbody>
                                                    <tr class=" ">
                                                        <th class="text-center">Sr.No</th>
                                                        <th>Description</th>
                                                        <th class="text-center"><p>My<span class="number-range">3</span>Number</p></th>
                                                        <th class="text-center">Lines</th>
                                                        <th class="text-end">Price Per Unit (Including
VAT)</th><th>Discount
(AED)</th> 
														<th>Amount Before VAT
(AED)</th>
                                                        <th class="text-end">Total Amount</th> 
                                                     </tr>
                                                    <tr>
                                                        <td class="text-center">1</td>
                                                        <td>
                                                            <p class="font-w600 mb-1">National Draw Mai Blue bottled water </p>
                                                            <div class="text-muted">
                                                                <div class="text-muted">250ml  </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">123<br>234</td>
                                                        <td class="text-center">2</td>
                                                        <td class="text-end">AED 10</td>
                                                        <td class="text-end">AED 20</td>
														  <td class="text-end"><strong>AED 500</strong></td>
														   <td class="text-end"><strong>AED 500</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">2</td>
                                                        <td>
                                                            <p class="font-w600 mb-1">National Draw Mai Blue bottled water</p>
                                                            <div class="text-muted">500ml </div>
                                                        </td>
                                                        <td class="text-center">123<br>234<br>563<br>533</td>
                                                        <td class="text-center">4</td>
                                                        <td class="text-end">AED 20</td>
                                                        <td class="text-end">AED 80</td>
														  <td class="text-end"><strong>AED 500</strong></td>
														   <td class="text-end"><strong>AED 500</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">3</td>
                                                        <td>
                                                           <p class="font-w600 mb-1">National Draw Mai Blue bottled water</p>
                                                            <div class="text-muted">1250ml </div>
															</td>
                                                        <td class="text-center">123<br>234<br>563</td>
                                                        <td class="text-center">3</td>
                                                        <td class="text-end">AED 50</td>
                                                        <td class="text-end">AED 150</td>
														  <td class="text-end"><strong>AED 500</strong></td>
														   <td class="text-end"><strong>AED 500</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">4</td>
                                                        <td>
                                                           <p class="font-w600 mb-1">National Draw Mai Blue bottled water</p>
                                                            <div class="text-muted">2500ml </div>
															</td>
                                                        <td class="text-center">123<br>234</td>
                                                        <td class="text-center">2</td>
                                                        <td class="text-end">AED 100</td>
                                                        <td class="text-end">AED 200</td>
														  <td class="text-end"><strong>AED 500</strong></td>
														   <td class="text-end"><strong>AED 500</strong></td>
                                                    </tr>
                                                   
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer text-end">
                                      
                                     <ul class="footer-invoice">
									 <h4>Terms & Conditions:</h4>
									   <li>National Draw has 4 types of drinking water to purchase 250 ml for AED 10, 500 ml for AED 20, 1250 ml for AED 50, 2500 ml for AED 100
</li> 

<li>The purchase of any type of water will allow the purchaser the optional entry to a draw</li> 


<li>The Draw will take place on the 14th and 28th of every month the participant will select 3 digits number between “0” and “9” number can be repeated.
</li> 
<li>In case the exact numbers are obtained from the draw number, the winner will win his original purchase amount multiplied by 250 times as winnings.
</li> 
<li>In case the reverse numbers are obtained from the draw number, the winner win his original purchase amount multiplied by 25 times.
</li> 
<li>In case the chosen numbers are matching the draw number but mixed, the winner win his original purchase amount multiplied by 2.5 times.
</li> 

									   </ul>
                                        <button type="button" class="btn btn-secondary  mb-1" onClick="javascript:window.print();"><i class="si si-printer"></i> Print Invoice</button>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                        

                    </div>
                  
                </div>
            </div>
            
        </div>

        	
<?php include('assets/include/footer.php'); ?>	
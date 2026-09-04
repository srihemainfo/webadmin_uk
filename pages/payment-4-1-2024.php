<style>
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



	input,

	select {

		border: 1px solid #CCC;

	}



	button {

		color: #FFF;

		background-color: #428BCA;

		border: 1px solid #357EBD;

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
</style>

<script>
	window.onload = function() {

		var page_origin = window.location.origin;

		let anchor = document.getElementById("anchor");

		anchor.href = page_origin;

	}
</script>

<div class="main-content app-content mt-0">

	<div class="side-app">





		<div class="main-container container-fluid">





			<div class="page-header">

				<h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Payments</h1>

				<div>

					<ol class="breadcrumb">

						<li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

						<li class="breadcrumb-item active" aria-current="page">Payments</li>

					</ol>

				</div>

			</div>



			<div class="row row-sm">

				<div class="col-lg-12">

					<div class="card">

						<div class="card-header">
							<div class="col-lg-4">
								<h3 class="card-title"><strong>Transaction History</strong></h3><br>
							</div>
						    <div class="row">
    
    							<div class="col-lg-4">
    								<input class="form-control" type="text" id="datefilter" name="datefilter" value="" placeholder="YYYY-MM-DD" autocomplete="off" maxlength="10" oninput="this.value = this.value.replace(/[^0-9-]/g, '');" />
    							</div>
    							<div class="col-lg-4">
    								<button onclick="viewtable()">GO</button>
    							</div>
						        
						    </div>

						</div>



						<div class="card-body">

							<div class="table-responsive">

								<table class="table table-bordered text-nowrap border-bottom" id="paymentHistory" style="width:100%;">

									<thead>

										<tr>

											<th class="wd-15p border-bottom-0">Date & Time</th>
											<th class="wd-15p border-bottom-0">Transaction ID</th>
											<th class="wd-15p border-bottom-0">Customer Name</th>
											<th class="wd-15p border-bottom-0">Mobile Number</th>
											<th class="wd-20p border-bottom-0">Email ID</th>
											<th class="wd-25p border-bottom-0">Purchase Type</th>
											<th class="wd-20p border-bottom-0">Plan Type</th>
											<th class="wd-20p border-bottom-0">Payment Gatway</th>
											<th class="wd-25p border-bottom-0">Payment Status</th>
											<th class="wd-25p border-bottom-0">Amount (AED)</th>

										</tr>

									</thead>

									<tbody>

									</tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="wd-15p border-bottom-0"></th>
											<th class="wd-15p border-bottom-0"></th>
											<th class="wd-15p border-bottom-0"></th>
											<th class="wd-15p border-bottom-0"></th>
											<th class="wd-20p border-bottom-0"></th>
											<th class="wd-25p border-bottom-0"></th>
											<th class="wd-20p border-bottom-0"></th>
											<th class="wd-20p border-bottom-0"></th>
											<th class="wd-25p border-bottom-0">Total</th>
											<th class="wd-25p border-bottom-0">0</th>
                                        </tr>
                                    </tfoot>
								</table>

							</div>

						</div>

					</div>

				</div>

			</div>







		</div>



	</div>

</div>





<div class="modal  fade" id="pointreq" tabindex="-1" role="dialog">

	<div class="modal-dialog modal-sm" role="document">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title">Point Request</h5>

				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">×</span>

				</button>

			</div>

			<div class="modal-body">



				<div class="row">



					<div class="col-sm-12">

						<form class="login100-form validate-form">



							<div class="wrap-input100 validate-input input-group">

								<a href="javascript:void(0)" class="input-group-text bg-white text-muted">

									<i class="side-menu__icon fa fa-money"></i>

								</a>

								<input class="input100 border-start-0 ms-0 form-control" type="text" placeholder="Enter Points">

							</div>



							<div class="row">

								<div class="col-6">



									<label class="custom-control custom-radio">

										<input type="radio" class="custom-control-input" name="example-radios" value="option1" checked="">

										<span class="custom-control-label">Prepaid</span>

									</label>





								</div>

								<div class="col-6">

									<label class="custom-control custom-radio">

										<input type="radio" class="custom-control-input" name="example-radios" value="option2">

										<span class="custom-control-label">Credit</span>

									</label>





								</div>

							</div>

							<div class="row">

								<div class="col-md-8">

									<div class="form-group">

										<div class="form-label">Transaction Method</div>

										<label class="custom-switch form-switch me-5">

											<input type="radio" name="custom-switch-radio" class="custom-switch-input">

											<span class="custom-switch-indicator"></span>

											<span class="custom-switch-description">Offline</span>

										</label>

									</div>

									<div class="form-group">

										<label class="custom-switch form-switch">

											<input type="radio" name="custom-switch-radio" class="custom-switch-input" checked="">

											<span class="custom-switch-indicator"></span>

											<span class="custom-switch-description">Online</span>

										</label>

									</div>



								</div>



							</div>

					</div>

					<div class="row">

						<div class="col-12">

							<div class="form-label">Leader Name : <strong>Alex</strong></div>

							<div class="form-label">ID : <strong>123456</strong></div>

						</div>

					</div>

					</form>

				</div>

			</div>



		</div>

		<div class="modal-footer">



			<button class="btn btn-secondary">Request</button>

		</div>

	</div>

</div>

</div>

<div class="modal  fade" id="withdraw" tabindex="-1" role="dialog">

	<div class="modal-dialog modal-sm" role="document">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title">Withdrawal Request</h5>

				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">×</span>

				</button>

			</div>

			<div class="modal-body">



				<div class="row">



					<div class="col-sm-12">

						<form class="login100-form validate-form">



							<div class="wrap-input100 validate-input input-group">

								<a href="javascript:void(0)" class="input-group-text bg-white text-muted">

									<i class="side-menu__icon fa fa-money"></i>

								</a>

								<input class="input100 border-start-0 ms-0 form-control" type="text" placeholder="Enter Amount">

							</div>

							<div class="form-group">

								<label class="form-label">Transaction Method</label>

								<select name="country" class="form-control form-select select2" data-bs-placeholder="Select Country">

									<option value="Cash">Cash</option>

									<option value="Bank transfer">Bank transfer</option>

									<option value="Exchange Transfer">Exchange Transfer</option>



								</select>

							</div>

						</form>

					</div>

				</div>



			</div>

			<div class="modal-footer">



				<button class="btn btn-secondary">Request</button>

			</div>

		</div>

	</div>

</div>

<script>
	var origin = window.location.origin;

	var url = origin + "/ajax/service/transaction_services.php";



	$(function() {
        createDatePricket('datefilter');
		collect_total_earnings();
		viewtable();

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
            // minDate: moment('2023-02-01').toDate(),
            // minYear: moment().format("YYYY"),
            // maxYear: moment().add(1, 'years').format("YYYY"),
            // maxDate: moment().add(1, 'days').toDate(),

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




	function collect_total_earnings() {

		var formdata = [];

		formdata.push({

			name: 'method',

			value: "collect_trans_earnings"

		});

		var post_data = formdata;

		var onsuccess = function(data) {



			var response = JSON.parse(data);

			if (response != "") {

				if (response.type == 1) {

					document.getElementById('total_amt').innerText = 'AED ' + response.total_amt;

					document.getElementById('total_my_point').innerText = response.mypoint;

					document.getElementById('myearnings').innerText = response.myearnings;

				} else {

					document.getElementById('total_amt').innerText = response.result;

				}



			}

		}

		do_ajax_call(post_data, onsuccess, url);

	}


    function viewtable() {
    $('#paymentHistory').DataTable();
    var newdate = $('#datefilter').val();
        // alert(deva);
    if (newdate != '') {
        var formdate = moment($('#datefilter').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
        var todate = moment($('#datefilter').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");
    } else {
        var formdate = moment().startOf('day').format("YYYY-MM-DD HH:mm:ss"); // Start of the current day
        var todate = moment().endOf('day').format("YYYY-MM-DD HH:mm:ss");     // End of the current day
    }

    $.ajax({
        url: url, // Replace with your backend endpoint
        type: 'POST',
        data: {
            method: 'onllin_earnings',
            formdate: formdate,
            todate: todate
        },
        success: function(data) {
            try {
                // Parse the JSON response
                var response = JSON.parse(data);

                if (response.type === 1 && response.result) {
                    // Destroy the table if it exists
                    if ($.fn.DataTable.isDataTable('#paymentHistory')) {
                        $('#paymentHistory').DataTable().clear().destroy();
                    }

                    // Initialize the DataTable with new data
                    $('#paymentHistory').DataTable({
                        order: [[0, 'desc']],
                        dom: 'Bfrtip',
                        buttons: [
                            'pageLength',
                            {
                                extend: 'copyHtml5',
                                title: `Payments Report : (${formdate} to ${todate})`
                            },
                            {
                                extend: 'csvHtml5',
                                title: `Payments Report : (${formdate} to ${todate})`
                            },
                            {
                                extend: 'excelHtml5',
                                orientation: 'landscape',
                                pageSize: 'LEGAL',
                                title: `Payments Report : (${formdate} to ${todate})`
                            },
                            {
                                extend: 'pdfHtml5',
                                title: `Payments Report : (${formdate} to ${todate})`
                            },
                            'print'
                        ],
                        data: response.result,
                        columns: [
                            { data: 'datetime' },
                            { data: 'ticketReferenceID' },
                            {
                                data: 'name',
                                render: function(data, type, row) {
                                    return row.name && row.lname ? `${row.name} ${row.lname}` : row.name;
                                }
                            },
                            { data: 'mobile' },
                            { data: 'email' },
                            { 
                                data: 'purchaseType',
                                render: function(data, type, row) {
                                        return row.purchaseType != null && row.purchaseType != '' ? row.purchaseType: 'NA';
                                    }
                                
                            },
                            { 
                                data: 'planType',
                        
                                render: function(data, type, row) {
                                        return row.planType != null && row.planType != '' ? row.planType : 'NA';
                                    }
                            },
                            { 
                                data: 'gateway',
                            
                                render: function(data, type, row) {
                                        return row.gateway != null && row.gateway != '' ? row.gateway: 'NA';
                                    }
                            },
                            { data: 'paymentStatus',
                                render: function(data, type, row) {
                                            return row.gateway != null && row.gateway != '' ? row.gateway: 'NA';
                                        }
                                
                            },
                            { data: 'Amount' }
                        ],
                        footerCallback: function(row, data, start, end, display) {
                                var api = this.api();
                
                                // Remove the formatting to get integer data for summation
                
                                var intVal = function(i) {
                                    return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                
                                };
                
                                // Total over all pages
                
                                total = api
                
                                    .column(9)
                                    .data()
                                    .reduce(function(a, b) {
                                        let x = intVal(a) + intVal(b);
                                        return x.toFixed(2);
                                    }, 0);
                                // Total over this page
                                pageTotal = api
                
                                    .column(9, {
                
                                        page: 'current'
                
                                    })
                
                                    .data()
                                    .reduce(function(a, b) {
                                        return intVal(a) + intVal(b);
                                    }, 0);
                                // Update footer
                                $(api.column(9).footer()).html('' + pageTotal + ' ( ' + total + ' total)');
                
                            }
                    });
                } else {
                    console.warn("No data available or invalid response type.");
                    if ($.fn.DataTable.isDataTable('#paymentHistory')) {
                        $('#paymentHistory').DataTable().clear().draw();
                    }
                }
            } catch (error) {
                console.error("Error parsing JSON response:", error);
                console.error("Response received:", data);
                $('#paymentHistory').DataTable().clear().draw();
                // alert("Failed to load payment history. Please try again.");
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
            console.error("Response Text:", xhr.responseText);
            $('#paymentHistory').DataTable().clear().draw();
            // alert("An error occurred while fetching payment history. Please check your connection or contact support.");
        }
    });
}


</script>
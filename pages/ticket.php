<style>
    textarea {
        height: 100px;
        padding: 12px 20px;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 4px;
        background-color: #f8f8f8;
        font-size: 16px;
        resize: none;
    }

    input,
    select {
        border: 1px solid #CCC;
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

    #mytext {
        width: 361px;
        height: 63px;
        border: 0px;
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
        <input type="hidden" id="tabID" value="agents">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>ND Ticket</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">ND Ticket</li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->
            <!-- ROW-1 -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                            <div class="card overflow-hidden">
                                <div class="card-body">
                                    <div class="mt-2">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6 mb-2">
                                                <span>Select Date</span> &nbsp; <span style="color:red;">*</span>
                                                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                    <i class="fa fa-calendar"></i>&nbsp;
                                                    <span></span> <i class="fa fa-caret-down"></i>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 mb-2">
                                                <span>Select User</span> &nbsp; <span style="color:red;">*</span>
                                                <select id="userType" class="form-control">
                                                    <option value="" selected>Select user</option>
                                                    <option value="customer">Customer</option>
                                                    <option value="agent">Agent</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 mb-2">
                                                <br>
                                                <button type="submit" id="smslogsearch" class="btn btn-info" onclick="searchTicket()">Go</button>
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ROW-1 END -->
            <!-- ROW-4 -->
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="col-lg-4">
                                <h3 class="card-title"><strong>ND Ticket</strong></h3>
                            </div>
                            <div class="col-lg-8">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="sms_report1" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <!--<th class="wd-15p border-bottom-0">s.No</th>-->
                                            <th class="wd-15p border-bottom-0">Name</th>
                                            <th class="wd-15p border-bottom-0">Ticket Type or Agent Name</th>
                                            <th class="wd-15p border-bottom-0">Offer Type</th>
                                            <th class="wd-15p border-bottom-0">Mobile Number</th>
                                            <th class="wd-15p border-bottom-0">Email ID</th>
                                            <th class="wd-15p border-bottom-0">Ticket ID</th>
                                            <th class="wd-20p border-bottom-0">Raffle ID</th>
                                            <!--<th class="wd-20p border-bottom-0">Product Category</th>-->
                                            <th class="wd-20p border-bottom-0">Grand Total</th>
                                            <th class="wd-15p border-bottom-0">Purchase Date & Time</th>
                                            <th class="wd-15p border-bottom-0">Start Date & Time</th>
                                            <th class="wd-15p border-bottom-0">End Date & Time</th>
                                            <th class="wd-15p border-bottom-0">Action</th>
                                            <!--<th></th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <!--<tfoot>-->
                                    <!--    <tr>-->
                                    <!--        <th></th><th></th><th></th><th></th><th></th><th></th>-->
                                    <!--        <th style="text-align:right">Total:</th>-->
                                    <!--        <th></th>-->
                                    <!--        <th></th>-->
                                    <!--        <th></th>-->

                                    <!--    </tr>-->
                                    <!--</tfoot>-->
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- ROW-4 END -->
        </div>
        <!-- CONTAINER END -->
    </div>
</div>
<!--<div class="modal" id="myModal">-->
<!--   <div class="modal-dialog">-->
<!--      <div class="modal-content">-->
<!-- Modal Header -->
<!--         <div class="modal-header">-->
<!--            <h4 class="modal-title">Ticket History</h4>-->
<!--            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>-->
<!--         </div>-->
<!-- Modal Body -->
<!--         <div class="modal-body">-->
<!--            <p id="dataIdDisplay"></p>-->
<!--         </div>-->
<!-- Modal Footer -->
<!--         <div class="modal-footer">-->
<!--            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>-->
<!--         </div>-->
<!--      </div>-->
<!--   </div>-->
<!--</div>-->

<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Remaining Data</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Remaining data will be displayed here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Table to display initial data -->
<table id="dataTable">
    <!-- Table headers -->
    <thead>
        <tr>
            <th>Data</th>
            <th>Action</th>
        </tr>
    </thead>
    <!-- Table body -->
    <tbody>
        <!-- Table rows will be dynamically added here -->
    </tbody>
</table>

<script>
    var origin = window.location.origin;



    var url = origin + "/ajax/service/datatable_services.php";
    var e_url = origin + "/ajax/service/NDemail_services.php";



    $(function() {



        createDatePricket('reportrange');

        searchTicket();



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



                //   'Last 30 Days': [moment().subtract(29, 'days').set({

                //       hour: 0,
                //       minute: 0,
                //       second: 0,
                //       millisecond: 0

                //   }), moment().set({

                //       hour: 23,
                //       minute: 59,
                //       second: 59,
                //       millisecond: 59

                //   })],



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

    $(function() {

        $('input[name="datefilter"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

    });



    function searchTicket() {
        var formdate = moment($('#reportrange').data('daterangepicker').startDate.toDate()).format("YYYY-MM-DD HH:mm:ss");
        var todate = moment($('#reportrange').data('daterangepicker').endDate.toDate()).format("YYYY-MM-DD HH:mm:ss");
        var userType = $('#userType').val();
        var title = 'ND Ticket Reports : (' + formdate + '  to  ' + todate + ')';

        var table = $("#sms_report1").DataTable({
            destroy: true,
            pageLength: 10,
            order: [
                 ['asc']
            ],
            // columnDefs: [{
            //     type: 'date',
            //     targets: [5]
            // }],
            paging: true,
            searching: true,
            info: true,
            ajax: {
                url: url,
                method: "POST",
                dataSrc: "",
                data: {
                    method: 'list_oticket',
                    agdate: formdate,
                    todate: todate,
                    userType: userType,
                },
                error: function(xhr, error, thrown) {
                    console.error("AJAX error:", error);
                }
            },
            dom: 'Bfrtip',
            buttons: [
                'pageLength',
                'copy',
                // {
                //     extend: 'excelHtml5',
                //     title: title
                // },
                // {
                //     extend: 'csvHtml5',
                //     title: title
                // },
                {
                    extend: 'excelHtml5',
                    title: title
                },
                
            ],
            error: function(xhr, error, thrown) {
                console.error("DataTables error:", error);
            },
            columns: [
                {
                    data: 'fullname'  
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        if (data.agentId === '' || data.agentId === 'null' || data.agentId === '0') {
                            return 'Online Ticket';
                        } else {
                            return data.agentName;
                        }
                    }
                },
                {
                    
                    data: null,
                    render: function(data, type, row, meta) {
                        if(data.invoiceNo === '' || data.invoiceNo === null || data.invoiceNo === 0){
                            
                            return 'Gift Coupon';
                            
                        } else if (data.Discout_offer === 'general'){
                            return 'General Offer';
                        } else if(data.Discout_offer === 'coupon'){
                            
                            return 'Discount Coupon';
                            
                        } else if(data.discount !='' && data.discount != 0) {
                            return 'Discount Code'
                        } else{
                            return 'NA';
                        }
                    }
                    // data: 'copupon_type'
                    
                },
                {
                    data: 'mobile'
                },
                {
                    data: 'email'
                },
                {
                    data: 'ticketNo'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        if (Array.isArray(data.raffleIds)) {
                            return formatMatrix(data.raffleIds);
                        }
                        try {
                            var parsedData = JSON.parse(data.raffleIds);
                            if (Array.isArray(parsedData)) {
                                return formatMatrix(parsedData);
                            } else {
                                console.error("Unexpected JSON format:", parsedData);
                                return "Error: Invalid data format";
                            }
                        } catch (error) {
                            console.error("Error parsing JSON:", error);
                            return "Error: Invalid JSON";
                        }
                    }
                },
                // {
                //     data: 'Product'
                // },
                {
                    data: 'grandtotal'
                },
                
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return moment(data.purchaseDatetime).format("DD MMM YYYY hh:mm a");
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return moment(data.startDate).format("DD MMM YYYY");
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
                        var Turl = origin + "/ticketHistoryPay/list/" + data.id;
                        var ticketview = origin + "/pages/view-ticket.php?ticketNo=" + data.ticketNo;
                        var invoiceLink = data.invoiceNo ? `<a target="_blank" href="<?= $baseurl; ?>invoice/${data.referenceID}" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span style="font-size: 18px;" class="fa fa-file-text-o"></span></a>` : '';
                        return `
                        <a target="_blank" href="<?= $baseurl; ?>ticket-view/${data.referenceID}" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span style="font-size: 18px;" class="fa fa-files-o"></span></a>
                        ${invoiceLink}
                        
                        <a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size: 18px;" class="fa fa-paper-plane" onclick="sendemailtopurchase('${data.id}', '${data.email}', 'ticket')">&nbsp;Email</span></a>
                       
                        <a target="_blank" href="<?= $adminurl; ?>MyTicketHistory/list/${data.id}" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span style="font-size: 18px;  color:#3fdb3f;"  class="fa fa-money">&nbsp;Ticket  History</span></a>
                    `;
                    
                    // <a target="_blank" href="<?= $baseurl; ?>invoice/${data.referenceID}" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span style="font-size: 18px;" class="fa fa-file-text-o"></span></a>
                    //  <a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: #1a73e8; font-size: 18px;" onclick="sendsmstopurchase('${data.id}', '${data.mobile}')">&nbsp;SMS</span></a>
                    }
                },
            ],
            // order: [
            //     [10, 'desc']
            // ],
            // footerCallback: function(row, data, start, end, display) {
            //     var api = this.api();
            //     var intVal = function(i) {
            //         return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            //     };
            //     var total = api.column(7).data().reduce(function(a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0);
            //     var pageTotal = api.column(7, { page: 'current' }).data().reduce(function(a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0);
            //     $(api.column(7).footer()).html('' + pageTotal.toFixed(2) + ' ( ' + total.toFixed(2) + ' total)');
            // }
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




    //   function showModal(remainingData) {
    //     alert(remainingData); // Example: You can replace this with your modal display logic
    // }

    function chunkArray(arr, size) {
        var chunkedArr = [];
        for (var i = 0; i < arr.length; i += size) {
            chunkedArr.push(arr.slice(i, i + size));
        }
        return chunkedArr;
    }

    function showModal(remainingData) {
        var modalHtml = `
        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Remaining Raffle IDs</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>${remainingData}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    `;
        $(modalHtml).modal('show');
    }


    $('#myModal').on('show.bs.modal', function(event) {

        var button = $(event.relatedTarget); // Button that triggered the modal

        var dataId = button.data('id'); // Extract data-id attribute from the button

        var modal = $(this);



        $.ajax({

            url: url,

            method: "POST",

            data: {

                method: 'list_oticket_Ticket_History',

                id: dataId

            },

            success: function(response) {

                // Parse the JSON response

                var data = JSON.parse(response);

                // Update the modal body with the data

                if (data.length > 0) {

                    var invoice = data[0]; // Assuming the first item in the data array contains customer information

                    var html = '<table class="table">';

                    html += '<tr><td>Name</td><td>' + invoice.user_id + '</td></tr>';

                    html += '<tr><td>totalAmt</td><td>' + invoice.totalAmt + '</td></tr>';

                    html += '<tr><td>taxValue</td><td>' + invoice.taxValue + '</td></tr>';

                    html += '<tr><td>Total</td><td>' + invoice.netTotal + '</td></tr>';

                    html += '</table>';

                    modal.find('.modal-body').html(html);

                } else {

                    modal.find('.modal-body').html('No data found');

                }

            },



            error: function(xhr, status, error) {

                console.error(error);

                // Handle error

            }

        });



    });

    function sendemailtopurchase(transid, toemail) {

        // alert(toemail);



        swal.fire({

            title: 'Do you want to resent email?',

            showCancelButton: true,

            allowOutsideClick: false,

            confirmButtonText: 'Send Email',

        }).then(function(result) {

            if (result.isConfirmed) {

                var formdata = [];

                formdata.push({

                    name: 'method',

                    value: "sendemailtopurchase"

                });

                formdata.push({

                    name: 'transid',

                    value: transid

                });

                formdata.push({

                    name: 'toemail',

                    value: toemail

                });


                formdata.push({

                    name: 'ottype',

                    value: 'OT'

                });

                formdata.push({

                    name: 'tablename',

                    value: 'ticket'

                });

                var post_data = formdata;

                var onsuccess = function(data) {

                    data = data.replace(/^\[\]/, '');
                    console.log("Data without prefix:", data); // Log the data without prefix

                    try {
                        var response = JSON.parse(data);
                        console.log("Parsed response:", response); // Log the parsed JSON object
                    } catch (error) {
                        console.error("Error parsing JSON:", error); // Log any errors that occur during JSON parsing
                        return;
                    }

                    if (response != "") {
                        console.log("Response type:", response.type); // Log the response type

                        if (response.type == 1) {
                            swal.fire({
                                title: 'Success',
                                text: 'Email send successful',
                                icon: 'success',
                                timer: 3000, // 3 seconds
                                showConfirmButton: false
                            });
                        } else if (response.type == 0) {
                            Swal.fire({
                                title: 'Failed',
                                text: response.result,
                                icon: 'error',
                                timer: 3000, // 3 seconds
                                showConfirmButton: false
                            });
                        }
                    }
                }


                do_ajax_call(post_data, onsuccess, e_url);





            }

        });

    }

    function sendsmstopurchase(transid, mobile) {

        // alert(mobile);

        swal.fire({

            title: 'Do you want to resent SMS?',

            showCancelButton: true,

            allowOutsideClick: false,

            confirmButtonText: 'Send SMS',

        }).then(function(result) {

            if (result.isConfirmed) {

                var formdata = [];

                formdata.push({

                    name: 'method',

                    value: "sendsmstopurchase"

                });

                formdata.push({

                    name: 'transid',

                    value: transid

                });

                formdata.push({

                    name: 'mobile',

                    value: mobile

                });

                formdata.push({

                    name: 'tablename',

                    value: 'ticket'

                });

                var post_data = formdata;

                var onsuccess = function(data) {

                    data = data.replace(/^\[\]/, '');
                    console.log("Data without prefix:", data); // Log the data without prefix

                    try {
                        var response = JSON.parse(data);
                        console.log("Parsed response:", response); // Log the parsed JSON object
                    } catch (error) {
                        console.error("Error parsing JSON:", error); // Log any errors that occur during JSON parsing
                        return;
                    }

                    // alert(response.type);

                    if (response != "") {
                        console.log("Response type:", response.type); // Log the response type

                        if (response.type == 1) {
                            swal.fire({
                                title: 'Success',
                                text: 'Email send successful',
                                icon: 'success',
                                timer: 3000, // 3 seconds
                                showConfirmButton: false
                            });
                        } else if (response.type == 0) {
                            Swal.fire({
                                title: 'Failed',
                                text: response.result,
                                icon: 'error',
                                timer: 3000, // 3 seconds
                                showConfirmButton: false
                            });
                        }
                    }
                }

                do_ajax_call(post_data, onsuccess, e_url);





            }

        });

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

                        value: "delete_NDticket"

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

                                swal.fire({
                                    title: 'Success',
                                    text: 'Ticket deleted successful',
                                    icon: 'success',
                                    timer: 3000, // 3 seconds
                                    showConfirmButton: false
                                });

                                searchTicket();


                                toast('success', response.result);

                                viewtable();



                            } else {

                                Swal.fire({
                                    title: 'Error',
                                    text: 'Failed to delete ticket',
                                    icon: 'error',
                                    timer: 3000, // 3 seconds
                                    showConfirmButton: false
                                });


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

    //   searchTicket();
</script>
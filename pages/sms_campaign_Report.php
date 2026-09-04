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
        width: 100%;
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

                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>SMS Campaign Report</h1>

                <div>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page">SMS Campaign Report</li>

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

                                            <!-- <div class="col-lg-3 col-sm-6 mb-2">
                                                <span>Select Date</span> &nbsp; <span style="color:red;">*</span>
                                                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                    <i class="fa fa-calendar"></i>&nbsp;
                                                    <span></span> <i class="fa fa-caret-down"></i>
                                                </div>
                                            </div> -->



                                            <div class="col-lg-3 col-sm-6 mb-2">
                                                <div class="form-group">
                                                    <label for="smtpauth">SMS Campaign List</label>&nbsp;<sup style="color: red;"><strong>*</strong></sup>
                                                    <select class="form-select" id="smtpauth">
                                                        <option value="">Select SMS Campaign</option>
                                                        <?php
                                                        $campignList = select_query($con, "sms_campaign", "", "`deletes` = '0' ORDER BY `id` DESC", "", "");
                                                        if ($campignList['nr'] > 0) {
                                                            foreach ($campignList['result'] as $key => $value) {
                                                        ?>
                                                                <option value="<?= $value['id']; ?>"><?= $value['campaign_name']; ?></option>
                                                        <?php
                                                            }
                                                        }

                                                        ?>

                                                    </select>
                                                </div>
                                            </div>


                                            <div class="col-lg-3 col-sm-6 mb-2">
                                                <div class="form-group">
                                                    <span>Select Status</span>
                                                    <select id="statusget" class="form-select">
                                                        <option value="click" selected>Clicked</option>
                                                        <!-- <option value="nonclick">Non Clicked</option> -->
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="col-lg-3 col-sm-6 mb-2">
                                                <div class="form-group">
                                                    <label for="c_mobile">Mobile No</label>
                                                    <input type="text" class="form-control" id="c_mobile" placeholder="Mobile No" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                                                </div>
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

                                <h3 class="card-title"><strong>SMS Campaign Report</strong></h3>

                            </div>

                            <div class="col-lg-8">















                            </div>



                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="sms_report1" style="width:100%;">

                                    <thead>

                                        <tr>

                                            <th class="wd-15p border-bottom-0">Mobile Number</th>
                                            <!-- <th class="wd-15p border-bottom-0">Number of Clicks</th> -->
                                            <th class="wd-20p border-bottom-0">Link</th>
                                            <th class="wd-20p border-bottom-0">Message</th>
                                            <!-- <th class="wd-15p border-bottom-0">Reference ID</th>
                                            <th class="wd-15p border-bottom-0">Date & Time</th>
                                            <th class="wd-15p border-bottom-0">Status</th> -->
                                            <!--<th></th>-->
                                        </tr>

                                    </thead>

                                    <tbody>

                                    </tbody>

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









<script>
    var url = origin + "/ajax/service/datatable_services.php";
    let SMSRedirectURL = '<?= constant('SMSRedirectURL'); ?>';




    $(function() {



        // createDatePricket('reportrange');
        // searchTicket();

    });


    // function createDatePricket(id) {
    //     var start = moment();
    //     var end = moment();

    //     function cb(start, end) {
    //         $('#' + id + ' span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY'));
    //     }
    //     $('#' + id).daterangepicker({
    //         startDate: start,
    //         endDate: end,
    //         maxSpan: {
    //             days: 6
    //         },
    //         minDate: moment().subtract(14, "days").format("MM/DD/YYYY"),
    //         maxDate: moment().format("MM/DD/YYYY"),
    //         ranges: {
    //             'Today': [moment(), moment()],
    //             'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    //             'Last 7 Days': [moment().subtract(6, 'days'), moment()],
    //             // 'Last 15 Days': [moment().subtract(14, 'days'), moment()],
    //             // 'This Month': [moment().startOf('month'), moment().endOf('month')],
    //             // 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    //         }
    //     }, cb);
    //     cb(start, end);
    // }

    function searchTicket() {
        // var table = $('#sms_report1').DataTable();
        // table.destroy();


        // var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
        // var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");
        var smtpauth = $(`#smtpauth`).val();
        if (smtpauth == '') {
            toast('error', 'Kindly select the SMS Campaign');
            return false;
        }


        var title = 'Campaign Report';

        var table = $("#sms_report1").DataTable({
            destroy: true,
            pageLength: 10,
            // responsive: {
            //     details: {
            //         type: 'column',
            //         target: -1,
            //     }
            // },
            // columnDefs: [{
            //     targets: -1,
            //     orderable: false,
            //     searchable: true,
            //     className: 'control',
            // }, {
            //     targets: 0,
            //     orderable: false,
            //     searchable: true,
            //     className: 'selectall-checkbox',
            // }],
            // select: {
            //     style: 'multi',
            //     selector: 'td:first-child',
            // },
            // order: [
            //     [5, 'desc']
            // ],
            // columnDefs: [{
            //         type: 'date',
            //         targets: [5]
            //     } 
            // ],
            paging: true,
            searching: true,
            info: true,
            ajax: {
                url: origin + "/ajax/service/sms_campaign_services.php",
                method: "POST",
                dataSrc: "result",
                data: {
                    method: 'campReport',
                    // agdate: formdate,
                    // todate: todate,
                    campID: smtpauth,
                    statusget: $('#statusget').val(),
                    mobile: $(`#c_mobile`).val()
                }
            },
            dom: 'Bfrtip',
            buttons: [
                'pageLength',
                // {
                //     extend: 'copyHtml5',
                //     title: title
                // },
                {
                    extend: 'csvHtml5',
                    title: title
                },
                {
                    extend: 'excelHtml5',
                    title: title
                },
                // {
                //     extend: 'pdfHtml5',
                //     orientation: 'landscape',
                //     pageSize: 'LEGAL',
                //     title: title
                // },
                {
                    extend: 'print',
                    title: title
                },
            ],
            columns: [{
                    data: "mobile"
                }, 
                // {
                //     data: "click_count"
                // },
                {
                    data: "link_url"
                }, {
                    data: null,
                    render: function(data, type, row, meta) {
                        let message = data.message;
                        return `<textarea readonly="">${message.replace(/UTMURL/g, `${SMSRedirectURL + data.link_url}`)}</textarea>`
                    }
                },
                // {
                //     data: "ip"
                // },
                // {
                //     data: "reference_id"
                // },
                // {
                //     data: "datetime"
                // },
                // {
                //     data: null,
                //     render: function(data, type, row, meta) {
                //         return moment(data.date).format("DD MMM YYYY hh:mm a")
                //     }
                // },
                // {
                //     data: "status"
                // },
                // {
                //     data: null,
                //     render: function(data, type, row, meta) {
                //         return ''
                //     }
                // }
            ],

        });

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
</script>
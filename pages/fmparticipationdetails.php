<?php
//    Date       Developer_name      Modifications
//    30-3-2023   Prakash            Email Add Option Development Finished



$pageTitle = 'FM Participation List';

?>
<style>
    span.fa.fa-trophy.fs-14 {
        color: #9d8711;
        font-size: 19px !important;
        font-weight: 900;
    }
</style>

<div class="main-content app-content mt-0">
    <div class="side-app">


        <div class="main-container container-fluid">

            <div class="page-header">
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a><?= ucwords($pageTitle); ?></h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= ucwords($pageTitle); ?></li>
                    </ol>
                </div>
            </div>



            <!-- <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <h3 class="card-title"><?= ucwords('Email List'); ?></h3>
                                </div>

                                <div class="col-lg-6 col-sm-10">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-select" id="status">
                                            <option value="">All</option>
                                            <option value="0">Active</option>
                                            <option value="1">Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-2 mt-5">
                                    <button class="btn btn-info" onclick="viewtable()">Go</button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom dataTable" id="emailListtable" style="width:100%;">

                                    <thead>
                                        <tr>
                                            <th class="wd-15p border-bottom-0">S. NO</th>
                                            <th class="wd-15p border-bottom-0">Host</th>
                                            <th class="wd-15p border-bottom-0">SMTP Auth</th>
                                            <th class="wd-15p border-bottom-0">Username</th>
                                            <th class="wd-15p border-bottom-0">Password</th>
                                            <th class="wd-15p border-bottom-0">SMTP Secure</th>
                                            <th class="wd-15p border-bottom-0">Port</th>
                                            <th class="wd-15p border-bottom-0">From Email</th>
                                            <th class="wd-15p border-bottom-0">From Name</th>
                                            <th class="wd-15p border-bottom-0">Reply To Mail</th>
                                            <th class="wd-15p border-bottom-0">Character Set</th>
                                            <th class="wd-15p border-bottom-0">Encoding</th>
                                            <th class="wd-15p border-bottom-0">status</th>
                                            <th class="wd-15p border-bottom-0">Assigned To</th>
                                            <th class="wd-15p border-bottom-0">Action</th>
                                         
                                        </tr>
                                    </thead>

                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <h3 class="card-title"><?= ucwords('Participation List'); ?></h3>
                                </div>
                                <div class="col-lg-4  col-sm-10 ">
                                    <span>Select Contest Date</span> &nbsp; <span style="color:red;">*</span>
                                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                        <span></span> <i class="fa fa-caret-down"></i>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-2 mt-5">
                                    <button class="btn btn-info" onclick="fmContestList()">Go</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- OLD  -->
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <!-- <div class="card-header">
                           
                        </div> -->

                        <div class="card-body">
                            <div class="tab-menu-heading border-0 p-0">

                                <div class="tabs-menu1">

                                    <!-- Tabs -->
                                    <ul class="nav panel-tabs ">
                                        <li><a href="#tab1" id="aemailLog" class="text-dark active" data-bs-toggle="tab" onclick="fmContestList()">PARTICIPANTS LIST</a></li>
                                        <li><a href="#tab2" id="lemailLog" class="text-dark" data-bs-toggle="tab" onclick="fmContestList()">PRE-WINNER LIST</a></li>
                                    </ul>

                                </div>

                            </div>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-nowrap border-bottom dataTable" id="aemailLog1" style="width:100%;">

                                            <thead>
                                                <tr>
                                                    <!-- <th class="wd-15p border-bottom-0"></th> -->
                                                    <th class="wd-15p border-bottom-0">Registration Date</th>
                                                    <th class="wd-15p border-bottom-0">Selected My3Number</th>
                                                    <!-- <th class="wd-15p border-bottom-0">Combination My3Number</th> -->
                                                    <!-- <th class="wd-15p border-bottom-0">Winning My3Number</th> -->
                                                    <th class="wd-15p border-bottom-0">Name</th>
                                                    <th class="wd-15p border-bottom-0">Email</th>
                                                    <th class="wd-15p border-bottom-0">Mobile</th>
                                                    <!-- <th class="wd-15p border-bottom-0"></th> -->
                                                    <!-- <th class="wd-15p border-bottom-0">Createdon</th> -->
                                                    <th class="wd-15p border-bottom-0">Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab2">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-nowrap border-bottom dataTable" id="lemailLog1" style="width:100%;">

                                            <thead>
                                                <tr>
                                                    <!-- <th class="wd-15p border-bottom-0"></th> -->
                                                    <th class="wd-15p border-bottom-0">Registration Date</th>
                                                    <th class="wd-15p border-bottom-0">Selected My3Number</th>
                                                    <!-- <th class="wd-15p border-bottom-0">Combination My3Number</th> -->
                                                    <!-- <th class="wd-15p border-bottom-0">Winning My3Number</th> -->
                                                    <th class="wd-15p border-bottom-0">Name</th>
                                                    <th class="wd-15p border-bottom-0">Email</th>
                                                    <th class="wd-15p border-bottom-0">Mobile</th>
                                                    <!-- <th class="wd-15p border-bottom-0"></th> -->
                                                    <!-- <th class="wd-15p border-bottom-0">Createdon</th> -->
                                                    <th class="wd-15p border-bottom-0">Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <br> <br>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>



<!-- Modal -->
<div class="modal fade" id="testmail">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content modal-content-demo">

            <div class="modal-header">
                <h6 class="modal-title">Winner Details</h6>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>

            <div class="modal-body">

                <div class="container">
                    <div class="form-group" id="customerpreference">

                        <div class="row" style="justify-content: center;" id="winnerDataDetails">


                        </div>
                        <div class="row">
                            <!-- <div class="col-md-12">
                                <div class="form-group">
                                    <label for="tomy3Number">My3Number</label>
                                    <input type="text" class="form-control" id="tomy3Number" maxlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="000">
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer" id="SaveBtn">

            </div>

        </div>

    </div>

</div>




<script>
    $(function() {
        createDatePricket('reportrange');
        fmContestList();
    });

    ///////  New ///////
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

    function createDatePricket(id) {
        var start = moment();

        function cb(start) {
            $('#' + id + ' span').html(start.format('MMM Do YY'));
        }
        $('#' + id).daterangepicker({
            startDate: start,
            singleDatePicker: true,
            maxDate: moment().add(1, 'day').format("MM/DD/YYYY"),
            autoApply: true,
        }, cb);
        cb(start);
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
                location.reload();
            }
        })
    }


    function fmContestList() {
        try {
            var title = 'Fm Participation Reports';
            let tableID = $('ul li a.active').attr('id') === 'aemailLog' ? 'aemailLog1' : 'lemailLog1';

            var table = $('#' + tableID).DataTable({
                destroy: true,
                pageLength: 10,
                // responsive: {
                //     details: {
                //         type: 'column',
                //         target: -1,
                //     }
                // },
                order: [0, 'asc'],
                columnDefs: [{
                    type: 'date',
                    targets: [0]
                }],
                paging: true,
                searching: true,
                info: true,
                ajax: {
                    url: origin + "/ajax/service/fmcontestServices.php",
                    method: "POST",
                    dataSrc: "result",
                    data: {
                        method: 'fm_participation_list',
                        contestDate: moment($('#reportrange').data('daterangepicker').startDate._d).format("YYYY-MM-DD"),
                        winnersOnly: $('ul li a.active').attr('id') === 'aemailLog' ? true : false
                    },
                },
                dom: 'Bfrtip',
                buttons: [
                    'pageLength',
                    // 'copy',
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
                    //     orientation: 'portrait',
                    //     pageSize: 'A4',
                    //     title: title

                    // }, 'print',
                ],
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return moment(data.reg_date).format("DD MMM YYYY");
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return data.my3number;
                        }
                    },
                    // {
                    //     data: null,
                    //     render: function(data, type, row, meta) {
                    //         return data.comb_no;
                    //     }
                    // },
                    // {
                    //     data: null,
                    //     render: function(data, type, row, meta) {
                    //         return data.winning_my3number ?? '';
                    //     }
                    // },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            let firstName = data.name ?? '';
                            let lastName = data.lname ?? '';
                            return firstName + ' ' + lastName;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return data.email ?? '';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return data.mobile ?? '';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return '';
                            // return (data.winner_id < 1) ? `<a class="btn text-primary btn-sm" onclick="editForm(${data.id})"><span class="fa fa-trophy fs-14">&nbsp;Announce</span></a>` : '';

                        }
                    }
                ],
            });
        } catch (e) {
            console.log('Error: ' + e.message);
        }
    }

    function editForm(partID) {
        try {
            // const data = JSON.parse(dataString);

            if (partID == '' || partID == null || partID == undefined) {
                toast('error', 'The ID Missing! Kindly Refresh and Try Again!');
                return false;
            }

            var formdata = [];
            formdata.push({
                name: 'method',
                value: "getWinnerData"
            }, {
                name: 'partID',
                value: partID
            });


            var post_data = formdata;



            var onsuccess = function(data) {
                var response = JSON.parse(data);
                if (response != "") {
                    if (response.type == '1') {
                        // fmContestList();
                        // toast('success', response.result);
                        let firstName = response.winnerData.name ?? '';
                        let lastName = response.winnerData.lname ?? '';

                        $(`#winnerDataDetails`).html(`
                        <div class="row mb-3">
                                            <div class="col-6">
                                                <p class="mdtext">Contest Date </p>
                                            </div>
                                            <div class="col-6">
                                                <p>${response.winnerData.reg_date}</p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <p class="mdtext">My3Number </p>
                                            </div>
                                            <div class="col-6">
                                                <p>${response.winnerData.my3number}</p>
                                            </div>
                                        </div>     
                                          <div class="row mb-3">
                                            <div class="col-6">
                                                <p class="mdtext">Name </p>
                                            </div>
                                            <div class="col-6">
                                                <p>${firstName + ' ' + lastName}</p>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <p class="mdtext">Email </p>
                                            </div>
                                            <div class="col-6">
                                            <p>${response.winnerData.email}</p>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <p class="mdtext">Mobile </p>
                                            </div>
                                            <div class="col-6">
                                            <p>${response.winnerData.mobile}</p>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <p class="mdtext">Prize Amount </p>
                                            </div>
                                            <div class="col-6">
                                                <p>AED ${response.winnerData.prize_amt}</p>
                                            </div>
                                        </div>`);

                        $(`#SaveBtn`).html(`<button class="btn btn-info" onclick="announceFMWinner(${partID})">Announce Winner</button>`);
                        $('#testmail').modal('show');
                    } else {
                        toast('error', response.result);

                    }
                }
            }

            do_ajax_call(post_data, onsuccess, origin + "/ajax/service/fmcontestServices.php");



        } catch (error) {
            console.log('Error Message: ' + error.message);
        }
    }

    function announceFMWinner(id) {
        try {
            if (id == '' || id == null || id == undefined) {
                toast('error', 'The ID Missing! Kindly Refresh and Try Again!');
                return false;
            }
            // let my3number = $(`#tomy3Number`).val();
            // if (my3number == '' || my3number == null || my3number == undefined) {
            //     toast('error', 'Kindly Enter My3Number!');
            //     return false;
            // }
            // if (my3number.length != 3) {
            //     toast('error', 'Kindly Enter 3 digit!');
            //     return false;
            // }

            var formdata = [];
            formdata.push({
                name: 'method',
                value: "announceFMWinner"
            }, {
                name: 'partID',
                value: id
            });

            var post_data = formdata;

            var btn = $('#SaveBtn').html();
            $('#SaveBtn').html(`<div class="spinner-grow text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-secondary" role="status">
                                        <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-success" role="status">
                                        <span class="sr-only">Loading...</span>
                                        </div>`);
            var onsuccess = function(data) {
                var response = JSON.parse(data);
                if (response != "") {
                    if (response.type == '1') {
                        fmContestList();
                        toast('success', response.result);
                        $('#SaveBtn').html(btn);
                        $('#testmail').modal('hide');
                    } else {
                        toast('error', response.result);
                        $('#SaveBtn').html(btn);
                    }
                }
            }

            do_ajax_call(post_data, onsuccess, origin + "/ajax/service/fmcontestServices.php");

        } catch (error) {
            console.log('Error Message: ' + error.message);
        }
    }
</script>
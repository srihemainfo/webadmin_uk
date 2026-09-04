<?php
//    Date       Developer_name      Modifications
//    30-3-2023   Prakash            Email Add Option Development Finished



$pageTitle = 'FM Contest';

?>

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

            <!-- <div class="row">



                <div class="col-xl-12">
                    <form id="editprofileform">

                        <div class="card">


                            <div class="card-header">
                                <h3 class="card-title" id="th1titlte">Add Email (PHPMailer Config Only)</h3>
                            </div>

                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="host">HOST</label><sup style="color: red;"><strong>*</strong></sup>
                                            <input type="text" id="host" class="form-control" oninput="this.value = this.value.replace(/[^A-Za-z0-9!@#$%^&*.]/g, '');" placeholder="eg. name@example.com" maxlength="70">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="smtpauth">SMTP AUTH</label><sup style="color: red;"><strong>*</strong></sup>
                                            <select class="form-select" id="smtpauth">
                                                <option value="1">TRUE</option>
                                                <option value="0">FALSE</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="username">User Name</label><sup style="color: red;"><strong>*</strong></sup>
                                            <input type="text" class="form-control" id="username" maxlength="70" placeholder="eg. name@example.com">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Password</label><sup style="color: red;"><strong>*</strong></sup>
                                            <input type="password" class="form-control" id="password" maxlength="255" placeholder="eg. test123">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="smtpsecure">SMTP Secure</label><sup style="color: red;"><strong>*</strong></sup>
                                            <select class="form-select" id="smtpsecure">
                                                <option value="tls">TLS</option>
                                                <option value="ssl">SSL</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="port">PORT</label><sup style="color: red;"><strong>*</strong></sup>
                                            <select class="form-select" id="port">
                                                <option value="587">587</option>
                                                <option value="465">465</option>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fromemail">From Email</label><sup style="color: red;"><strong>*</strong></sup>
                                            <input type="email" class="form-control" id="fromemail" placeholder="eg. name@example.com">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fromname">From Name</label><sup style="color: red;"><strong>*</strong></sup>
                                            <input type="text" class="form-control" id="fromname" placeholder="eg. NATIONAL DRAW">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="replyto">Reply To Email</label><sup style="color: red;"><strong>*</strong></sup>
                                            <input type="email" class="form-control" id="replyto" placeholder="eg. name@example.com">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cahrset">Character Set</label>
                                            <input type="text" class="form-control" id="cahrset" placeholder="eg. UTF-8">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="encoding">Encoding</label>
                                            <input type="text" class="form-control" id="encoding" placeholder="eg. base64">
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="card-footer text-end" id="saveEmailBtn">
                                <button type="button" onclick="saveEmail(0)" class="btn btn-success bg-success-gradient my-1">Save</button>
                            </div>
                        </div>
                    </form>
                </div>




            </div> -->

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
                                    <h3 class="card-title"><?= ucwords('FM Contest List'); ?></h3>
                                </div>
                                <!-- <div class="col-lg-4  col-sm-10 ">
                                    <span>Select Date</span> &nbsp; <span style="color:red;">*</span>
                                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                        <span></span> <i class="fa fa-caret-down"></i>
                                    </div>
                                </div> -->
                                <!-- <div class="col-lg-6 col-sm-2 mt-5">
                                    <button class="btn btn-info" onclick="fmContestList()">Go</button>
                                </div> -->
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom dataTable" id="emailLog" style="width:100%;">

                                    <thead>
                                        <tr>
                                            <!-- <th class="wd-15p border-bottom-0"></th> -->
                                            <th class="wd-15p border-bottom-0">Contest Date</th>
                                            <th class="wd-15p border-bottom-0">Straight My3Number</th>
                                            <th class="wd-15p border-bottom-0">Combination My3Number</th>
                                            <th class="wd-15p border-bottom-0">Guess Your Number</th>
                                            <!--  <th class="wd-15p border-bottom-0">Winner Name</th>
                                            <th class="wd-15p border-bottom-0">Winner Email</th>
                                            <th class="wd-15p border-bottom-0">Winner Mobile</th> -->
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
                </div>
            </div>

        </div>
    </div>


</div>



<!-- Modal -->
<div class="modal fade" id="testmail1">

    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">

        <div class="modal-content modal-content-demo">

            <div class="modal-header">
                <h6 class="modal-title">Change My3Number Form</h6>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>

            <div class="modal-body">

                <div class="container">
                    <div class="form-group" id="customerpreference">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="tomy3Number">My3Number</label>
                                    <input type="text" class="form-control" id="tomy3Number" maxlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="000">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="towinmy3Number">Winning Number</label>
                                    <input type="text" class="form-control" id="towinmy3Number" maxlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="000">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer" id="SaveBtn1">

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
        var title = 'Fm Contest Reports';

        var table = $("#emailLog").DataTable({
            destroy: true,
            pageLength: 10,
            // responsive: {
            //     details: {
            //         type: 'column',
            //         target: -1,
            //     }
            // },
            order: [0, 'desc'],
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
                    method: 'fm_contest_info',
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
                        return data.straight_no;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.comb_no;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.winning_my3number ?? '';
                    }
                },
                // {
                //     data: null,
                //     render: function(data, type, row, meta) {
                //         return data.name ?? '';
                //     }
                // },
                // {
                //     data: null,
                //     render: function(data, type, row, meta) {
                //         return data.email ?? '';
                //     }
                // },
                // {
                //     data: null,
                //     render: function(data, type, row, meta) {
                //         return data.mobile ?? '';
                //     }
                // },
                {
                    data: null,
                    render: function(data, type, row, meta) {

                        let htmlS = (!moment().isAfter(moment(data.reg_date))) ?
                            `<a class="btn text-primary btn-sm" onclick="editFormMy3number(${data.id})"><span class="fa fa-pencil-square-o fs-14">&nbsp;Edit</span></a>` :
                            '';

                        if (!moment().isBefore(moment(data.reg_date))) {
                            htmlS += (moment(data.reg_date).isBefore(moment())) ?
                                (parseInt(data.pre_winner_id) === 0) ?
                                `<a class="btn text-primary btn-sm" onclick="editForm('${data.reg_date}')"><span class="fa fa-trophy fs-14">&nbsp;Update Winner</span></a>` :
                                '' :
                                '';
                        }


                        return htmlS;
                    }
                }
            ],
        });

    }

    function editFormMy3number(id) {
        try {
            if (id == '' || id == null || id == undefined) {
                toast('error', 'The ID Missing! Kindly Refresh and Try Again!');
                return false;
            }
            $(`#SaveBtn1`).html(`<button class="btn btn-info" onclick="saveMyNumber(${id})">Save</button>`);
            $('#testmail1').modal('show');
        } catch (error) {
            console.log('Error Message: ' + error.message);
        }
    }

    function saveMyNumber(id) {
        try {
            if (id == '' || id == null || id == undefined) {
                toast('error', 'The ID Missing! Kindly Refresh and Try Again!');
                return false;
            }
            let my3number = $(`#tomy3Number`).val();
            let winningNumber = $(`#towinmy3Number`).val();

            if (my3number == '' || my3number == null || my3number == undefined) {
                toast('error', 'Kindly Enter My3Number!');
                return false;
            }
            if (my3number.length != 3) {
                toast('error', 'Kindly Enter 3 digit!');
                return false;
            }

            if (winningNumber == '' || winningNumber == null || winningNumber == undefined) {
                toast('error', 'Kindly Enter Winning My3Number!');
                return false;
            }
            if (winningNumber.length != 3) {
                toast('error', 'Kindly Enter 3 digit Winning My3Number!');
                return false;
            }

            var formdata = [];
            formdata.push({
                name: 'method',
                value: "changeMy3Number"
            }, {
                name: 'my3number',
                value: my3number
            }, {
                name: 'id',
                value: id
            }, {
                name: 'winningNumber',
                value: winningNumber
            });


            var post_data = formdata;

            var btn = $('#SaveBtn1').html();
            $('#SaveBtn1').html(`<div class="spinner-grow text-primary" role="status">
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
                        $('#SaveBtn1').html(btn);
                        $('#testmail1').modal('hide');
                    } else {
                        toast('error', response.result);
                        $('#SaveBtn1').html(btn);
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


    function editForm(partID) {
        try {
            // const data = JSON.parse(dataString);

            if (partID == '' || partID == null || partID == undefined) {
                toast('error', 'The Date Missing! Kindly Refresh and Try Again!');
                return false;
            }

            var formdata = [];
            formdata.push({
                name: 'method',
                value: "getWinnerData"
            }, {
                name: 'winnerlistData',
                value: moment(partID).format("YYYY-MM-DD")
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

                        $(`#SaveBtn`).html(`<button class="btn btn-info" onclick="announceFMWinner(${response.winnerData.id})">Select Winner</button>`);
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
</script>
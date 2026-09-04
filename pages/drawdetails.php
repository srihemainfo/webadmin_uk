<?php
//  Date           Developer             Modification
//  5-4-2023        Prakash               Date Picker changed 
//  5-4-2023        Prakash               Timer changed to 12H to 24H Format


$pageTitle = "Announcement Popup";
$content = "";
$popup = select_query($con, "popup", "", "`id` = '1' AND `status` = '0' AND `deletes` = '0'", "", "");
if ($popup['nr'] > 0) {
    $fromDate = $popup['result'][0]['start_time'];
    $toDate = $popup['result'][0]['end_time'];
    $content = stripcslashes($popup['result'][0]['content']);
}
?>
<style>
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
        var n = window.location.origin;
        document.getElementById("anchor").href = n
    };
</script>

<!--app-content open-->
<div class="main-content app-content mt-0">
    <div class="side-app">
        <input type="hidden" id="tabID" value="agents">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>
                    <?= ucwords($pageTitle); ?>
                </h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= ucwords($pageTitle); ?>
                        </li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- ROW-1 -->

            <!-- ROW-1 END -->





            <!-- ROW-4 -->
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">

                            <div class="col-lg-4">
                                <h3 class="card-title"><strong>Announcement Popup Form</strong></h3>
                            </div>
                            <div class="col-lg-8">
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-5 col-sm-6 mb-2">
                                    <label><strong>Popup Start & End Date Time</strong></label>
                                    <div id="csetime" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"><i class="fa fa-calendar"></i>&nbsp;<span></span> <i class="fa fa-caret-down"></i></div>
                                </div>
                                <!-- <div class="col-6">
                                    <label>Start Date & Time</label><br>
                                    <input type="datetime-local" id="start_time" value="<?= date("Y-m-d h:i:s", strtotime($fromDate)); ?>" name="start_time">
                                </div>
                                <div class="col-6">
                                    <label>End Date & Time</label><br>
                                    <input type="datetime-local" id="end_time" value="<?= date("Y-m-d h:i:s", strtotime($toDate)); ?>" name="end_time">
                                </div> -->
                                <div class="col-12">
                                    <br>
                                    <textarea id="summernote"></textarea>
                                </div>
                                <div class="col-12" id="savebtn">
                                    <br>
                                    <button type="button" onclick="saveNote()" class="btn btn-primary">Save</button>
                                </div>

                            </div>


                            <!-- </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- ROW-4 END -->
        </div>
        <!-- CONTAINER END -->
    </div>
</div>


<!--app-content close-->
<script id="rendered-js">
    var url = window.location.origin + "/ajax/service/report_services.php";


    $(document).ready(function() {
        var markupStr = '<?= $content; ?>';
        $('textarea#summernote').summernote({
            placeholder: 'Enter The Confirm Note',
            tabsize: 2,
            height: 100
        });


        $('#summernote').summernote('code', markupStr);
        createDatePricket('csetime');
        // searchTicket();
    });




    function saveNote() {

        var textareaValue = $('#summernote').summernote('code');
        var start_time = moment($('#csetime').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
        var end_time = moment($('#csetime').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");
        // var start_time = $('#start_time').val();
        // var end_time = $('#end_time').val();

        if (start_time == '') {
            toast('error', 'Please Select Start Time.');
            return false;
        }
        if (end_time == '') {
            toast('error', 'Please Select End Time.');
            return false;
        }
        if (textareaValue == '') {
            toast('error', 'Please Enter The Text.');
            return false;
        }

        var formdata = [];
        formdata.push({
            name: 'method',
            value: "update_desicription"
        });
        formdata.push({
            name: 'start_time',
            value: start_time
        });
        formdata.push({
            name: 'end_time',
            value: end_time
        });
        formdata.push({
            name: 'description',
            value: textareaValue
        });
        var post_data = formdata;
        var btn = document.getElementById('savebtn').innerHTML;
        document.getElementById('savebtn').innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';

        var onsuccess = function(data) {
            var response = JSON.parse(data);
            if (response != "") {
                if (response.type == 1) {
                    document.getElementById('savebtn').innerHTML = btn;
                    toast('success', response.result);
                    // location.reload();
                } else {
                    document.getElementById('savebtn').innerHTML = btn;
                    toast('error', response.result);
                }
            }
        }
        do_ajax_call(post_data, onsuccess, url);



    }


    function paymentSuccess(t, i) {
        Swal.fire({
            title: i,
            icon: t,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OKAY",
            allowOutsideClick: !1
        }).then(t => {
            t.isConfirmed && agentEarning()
        })
    }



    function toast(e, t) {
        let i = Swal.mixin({
            toast: !0,
            position: "top-end",
            showConfirmButton: !1,
            timer: 5e3,
            timerProgressBar: !0,
            didOpen(e) {
                e.addEventListener("mouseenter", Swal.stopTimer), e.addEventListener("mouseleave", Swal.resumeTimer)
            }
        });
        i.fire({
            icon: e,
            title: t
        })
    }


    function createDatePricket(id) {
        var start = moment();
        var end = moment();

        function cb(start, end) {
            $('#' + id + ' span').html(start.format("YYYY-MM-DD HH:mm:ss") + ' - ' + end.format("YYYY-MM-DD HH:mm:ss"));
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
            minDate: moment().format("MM/DD/YYYY"),
            timePicker: true,
            timePicker24Hour: true,
            timePickerSeconds: true,
            maxSpan: {
                days: 89
            },
            autoUpdateInput: true,
        }, cb);
        cb(start, end);

    }
</script>
<?php



// error_reporting(E_ALL);
// ini_set('display_errors', 1);



$pageTitle = "FAQ";

$getFaqData = select_query($con, "faq", "", "`id` = '$subid2'", "", "");

$getOrderNo = select_query($con, "faq", "", "`deletes` = '0' ORDER BY `orderno` DESC Limit 1", "", "");

if ($getFaqData['nr'] == 0) {
    $orderNo = 1;
    if ($getOrderNo['nr'] > 0) {
        $orderNo = $getOrderNo['result'][0]['orderno'] + 1;
    }
}
?>
<style>
    .faq_tbody {
        cursor: move;
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

    .text-danger1 {
        color: #4d26e8 !important;
    }

    .swal2-styled,
    #gcBtn {
        padding: 5px 18px 18px 18px;
    }
</style>

<script>
    window.onload = function() {
        var page_origin = window.location.origin;
        let anchor = document.getElementById("anchor");
        anchor.href = page_origin;
    }
</script>

<!--app-content open-->
<div class="main-content app-content mt-0">
    <div class="side-app">
        <input type="hidden" id="tabID" value="agents">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a><?= ucwords($pageTitle); ?>
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
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">


                            <div class="col-lg-8">




                                <fieldset>
                                    <span>Order</span>&nbsp;<span style="color: red;">*</span>
                                    <input id="order1" name="order1" class="form-control" readonly value="<?php if ($getFaqData['nr'] > 0) {
                                                                                                                echo $getFaqData['result'][0]['orderno'];
                                                                                                            } else {
                                                                                                                echo $orderNo;
                                                                                                            }  ?>">
                                    <br><br>
                                    <span>Question:</span>
                                    <br>

                                    <textarea type="text" class="form-control" id="ques" required><?php if ($getFaqData['nr'] > 0) {
                                                                                                        echo $getFaqData['result'][0]['ques'];
                                                                                                    } ?></textarea>

                                    <br>
                                    <span>Answer:</span>
                                    <br>

                                    <textarea type="text" class="form-control" id="Answ" required><?php if ($getFaqData['nr'] > 0) {
                                                                                                        echo $getFaqData['result'][0]['ans'];
                                                                                                    } ?></textarea>

                                    <br>

                                    <br>


                                    <br>
                                    <?php if ($getFaqData['nr'] > 0) { ?>
                                        <button class="btn btn-info" id="gcBtn" onclick="faqupdate(<?= $getFaqData['result'][0]['id'] ?>)">Update</buton>
                                        <?php } else { ?>
                                            <button class="btn btn-info" id="gcBtn" onclick="faqsub()">Submit</buton>
                                            <?php } ?>


                                            <fieldset>






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
                                <h3 class="card-title"><strong>FAQ</strong></h3>
                            </div>
                            <div class="col-lg-8">







                            </div>

                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered display" id="faq_datatable" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p border-bottom-0">Order</th>
                                            <th class="wd-15p border-bottom-0">Question</th>
                                            <th class="wd-15p border-bottom-0">Answer</th>
                                            <th class="wd-20p border-bottom-0">Action</th>

                                            <th class="wd-25p border-bottom-0"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="faq_tbody">
                                    </tbody>
                                    <!-- <tfoot>
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
                                        </tfoot> -->
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

<!--app-content close-->

<!-- Delete Modal -->

<div class="modal fade" id="deleteinfo" tabindex="-1" role="dialog" aria-labelledby="customModalLabel" aria-modal="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="customModalLabel">Delete</h5> <button type="button" onclick="refersh()" class="close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>

            </div>

            <div class="modal-body" id="dele">



            </div>

            <div class="modal-footer custom">



                <div class="divider"></div>

                <div class="right-side">



                    <button aria-label="Close" class="btn btn-danger pd-x-25 success" onclick="refersh()" data-bs-dismiss="modal">Close</button>





                </div>

            </div>

        </div>

    </div>

</div>




<script>
    $(document).ready(function() {
        searchTicket();

        $(".faq_tbody").sortable({
            delay: 150,
            stop: function() {
                var selectedData = new Array();;
                $('.faq_tbody>tr').each(function() {
                    selectedData.push($(this).data("id"));
                });
                updateOrder(selectedData);
            }
        });

        function updateOrder(data) {
            $.ajax({
                url: window.location.origin + "/ajax/service/faq_services.php",
                type: 'post',
                data: {
                    position: data,
                    method: 'update_orderno'
                },
                success: function() {
                    paymentSuccess('success', "Order Updated Sucessfully.");
                    searchTicket();
                }
            })
        }
    });





    function searchTicket() {
        var table = $('#faq_datatable').DataTable();
        table.destroy();

        // let draw_new_id = $('#draw_new_id').val();
        // let ticket_name = $('#ticket_name').val();
        // let agdate = $('#agdate').val();
        // if (draw_new_id != '') {

        var title = 'Ticket Reports';
        table = $("#faq_datatable").DataTable({
            pageLength: 10,
            order: [],
            responsive: {
                details: {
                    type: 'column',
                    target: -1,
                }
            },
            columnDefs: [{
                targets: -1,
                orderable: false,
                searchable: false,
                className: 'control',
            }, {
                targets: 0,
                orderable: false,
                searchable: false,
                className: 'selectall-checkbox',
            }],
            select: {
                style: 'multi',
                selector: 'td:first-child',
            },
            paging: true,
            searching: true,
            info: true,
            ajax: {
                url: window.location.origin + "/ajax/service/faq_services.php",
                method: "POST",
                dataSrc: "",
                data: {
                    method: 'faq_list',
                    // ticket_name: ticket_name,
                    // draw_id: draw_new_id,
                    // agdate: agdate
                }
            },
            createdRow: function(row, data, dataIndex) {
                $(row).attr('data-orderid', data.order);
                $(row).attr('data-id', data.id);
            },
            // dom: 'Bfrtip',
            // buttons: [
            //     'pageLength',
            //     'copy',
            //     {
            //         extend: 'csvHtml5',
            //         title: title
            //     },
            //     {
            //         extend: 'excelHtml5',
            //         title: title
            //     },
            //     {
            //         extend: 'pdfHtml5',
            //         orientation: 'landscape',
            //         pageSize: 'LEGAL',
            //         title: title
            //     }, 'print',
            // ],
            columns: [
                // {
                //     data: "ticketno"
                // },
                // {
                //     data: "cusname"
                // },
                // {
                //     data: "mobile"
                // },
                // {
                //     data: "email"
                // },
                // {
                //     data: "My3Numbers"
                // },
                // {
                //     data: "proamt"
                // },
                // {
                //     data: "transaction_id"
                // },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.order;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.question;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.answer;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return '<a class="btn text-danger1 btn-sm" href="faq/' + data.id + '" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-edit fs-14""></span></a><a class="btn text-danger btn-sm" data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-trash-2 fs-14" onclick="deleteinfo(' + data.id + ')"></span></a>';
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return ''
                    }
                }
            ],

            // footerCallback: function(row, data, start, end, display) {
            //     var api = this.api();

            //     // Remove the formatting to get integer data for summation
            //     var intVal = function(i) {
            //         return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            //     };

            //     // Total over all pages
            //     total = api
            //         .column(5)
            //         .data()
            //         .reduce(function(a, b) {
            //             return intVal(a) + intVal(b);
            //         }, 0);

            //     // Total over this page
            //     pageTotal = api
            //         .column(5, {
            //             page: 'current'
            //         })
            //         .data()
            //         .reduce(function(a, b) {
            //             return intVal(a) + intVal(b);
            //         }, 0);

            //     // Update footer
            //     $(api.column(5).footer()).html('' + pageTotal + ' ( ' + total + ' total)');
            // },
        });
        // } else {
        //     toast('error', 'Please Select Draw');
        // }

    }

    function faqsub() {

        //    alert('hi');
        let orderno = $('#order1').val();

        let question = $('#ques').val();

        let answers = $('#Answ').val();

        if ($('#order1').val().trim().length < 1 || $('#order1').val().trim() == '') {
            toast('warning', 'Kindly select order no');
            return false;
        }

        if ($('#ques').val().trim().length < 1 || $('#ques').val().trim() == '') {
            toast('warning', 'Kindly Enter the question');
            return false;
        }

        if ($('#Answ').val().trim().length < 1 || $('#Answ').val().trim() == '') {
            toast('warning', 'Kindly Enter the answer');
            return false;
        }

        var formdata = [];

        formdata.push({

            name: 'method',

            value: "faqinsert"

        });

        formdata.push({

            name: 'orderno',

            value: orderno

        });

        formdata.push({

            name: 'question',

            value: question

        });

        formdata.push({

            name: 'answers',

            value: answers

        });

        var post_data = formdata;
        var btn = document.getElementById('gcBtn').innerHTML;
        // document.getElementById('gcBtn').innerHTML = '<button class="btn btn-info" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';
        var onsuccess = function(data) {

            var response = JSON.parse(data);
            console.log(response);

            if (response != "") {

                if (response.type == 1) {
                    document.getElementById('gcBtn').innerHTML = btn;
                    paymentSuccess('success', response.result);
                    //location.reload();

                } else {
                    document.getElementById('gcBtn').innerHTML = btn;
                    toast('error', response.result);

                }

            }

        }

        do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/faq_services.php");

    }


    function faqupdate(id) {

        //    alert('hi');
        let orderno = $('#order1').val();

        let question = $('#ques').val();

        let answers = $('#Answ').val();

        if ($('#order1').val().trim().length < 1 || $('#order1').val().trim() == '') {
            toast('warning', 'Kindly select order no');
            return false;
        }

        if ($('#ques').val().trim().length < 1 || $('#ques').val().trim() == '') {
            toast('warning', 'Kindly Enter the question');
            return false;
        }

        if ($('#Answ').val().trim().length < 1 || $('#Answ').val().trim() == '') {
            toast('warning', 'Kindly Enter the answer');
            return false;
        }

        var formdata = [];

        formdata.push({

            name: 'method',

            value: "faqupdate1"

        });

        formdata.push({

            name: 'id',

            value: id

        });

        formdata.push({

            name: 'orderno',

            value: orderno

        });

        formdata.push({

            name: 'question',

            value: question

        });

        formdata.push({

            name: 'answers',

            value: answers

        });

        var post_data = formdata;
        var btn = document.getElementById('gcBtn').innerHTML;
        // document.getElementById('gcBtn').innerHTML = '<button class="btn btn-info" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';
        var onsuccess = function(data) {

            var response = JSON.parse(data);
            console.log(response);

            if (response != "") {

                if (response.type == 1) {
                    document.getElementById('gcBtn').innerHTML = btn;
                    paymentSuccess('success', response.result);
                    // location.reload();
                    /*var s = setInterval(function () {
                        window.location.href="https://admin.littledraw.net/faq/";
                            clearInterval(s);
                        },2000);*/

                } else {
                    document.getElementById('gcBtn').innerHTML = btn;
                    toast('error', response.result);

                }

            }

        }

        do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/faq_services.php");

    }




    function deleteinfo(id) {
        Swal.fire({
            title: 'Are you sure ? you want to delete data ?',
            icon: 'warning',
            showDenyButton: true,
            confirmButtonText: 'Yes',
            denyButtonText: 'No'
        }).then((result) => {
            if (result['isConfirmed']) {
                var formdata = [];
                formdata.push({
                    name: 'method',
                    value: "delete_result_now"
                });

                formdata.push({

                    name: 'id',

                    value: id

                });


                var post_data = formdata;

                var onsuccess = function(data) {



                    var response = JSON.parse(data);
                    console.log(response)
                    if (response != "") {
                        paymentSuccess('success', response.result);

                        /*document.getElementById('dele').innerHTML = response.result;
            
                    $('#deleteinfo').modal('show');*/



                    } else {
                        paymentSuccess('danger', response.result);
                        /*document.getElementById('dele').innerHTML = response.result;
            
                    $('#deleteinfo').modal('show');*/

                    }

                }



                do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/faq_services.php");
            }
        })

    }


    function refersh() {



        location.reload();

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
                window.location.href = origin + "/faq";

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
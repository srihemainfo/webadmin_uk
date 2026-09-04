
<!--1.modifications unknown-->
<!--ajaxfile: ajax/service/video_services.php-->

Date      Developer_name      Modifications

-->

<style>
    .nav-item .nav-link,

    .nav-tabs .nav-link {

        -webkit-transition: all 300ms ease 0s;

        -moz-transition: all 300ms ease 0s;

        -o-transition: all 300ms ease 0s;

        -ms-transition: all 300ms ease 0s;

        transition: all 300ms ease 0s;

    }



    .card-Tab a {

        -webkit-transition: all 150ms ease 0s;

        -moz-transition: all 150ms ease 0s;

        -o-transition: all 150ms ease 0s;

        -ms-transition: all 150ms ease 0s;

        transition: all 150ms ease 0s;

    }



    [data-toggle="collapse"][data-parent="#accordion"] i {

        -webkit-transition: transform 150ms ease 0s;

        -moz-transition: transform 150ms ease 0s;

        -o-transition: transform 150ms ease 0s;

        -ms-transition: all 150ms ease 0s;

        transition: transform 150ms ease 0s;

    }



    [data-toggle="collapse"][data-parent="#accordion"][aria-expanded="true"] i {

        filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2);

        -webkit-transform: rotate(180deg);

        -ms-transform: rotate(180deg);

        transform: rotate(180deg);

    }





    .now-ui-icons {

        display: inline-block;

        font: normal normal normal 14px/1 'Nucleo Outline';

        font-size: inherit;

        speak: none;

        text-transform: none;

        -webkit-font-smoothing: antialiased;

        -moz-osx-font-smoothing: grayscale;

    }



    @-webkit-keyframes nc-icon-spin {

        0% {

            -webkit-transform: rotate(0deg);

        }



        100% {

            -webkit-transform: rotate(360deg);

        }

    }



    @-moz-keyframes nc-icon-spin {

        0% {

            -moz-transform: rotate(0deg);

        }



        100% {

            -moz-transform: rotate(360deg);

        }

    }



    @keyframes nc-icon-spin {

        0% {

            -webkit-transform: rotate(0deg);

            -moz-transform: rotate(0deg);

            -ms-transform: rotate(0deg);

            -o-transform: rotate(0deg);

            transform: rotate(0deg);

        }



        100% {

            -webkit-transform: rotate(360deg);

            -moz-transform: rotate(360deg);

            -ms-transform: rotate(360deg);

            -o-transform: rotate(360deg);

            transform: rotate(360deg);

        }

    }



    .now-ui-icons.objects_umbrella-13:before {

        content: "\ea5f";

    }



    .now-ui-icons.shopping_cart-simple:before {

        content: "\ea1d";

    }



    .now-ui-icons.shopping_shop:before {

        content: "\ea50";

    }



    .now-ui-icons.ui-2_settings-90:before {

        content: "\ea4b";

    }



    .nav-tabs {

        border: 0;

        padding: 15px 0.7rem;

    }



    .nav-tabs:not(.nav-tabs-neutral)>.nav-item>.nav-link.active {

        box-shadow: 0px 0px 11px 0px rgb(0 0 0 / 30%);

    }



    .card .nav-tabs {

        border-top-right-radius: 0.1875rem;

        border-top-left-radius: 0.1875rem;

    }



    .nav-tabs>.nav-item>.nav-link {

        color: #888888;

        margin: 0;

        margin-right: 5px;

        background-color: transparent;

        /* border: 1px solid transparent; */

        border-radius: 30px;

        font-size: 14px;

        padding: 11px 23px;

        line-height: 1.5;

    }



    .nav-tabs>.nav-item>.nav-link:hover {

        background-color: #f0f0f5;

        color: #8692a9;

    }



    .nav-tabs>.nav-item>.nav-link.active {

        background-color: #1f68af !important;

        border-radius: 30px;

        color: #FFFFFF;

    }



    .nav-tabs>.nav-item>.nav-link i.now-ui-icons {

        font-size: 14px;

        position: relative;

        top: 1px;

        margin-right: 3px;

    }



    .nav-tabs.nav-tabs-neutral>.nav-item>.nav-link {

        color: #FFFFFF;

    }



    .nav-tabs.nav-tabs-neutral>.nav-item>.nav-link.active {

        background-color: rgba(255, 255, 255, 0.2);

        color: #FFFFFF;

    }



    .card-Tab {

        border: 0;

        border-radius: 0.1875rem;

        display: inline-block;

        position: relative;

        width: 100%;

        margin-bottom: 30px;



    }



    .card .card-header {

        background-color: transparent;

        border-bottom: 0;

        background-color: transparent;

        border-radius: 12px;

        padding: 0;

    }



    .card-Tab[data-background-color="orange"] {

        background-color: #f96332;

    }



    .card-Tab[data-background-color="red"] {

        background-color: #FF3636;

    }



    .card-Tab[data-background-color="yellow"] {

        background-color: #FFB236;

    }



    .card-Tab[data-background-color="blue"] {

        background-color: #2CA8FF;

    }



    .card-Tab[data-background-color="green"] {

        background-color: #15b60d;

    }



    [data-background-color="orange"] {

        background-color: #e95e38;

    }



    [data-background-color="black"] {

        background-color: #2c2c2c;

    }



    [data-background-color]:not([data-background-color="gray"]) {

        color: #FFFFFF;

    }



    [data-background-color]:not([data-background-color="gray"]) p {

        color: #FFFFFF;

    }



    [data-background-color]:not([data-background-color="gray"]) a:not(.btn):not(.dropdown-item) {

        color: #FFFFFF;

    }



    [data-background-color]:not([data-background-color="gray"]) .nav-tabs>.nav-item>.nav-link i.now-ui-icons {

        color: #FFFFFF;

    }





    /* @font-face {

		font-family: 'Nucleo Outline';

		src: url("https://github.com/creativetimofficial/now-ui-kit/blob/master/assets/fonts/nucleo-outline.eot");

		src: url("https://github.com/creativetimofficial/now-ui-kit/blob/master/assets/fonts/nucleo-outline.eot") format("embedded-opentype");

		src: url("https://raw.githack.com/creativetimofficial/now-ui-kit/master/assets/fonts/nucleo-outline.woff2");

		font-weight: normal;

		font-style: normal;



	} */



    .now-ui-icons {

        display: inline-block;

        font: normal normal normal 14px/1 'Nucleo Outline';

        font-size: inherit;

        speak: none;

        text-transform: none;

        /* Better Font Rendering */

        -webkit-font-smoothing: antialiased;

        -moz-osx-font-smoothing: grayscale;

    }









    @media screen and (max-width: 768px) {



        .nav-tabs {



            text-align: center;

        }



        .nav-tabs .nav-item>.nav-link {

            margin-bottom: 5px;

        }

    }



    .card-Tab-header {

        background: #fff;

        border-bottom: 2px solid #f0f0f5;

        border-radius: 7px 7px 0 0;

    }



    .card-result {

        position: relative;

        display: -ms-flexbox;

        display: flex;

        -ms-flex-direction: column;

        flex-direction: column;

        min-width: 0;

        word-wrap: break-word;

        border: inherit !important;

        border-radius: 0;

        background: #fff;

    }



    h1.page-title {

        color: #555555;

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



    input,

    select {

        border: 1px solid #CCC;

    }



    button {

        color: #FFF;

        background-color: #428BCA;

        border: 1px solid #357EBD;

    }



    .mdtext {

        font-weight: 600;

    }



    .input101 {

        width: 100%;

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

<?php
// if (isset($_POST['videoURL']) && !empty($_POST['videoURL'])) {
//    

//     $winnerresult = array("draw_id" => $_POST[draw_result_url], "youtube_url" => $_POST[video_url]);

//     $aticket = insert($con, "past_video_url", "", $winnerresult, "", "", "");

//     

// }

?>
<script>

window.onload = function(){

var page_origin = window.location.origin;

let anchor =  document.getElementById("anchor");

anchor.href = page_origin;

}
</script>










<html>







<body>

    <div class="main-content app-content mt-0">

        <div class="side-app">

            <div class="main-container container-fluid">



                <div class="page-header">

                    <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Youtube Video Upload</h1>

                    <div>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                            <li class="breadcrumb-item active" aria-current="page">Youtube Video Upload</li>

                        </ol>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-12 m-auto">



                        <div class="card-Tab">



                            <div class="card-Tab-body">

                                 <!--Tab panes -->



                                <div class="tab-content">

                                    <div class="tab-pane active" id="home">

                                        <div class="card-result">

                                            <div class="card-body">





                                                <fieldset>

                                                    <span>Select Draw</span>&nbsp;<span style="color: red;">*</span>

                                                    <select id="draw_result_url" name="draw_result_url" class="form-select">

                                                        <option value="">Select Draw</option>

                       <?php
                                        $draw = select_query($con, "draw", "", "`dailyThirllStatus` != 'Pending' and `deletes`='0' ORDER BY `id` DESC ", "", "");
                                        
                                        if ($draw['nr'] > 0) {
                                            foreach ($draw['result'] as $key => $value) {
                                                $naeme = explode("#", $value['name']);
                                                $displayValue = 'Draw No #' . str_pad($value['dailyDrawNo'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($value['resultDate'])) . ' ' . str_replace("Draw", "", $naeme[0]) . ' (' . date("D", strtotime($value['resultDate'])) . ')';
                                                // Skip rendering the option if the display value is empty
                                                if (!empty(trim($displayValue))) {
                                        ?>
                                                    <option value="<?= $value['id']; ?>"><?= $displayValue; ?></option>
                                        <?php
                                                }
                                            }
                                        }
                                        ?>



                                                    </select>

                                                    <br><br>

                                                    <span>Video URL Upload:</span>&nbsp;<span style="color: red;">*</span>

                                                    <br>



                                                    <input type="text" class="form-control" id="video_url" name="video_url" required>



                                                    <br>





                                                    <input type="submit" class="btn btn-info" onclick="videoURL()" name="videoURL" value="Submit">







                                                </fieldset>













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



        <div class="row mx-4">

            <div class="col-lg-12">

                <div class="card-result">

                    <div class="card-header">

                        <div class="col-lg-4">

                            <h3 class="card-title"><strong>Youtube Video Upload</strong></h3>

                        </div>





                        <div class="col-lg-8">

                            <input type="date" name="formdate" id="formdate" value="<?php echo date("Y-m-d"); ?>">

                            <button onclick="viewtable()">GO</button>

                        </div>





                    </div>



                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-bordered text-nowrap border-bottom" id="videoupload" style="width:100%;">

                                <thead>

                                    <tr>

                                        <th class="wd-15p border-bottom-0">Date</th>

                                        <th class="wd-15p border-bottom-0"> Draw</th>

                                        <th class="wd-15p border-bottom-0">Video URL</th>



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



     delete modul 



    <div class="modal fade" id="deleteinfo" tabindex="-1" role="dialog" aria-labelledby="customModalLabel" aria-modal="true">

        <div class="modal-dialog" role="document">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="customModalLabel">Delete</h5> <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>

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





     <script src='https://code.jquery.com/jquery-3.3.1.slim.min.js'></script>

    <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js'></script> -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



    <script>
        var origin = window.location.origin;

        var ajax_url = origin + "/ajax/service/video_services.php";



        $(function() {

            viewtable();



        });



        function viewtable() {

            var table = $('#videoupload').DataTable();

            table.destroy();

            var formdata = [];

            formdata.push({

                name: 'method',

                value: "video_list"

            });





            let formdate = $('#formdate').val();





            formdata.push({

                name: 'formdate',

                value: formdate

            });







            var post_data = formdata;

            var onsuccess = function(data) {



                var response = JSON.parse(data);

                if (response != "") {

                    if (response.type == 1) {









                        $('#videoupload').DataTable({

                            order: [

                                [0, 'asc']

                            ],

                            dom: 'Bfrtip',

                            buttons: [

                                'copy', 'csv', 'excel', 'pdf', 'print'

                            ],

                            "data": response.result,

                            "columns": [ {
                                            'data': 'date',
                                            'render': function (data, type, row) {
                                                return moment(data).format('YYYY-MM-DD');
                                            }
                                        },


                                {

                                    'data': 'drawid'

                                },



                                {

                                    'data': 'url'

                                },







                                {

                                    'data': null,

                                    render: function(data, type, row) {



                                        return ' <a class="btn text-danger btn-sm" data-bs-target="#deleteinfo" data-bs-toggle="modal" data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-trash-2 fs-14" onclick="deleteinfo(' + data.id + ')"></span></a>'





                                    }

                                }

                            ],

                        });





                    } else {

                        var table = $('#videoupload').DataTable();

                        table.clear().draw();

                    }



                }

            }



            do_ajax_call(post_data, onsuccess, ajax_url);



        }







        function deleteinfo(id) {



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

                if (response != "") {



                    document.getElementById('dele').innerHTML = response.result;

                    $('#deleteinfo').modal('show');
                    window.reload();



                } else {

                    document.getElementById('dele').innerHTML = response.result;

                    $('#deleteinfo').modal('show');

                }

            }



            do_ajax_call(post_data, onsuccess, ajax_url);



        }





        function refersh() {



            location.reload();

        }





     function videoURL() {
    var formdata = [];
    formdata.push({
        name: 'method',
        value: "URL_insert"
    });

    let drawresult = $('#draw_result_url').val();
    let videourl = $('#video_url').val();

    if (videourl.trim() === '') {
        toast('error', 'Video url field is required');
        return;
    }
    if (drawresult.trim() === '') {
        toast('error', 'Please select a draw');
        return;
    }
    if (videourl.indexOf('"') === -1) {
        formdata.push({
            name: 'drawresult',
            value: drawresult
        });
        formdata.push({
            name: 'videourl',
            value: videourl
        });

        var post_data = formdata;
        var onsuccess = function(data) {
            var response = JSON.parse(data);
            if (response != "") {
                if (response.type == 1) {
                    toast('success', 'Upload Successfully');
                }
                location.reload();
            }
        }

        do_ajax_call(post_data, onsuccess, ajax_url);
    } else {
        toast('error', 'Please do remove the "Quotes" before and after the link');
    }
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



</body>





</html>
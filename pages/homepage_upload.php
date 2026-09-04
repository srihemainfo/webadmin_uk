<style>

/*
Date         Developer_name      Modifications
04-02-2023    Sathiya            Total Winners & Prizes Upto Date dynamic upload image function working



*/


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
</style>
<?php















if (isset($_POST['home_imageup'])) {

    $userid = $_SESSION['memid'];
    echo $userid;


    if (isset($_FILES['home_pohto'])) {


        $allowed = array('jpg', 'jpeg', 'tif', 'tiff', 'png','webp');

        $file_name = $_FILES['home_pohto']['name'];
        $file_type = $_FILES['home_pohto']['type'];
        $file_size = $_FILES['home_pohto']['size'];
        $file_tem_loc = $_FILES['home_pohto']['tmp_name'];

        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        var_dump($ext);
        $name = "";

        if ("jpg" == $ext) {
            $name .= str_replace(".jpg", "", $file_name) . $userid . date("Ymdhms");
        } else if ("jpeg" == $ext) {
            $name .= str_replace(".jpeg", "", $file_name) . $userid . date("Ymdhms");
        } else if ("tif" == $ext) {
            $name .= str_replace(".tif", "", $file_name) . $userid . date("Ymdhms");
        } else if ("tiff" == $ext) {
            $name .= str_replace(".tiff", "", $file_name) . $userid . date("Ymdhms");
        } else if ("png" == $ext) {
            $name .= str_replace(".png", "", $file_name) . $userid . date("Ymdhms");
        } else if ("webp" == $ext) {
            $name .= str_replace(".webp", "", $file_name) . $userid . date("Ymdhms");
        }

        $file_store = "";
        $path = "";

        if (in_array($ext, $allowed)) {
            mkdir("/home/nationaldraw/public_html/assets/homeimg/" . $userid, 0755);
            mkdir("/home/nationaldraw/public_html/assets/homeimg/" . $userid . "/img", 0755);

            $path = "/home/nationaldraw/public_html/assets/homeimg/" . $userid . "/img";

            $file_store = $path . '/' . uniqid() . $userid . date("Ymdhms") . '.' . $ext;
        }
        $fileNEW = trim(str_replace("/home/nationaldraw/public_html/", "", $file_store));



        if (move_uploaded_file($file_tem_loc, $file_store)) {

            $home_result = array("draw_id" => $_POST['draw_result'], "image_url" =>  $fileNEW, "product_id" =>  $_POST['Participated_result'], "createdon" => $dubaidate_time);

            $home_images = insert($con, "homeview_images", "", $home_result, "", "", "");
            $errors = $aticket['errors'];
            if ($errors != "") {
                divert($adminurl . "homeviewupload/list/" . 'failed');
            } else {
                divert($adminurl . "homeviewupload/list/" . 'success');
            }
        }
    } else {
        divert($adminurl . "homeviewupload/list/" . 'failed');
    }
}








?>





<html>

<!-- Design Structure Forms -->

<body>
    <script src="../../js/common.js"></script>

    <div class="main-content app-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <div class="page-header">
                    <h1 class="page-title">Home Image Uplaod</h1>
                    <div>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Result Uplaod</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-auto">
                        <?php
                        if ($subid3 == 'success') {
                        ?>
                            <div class="alert alert-success" role="alert">
                                Submited successfully
                            </div>
                        <?php
                        }
                        ?>
                        <?php
                        if ($subid3 == 'failed') {
                        ?>
                            <div class="alert alert-danger" role="alert">
                                FAILED!
                            </div>
                        <?php
                        }
                        ?>
                        <div class="tab-pane" id="profile">
                            <div class="card-result">
                                <?php
                                if ($subid3 == 'success1') {
                                ?>
                                    <div class="alert alert-success" role="alert">
                                        Submited successfully
                                    </div>
                                <?php
                                }
                                ?>
                                <?php
                                if ($subid3 == 'failed1') {
                                ?>
                                    <div class="alert alert-danger" role="alert">
                                        FAILED!
                                    </div>
                                <?php
                                }
                                ?>
                                <div class="card-body">

                                    <form action="" method="post" enctype="multipart/form-data">

                                        <fieldset>

                                            <span>Select Draw</span>&nbsp;<span style="color: red;">*</span>
                                            <select id="draw_result" name="draw_result" class="form-select">
                                                <option value="">Select Draw</option>
                                                <?php
                                                $draw = select_query($con, "draw", "", "`status` != 'Pending' and `deletes`='0' ORDER BY `id` DESC ", "", "");
                                                if ($draw['nr'] > 0) {
                                                    foreach ($draw['result'] as $key => $value) {
                                                ?>
                                                        <option value="<?= $value['name']; ?>"><?= $value['name']; ?></option>
                                                <?php

                                                    }
                                                }
                                                ?>
                                            </select>
                                            <br><br>

                                            <span>Image:</span>&nbsp;<span style="color: red;">* (Uplaod size : *1200 × 673 px)</span>
                                            <br>

                                            <input type="file" class="form-control" name="home_pohto" required>

                                            <br>
                                            <span>Image Orders:</span>&nbsp;<span style="color: red;">*</span>
                                            <select id="Participated_result" name="Participated_result" class="form-select">
                                                <option value="">Select Category</option>
                                                <option value="1">Total Prize Winners</option>

                                            </select>

                                            <br>
                                            <!-- <br>
                                            <span>Upload Image Orders:</span>&nbsp;<span style="color: red;">*</span>
                                            <select id="Order_image" name="Order_image" class="form-select">
                                                <option value="">Select Category</option>
                                                <option value="1">First_Image</option>
                                                <option value="2">Second_image</option>
                                                <option value="3">Third__image</option>
                                                <option value="4">Fourth_image</option>
                                                <option value="5">Fifth_image</option>

                                            </select>


                                            <br> -->


                                            <br>

                                            <input type="submit" class="btn btn-info" name="home_imageup" value="Submit">



                                        </fieldset>

                                    </form>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>
            </div>

        </div>
        <br>
        <br>
        <!-- Data table -->
        <div class="row mx-4">
            <div class="col-lg-12">
                <div class="card-result">
                    <div class="card-header">
                        <div class="col-lg-4">
                            <h3 class="card-title"><strong>Home Image Uplaod</strong></h3>
                        </div>


                        <div class="col-lg-8">
                            <input type="date" name="formdate" id="formdate" value="<?php echo date("Y-m-d"); ?>">
                            <button onclick="viewtable()">GO</button>
                        </div>


                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap border-bottom" id="homeimageupload" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th class="wd-15p border-bottom-0">Date&Time</th>
                                        <th class="wd-15p border-bottom-0">Draw</th>
                                        <th class="wd-15p border-bottom-0">Image</th>
                                        <th class="wd-15p border-bottom-0">product</th>
                                        <!-- <th class="wd-15p border-bottom-0">Uplaod Image Orders</th> -->
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
    <!-- delete modal -->

    <div class="modal fade" id="deleteinfo" tabindex="-1" role="dialog" aria-labelledby="customModalLabel" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customModalLabel">Delete</h5> <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                </div>
                <div class="modal-body">
                    <div class="text-center" id="dele">


                    </div>
                </div>
                <div class="modal-footer custom">

                    <div class="divider"></div>
                    <div class="right-side">

                        <button aria-label="Close" class="btn btn-danger pd-x-25 success" onclick="refersh()" data-bs-dismiss="modal">Clsoe</button>


                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Preview modal -->
    <div class="modal fade" id="previewModal1" tabindex="-1" role="dialog" aria-labelledby="customModalLabel" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customModalLabel">Preview</h5> <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                </div>
                <div class="modal-body" style="height: 300px">
                    <div id="preimg">
                        <img src="" id="preimag">
                        <!-- <a href="" id="preimag"></a> -->
                    </div>
                </div>
                <div class="modal-footer custom">
                    <div class="left-side">


                    </div>

                </div>
            </div>
        </div>
    </div>




    <script>
        var origin = window.location.origin;
        var ajax_url = origin + "/ajax/service/image_services.php";

        $(function() {
            viewtable();

        });

        function viewtable() {
            var table = $('#homeimageupload').DataTable();
            table.destroy();
            var formdata = [];
            formdata.push({
                name: 'method',
                value: "homeupimage_list"
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




                        $('#homeimageupload').DataTable({
                            order: [
                                [0, 'asc']
                            ],
                            dom: 'Bfrtip',
                            buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                            ],
                            "data": response.result,
                            "columns": [{
                                    'data': 'date'
                                },
                                {
                                    'data': 'drawid'
                                },
                                {
                                    'data': 'url'
                                },
                                {
                                    'data': 'product'
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
                        var table = $('#homeimageupload').DataTable();
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
                value: "delete_homeimage_now"
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


        function preview(url) {
            $('#previewModal1').modal('show');
            $("#preimag").attr("src", url);
            $("#preimag").load();
            // document.getElementById('preimg').innerHTML = '<a href="'+url+'"></a>';
        }
    </script>
</body>


</html>
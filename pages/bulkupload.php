<!--

1.modifications unknown


Date      Developer_name      Modifications


-->

<style>

    button.btn.btn-primary.uploade {

        margin-top: 21px;

        margin-bottom: 20px;

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

window.onload = function(){

var page_origin = window.location.origin;

let anchor =  document.getElementById("anchor");

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

                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Blukupload</h1>

                <div>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page">Blukupload</li>

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

                                    <div class="d-flex">

                                        <div class="mt-2">

                                            <div id="err"></div>

                                            <input type="hidden" id="bulkData">

                                            <input type="file" name="fileupload" id="fileupload">

                                            <div id="upbtn">

                                                <button onclick="move_new_file()" class="btn btn-primary uploade">Upload</button>

                                               

                                            </div>

                                            <p>Uploading file format</p>

                                            <a href="<?= $adminurl . 'xlsx/format.xlsx'; ?>" download>

                                                <button class="btn"><i class="fa fa-download"></i> Download</button>

                                            </a>





                                        </div>

<div>



    <select  id="draw_new_id" class="form-select">

    <option value="">Select Draw</option> 

        <?php

$draw = select_query($con, "draw", "", "`status` != 'Pending' and `deletes`='0' ", "", "");

if ($draw['nr'] > 0) {

    foreach ($draw['result'] as $key => $value) {

?>

 <option value="<?= $value['id']; ?>"><?= $value['name']; ?></option> 

<?php



    }

}

?>

       

    </select>

</div>

                                    </div>

                                </div>

                            </div>

                        </div>







                    </div>

                </div>

            </div>

            <!-- ROW-1 END -->











            <!-- ROW-4 -->

            <div class="row">

                <div class="col-12 col-sm-12">

                    <div class="card">

                        <div class="card-header">

                            <h3 class="card-title mb-0" id="tabtitle">Blukupload List</h3>

                        </div>

                        <div class="card-body pt-4">

                            <div class="grid-margin">

                                <div class="">

                                    <div class="panel panel-primary">

<div id="privewerr">



</div>

                                        <div id="priviewData">



                                        </div>







                                    </div>

                                </div>

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







<div class="modal fade" id="Bluk_success">

	<div class="modal-dialog modal-sm" role="document">

		<div class="modal-content modal-content-demo">

			<div class="modal-header">

				<h6 class="modal-title">Success</h6>

				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">×</span>

				</button>

			</div>

			<div class="modal-body">

				<div id="successerror2">



				</div>

			</div>

			<div class="modal-footer">

				<button class="btn ripple btn-danger" onclick="pageload()" data-bs-dismiss="modal" type="button">Close</button>

			</div>

		</div>

	</div>

</div>



<script>

    var origin = window.location.origin;

    var URL = origin + "/xlsx/file.php?";



    function move_new_file() {

        document.getElementById('err').innerHTML = '';

        document.getElementById('priviewData').innerHTML = '';

        document.getElementById('privewerr').innerHTML = '';

        document.getElementById('bulkData').value = '';

        let draw_new_id = document.getElementById('draw_new_id').value;

        if(draw_new_id != ''){



        

        let file = document.getElementById('fileupload').value;

        if (file != '') {

            document.getElementById('upbtn').innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';





            var formData = new FormData();



            formData.append('method', 'move_file');

            formData.append('mfile', fileupload.files[0]);

            formData.append('draw_new_id', draw_new_id);

            $.ajax({

                url: URL,

                type: 'post',

                data: formData,



                success: function(data) {

                    var response = JSON.parse(data);

                    if (response != "") {

                        if (response.type == 1) {

                            document.getElementById('priviewData').innerHTML = response.output;

                            document.getElementById('bulkData').value = response.myobj;

                            // console.log(JSON.parse(response.myobj));

                            document.getElementById('upbtn').innerHTML = ' <button onclick="move_new_file()" class="btn btn-primary">Upload</button>';



                        } else {

                            document.getElementById('upbtn').innerHTML = ' <button onclick="move_new_file()" class="btn btn-primary">Upload</button>';

                            document.getElementById('privewerr').innerHTML = '<div class="alert alert-danger" role="alert">'+ response.result+'</div>';



                            // console.log(response);

                        }

                    }



                },

                processData: false,

                contentType: false

            });

        } else {

            document.getElementById('err').innerHTML = '<div class="alert alert-danger" role="alert">Select the file.</div>';

        }

    } else {

            document.getElementById('err').innerHTML = '<div class="alert alert-danger" role="alert">Select the draw.</div>';

        }

    }





    function bulk_insert() {

        document.getElementById('submetbtn').innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';

        var success = [];

        var data = JSON.parse(document.getElementById('bulkData').value);

        var len = Object.keys(data).length;

        // console.log(len);



        for (let i = 0; i < len; i++) {

            let ticketid = data[i]['ticketid'];

            let linescount = parseInt(data[i]['linescount']) + 1;

            let drawid = data[i]['drawid'];

            let myUser = data[i]['mobile'];

            let inform = data[i]['inform'];

            let purdata = data[i]['purdata'];

            var formdata = $('#mticket').serializeArray();

            formdata.push({

                name: 'method',

                value: "blukregisteroffline"

            });

            formdata.push({

                name: 'ticketnumber',

                value: ticketid

            });

            formdata.push({

                name: 'count',

                value: linescount

            });

            formdata.push({

                name: 'drawid',

                value: drawid

            });



            formdata.push({

                name: 'myUser',

                value: myUser

            });



            formdata.push({

                name: 'inform',

                value: inform

            });

            formdata.push({

                name: 'purdata',

                value: purdata

            });



            for (let r = 0; r < linescount; r++) {

                // console.log(data[i]['data']['my3number' + r]);

                let n = r + 1;

                // let no = parseInt(data[i]['data']['ticketid' + r]) + 1;



                formdata.push({

                    name: 'my3number' + n,

                    value: data[i]['data']['my3number' + r]

                });

                formdata.push({

                    name: 'productid' + n,

                    value: data[i]['data']['productid' + r]

                });



                formdata.push({

                    name: 'ticketid' + n,

                    value: data[i]['data']['ticketid' + r]

                });



            }







            var post_data = formdata;



            var onsuccess = function(data) {

                var response = JSON.parse(data);

                if (response != "") {

                    success.push(response.type);

                    if (success.length == len) {

                        document.getElementById('submetbtn').innerHTML = '<button class="btn btn-primary" onclick="bulk_insert()">Submit</button>';

                        // location.reload();

                        document.getElementById('successerror2').innerHTML = '<div class="alert alert-success" role="alert">Uploaded Successfully</div>';

                        $('#Bluk_success').modal('show');

                    }

                    if (response.type == 1) {



                    } else {



                    }

                }

            }

            do_ajax_call(post_data, onsuccess);







        }



    }







    function myCheckbox(id) {

        console.log(id);

    }







    function pageload(){

        location.reload();

    }

</script>
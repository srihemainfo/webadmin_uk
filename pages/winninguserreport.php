<?php

/**
 * Date             Developer              Modification
 * 9-6-2023         Prakash                 CDNs Removed
 */
$pageTitle = "Balance Sheet";

?>



<style>
    table.jexcel.jexcel_overflow {
        width: 100%;
    }

    .positive-value {
        color: green !important;
    }

    .negative-value {
        color: red !important;
    }

    .jexcel_toolbar i.jexcel_toolbar_item {
        margin: 0 12px;
    }

    .jexcel_toolbar {
        padding: 4px 10px 10px 10px;
    }

    input,
    select {
        border: 1px solid #ccc
    }

    button,
    input {
        height: 35px;
        margin: 0;
        padding: 6px 12px;
        border-radius: 2px;
        font-family: inherit;
        font-size: 100%;
        color: inherit
    }

    .jexcel>tbody>tr>td.readonly {
        color: #000;
    }

    .back-arrow-btn i {
        background: #fff;
        font-size: 16px;
        padding: 2px 3px;
        border-radius: 50px;
        border: 2px solid #6c6e70;
        color: #6c6e70;
        margin-right: 15px;
        width: 24px;
        height: 24px
    }

    .stickyFooter {
        position: sticky;
        bottom: 0;
        background-color: rgb(255, 250, 163);
        z-index: 2;
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

                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a><?= ucwords($pageTitle); ?></h1>

                <div>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page"><?= ucwords($pageTitle); ?></li>

                    </ol>

                </div>

            </div>

            <!-- PAGE-HEADER END -->








            <div class="row row-sm">

                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <div class="row">


                                <div class="col-lg-7 col-sm-10 col-md-8 text-start">
                                    <h3 class="card-title"><strong>Balance Sheet</strong></h3>
                                </div>
                                <div class="col-lg-5 col-sm-2 col-md-4 text-end">
                                    <button class="btn btn-info" id="loadBtn" onclick="winner_list()">GO</button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive">
                                    <div class="col-12">
                                        <div id="spreadsheetErr"></div>
                                        <div id="spreadsheet"></div>
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





<script>
    (function() {

        winner_list();

    })();

    const SUMCOL = (instance, columnId) => {
        // console.log(columnId);
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId].innerHTML)) {
                total += Number(instance.records[j][columnId].innerHTML);
            }
        }

        return total;

    }

    // const COLORIZE = (color, value) => {
    //     return '<span style="color:' + color + '">' + value + '</span>';

    // }

    function winner_list() {

        var formdata = [];

        formdata.push({

            name: 'method',

            value: "winner_list_services"

        });

        var tableElement = document.getElementById('spreadsheet');

        if (tableElement && tableElement.jexcel) {
            tableElement.jexcel.destroy();
        }

        var post_data = formdata;
        $('#spreadsheetErr').html(`<button class="btn btn-primary" style="color: #36894c !important;
            background-color: unset !important;
            border-color: unset !important;border: unset !important;">
            <span class="spinner-border spinner-border-sm"></span>
            Large amounts of data are being collected. This process may take some time. Please avoid refreshing the screen...
        </button>`);
        // document.getElementById('').innerHTML = '';

        $(`#loadBtn`).prop('disabled', true);

        $.ajax({

            type: "POST",

            url: origin + "/ajax/service/salesreportServices.php",
            data: post_data,

            success: function(data) {

                var response = JSON.parse(data);

                if (response != "") {


                    if (response.type == 1) {

                        var result = [];


                        $(`#spreadsheetErr`).html(``);
                        var i = 0;

                        response.data.forEach((draw, index) => {

                            i = index + 1;

                            result.push({
                                // 'name': users.name.trim(),
                                'resultDate': moment(draw.resultDate).format('DD MMMM YYYY'),
                                'saleCount': draw.saleCount,
                                'totalGold': draw.totalGold,
                                'totalGoldPrize': draw.totalGoldPrize,
                                'totalPL': `=B${i}-D${i}`
                                // 'totalPL': `<span style="color:${(draw.totalPL > 0 ? 'green' : 'red')}">${draw.totalPL}</span>`

                            });







                        });


                        result.push({
                            // 'name': users.name.trim(),
                            'resultDate': 'Total',
                            'saleCount': `=SUM(B1:B${i})`,
                            'totalGold': `=SUM(C1:C${i})`,
                            'totalGoldPrize': `=SUM(D1:D${i})`,
                            'totalPL': `=SUM(B1:B${i}) - SUM(D1:D${i})`
                            // 'totalPL': `<span style="color:${(draw.totalPL > 0 ? 'green' : 'red')}">${draw.totalPL}</span>`

                        });

                        // [, , , , ]


                        // document.getElementById('spreadsheet').innerHTML = '';
                        var table = $('#spreadsheet').jexcel({
                            // destroy: true,
                            csvHeaders: true,
                            tableOverflow: true,
                            tableHeight: '500px',
                            data: result,
                            search: true,
                            // lazyLoading: true,
                            // loadingSpin: true,
                            // freezeRows: 100,
                            freezeColumns: 1,
                            columns: [{
                                    type: 'html',
                                    width: '100',
                                    name: 'resultDate',
                                    title: 'Date',
                                    readOnly: true
                                },
                                {
                                    type: 'html',
                                    width: '100',
                                    name: 'saleCount',
                                    title: 'Sale Count',
                                    readOnly: true
                                },
                                {
                                    type: 'html',
                                    width: '100',
                                    name: 'totalGold',
                                    title: 'Total Grams',
                                    readOnly: true
                                },
                                {
                                    type: 'html',
                                    width: '100',
                                    name: 'totalGoldPrize',
                                    title: 'Total Gold Prize',
                                    readOnly: true
                                },
                                {
                                    type: 'html',
                                    width: '100',
                                    name: 'totalPL',
                                    title: 'Profit/Loss',
                                    readOnly: true,
                                    // value: function(instance, cell, col, row, data, sheet) {
                                    //     // Calculate the value based on the values in columns B and D
                                    //     var bValue = parseFloat(sheet.getValue(row, 1)); // Get the value from column B in the current row
                                    //     var dValue = parseFloat(sheet.getValue(row, 3)); // Get the value from column D in the current row
                                    //     return isNaN(bValue) || isNaN(dValue) ? '' : bValue - dValue; // Return empty string if values are not numbers
                                    // }
                                    // onupdate: function(value, cell, row, col, source) {
                                    //     if (parseFloat(value) < 0) {
                                    //         cell.style.color = 'red'; // Set text color to red for negative values
                                    //     } else {
                                    //         cell.style.color = 'green'; // Set text color to green for positive values
                                    //     }
                                    // }
                                },

                            ],
                            updateTable: function(instance, cell, col, row, val, label, cellName) {
                                if (col === 4) { // Assuming 'Profit/Loss' column is at index 4
                                    if (parseFloat(label) > 0) {
                                        cell.style.color = 'green';
                                        // cell.classList.add('positive-value'); // Add class for green color
                                        // cell.classList.remove('negative-value'); // Remove class for red color
                                    } else {
                                        cell.style.color = 'red';
                                        // cell.classList.add('negative-value'); // Add class for red color
                                        // cell.classList.remove('positive-value'); // Remove class for green color
                                    }
                                }



                                if (cell.innerHTML == 'Total') {
                                    cell.parentNode.style.backgroundColor = '#fffaa3';
                                    // cell.parentNode.style.position = 'sticky';
                                    // cell.parentNode.style.bottom = '0';
                                    // cell.parentNode.style.backgroundColor = 'rgb(255, 250, 163)';
                                    // cell.parentNode.style.zIndex = '2';

                                    cell.parentNode.classList.add('stickyFooter');


                                }


                            },

                            // footers: [
                            //     ['Total', '=SUMCOL(TABLE(), 1)', '=SUMCOL(TABLE(), 2)', '=SUMCOL(TABLE(), 3)', '=SUMCOL(TABLE(), 1) - SUMCOL(TABLE(), 3)']
                            // ],

                            // updateTable: function(instance, cell, col, row, val, label, cellName) {
                            //     // if (cell.innerHTML == 'Total') {
                            //     //     cell.parentNode.style.backgroundColor = '#fffaa3';
                            //     // }

                            //     if (col == 4) {
                            //         if (parseFloat(label) > 0) {
                            //             cell.style.color = 'red';
                            //         } else {
                            //             cell.style.color = 'green';
                            //         }
                            //     }
                            // },
                            toolbar: [
                                // {
                                //     type: 'i',
                                //     content: 'undo',
                                //     onclick: function() {
                                //         table.undo();
                                //     }
                                // },
                                // {
                                //     type: 'i',
                                //     content: 'redo',
                                //     onclick: function() {
                                //         table.redo();
                                //     }
                                // },
                                {
                                    type: 'i',
                                    content: 'save',
                                    onclick: function() {
                                        try {
                                            table.download('xlsx', {
                                                filename: 'Balance Report'
                                            });
                                        } catch (error) {
                                            console.error('Download error:', error);
                                        }
                                    }
                                },
                                // {
                                //     type: 'select',
                                //     k: 'font-family',
                                //     v: ['Arial', 'Verdana']
                                // },
                                // {
                                //     type: 'select',
                                //     k: 'font-size',
                                //     v: ['9px', '10px', '11px', '12px', '13px', '14px', '15px', '16px', '17px', '18px', '19px', '20px']
                                // },
                                // {
                                //     type: 'i',
                                //     content: 'format_align_left',
                                //     k: 'text-align',
                                //     v: 'left'
                                // },
                                // {
                                //     type: 'i',
                                //     content: 'format_align_center',
                                //     k: 'text-align',
                                //     v: 'center'
                                // },
                                // {
                                //     type: 'i',
                                //     content: 'format_align_right',
                                //     k: 'text-align',
                                //     v: 'right'
                                // },
                                // {
                                //     type: 'i',
                                //     content: 'format_bold',
                                //     k: 'font-weight',
                                //     v: 'bold'
                                // },
                                {
                                    type: 'color',
                                    content: 'format_color_text',
                                    k: 'color'
                                },
                                {
                                    type: 'color',
                                    content: 'format_color_fill',
                                    k: 'background-color'
                                },
                            ],
                            // footer: true,
                        });


                    } else {
                        $(`#spreadsheetErr`).html(`<div class="alert alert-danger" role="alert">Could Not Get Data</div>`);
                        // document.getElementById('').innerHTML = '';
                    }

                }

            },
            complete: function() {
                $(`#loadBtn`).prop('disabled', false);
            }

        });



        // Event listener for cell value changes
        // table.jexcel('addEventListener', 'onchange', function(instance, cell, col, row, val) {
        //     // Check if the cell is in the 'totalPL' column
        //     if (col === 4) { // Assuming 'Profit/Loss' is the fifth column (index 4)
        //         // Get the cell object
        //         var cellObj = table.jexcel('getCell', col, row);
        //         // Set the color based on the value
        //         if (val < 0) {
        //             cellObj.style.color = 'red'; // Negative value, set color to red
        //         } else {
        //             cellObj.style.color = 'green'; // Positive value, set color to green
        //         }
        //     }
        // });

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

                agentEarning();

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
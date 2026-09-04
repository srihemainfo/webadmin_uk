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
    border: 1px solid #ccc;
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
  #waMessage{
    background:#dcf8c6;
    padding:12px 15px;
    border-radius:10px;
    max-width:90%;
    box-shadow:0 1px 2px rgba(0,0,0,.2);
  }
</style>

<script>
  window.onload = function () {
    var page_origin = window.location.origin;

    let anchor = document.getElementById("anchor");

    anchor.href = page_origin;
  };
</script>

<div class="main-content app-content mt-0">
  <div class="side-app">
    <input type="hidden" id="tabID" value="agents" />

    <div class="main-container container-fluid">
      <div class="page-header">
        <h1 class="page-title">
          <a href="javascript:void(0)" class="back-arrow-btn">
            <i
              class="fa fa-chevron-left"
              onclick="history.go(-1)"
              aria-hidden="true"
            ></i>
          </a>

          Customer App link Report
        </h1>

        <div>
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="" id="anchor">Home</a>
            </li>

            <li class="breadcrumb-item active">Customer App link Report</li>
          </ol>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
              <div class="card overflow-hidden">
                <div class="card-body">
                  <div class="mt-2">
                    <div class="row">
                      <div class="col-lg-3 col-sm-6 mb-2">
                        <span>Select Date</span>
                        <span style="color: red">*</span>

                        <div
                          id="reportrange"
                          style="
                            background: #fff;
                            cursor: pointer;
                            padding: 5px 10px;
                            border: 1px solid #ccc;
                            width: 100%;
                          "
                        >
                          <i class="fa fa-calendar"></i>
                          &nbsp;
                          <span></span>
                          <i class="fa fa-caret-down"></i>
                        </div>
                      </div>

                      <div class="col-lg-3 col-sm-6 mb-2">
                        <span>Select Status</span>

                        <select id="statusget" class="form-select">
                          <option value="All">All</option>
                          <option value="0">Not Delivered</option>
                          <option value="1">Sent</option>
                        </select>
                      </div>

                      <div class="col-lg-3 col-sm-6 mb-2">
                        <br />

                        <button
                          type="submit"
                          id="smslogsearch"
                          class="btn btn-info"
                          onclick="searchTicket()"
                        >
                          Go
                        </button>
                      </div>
                    </div>

                    <br />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row row-sm">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="col-lg-4">
                <h3 class="card-title">
                  <strong>WhatsApp Report</strong>
                </h3>
              </div>

              <div class="col-lg-8"></div>
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table
                  class="table table-bordered text-nowrap border-bottom"
                  id="WhatsApp_report1"
                  style="width: 100%"
                >
                  <thead>
                    <tr>
                      <th class="wd-15p border-bottom-0">Mobile Number</th>

                      <th class="wd-15p border-bottom-0">Template Name</th>

                      <th class="wd-20p border-bottom-0">Details</th>

                      <!--<th class="wd-20p border-bottom-0">Whatsapp</th>-->

                      <!--<th class="wd-20p border-bottom-0">IP Address</th>-->

                      <!--<th class="wd-15p border-bottom-0">Reference ID</th>-->

                      <th class="wd-15p border-bottom-0">Date & Time</th>

                      <th class="wd-15p border-bottom-0">Status</th>
                    </tr>
                  </thead>

                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="messageModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
    
          <div class="modal-header">
            <h5 class="modal-title">WhatsApp Message</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal">x</button>
          </div>
    
          <div class="modal-body">
    
            <div id="waMessage"
            style="
            background:#e7ffdb;
            padding:15px;
            border-radius:10px;
            font-size:14px;
            line-height:22px;
            ">
            </div>
    
          </div>
    
        </div>
      </div>
    </div>

  <script>
    var origin = window.location.origin;

    var url = origin + "/ajax/service/datatable_services.php";

    $(function () {
      createDatePricket("reportrange");

      searchTicket();
    });

    function createDatePricket(id) {
      var start = moment();

      var end = moment();

      function cb(start, end) {
        $("#" + id + " span").html(
          start.format("MMM Do YY") + " - " + end.format("MMM Do YY"),
        );
      }

      $("#" + id).daterangepicker(
        {
          startDate: start,
          endDate: end,

          maxSpan: { days: 15 },

          minDate: moment().subtract(14, "days"),

          maxDate: moment(),

          ranges: {
            Today: [moment(), moment()],
            Yesterday: [
              moment().subtract(1, "days"),
              moment().subtract(1, "days"),
            ],
            "Last 7 Days": [moment().subtract(6, "days"), moment()],
            "Last 15 Days": [moment().subtract(14, "days"), moment()],
          },
        },
        cb,
      );

      cb(start, end);
    }

    function searchTicket() {
      var formdate = moment(
        $("#reportrange").data("daterangepicker").startDate._d,
      ).format("YYYY-MM-DD");

      var todate = moment(
        $("#reportrange").data("daterangepicker").endDate._d,
      ).format("YYYY-MM-DD");

      var title =
        "Customer App link Report : (" + formdate + " to " + todate + ")";

      $("#WhatsApp_report1").DataTable({
        destroy: true,
        pageLength: 10,

        order: [[5, "desc"]],

        paging: true,
        searching: true,
        info: true,

        ajax: {
          url: url,
          method: "POST",
          dataSrc: "",

          data: {
            method: "Customer_Applink_Report",
            agdate: formdate,
            todate: todate,
            statusget: $("#statusget").val(),
          },
        },

        dom: "Bfrtip",

        buttons: [
          "pageLength",
          "copy",
          {
            extend: "excelHtml5",
            title: title,
          },
        ],

        columns: [
          { data: "mobile" },

          {
            data: "temp_name"
          },

          {
            data:null,
            render:function(data){
            
                let message=data.body;
                
                let preview=message.replace(/(<([^>]+)>)/gi,"").substring(0,40);
                
                return `
                    <div style="cursor:pointer;color:#0d6efd"
                    onclick="showMessage(\`${message.replace(/`/g,'\\`')}\`)">
                    
                    ${preview}...
                    
                    </div>
                `;
            
            }
          },

//           {
//             data: null,
//             render: function (data) {
//               const number = data.mobile;

//               const message = data.details || "";

//               const waUrl = `https://web.whatsapp.com/send?phone=${number}&text=${encodeURIComponent(message)}`;

//               return `<a href="${waUrl}" target="_blank">

// <i class="bi bi-whatsapp text-success" style="font-size:22px;"></i>

// </a>`;
//             },
//           },

        //   { data: "ip" },

        //   { data: "reference_id" },

          {
            data: "created_at",
            render: function (data) {
              return moment(data).format("DD MMM YYYY hh:mm a");
            },
          },

          {
            data: "status",
            render: function (data) {
              if (data == 1) {
                return `<span style="color:green;font-weight:bolder;">SENT</span>`;
              } else {
                return `<span style="color:red;font-weight:bolder;">NOT DELIVERED</span>`;
              }
            },
          },
        ],
      });
    }
    
    function showMessage(message){

        $("#waMessage").html(message);
        
        $("#messageModal").modal("show");
    
    }
  </script>
</div>

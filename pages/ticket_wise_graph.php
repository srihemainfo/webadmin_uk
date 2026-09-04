  <?php
  
  //1.modifications unknown//


//Date      Developer_name      Modifications//
// 10-10-23   sathiya           ticket wise graph moved to live
  
    $page_name = 'Ticket Wise';

    ?>

  <style>
      .chart-container {

          position: relative;

          height: 100vh;

          overflow: hidden;

      }

      .aed-agent {
          text-align: center;

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



          <div class="main-container container-fluid">





              <div class="page-header">

                  <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a><?= ucwords($page_name); ?></h1>

                  <div>

                      <ol class="breadcrumb">

                          <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                          <li class="breadcrumb-item active" aria-current="page"><?= ucwords($page_name); ?></li>

                      </ol>

                  </div>

              </div>







              <div class="row">

                  <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">

                      <div class="row" id="collectdiv">











                      </div>

                  </div>

              </div>





              <?php if ($roll_id == 1 || $roll_id == 2) { ?>

                  <div class="row">


                      <div class="col-12">

                          <div class="card">

                              <div class="card-header">

                                  <h3 class="card-title mb-0" id="tabtitle">Daily Ticket Sales</h3>

                              </div>

                              <div class="card-body pt-4">

                                  <div id="ticket_wise_graphs" class="chart-container"></div>

                              </div>

                          </div>

                      </div>

                  </div>

              <?php } ?>











          </div>



      </div>

  </div>

  <script src="https://fastly.jsdelivr.net/npm/echarts@5.4.0/dist/echarts.min.js"></script>



  <script>
      var origin = window.location.origin;

      var url = origin + "/ajax/service/transaction_services.php";

      var graph_services = origin + "/ajax/service/graph_services.php";



      $(function() {



          <?php if ($roll_id == 1 || $roll_id == 2) { ?>


              ticket_graph();

          <?php } ?>


      });





      setInterval(function() {



      }, runtime);





      <?php if ($roll_id == 1 || $roll_id == 2) { ?>







         function ticket_graph() {

              var formdata = [];

              formdata.push({

                  name: 'method',

                  value: "ticket_graph"

              });

              var post_data = formdata;

              var onsuccess = function(data) {



                  var response = JSON.parse(data);

                  if (response != "") {

                      if (response.type == 1) {





                          var dom = document.getElementById('ticket_wise_graphs');

                          var myChart = echarts.init(dom, null, {

                              renderer: 'canvas',

                              useDirtyRect: false

                          });

                          var app = {};



                          var option;



                          const posList = [

                              'left',

                              'right',

                              'top',

                              'bottom',

                              'inside',

                              'insideTop',

                              'insideLeft',

                              'insideRight',

                              'insideBottom',

                              'insideTopLeft',

                              'insideTopRight',

                              'insideBottomLeft',

                              'insideBottomRight'

                          ];

                          app.configParameters = {

                              rotate: {

                                  min: -90,

                                  max: 90

                              },

                              align: {

                                  options: {

                                      left: 'left',

                                      center: 'center',

                                      right: 'right'

                                  }

                              },

                              verticalAlign: {

                                  options: {

                                      top: 'top',

                                      middle: 'middle',

                                      bottom: 'bottom'

                                  }

                              },

                              position: {

                                  options: posList.reduce(function(map, pos) {

                                      map[pos] = pos;

                                      return map;

                                  }, {})

                              },

                              distance: {

                                  min: 0,

                                  max: 100

                              }

                          };

                          app.config = {

                              rotate: 90,

                              align: 'left',

                              verticalAlign: 'middle',

                              position: 'insideBottom',

                              distance: 15,

                              onChange: function() {

                                  const labelOption = {

                                      rotate: app.config.rotate,

                                      align: app.config.align,

                                      verticalAlign: app.config.verticalAlign,

                                      position: app.config.position,

                                      distance: app.config.distance

                                  };

                                  myChart.setOption({

                                      series: [{

                                              label: labelOption

                                          },

                                          {

                                              label: labelOption

                                          },

                                          {

                                              label: labelOption

                                          },

                                          {

                                              label: labelOption

                                          }

                                      ]

                                  });

                              }

                          };

                          const labelOption = {

                              show: true,

                              position: app.config.position,

                              distance: app.config.distance,

                              align: app.config.align,

                              verticalAlign: app.config.verticalAlign,

                              rotate: app.config.rotate,

                              formatter: '{c}  {name|{a}}',

                              fontSize: 16,

                              rich: {

                                  name: {}

                              }

                          };

                          option = {

                              tooltip: {

                                  trigger: 'axis',

                                  axisPointer: {

                                      type: 'shadow'

                                  }

                              },

                              legend: {

                                  data: ['Online Ticket', 'Cash points Ticket', 'Bonus points Ticket', 'Agent Ticket', 'Kiosk Ticket','Coupon Ticket']

                              },

                              toolbox: {

                                  show: true,

                                  orient: 'vertical',

                                  left: 'right',

                                  top: 'center',

                                  feature: {

                                      mark: {

                                          show: true

                                      },

                                      dataView: {

                                          show: true,

                                          readOnly: false

                                      },

                                      magicType: {

                                          show: true,

                                          type: ['line', 'bar', 'stack']

                                      },

                                      restore: {

                                          show: true

                                      },

                                      saveAsImage: {

                                          show: true

                                      }

                                  }

                              },

                              xAxis: [{

                                  type: 'category',

                                  axisTick: {

                                      show: false

                                  },

                                  data: response.result.date

                              }],

                              yAxis: [{

                                  type: 'value'

                              }],

                              series: [{

                                      name: 'Online Ticket',

                                      type: 'bar',

                                      barGap: 0,

                                      label: labelOption,

                                      emphasis: {

                                          focus: 'series'

                                      },

                                      data: response.result.day7.ticket

                                  },


                                  {

                                      name: 'Cash points Ticket',

                                      type: 'bar',

                                      label: labelOption,

                                      emphasis: {

                                          focus: 'series'

                                      },

                                      data: response.result.day7.cpticket

                                  },
                                  {

                                   name: 'Bonus points Ticket',
                                   
                                   type: 'bar',
                                   
                                   label: labelOption,
                                   
                                   emphasis: {
                                   
                                       focus: 'series'
                                   
                                   },
                                   
                                   data: response.result.day7.bpticket
                                   
                                   },
                                  {

                                      name: 'Agent Ticket',

                                      type: 'bar',

                                      label: labelOption,

                                      emphasis: {

                                          focus: 'series'

                                      },

                                      data: response.result.day7.aticket

                                  },

                                  {

                                      name: 'Kiosk Ticket',

                                      type: 'bar',

                                      label: labelOption,

                                      emphasis: {

                                          focus: 'series'

                                      },

                                      data: response.result.day7.kticket

                                  },
                                  {

                                    name: 'Coupon Ticket',
                                    
                                    type: 'bar',
                                    
                                    label: labelOption,
                                    
                                    emphasis: {
                                    
                                        focus: 'series'
                                    
                                    },
                                    
                                    data: response.result.day7.cticket
                                    
                                    }


                              ]

                          };



                          if (option && typeof option === 'object') {
                              myChart.setOption(option);
                          }



                          window.addEventListener('resize', myChart.resize);

                      }

                  }

              }

              do_ajax_call(post_data, onsuccess, graph_services);

          }

      <?php } ?>
  </script>
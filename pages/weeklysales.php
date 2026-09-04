  <?php





    /*







Date      Developer_name      Modifications

 2-2-2023     Prakash     -- dashboard screen deleted ticket count  showed

                          -- Manual ticket remove
09-10-2023     Sathiya     -- Last week data changed into last 7 day data


*/



    $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC ", "roll_id", "");



    $roll_name = select_top_name($con, "role", "name", "`id`='$roll_id'", "name", "");



    $n_roll_id = intval($roll_id) + 1;



    $below_agent_name = select_top_name($con, "role", "name", "`id`='$n_roll_id'", "name", "");



    $titlename = '';



    $titlename1 = '';



    if ($_SESSION['memid'] != 1) {



        $titlename = 'My ' . ucwords($below_agent_name);



        $titlename1 = 'My User';
    } else {



        $titlename = 'All Agent List';



        $titlename1 = 'All User';
    }



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
  </style>



  <!--app-content open-->



  <div class="main-content app-content mt-0">



      <div class="side-app">



          <input type="hidden" id="tabID" value="agents">



          <!-- CONTAINER -->



          <div class="main-container container-fluid">







              <!-- PAGE-HEADER -->



              <div class="page-header">



                  <h1 class="page-title">Weekly Sales</h1>



                  <div>



                      <ol class="breadcrumb">



                          <li class="breadcrumb-item active" aria-current="page">Weekly Sales</li>



                      </ol>



                  </div>



              </div>



              <!-- PAGE-HEADER END -->







              <!-- ROW-1 -->



              <div class="row">



                  <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">



                      <div class="row" id="collectdiv">























                      </div>



                  </div>



              </div>



              <!-- ROW-1 END -->







              <?php if ($roll_id == 1 || $roll_id == 2) { ?>



                  <div class="row">



                      <!-- <div class="col-6">



                          <div class="card">



                              <div class="card-body pt-4">



                                  <div id="draw_slae_graph" class="chart-container"></div>



                              </div>



                          </div>



                      </div> -->



                      <div class="col-12">



                          <div class="card">



                              <div class="card-body pt-4">

<h1>Current Draw Sales</h1>

                                  <div id="product_graphs" class="chart-container"></div>





                                  <h1>Last 7 Days Data</h1>

                                  <div id="product_graphs1" class="chart-container"></div>



                              </div>



                          </div>



                      </div>



                  </div>



              <?php } ?>







              <!-- ROW-4 -->



              <!-- <div class="row">



                  <div class="col-12 col-sm-12">



                      <div class="card">



                          <div class="card-header">



                              <h3 class="card-title mb-0" id="tabtitle"><?= $titlename ?></h3>



                          </div>



                          <div class="card-body pt-4">



                              <div class="grid-margin">



                                  <div class="">



                                      <div class="panel panel-primary">



                                          <div class="tab-menu-heading border-0 p-0">



                                              <div class="tabs-menu1">



                                            



                                                  <ul class="nav panel-tabs product-sale">



                                                      <li><a href="#tab5" class="active" onclick="settabid('agents', '<?= $titlename; ?>')" data-bs-toggle="tab"><?= $titlename; ?></a></li>



                                                      <li><a href="#tab6" data-bs-toggle="tab" onclick="settabid('customer', '<?= $titlename1; ?>')" class="text-dark"><?= $titlename1; ?></a></li>







                                                  </ul>



                                              </div>



                                          </div>



                                          <div class="panel-body tabs-menu-body border-0 pt-0">



                                              <div class="tab-content">



                                                  <div class="tab-pane active" id="tab5">



                                                      <div class="table-responsive">



                                                          <table id="agentlist" class="table table-bordered text-nowrap mb-0">



                                                              <thead class="border-top">



                                                                  <tr>



                                                                      <th class="bg-transparent border-bottom-0" style="width: 5%;">AGENT ID</th>



                                                                      <th class="bg-transparent border-bottom-0">TYPE</th>



                                                                      <th class="bg-transparent border-bottom-0">NAME</th>



                                                                      <th class="bg-transparent border-bottom-0">POINTS</th>



                                                                      <th class="bg-transparent border-bottom-0">PHONE NO</th>



                                                                      <th class="bg-transparent border-bottom-0" style="width: 10%;">EMIRATE / PASSPORT ID</th>



                                                                      <th class="bg-transparent border-bottom-0" style="width: 5%;">E-MAIL</th>



                                                                      <th class="bg-transparent border-bottom-0" style="width: 5%;">ACTION</th>



                                                                  </tr>



                                                              </thead>



                                                              <tbody>



                                                              </tbody>



                                                          </table>



                                                      </div>



                                                  </div>







                                                  <div class="tab-pane" id="tab6">



                                                      <div class="table-responsive">



                                                          <table id="customerlist" class="table table-bordered text-nowrap mb-0">



                                                              <thead class="border-top">



                                                                  <tr>



                                                                      <th class="bg-transparent border-bottom-0" style="width: 5%;">CUSTOMER ID</th>



                                                                      <th class="bg-transparent border-bottom-0">NAME</th>



                                                                      <th class="bg-transparent border-bottom-0">POINTS</th>



                                                                      <th class="bg-transparent border-bottom-0">PHONE NO</th>



                                                                      <th class="bg-transparent border-bottom-0">EMIRATE / PASSPORT ID</th>



                                                                      <th class="bg-transparent border-bottom-0" style="width: 10%;">E-MAIL</th>



                                                                      <th class="bg-transparent border-bottom-0" style="width: 5%;">ACTION</th>



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



                      </div>



                  </div>



              </div> -->



              <!-- ROW-4 END -->















          </div>



          <!-- CONTAINER END -->



      </div>



  </div>



  <script src="https://fastly.jsdelivr.net/npm/echarts@5.4.0/dist/echarts.min.js"></script>



  <!--app-content close-->



  <script>
    //   var origin = window.location.origin;

      var url = window.location.origin + "/ajax/service/transaction_services.php";

      var graph_services = window.location.origin + "/ajax/service/graph_services.php";



      $(function() {

        //   collect_earning();

          <?php if ($roll_id == 1 || $roll_id == 2) { ?>

              //   darw_graph();

              product_graph();

              product_graph1();

          <?php } ?>



      });











      //   setInterval(function() {



      //       collect_earning();



      //   }, runtime);







      function settabid(tabid, tabtitle) {



          $('#tabID').val(tabid);



          //   $('#tabtitle').val(tabtitle);



          document.getElementById('tabtitle').innerText = tabtitle;



          if (tabid == 'agents') {



              var table = $('#agentlist').DataTable();



              table.destroy();



          } else {



              var table = $('#customerlist').DataTable();



              table.destroy();



          }



          viewtable();



      }











      function collect_earning() {

          var formdata = [];

          formdata.push({

              name: 'method',

              value: "collect_earning"

          });

          var post_data = formdata;

          var onsuccess = function(data) {

              var response = JSON.parse(data);

              if (response != "") {

                  if (response.type == 1) {

                      document.getElementById('collectdiv').innerHTML = response.output;

                  } else {

                      document.getElementById('collectdiv').innerHTML = response.result;

                  }

              }

          }

          do_ajax_call(post_data, onsuccess, url);

      }



















      //   function viewtable() {



      //       let tabID = $('#tabID').val();



      //       var formdata = [];



      //       formdata.push({



      //           name: 'method',



      //           value: "list_agent",



      //       });



      //       if (tabID == 'agents') {



      //           formdata.push({



      //               name: 'role',



      //               value: "2,3,4,5"



      //           });



      //           formdata.push({



      //               name: 'type',



      //               value: "agent"



      //           });



      //       } else if (tabID == 'customer') {



      //           formdata.push({



      //               name: 'role',



      //               value: "0"



      //           });



      //       } else {



      //           formdata.push({



      //               name: 'role',



      //               value: "6"



      //           });



      //       }







      //       var post_data = formdata;











      //       var onsuccess = function(data) {



      //           var response = JSON.parse(data);



      //           //   console.log(response);



      //           if (response != "") {







      //               if (tabID == 'agents') {



      //                   $('#agentlist').DataTable({



      //                       order: [



      //                           [0, 'desc']



      //                       ],



      //                       "data": response,



      //                       "columns": [{



      //                               'data': 'nid'



      //                           },







      //                           {



      //                               'data': 'rollType'



      //                           },







      //                           {



      //                               'data': 'name'



      //                           },



      //                           {



      //                               'data': 't_point'



      //                           },



      //                           {



      //                               'data': 'mobile'



      //                           },



      //                           {



      //                               'data': 'passport'



      //                           },



      //                           {



      //                               'data': 'email'



      //                           },



      //                           {



      //                               'data': 'action'



      //                           }



      //                       ],



      //                   });



      //               } else if (tabID == 'customer') {



      //                   $('#customerlist').DataTable({



      //                       order: [



      //                           [0, 'desc']



      //                       ],



      //                       "data": response,



      //                       "columns": [{



      //                               'data': 'nid'



      //                           },



      //                           {



      //                               'data': 'name'



      //                           },



      //                           {



      //                               'data': 't_point'



      //                           },



      //                           {



      //                               'data': 'mobile'



      //                           },



      //                           {



      //                               'data': 'passport'



      //                           },



      //                           {



      //                               'data': 'email'



      //                           },



      //                           {



      //                               'data': 'action'



      //                           }



      //                       ],



      //                   });



      //               } else {



      //                   $('#example').DataTable({



      //                       order: [



      //                           [0, 'desc']



      //                       ],



      //                       "data": response,



      //                       "columns": [{



      //                               'data': 'nid'



      //                           },



      //                           {



      //                               'data': 'name'



      //                           },



      //                           {



      //                               'data': 't_point'



      //                           },



      //                           {



      //                               'data': 'mobile'



      //                           },



      //                           {



      //                               'data': 'passport'



      //                           },



      //                           {



      //                               'data': 'email'



      //                           },



      //                           {



      //                               'data': 'action'



      //                           }



      //                       ],



      //                   });



      //               }











      //           }



      //       }







      //       do_ajax_call(post_data, onsuccess);







      //   }







      <?php if ($roll_id == 1 || $roll_id == 2) { ?>







          //   function darw_graph() {







          //       var formdata = [];



          //       formdata.push({



          //           name: 'method',



          //           value: "draw_earn_graph"



          //       });



          //       var post_data = formdata;



          //       var onsuccess = function(data) {







          //           var response = JSON.parse(data);



          //           if (response != "") {



          //               if (response.type == 1) {



          //                   var dom = document.getElementById('draw_slae_graph');



          //                   var myChart = echarts.init(dom, null, {



          //                       renderer: 'canvas',



          //                       useDirtyRect: false



          //                   });



          //                   var app = {};







          //                   var option;







          //                   option = {



          //                       toolbox: {



          //                           show: true,



          //                           feature: {



          //                               mark: {



          //                                   show: true



          //                               },



          //                               dataView: {



          //                                   show: true,



          //                                   readOnly: false



          //                               },



          //                               restore: {



          //                                   show: true



          //                               },



          //                               saveAsImage: {



          //                                   show: true



          //                               }



          //                           }



          //                       },



          //                       tooltip: {



          //                           trigger: 'item'



          //                       },



          //                       legend: {



          //                           top: '5%',



          //                           left: 'center'



          //                       },



          //                       series: [{



          //                           name: 'Access From',



          //                           type: 'pie',



          //                           radius: ['40%', '70%'],



          //                           avoidLabelOverlap: false,



          //                           itemStyle: {



          //                               borderRadius: 10,



          //                               borderColor: '#fff',



          //                               borderWidth: 2



          //                           },



          //                           label: {



          //                               show: false,



          //                               position: 'center'



          //                           },



          //                           emphasis: {



          //                               label: {



          //                                   show: true,



          //                                   fontSize: '20',



          //                                   fontWeight: 'bold'



          //                               }



          //                           },



          //                           labelLine: {



          //                               show: false



          //                           },



          //                           data: response.result



          //                       }]



          //                   };











          //                   if (option && typeof option === 'object') {



          //                       myChart.setOption(option);



          //                   }







          //                   window.addEventListener('resize', myChart.resize);



          //               }



          //           }



          //       }



          //       do_ajax_call(post_data, onsuccess, graph_services);



          //   }







          function product_graph() {



              var formdata = [];



              formdata.push({



                  name: 'method',



                  value: "product_graph"



              });



              var post_data = formdata;



              var onsuccess = function(data) {







                  var response = JSON.parse(data);



                  if (response != "") {



                      if (response.type == 1) {











                          var dom = document.getElementById('product_graphs');



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



                                  data: ['AED 10', 'AED 20', 'AED 50', 'AED 100']



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



                                      name: 'AED 10',



                                      type: 'bar',



                                      barGap: 0,



                                      label: labelOption,



                                      emphasis: {



                                          focus: 'series'



                                      },



                                      data: response.result.day7.AED10



                                  },



                                  {



                                      name: 'AED 20',



                                      type: 'bar',



                                      label: labelOption,



                                      emphasis: {



                                          focus: 'series'



                                      },



                                      data: response.result.day7.AED20



                                  },



                                  {



                                      name: 'AED 50',



                                      type: 'bar',



                                      label: labelOption,



                                      emphasis: {



                                          focus: 'series'



                                      },



                                      data: response.result.day7.AED50



                                  },



                                  {



                                      name: 'AED 100',



                                      type: 'bar',



                                      label: labelOption,



                                      emphasis: {



                                          focus: 'series'



                                      },



                                      data: response.result.day7.AED100



                                  }



                              ]



                          };







                          if (option && typeof option === 'object') {



                              myChart.setOption(option);



                          }







                          window.addEventListener('resize', myChart.resize);































































                          //   var dom = document.getElementById('product_graphs');



                          //   var myChart = echarts.init(dom, null, {



                          //       renderer: 'canvas',



                          //       useDirtyRect: false



                          //   });



                          //   var app = {};







                          //   var option;







                          //   option = {



                          //       color: ['#80FFA5', '#00DDFF', '#37A2FF', '#FF0087', '#FFBF00'],



                          //       title: {



                          //           text: 'Product Wise Report'



                          //       },



                          //       tooltip: {



                          //           trigger: 'axis',



                          //           axisPointer: {



                          //               type: 'cross',



                          //               label: {



                          //                   backgroundColor: '#6a7985'



                          //               }



                          //           }



                          //       },



                          //       legend: {



                          //           data: ['AED 10', 'AED 20', 'AED 50', 'AED 100']



                          //       },



                          //       toolbox: {



                          //           feature: {



                          //               saveAsImage: {}



                          //           }



                          //       },



                          //       grid: {



                          //           left: '3%',



                          //           right: '4%',



                          //           bottom: '3%',



                          //           containLabel: true



                          //       },



                          //       xAxis: [{



                          //           type: 'category',



                          //           boundaryGap: false,



                          //           data: response.result.date



                          //       }],



                          //       yAxis: [{



                          //           type: 'value'



                          //       }],



                          //       series: [{



                          //               name: 'AED 10',



                          //               type: 'line',



                          //               stack: 'Total',



                          //               smooth: true,



                          //               lineStyle: {



                          //                   width: 0



                          //               },



                          //               showSymbol: false,



                          //               areaStyle: {



                          //                   opacity: 0.8,



                          //                   color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{



                          //                           offset: 0,



                          //                           color: 'rgb(128, 255, 165)'



                          //                       },



                          //                       {



                          //                           offset: 1,



                          //                           color: 'rgb(1, 191, 236)'



                          //                       }



                          //                   ])



                          //               },



                          //               emphasis: {



                          //                   focus: 'series'



                          //               },



                          //               data: response.result.day7.AED10



                          //           },



                          //           {



                          //               name: 'AED 20',



                          //               type: 'line',



                          //               stack: 'Total',



                          //               smooth: true,



                          //               lineStyle: {



                          //                   width: 0



                          //               },



                          //               showSymbol: false,



                          //               areaStyle: {



                          //                   opacity: 0.8,



                          //                   color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{



                          //                           offset: 0,



                          //                           color: 'rgb(0, 221, 255)'



                          //                       },



                          //                       {



                          //                           offset: 1,



                          //                           color: 'rgb(77, 119, 255)'



                          //                       }



                          //                   ])



                          //               },



                          //               emphasis: {



                          //                   focus: 'series'



                          //               },



                          //               data: response.result.day7.AED20



                          //           },



                          //           {



                          //               name: 'AED 50',



                          //               type: 'line',



                          //               stack: 'Total',



                          //               smooth: true,



                          //               lineStyle: {



                          //                   width: 0



                          //               },



                          //               showSymbol: false,



                          //               areaStyle: {



                          //                   opacity: 0.8,



                          //                   color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{



                          //                           offset: 0,



                          //                           color: 'rgb(55, 162, 255)'



                          //                       },



                          //                       {



                          //                           offset: 1,



                          //                           color: 'rgb(116, 21, 219)'



                          //                       }



                          //                   ])



                          //               },



                          //               emphasis: {



                          //                   focus: 'series'



                          //               },



                          //               data: response.result.day7.AED50



                          //           },



                          //           {



                          //               name: 'AED 100',



                          //               type: 'line',



                          //               stack: 'Total',



                          //               smooth: true,



                          //               lineStyle: {



                          //                   width: 0



                          //               },



                          //               showSymbol: false,



                          //               areaStyle: {



                          //                   opacity: 0.8,



                          //                   color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{



                          //                           offset: 0,



                          //                           color: 'rgb(255, 0, 135)'



                          //                       },



                          //                       {



                          //                           offset: 1,



                          //                           color: 'rgb(135, 0, 157)'



                          //                       }



                          //                   ])



                          //               },



                          //               emphasis: {



                          //                   focus: 'series'



                          //               },



                          //               data: response.result.day7.AED100



                          //           }







                          //       ]



                          //   };











                          //   if (option && typeof option === 'object') {



                          //       myChart.setOption(option);



                          //   }







                          //   window.addEventListener('resize', myChart.resize);



                      }



                  }



              }



              do_ajax_call(post_data, onsuccess, graph_services);



          }



          function product_graph1() {



              var formdata = [];



              formdata.push({



                  name: 'method',



                  value: "product_graph1"



              });



              var post_data = formdata;



              var onsuccess = function(data) {







                  var response = JSON.parse(data);



                  if (response != "") {



                      if (response.type == 1) {











                          var dom = document.getElementById('product_graphs1');



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



                                  data: ['AED 10', 'AED 20', 'AED 50', 'AED 100']



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



                                      name: 'AED 10',



                                      type: 'bar',



                                      barGap: 0,



                                      label: labelOption,



                                      emphasis: {



                                          focus: 'series'



                                      },



                                      data: response.result.day7.AED10



                                  },



                                  {



                                      name: 'AED 20',



                                      type: 'bar',



                                      label: labelOption,



                                      emphasis: {



                                          focus: 'series'



                                      },



                                      data: response.result.day7.AED20



                                  },



                                  {



                                      name: 'AED 50',



                                      type: 'bar',



                                      label: labelOption,



                                      emphasis: {



                                          focus: 'series'



                                      },



                                      data: response.result.day7.AED50



                                  },



                                  {



                                      name: 'AED 100',



                                      type: 'bar',



                                      label: labelOption,



                                      emphasis: {



                                          focus: 'series'



                                      },



                                      data: response.result.day7.AED100



                                  }



                              ]



                          };







                          if (option && typeof option === 'object') {



                              myChart.setOption(option);



                          }







                          window.addEventListener('resize', myChart.resize);































































                          //   var dom = document.getElementById('product_graphs');



                          //   var myChart = echarts.init(dom, null, {



                          //       renderer: 'canvas',



                          //       useDirtyRect: false



                          //   });



                          //   var app = {};







                          //   var option;







                          //   option = {



                          //       color: ['#80FFA5', '#00DDFF', '#37A2FF', '#FF0087', '#FFBF00'],



                          //       title: {



                          //           text: 'Product Wise Report'



                          //       },



                          //       tooltip: {



                          //           trigger: 'axis',



                          //           axisPointer: {



                          //               type: 'cross',



                          //               label: {



                          //                   backgroundColor: '#6a7985'



                          //               }



                          //           }



                          //       },



                          //       legend: {



                          //           data: ['AED 10', 'AED 20', 'AED 50', 'AED 100']



                          //       },



                          //       toolbox: {



                          //           feature: {



                          //               saveAsImage: {}



                          //           }



                          //       },



                          //       grid: {



                          //           left: '3%',



                          //           right: '4%',



                          //           bottom: '3%',



                          //           containLabel: true



                          //       },



                          //       xAxis: [{



                          //           type: 'category',



                          //           boundaryGap: false,



                          //           data: response.result.date



                          //       }],



                          //       yAxis: [{



                          //           type: 'value'



                          //       }],



                          //       series: [{



                          //               name: 'AED 10',



                          //               type: 'line',



                          //               stack: 'Total',



                          //               smooth: true,



                          //               lineStyle: {



                          //                   width: 0



                          //               },



                          //               showSymbol: false,



                          //               areaStyle: {



                          //                   opacity: 0.8,



                          //                   color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{



                          //                           offset: 0,



                          //                           color: 'rgb(128, 255, 165)'



                          //                       },



                          //                       {



                          //                           offset: 1,



                          //                           color: 'rgb(1, 191, 236)'



                          //                       }



                          //                   ])



                          //               },



                          //               emphasis: {



                          //                   focus: 'series'



                          //               },



                          //               data: response.result.day7.AED10



                          //           },



                          //           {



                          //               name: 'AED 20',



                          //               type: 'line',



                          //               stack: 'Total',



                          //               smooth: true,



                          //               lineStyle: {



                          //                   width: 0



                          //               },



                          //               showSymbol: false,



                          //               areaStyle: {



                          //                   opacity: 0.8,



                          //                   color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{



                          //                           offset: 0,



                          //                           color: 'rgb(0, 221, 255)'



                          //                       },



                          //                       {



                          //                           offset: 1,



                          //                           color: 'rgb(77, 119, 255)'



                          //                       }



                          //                   ])



                          //               },



                          //               emphasis: {



                          //                   focus: 'series'



                          //               },



                          //               data: response.result.day7.AED20



                          //           },



                          //           {



                          //               name: 'AED 50',



                          //               type: 'line',



                          //               stack: 'Total',



                          //               smooth: true,



                          //               lineStyle: {



                          //                   width: 0



                          //               },



                          //               showSymbol: false,



                          //               areaStyle: {



                          //                   opacity: 0.8,



                          //                   color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{



                          //                           offset: 0,



                          //                           color: 'rgb(55, 162, 255)'



                          //                       },



                          //                       {



                          //                           offset: 1,



                          //                           color: 'rgb(116, 21, 219)'



                          //                       }



                          //                   ])



                          //               },



                          //               emphasis: {



                          //                   focus: 'series'



                          //               },



                          //               data: response.result.day7.AED50



                          //           },



                          //           {



                          //               name: 'AED 100',



                          //               type: 'line',



                          //               stack: 'Total',



                          //               smooth: true,



                          //               lineStyle: {



                          //                   width: 0



                          //               },



                          //               showSymbol: false,



                          //               areaStyle: {



                          //                   opacity: 0.8,



                          //                   color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{



                          //                           offset: 0,



                          //                           color: 'rgb(255, 0, 135)'



                          //                       },



                          //                       {



                          //                           offset: 1,



                          //                           color: 'rgb(135, 0, 157)'



                          //                       }



                          //                   ])



                          //               },



                          //               emphasis: {



                          //                   focus: 'series'



                          //               },



                          //               data: response.result.day7.AED100



                          //           }







                          //       ]



                          //   };











                          //   if (option && typeof option === 'object') {



                          //       myChart.setOption(option);



                          //   }







                          //   window.addEventListener('resize', myChart.resize);



                      }



                  }



              }



              do_ajax_call(post_data, onsuccess, graph_services);



          }



      <?php } ?>
  </script>
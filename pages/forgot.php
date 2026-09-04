  <!DOCTYPE html>
  
  <!--
  
 1. modifications unknown


Date      Developer_name      Modifications

  
  -->
  
  <html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.min.js" integrity="sha512-7rusk8kGPFynZWu26OKbTeI+QPoYchtxsmPeBqkHIEXJxeun4yJ4ISYe7C6sz9wdxeE1Gk3VxsIWgCZTc+vX3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">



    <style>
      .alert.alert-danger {
        margin: 14px 40px 15px 40px;
      }

      button.btn.btn-outline-primary {
        background: #dc3545;
        color: #fff;
        border: none;
      }



      .page-main {
        background: #1193bd;
      }

      .close-icon {
        position: absolute;
        top: 28px;
        right: 18px;
        padding: 8px;
      }

      .close-icon i {
        font-size: 25px;
        color: #999999;
      }

      div#getemail {
        margin-top: 30px;
      }

      button.btn.btn-outline-secondary.dropdown-toggle {
        background: #3c3c3c;
      }

      button.btn.btn-outline-secondary.dropdown-toggle {
        border: 1px solid #000 !important;
        color: #000 !important;
        height: 38px !important;
      }

      .form-group input {
        border: 1px solid #dbdbdb;
        background-color: #ffff;
        color: #c4cbf9;
        font-size: 16px;
      }

      .wrapper h6 {
        color: #000;
        color: #000;
        font-weight: bold;
        font-size: 23px;
        text-align: center !important;
        padding: 25px 0px;
      }

      span.mobile-design {
        color: #fff;
        font-size: 20px;
        font-weight: bold;
      }

      span.mobile-email {
        color: #fff;
        font-size: 20px;
        font-weight: bold;
      }

      .wrapper {

        background-image: -webkit-linear-gradient(124deg, #fff 0%, #fff 100%);
        min-height: 370px;

      }

      .form-group {
        width: 78%;
        margin: auto;
      }

      .nice-select {
        width: 100%;
        min-height: 0px !important;

      }




      button.btn.btn-outline-secondary.dropdown-toggle {
        border: 1px solid #ffff !important;
        color: #fff !important;
        height: 36px !important;
        width: 106px;
      }

      .bgWhite button.btn.btn-danger.sendotp {
        margin: 12px;
      }

      button.btn.btn-danger.sendotp {
        background: #dc3545;
        color: #fff;
        border: none;
      }

      input[type=radio] {

        width: 1.2em;
        height: 1.2em;


      }

      .customBtn {
        border-radius: 0px;
        padding: 10px;
      }

      input.otp {
        display: inline-block;
        width: 50px;
        height: 50px;
        text-align: center;
        border: 1px solid #999999;
        margin: 1px 2px;
      }

      .title-otp {
        text-align: center;
        font-weight: 500;
      }

      .title {
        color: #fff;
        font-size: 15px;
        font-weight: 600;
        text-align: center;
      }

      input.form-control.mobil {
        font-size: 14px;
      }

      div#getphone {
        padding: 35px;
        margin: 0;
      }


      .formwrapper {
        margin: 0 auto;
        text-align: center;
        margin-top: 30px;
      }

      .btn-warning:not(:disabled):not(.disabled).active,
      .btn-warning:not(:disabled):not(.disabled):active,
      .show>.btn-warning.dropdown-toggle label.btn.btn-warning.mobile {
        background-image: -webkit-linear-gradient(-45deg, #000 0%, #000 100%) !important;
        color: #fff;
        /* padding: 7px 35px !important; */
        border-color: #000;

      }




      label.btn.btn-warning1.mobile {
        background: #dddd;
      }

      label.btn.btn-warning1.mobile.focus.active {
        background: #424242;
      }

      label.btn.btn-warning1.email {
        background: #ddd;
      }

      label.btn.btn-warning1.mobile.active {
        background: #424242;
        color: #fff;
      }




      .btn-warning {
        background: #b5b5b5 !important;
        color: #ffffff !important;
        border: none !important;
      }

      /* .btn-warning:hover {
        background-image: -webkit-linear-gradient(-45deg, #ce2629 0%, #0c4072 100%) !important;
        color: #fff !important;

        border-color: #0c4072 !important;
        border: 1px solid #584646 !important;
      } */
    </style>
    <script>
      window.console = window.console || function(t) {};

      if (document.location.search.match(/type=embed/gi)) {
        window.parent.postMessage("resize", "*");
      }
    </script>


  </head>

  <body>
    <div class="container">
      <div class="row">

        <div class="col-12 col-md-5 col-lg-5 col-xl-5 m-auto">
          <div class="wrapper">
            <div class="close-icon">

              <a href="<?php echo $adminurl; ?>"><i class="fa fa-times-circle"></i></a>
            </div>
            <h6 class="text-center"> Forgot Password</h6>

            <div id="error">

            </div>
            <div class="col-12 col-md-6 col-lg-6 col-xl-6 text-center mx-auto">
              <div class="btn-group btn-group-toggle me" data-toggle="buttons">
                <label class="btn btn-warning email active">
                  <input type="radio" name="options" id="radio2" onclick="me($(this).val())" name="optradio" value="2" autocomplete="off" checked> Email
                </label>
                <label class="btn btn-warning mobile">
                  <input type="radio" name="options" onclick="me($(this).val())" id="radio1" name="optradio" value="1" autocomplete="off"> Mobile
                </label>

              </div>
            </div>







            <div id="getphone">
              <label for="mobile" style="color: #000;">Mobile:</label>
              <div class="input-group me">

                <div class="input-group-prepend ">
                  <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    +971
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item">+971</a>
                  </div>
                </div>

                <input type="text" class="form-control mobil" id="mobile" placeholder="Mobile Number">
                <div class="col-md-12 text-center mt-3 ">
                  <button type="button" onclick="forgototp('mobile')" class="btn btn-danger sendotp">SEND OTP</button>
                </div>
              </div>
            </div>

            <div class="form-group me" id="getemail">
              <label for="email" style="color: #000;">Email:</label>
              <input type="email" class="form-control" placeholder="Enter email" id="email">
              <div class="col-md-12 text-center mt-3">
                <button type="button" onclick="forgototp('email')" class="btn btn-outline-primary" data-mdb-ripple-color="dark">SEND OTP</button>
              </div>
            </div>


            <div class="row" id="enterotp">


            </div>


            <div id="passwordupdate">





            </div>

          </div>

        </div>
      </div>
    </div>




  </body>

  </html>

  <script>
    var origin = window.location.origin;
    var URL = origin + "/ajax/service/forgot_services.php?";
    $(document).ready(function() {
      $('#getphone').hide();
      $('#getemail').show();
      $('#enterotp').hide();
      $('#passwordupdate').hide();
    });

    function me(value) {

      if (value == 1) {
        $('#getemail').hide();
        $('#getphone').show();
        document.getElementById('error').innerHTML = '';
      } else {
        $('#getphone').hide();
        $('#getemail').show();
        document.getElementById('error').innerHTML = '';
      }
    }


    function forgototp(key) {
      document.getElementById('error').innerHTML = '';
      if (key == 'email') {
        let email = $('#email').val();
        if (email != '') {
          if (validateEmail(email)) {
            forgotfun(key);
          } else {
            document.getElementById('error').innerHTML = '<div class="alert alert-danger" role="alert">Please Enter the valid email.</div>';
          }
        } else {
          document.getElementById('error').innerHTML = '<div class="alert alert-danger" role="alert">Please Enter the email.</div>';
        }
      } else if (key == 'mobile') {
        let mobile = $('#mobile').val();
        if (mobile != '') {
          forgotfun(key);
        } else {
          document.getElementById('error').innerHTML = '<div class="alert alert-danger" role="alert">Please Enter the mobile number.</div>';
        }
      }





    }


    function forgotfun(key) {
      let mobile = '';
      let no = $('#mobile').val()

      if (no != '') {
        mobile = '971' + no;
      }

      $.ajax({
        url: URL,
        type: 'post',
        data: {
          method: "forgototp",
          mobile: mobile,
          email: $('#email').val(),
          key: key
        },
        success: function(data) {
          var response = JSON.parse(data);
          if (response != "") {
            if (response.type == 1) {
              $('#enterotp').show();
              $('.me').hide();
              document.getElementById('enterotp').innerHTML = response.output;
              document.getElementById('error').innerHTML = '';
            } else {
              document.getElementById('error').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';
            }
          }
        }
      });
    }


    function resend(key, value) {
      let email = '';
      let mobile = '';
      if (key == 'email') {
        email = value;
      } else {
        mobile = value;
      }

      $.ajax({
        url: URL,
        type: 'post',
        data: {
          method: "forgototp",
          mobile: mobile,
          email: email,
          key: key
        },
        success: function(data) {
          var response = JSON.parse(data);
          if (response != "") {
            if (response.type == 1) {
              $('#enterotp').show();
              $('.me').hide();
              document.getElementById('enterotp').innerHTML = response.output;
              document.getElementById('error').innerHTML = '';
            } else {
              document.getElementById('error').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';
            }
          }
        }
      });
    }

    function verify() {
      let otp = $('#otp1').val() + $('#otp2').val() + $('#otp3').val() + $('#otp4').val();
      $.ajax({
        url: URL,
        type: 'post',
        data: {
          method: "verify",
          otp: otp

        },
        success: function(data) {
          var response = JSON.parse(data);
          if (response != "") {
            if (response.type == 1) {
              $('#enterotp').hide();
              $('#passwordupdate').show();
              document.getElementById('passwordupdate').innerHTML = response.output;
              document.getElementById('error').innerHTML = '';
              document.getElementById('enterotp').innerHTML = '';
            } else {
              document.getElementById('error').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';
            }
          }
        }
      });
    }

    function updatePassword(newpass, conpass) {
      $.ajax({
        url: URL,
        type: 'post',
        data: {
          method: "updatePassword",
          newpass: newpass,
          conpass: conpass

        },
        success: function(data) {
          var response = JSON.parse(data);
          if (response != "") {
            if (response.type == 1) {
              $('#enterotp').hide();
              $('#passwordupdate').show();
              document.getElementById('error').innerHTML = '';
              window.location.href = origin + '/login';
            } else {
              document.getElementById('error').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';
            }
          }
        }
      });
    }

    let digitValidate = function(ele) {
      console.log(ele.value);
      ele.value = ele.value.replace(/[^0-9]/g, '');
    }

    let tabChange = function(val) {
      let ele = document.querySelectorAll('input');
      if (ele[val - 1].value != '') {
        ele[val].focus()
      } else if (ele[val - 1].value == '') {
        ele[val - 2].focus()
      }
    }



    function getCodeBoxElement(index) {
      return document.getElementById('otp' + index);
    }

    function onKeyUpEvent(index, event) {
      const eventCode = event.which || event.keyCode;
      if (getCodeBoxElement(index).value.length === 1) {
        if (index !== 4) {
          getCodeBoxElement(index + 1).focus();
        } else {
          getCodeBoxElement(index).blur();
          // Submit code
          console.log('submit code ');
        }
      }
      if (eventCode === 8 && index !== 1) {
        getCodeBoxElement(index - 1).focus();
      }
    }

    function onFocusEvent(index) {
      for (item = 1; item < index; item++) {
        if (window.CP.shouldStopExecution(0)) break;
        const currentElement = getCodeBoxElement(item);
        if (!currentElement.value) {
          currentElement.focus();
          break;
        }
      }
      window.CP.exitedLoop(0);
    }




    function validateEmail(email) {
      var reg = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
      if (reg.test(email)) {
        return true;
      }
      return false;
    }
  </script>
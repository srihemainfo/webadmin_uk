<?php
/**
 * Date          Developer                  Modification
 * 13-6-2023        Prakash                     payITcashBack Template has been updated.
 * 
 */

class mailTemplate
{
  // Properties
  public $con;
  public $baseurl;
  public $dubaidate_time;
  // Methods
  function __construct($con, $baseurl, $dubaidate_time)
  {
    $this->con = $con;
    $this->baseurl = $baseurl;
    $this->dubaidate_time = $dubaidate_time;
  }




  function payITcashBack(array $request)
  {


    // OLD Template 

  
    // $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        
    //     <html xmlns="http://www.w3.org/1999/xhtml">
        
    //     <head>
         
    //      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
         
    //      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
         
    //      <meta name="viewport" content="width=device-width, initial-scale=1.0">
         
    //      <title>Ticket Purchase OTP Mail Template</title>
         
    //      <script type="text/javascript" src="https://gc.kes.v2.scr.kaspersky-labs.com/7EA5E9BB-55E1-4C31-9C21-4943DDFED2E4/main.js?attr=WcpIPPU77QBGCqlcg3xpDqyznJyXbKsMbX-7wdHvAPyZ7Gh-DKZQO1JEfsp80tYXgi0nsW8T5x43FT8Z3vewOmsIAQ0-gA3Za_IRCkh3HoJemiIaUQmDTwLJ2mcpoFn0" charset="UTF-8"></script><style type="text/css">
         
         
         
    //        @import url("https://fonts.googleapis.com/css2?family=Barlow+Condensed&display=swap");
         
    //        body {
         
    //          margin: 0;
         
    //        }
         
    //        .wrapper {
         
             
         
    //          background:#CCC;
         
             
         
    //          }
         
    //        .main {
         
             
         
    //          background:#FFF;
         
    //          max-width:600px;
         
             
         
    //          } 
         
             
         
    //        table {
         
    //          border-spacing: 0;
         
    //        }
         
           
         
    //        img {
         
    //          border: 0;
         
    //        }
         
    //        .column-one {
         
         
         
    //          text-align:center;
         
    //          margin:0 auto;
         
    //          }
         
    //        .column-one .column {
         
             
         
    //          width:100%;
         
    //            margin:0 auto;
         
           
         
    //          }
         
         
    //      </style>
         
    //      </head>
         
    //      <body>
         
         
    //        <center class="wrapper">
         
         
    //          <table class="main" width="100%">
         
    //              <!-- BORDER -->
         
    //              <tr><td class="column-one" style="background: #29377d; height:50px;">
                 
         
    //              </td></tr>
         
    //                      <tr><td class="column-one" style="background: radial-gradient(circle,#fcef48 0%,#fdd206 100%); height:11px;">        
         
    //              </td></tr>        
         
    //              <tr><td class="column-one" >
         
    //              <table class="column"> <tr>
    //                <td valign="top" style="padding: 16px 0 0px 0;">  
         
    //              <center>
         
    //                <img src="' . $this->baseurl . 'assets/images/mailtemplate/logo1.png" style="border: 0px;"  >
                 
    //              </center>
         
    //                </td></tr></table>
         
                 
         
    //              </td></tr>
         
    //              <!-- LOGO  -->
         
    //                      <tr>
         
    //                        <td class="column-one" >
         
    //              <table align="center" class="column"> <tr><td valign="top" > 
    //               <tr>
                   
    //                 <td>   
                      
    //                   <h3 class="demoname" style="color: #29377d;font-family: Arial Narrow;font-style: italic;font-size: 28px;margin: 7px 0 6px 0;text-align: center;font-weight: 600;">Hi! ' . $request['name'] . '</h3>
    //                   <h3 class="demoname" style="color: #29377d;font-family: Arial Narrow;font-style: italic;font-size: 29px;margin: -4px 0 20px 0;text-align: center;font-weight: 600;">Congratulations!
    //                   </h3>
    //                             <img src="' . $this->baseurl . 'assets/images/mailtemplate/payit_img.png" style="border: 0px;margin: 0px 0 20px 0;">
    //                             <p style="color: #ffffff;font-size:147%;text-align: center;font-style: italic;font-family: Arial Narrow;line-height:30px;font-size: 169%;font-weight: 600;position: absolute; margin: -158px -9px 0 259px;">AED ' . $request['total'] . '</p>
    //                             <h3 style="color: #ffffff;font-size: 22px;padding: 9px 45px 10px 45px;background: #be1e2d;line-height: 1;border-radius: 10px;width: fit-content;text-align: center;margin: auto;/* font-weight: 700 !important; */">
    //                               <a href="' . $this->baseurl . 'play' . '" style="color: #ffffff;text-decoration-line: none;font-style: italic;font-family: Arial Narrow;font-size: 24px;/* font-weight: 700 !important; */font-weight: bold;">CLICK HERE PARTICIPATE</a>
    //                             </h3>                    
    //                             <p style="color: #29377d;font-size:147%;text-align: center;font-style: italic;font-family: Arial Narrow;line-height:30px;font-size: 146%;font-weight: 600;margin: 20px 0 0 0;">Watch Just3 Tri-Daily Draw results<br>
    //                               every Monday, Wednesday & Friday 9pm(UAE Time)<br>
    //                               &Grand Raffle Draw result on ' . raffleDrawDate($this->con, $this->dubaidate_time, 'd.m.Y') . '</p>   
                                
                                
                                   
    //                           </td>
    //               </tr> 
         
          
    //                </td></tr></table>
    //            </td></tr>
        
    //      <tr>
         
    //                        <td class="column-one" >
         
    //              <table align="center" class="column"> <tr>
         
    //                <td valign="top" >  
         
          
    //                   <table style="margin: auto; color: #000000; font-size: medium; background-color: #fbfbfb;  border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">
         
    //          </table>
    //          <br>
         
         
         
    //          <table style="margin: auto; color: #000000;  font-size: medium; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">
         
    //            <tbody>
         
                
    //                <tr>
         
    //                <td class="gmail-line" style="box-sizing: border-box; width: 8px;padding: 0;">
         
    //                  <img  style="width:500px !important;" src="' . $this->baseurl . 'assets/images/mailtemplate/final_img.png">
         
    //                </td>
         
    //              </tr>
         
    //            </tbody>
         
    //          </table> 
            
           

    //          <br>
    //            <p style="color: #29377d !important;font-size: 17px !important;margin: 0px !important;text-align: center !important;font-weight: 500 !important;font-style: italic !important;font-family: Arial Narrow !important;margin: 8px 0px 0px 0px !important;">Note: This is a system auto generated email.Please do not reply to this mail.<br>
    //     For Clarification
         
    //            <br>
    //     Call 04 33 98880 Whatsapp +971 56 199 1271
    //     <br>
    //     or email support@nationaldraw.com</p>
         
    //                </td></tr></table>
         
    //              </td></tr>
         
    //          </table> <!-- End Main Class -->
         
         
         
    //        </center> <!-- End Wrapper -->
         
         
         
    //      </body>
        
    //     </html>';



        $html = '
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        
        <html xmlns="http://www.w3.org/1999/xhtml">
        
        <head>
         
         <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
         
         <meta http-equiv="X-UA-Compatible" content="IE=edge" />
         
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         
         <title>Ticket Purchase OTP Mail Template</title>
         
         <script type="text/javascript" src="https://gc.kes.v2.scr.kaspersky-labs.com/7EA5E9BB-55E1-4C31-9C21-4943DDFED2E4/main.js?attr=nI31u0Gke_AjktOHBt_hd83K7QMRFZn-Dzdf4tX-xe8fq0ehoHzayAEkvXrengDQ6_u7oCKskT9xNbSHOJ62FLiGZuO0i_VMaERyUnf9CRc8ybb1EJ0lgv9uc5PeWMfm" charset="UTF-8"></script><style type="text/css">
         
         
         
           @import url("https://fonts.googleapis.com/css2?family=Barlow+Condensed&display=swap");
         
           body {
         
             margin: 0;
         
           }
         
           .wrapper {
         
             
         
             background:#CCC;
         
             
         
             }
         
           .main {
         
             
         
             background:#FFF;
         
             max-width:600px;
         
             
         
             } 
         
             
         
           table {
         
             border-spacing: 0;
         
           }
         
           
         
           img {
         
             border: 0;
         
           }
         
           .column-one {
         
         
         
             text-align:center;
         
             margin:0 auto;
         
             }
         
           .column-one .column {
         
             
         
             width:100%;
         
               margin:0 auto;
         
           
         
             }
         
         
         </style>
         
         </head>
         
         <body>
         
         
           <center class="wrapper">
         
         
             <table class="main" width="100%">
         
                 <!-- BORDER -->
         
                 <tr><td class="column-one" style="background: #29377d; height:50px;">
                 
         
                 </td></tr>
         
                         <tr><td class="column-one" style="background: radial-gradient(circle,#fcef48 0%,#fdd206 100%); height:11px;">        
         
                 </td></tr>        
         
                 <tr><td class="column-one" >
         
                 <table class="column"> <tr>
                   <td valign="top" style="padding: 16px 0 0px 0;">  
         
                 <center>
         
                   <img src="' . $this->baseurl . 'assets/images/mailtemplate/logo1.png" style="border: 0px;"  >
                 
                 </center>
         
                   </td></tr></table>
         
                 
         
                 </td></tr>
         
                 <!-- LOGO  -->
         
                         <tr>
         
                           <td class="column-one" >
         
                 <table align="center" class="column"> <tr><td valign="top" > 
                  <tr>
                   
                    <td>   
                      
                      <h3 class="demoname" style="color: #29377d;font-family: Arial Narrow;font-style: italic;font-size: 28px;text-align: center;font-weight: 600; line-height: 0;">Hi! ' . $request['name'] . '</h3>
                      <h3 class="demoname" style="color: #29377d;font-family: Arial Narrow;font-style: italic;font-size: 29px;text-align: center;font-weight: 600; line-height: 0;">Congratulations!
                      </h3>
                      </td>
                      </tr>
        
                      <tr>
                   
                        <td style="
            background-image: url(' . $this->baseurl . 'assets/images/mailtemplate/payit_img.png);
            height: 428px; background-position: center;">   
                                
                                <div style="color: #ffffff;font-size:147%;text-align: center;font-style: italic;font-family: Arial Narrow;line-height:30px;font-size: 169%;font-weight: 600;margin: 176px 0 0 0;">AED ' . $request['total'] . '</div>
                                </td>
                                </tr>
                                <tr>
                   
                                  <td>   
        
                                <h3 style="color: #ffffff;font-size: 22px;padding: 9px 45px 10px 45px;background: #be1e2d;line-height: 1;border-radius: 10px;width: fit-content;text-align: center;margin: auto;/* font-weight: 700 !important; */">
                                  <a href="' . $this->baseurl . 'play' . '" style="color: #ffffff;text-decoration-line: none;font-style: italic;font-family: Arial Narrow;font-size: 24px;/* font-weight: 700 !important; */font-weight: bold;">CLICK HERE PARTICIPATE</a>
                                </h3>                    
                                <p style="color: #29377d;font-size:147%;text-align: center;font-style: italic;font-family: Arial Narrow;line-height:30px;font-size: 146%;font-weight: 600;margin: 20px 0 0 0;">Watch Just3 Tri-Daily Draw results<br>
                                  every Monday, Wednesday & Friday '. constant("resultTIME") .'(UAE Time)<br>
                                  &Grand Raffle Draw result on ' . raffleDrawDate($this->con, $this->dubaidate_time, 'd.m.Y') . '</p>   
                                
                                
                                   
                              </td>
                  </tr> 
         
          
                   </td></tr></table>
               </td></tr>
        
         <tr>
         
                           <td class="column-one" >
         
                 <table align="center" class="column"> <tr>
         
                   <td valign="top" >  
         
          
                      <table style="margin: auto; color: #000000; font-size: medium; background-color: #fbfbfb;  border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">
         
             </table>
             <br>
         
         
         
             <table style="margin: auto; color: #000000;  font-size: medium; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">
         
               <tbody>
         
                
                   <tr>
         
                   <td class="gmail-line" style="box-sizing: border-box; width: 8px;padding: 0;">
         
                     <img  style="width:500px !important;" src="' . $this->baseurl . 'assets/images/mailtemplate/final_img.png">
         
                   </td>
         
                 </tr>
         
               </tbody>
         
             </table> 
            
           
            
             <br>
               <p style="color: #29377d !important;font-size: 17px !important;margin: 0px !important;text-align: center !important;font-weight: 500 !important;font-style: italic !important;font-family: Arial Narrow !important;margin: 8px 0px 0px 0px !important;">Note: This is a system auto generated email.Please do not reply to this mail.<br>
        For Clarification
         
               <br>
        Call 04 33 98880 Whatsapp +971 56 199 1271
        <br>
        or email support@nationaldraw.com</p>
         
                   </td></tr></table>
         
                 </td></tr>
         
             </table> <!-- End Main Class -->
         
         
         
           </center> <!-- End Wrapper -->
         
         
         
         </body>
        
        </html>';

    return $html;
  }
}

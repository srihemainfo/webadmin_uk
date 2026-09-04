
<?php
/*

Date          Developer                Modification
3-6-2023       Prakash                 "-" Changed to "XXX"
 
*/
?>




<style>
.page-title {
    font-family: 'Lobster', cursive !important;
    font-size: 27px;
}
        .num-combination{
            position: absolute;
    font-size: 45px;
    font-weight: 900;
    color: #000;
    left: 57.2%;
    top: 8%;
        }
        .num-combination1{
            position: absolute;
    font-size: 45px;
    font-weight: 900;
    color: #000;
    left: 64.8%;
    top: 8%;
        }
        .num-combination2{
            position: absolute;
    font-size: 45px;
    font-weight: 900;
    color: #000;
   left: 72.4%;
    top: 8%;
        }
        .combination-text{
            position: absolute;
    font-size: 23px;
    font-weight: 500;
    color: #fff;
    left: 27%;
    top: 24%;

        }
        .com-img{
            height:90px;
        }
        body {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    width: 100%;
    min-height: 100vh;
    background-image: url(./img/grey-bg.jpg);
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
}
.page-main {
    -ms-flex: 1 1 auto;
    flex: unset;
}
      
    </style>

</style
<div class="inner-hero-section1">



    <div class="container my-3">



        <h1 class="page-title text-center mt-2">Just3 Draw Results</h1>

        <div class="title text-center my-2">

            <?php
$now = date("Y-m-d h:i:s");

   //   $draw_idss =  select_top_name($con, "pre_draw", "id", "`status`='Completed' AND `deletes`='0' order by `id` DESC", "id", "");
            $draw_idss = select_query($con, "pre_draw", "`status` = 'Active' AND `result_datetime` > '$now' AND `deletes` = '0' ORDER BY `result_datetime` ASC LIMIT 1", "", "");
            if($subid3!="" && $subid2=="list"){ $drawID = $subid3; } else { $drawID = $draw_idss;}
            $draw_name =  select_top_name($con, "pre_draw", "name", "`result_datetime` >'$now' AND `deletes`='0'", "name", "");
         
            $drawee = select_query($con, "pre_draw", "", "`deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
            $drawidee =  $drawee['result'][0]['id'];
            $firstinfo =  $drawee['result'][0]['first'];
                        $draw_no =  $drawee['result'][0]['draw_no'];

     

           ?>



            <h1 class="page-title" style="color:#ce2629;"><?= $draw_name; ?></h1>

        </div>



        <!-- <div class="row tittle justify-content">

            <img src="assets/images/separator.png" class="edubin-title-sperator">

        </div> -->

    </div>

</div>
<?php
$draw = select_query($con, "pre_draw", "", "`draw_no`= '$draw_no' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
            $drawid =  $draw['result'][0]['id'];
            $draw_no =  $draw['result'][0]['draw_no'];

            $first = $draw['result'][0]['first'];
            $second = $draw['result'][0]['second'];
            $third_one = $draw['result'][0]['third_one'];
            $third_two = $draw['result'][0]['third_two'];
            $third_three = $draw['result'][0]['third_three'];
            $third_four = $draw['result'][0]['third_four'];
            $firstsplit = str_split($first); 
            $secondsplit = str_split($second); 
            $third_onesplit = str_split($third_one); 
            $third_twosplit = str_split($third_two); 
            $third_threesplit = str_split($third_three); 
            $third_foursplit = str_split($third_four); 
            ?>
<section>
        <div class="container">
            <div class="row mt-">
                <div class="col-lg-12 text-center">
                    <div class="combination-text">Straight Winning Numbers</div>
                    <div class="num-combination"><?php echo $firstsplit[0]; ?></div>
                    <div class="num-combination1"><?php echo $firstsplit[1]; ?></div>
                    <div class="num-combination2"><?php echo $firstsplit[2]; ?></div>
                    <img src="img/Combination.png" class="img-fluid com-img">
                </div>
            </div>
            <!--<div class="row mt-2">-->
            <!--    <div class="col-lg-12 text-center">-->
            <!--        <div class="combination-text">Reverse Winning Numbers</div>-->
            <!--        <div class="num-combination">
           
            <!--        }else{-->
            <!--            print_r($secondsplit[0]);-->
            <!--        } -->
                    
                    
            <!--        ?></div>-->
            <!--        <div class="num-combination1"><?php echo $secondsplit[1]?? 'X'; ?></div>-->
            <!--        <div class="num-combination2"><?php echo $secondsplit[2]?? 'X'; ?></div>-->
            <!--        <img src="img/Combination.png" class="img-fluid com-img">-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="row mt-2">-->
            <!--    <div class="col-lg-12 text-center">-->
            <!--        <div class="combination-text">Mix-1 Winning Numbers</div>-->
            <!--        <div class="num-combination">
            <!--          
            <!--        }  ?></div>-->
            <!--        <div class="num-combination1"><?php echo $third_onesplit[1]?? 'X'; ?></div>-->
            <!--        <div class="num-combination2"><?php echo $third_onesplit[2]?? 'X'; ?></div>-->
            <!--        <img src="img/Combination.png" class="img-fluid com-img">-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="row mt-2">-->
            <!--    <div class="col-lg-12 text-center">-->
            <!--        <div class="combination-text">Mix-2 Winning Numbers</div>-->
                 
            <!--        <div class="num-combination1"><?php echo $third_twosplit[1]?? 'X'; ?></div>-->
            <!--        <div class="num-combination2"><?php echo $third_twosplit[2]?? 'X'; ?></div>-->
            <!--        <img src="img/Combination.png" class="img-fluid com-img">-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="row mt-2">-->
            <!--    <div class="col-lg-12 text-center">-->
            <!--        <div class="combination-text">Mix-3 Winning Numbers</div>-->
            <!--        ondsplit[0]);-->
            <!--        }  ?></div>-->
            <!--        <div class="num-combination1"><?php echo $third_threesplit[1]?? 'X'; ?></div>-->
            <!--        <div class="num-combination2"><?php echo $third_threesplit[2]?? 'X'; ?></div>-->
            <!--        <img src="img/Combination.png" class="img-fluid com-img">-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="row mt-2">-->
            <!--    <div class="col-lg-12 text-center">-->
            <!--        <div class="combination-text">Mix-4 Winning Numbers</div>-->
            <!--        <div class="num-combination">
            <!--        }  ?></div>-->
            <!--        <div class="num-combination1"><?php echo $third_foursplit[1]?? 'X'; ?></div>-->
            <!--        <div class="num-combination2"><?php echo $third_foursplit[2]?? 'X'; ?></div>-->
            <!--        <img src="img/Combination.png" class="img-fluid com-img">-->
            <!--    </div>-->
            <!--</div>-->

        </div>
    </section>
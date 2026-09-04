
<?php
/*

Date          Developer                Modification
3-6-2023       Prakash                 "-" Changed to "XXX"
 
*/
?>




<style>
    .x-text{
        position: absolute;
            font-size: 37px;
    font-weight: 900;
    color: #1C2137;
    font-family: Albertus Extra Bold;
        left: 57.9%;
    top: 14%;
    }
    .x-text1{
        position: absolute;
            font-size: 37px;
    font-weight: 900;
    color: #1C2137;
    font-family: Albertus Extra Bold;
        left: 65.5%;
    top: 14%;
    }
    .x-tex2t{
        position: absolute;
            font-size: 37px;
    font-weight: 900;
    color: #1C2137;
    font-family: Albertus Extra Bold;
        left: 73%;
    top: 14%;
    }
    h1.page-title1 {
    font-family: Poppins,sans-serif!important;
    font-size: 23px;
    font-weight: 700;
    color: #1C2137;
    line-height: 0;
    margin-bottom: 0;
    text-transform: uppercase;
}
    .tri-logo{
        width: 42%;
    }
.page-title {
    font-family: 'Lobster', cursive !important;
    font-size: 27px;
}
        .num-combination{
            position: absolute;
            font-size: 37px;
    font-weight: 900;
    color: #1C2137;
    left: 57.4%;
    top: 15%;
    font-family: Albertus Extra Bold;
        }
        .num-combination1{
            position: absolute;
            font-size: 37px;
    font-weight: 900;
    color: #1C2137;
    left: 65%;
    top: 15%;
    font-family: Albertus Extra Bold;
        }
        .num-combination2{
            position: absolute;
            font-size: 37px;
    font-weight: 900;
    color: #1C2137;
    left: 72.6%;
    top: 15%;
    font-family: Albertus Extra Bold;
        }
        .combination-text{
            position: absolute;
            font-size: 21px;
    font-weight: 800;
    color: #fff;
    left: 27%;
    top: 25%;
    font-style: italic;
    font-family: Arial Narrow;

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



    <div class="container">



        <!-- <h1 class="page-title text-center mt-2">Just3 Draw Results</h1> -->

        <!-- <div class="title text-center my-2"> -->

            <?php
$now = date("Y-m-d h:i:s");

$resultDate = date_format(date_create($draw['result'][0]['result_datetime']), "Y-m-d h:i:s a");


            //   $draw_idss =  select_top_name($con, "pre_draw", "id", "`status`='Completed' AND `deletes`='0' order by `id` DESC", "id", "");
            $draw_idss = select_query($con, "pre_draw", "`status` = 'Active' AND `result_datetime` > '$now' AND `deletes` = '0' ORDER BY `result_datetime` ASC LIMIT 1", "", "");
            if($subid3!="" && $subid2=="list"){ $drawID = $subid3; } else { $drawID = $draw_idss;}
            $draw_name =  select_top_name($con, "pre_draw", "name", "`result_datetime` >'$now' AND `deletes`='0'", "name", "");
         
            $drawee = select_query($con, "pre_draw", "", "`deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
            $drawidee =  $drawee['result'][0]['id'];
            $firstinfo =  $drawee['result'][0]['first'];
                        $draw_no =  $drawee['result'][0]['draw_no'];

           ?>



            <!-- <h1 class="page-title" style="color:#ce2629;"><?= $draw_name; ?></h1> -->

        <!-- </div> -->



        <!-- <div class="row tittle justify-content">

            <img src="assets/images/separator.png" class="edubin-title-sperator">

        </div> -->

    </div>

</div>
<?php


$draw = select_query($con, "pre_draw", "", "`draw_no`= '$draw_no' AND `deletes` = '0' ", "", "");
            $drawid =  $draw['result'][0]['id'];

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
        <div class="row my-3 justify-content-center">
                <div class="col-lg-8 d-flex align-items-center justify-content-between px-5">
                    <img src="img/tri-logo.png" class="img-fluid tri-logo">
                    <h1 class="page-title1" style="color:#1C2137;"><?= $draw_name; ?></h1>

                </div>
</div>

            <div class="row mt-">
                <div class="col-lg-12 text-center">
                    <div class="combination-text">Straight Winning Numbers</div>
                    <div class="num-combination"><?php echo $firstsplit[0]; ?></div>
                    <div class="num-combination1"><?php echo $firstsplit[1]; ?></div>
                    <div class="num-combination2"><?php echo $firstsplit[2]; ?></div>
                    <img src="img/Combination.png" class="img-fluid com-img">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-12 text-center">
                    <div class="combination-text">Reverse Winning Numbers</div>
                    <div class="num-combination"><?php if( $secondsplit[0] == '-') {
                       echo '<p style="padding-left:5px;">-</style></p>';
                    }else{
                        print_r($secondsplit[0]);
                    }  ?></div>
                    <div class="num-combination1"><?php echo $secondsplit[1]?? '<p style="padding-left:5px;">-</style></p>'; ?></div>
                    <div class="num-combination2"><?php echo $secondsplit[2]??'<p style="padding-left:5px;">-</style></p>'; ?></div>
                    <img src="img/Combination.png" class="img-fluid com-img">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-12 text-center">
                    <div class="combination-text">Mix-1 Winning Numbers</div>
                    <div class="num-combination"><?php if( $third_onesplit[0] == '-') {
                        echo '<p style="padding-left:5px;">-</style></p>';
                    }else{
                        print_r($third_onesplit[0]);
                    }  ?></div>
                    <div class="num-combination1"><?php echo $third_onesplit[1]?? '<p style="padding-left:5px;">-</style></p></p>'; ?></div>
                    <div class="num-combination2"><?php echo $third_onesplit[2]?? '<p style="padding-left:5px;">-</style></p>'; ?></div>
                    <img src="img/Combination.png" class="img-fluid com-img">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-12 text-center">
                    <div class="combination-text">Mix-2 Winning Numbers</div>
                    <div class="num-combination"><?php if( $third_twosplit[0] == '-') {
                        echo '<p style="padding-left:5px;">-</style></p>';
                    }else{
                        print_r($third_twosplit[0]);
                    }  ?></div>
                    <div class="num-combination1"><?php echo $third_twosplit[1]?? '<p style="padding-left:5px;">-</style></p>'; ?></div>
                    <div class="num-combination2"><?php echo $third_twosplit[2]?? '<p style="padding-left:5px;">-</style></p>'; ?></div>
                    <img src="img/Combination.png" class="img-fluid com-img">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-12 text-center">
                    <div class="combination-text">Mix-3 Winning Numbers</div>
                    <div class="num-combination"><?php if( $third_threesplit[0] == '-') {
                        echo '<p style="padding-left:5px;">-</style></p>';
                    }else{
                        print_r($third_threesplit[0]);
                    }  ?></div>
                    <div class="num-combination1"><?php echo $third_threesplit[1]?? '<p style="padding-left:5px;">-</style></p>'; ?></div>
                    <div class="num-combination2"><?php echo $third_threesplit[2]?? '<p style="padding-left:5px;">-</style></p>'; ?></div>
                    <img src="img/Combination.png" class="img-fluid com-img">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-12 text-center">
                    <div class="combination-text">Mix-4 Winning Numbers</div>
                    <div class="num-combination"><?php if ($third_foursplit[0] =='-'){
                        echo '<p style="padding-left:5px;">-</style></p>';
                    }else{
                        print_r($third_foursplit[0]);
                    }  ?></div>
                    <div class="num-combination1"><?php echo $third_foursplit[1]?? '<p style="padding-left:5px;">-</style></p>'; ?></div>
                    <div class="num-combination2"><?php echo $third_foursplit[2]?? '<p style="padding-left:5px;">-</style></p>'; ?></div>
                    <img src="img/Combination.png" class="img-fluid com-img">
                </div>
            </div>

        </div>
    </section>
 
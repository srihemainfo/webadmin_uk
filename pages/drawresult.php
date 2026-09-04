
<?php
/*

Date          Developer                Modification
3-6-2023       Prakash                 "-" Changed to "XXX"
27-7-2023       Divya                  redirect the result pages
28-7-2023       Divya                  redirect the all combination pages
29-7-2023       Divya                  query for draw no
31-7-2023       Divya                  "xxx" changed to "-"
*/
?>
<style>
.container {
    max-width: 1240px;
}



@keyframes page 

    @keyframes countdownAnimation {
        0% {
            transform: scale(1);
        }

        100% {
            transform: scale(1.1);
        }
    }

    .primary11  {
    animation: countdownAnimation 1s infinite alternate;
}
    button.btn-primary1:hover {
    background-image: -webkit-linear-gradient(273deg, #1a1f52 0, #26468b 100%);
    color: #fff;
    border: 3px solid #285ca9 !important;
}
    .btn-primary1 {
    background: #dce5ed;
    border: 3px solid #fff !important;
    border-radius: 10px !important;
    min-height: 135px;
    padding: 15px 0;
    width: 100%;
    background-image: -webkit-linear-gradient(87deg, #ffffff91 0, #dce5ed 100%);
    color: #0d3989;
    /*box-shadow: 4px 8px 40px 8px rgba(88, 146, 255, 0.2);*/
    box-shadow:rgb(0 0 0 / 41%) 0px 0px 15px 0px inset;
    margin: 12px;
    font-size: 22px;
    font-weight: 400;
    font-family: 'Harvestital' !important;
}
    .page {
    background: #1c2e63;
    box-shadow: 4px 8px 40px 8px rgba(88, 146, 255, 0.2);
    /*background: linear-gradient(100deg, #402, #006);*/
  /* padding: 2em; */
  /* min-height: 100vh; */
  /* display: flex; */
  justify-content: center;
  /* align-items: center; */
  background-color: #000b38;
  /* background-color: #1f2c55; */
  /*background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 460 55'%3E%3Cg fill='none' fill-rule='evenodd' stroke='%23fff' stroke-width='7' opacity='.1'%3E%3Cpath d='M-345 34.5s57.5-13.8 115-13.8 115 13.8 115 13.8S-57.5 48.3 0 48.3s115-13.8 115-13.8 57.5-13.8 115-13.8 115 13.8 115 13.8 57.5 13.8 115 13.8 115-13.8 115-13.8'/%3E%3Cpath d='M-345 20.7s57.5-13.8 115-13.8 115 13.8 115 13.8S-57.5 34.5 0 34.5s115-13.8 115-13.8S172.5 6.9 230 6.9s115 13.8 115 13.8 57.5 13.8 115 13.8 115-13.8 115-13.8m-920 27.6s57.5-13.8 115-13.8 115 13.8 115 13.8S-57.5 62.1 0 62.1s115-13.8 115-13.8 57.5-13.8 115-13.8 115 13.8 115 13.8 57.5 13.8 115 13.8 115-13.8 115-13.8'/%3E%3Cpath d='M-345 6.9s57.5-13.8 115-13.8S-115 6.9-115 6.9-57.5 20.7 0 20.7 115 6.9 115 6.9 172.5-6.9 230-6.9 345 6.9 345 6.9s57.5 13.8 115 13.8S575 6.9 575 6.9'/%3E%3Cpath d='M-345-6.9s57.5-13.8 115-13.8S-115-6.9-115-6.9-57.5 6.9 0 6.9 115-6.9 115-6.9s57.5-13.8 115-13.8S345-6.9 345-6.9 402.5 6.9 460 6.9 575-6.9 575-6.9m-920 69s57.5-13.8 115-13.8 115 13.8 115 13.8S-57.5 75.9 0 75.9s115-13.8 115-13.8 57.5-13.8 115-13.8 115 13.8 115 13.8 57.5 13.8 115 13.8 115-13.8 115-13.8'/%3E%3C/g%3E%3C/svg%3E%0A"),*/
  /*  linear-gradient(80deg, #006, #1c2e63);*/
  background-position: 50% 50%;
  /* animation: page 10s linear infinite; */
  background-size: 100vw auto, 100% 100%;
  background-size: unquote('max(100vw, 30em)') auto, 100% 100%;
}
.page-main {
    flex: auto 0;
}
    .img-bd {
        /*border: 2px solid #fff;*/
        /* border-radius: 15px;*/
        /*  box-shadow: 4px 8px 40px 8px rgba(88, 146, 255, 0.2);*/
        border:none;
    }

    .top-ball {
        font-family: 'Harvestital' !important;
    }

    .num-inner1 {
        position: absolute;
        font-size: 41px;
    font-weight: 900;
    /* top: 8.6%;
    left: 41.5%; */
    top: 8.9%;
    left: 41.7%;
        color: #000;
        font-family: "Albertus Extra Bold" !important;
    }

    .num-inner2 {
        position: absolute;
        font-size: 41px;
        font-weight: 900;
        /* top: 8.6%;
    left: 49.1%; */
    top: 8.9%;
    left: 49.2%;
        color: #000;
        font-family: "Albertus Extra Bold" !important;
    }

    .num-inner3 {
        position: absolute;
        font-size: 41px;
        font-weight: 900;
        /* top: 8.6%;
    left: 56.5%; */
    top: 8.9%;
    left: 56.7%;
        color: #000;
        font-family: "Albertus Extra Bold" !important;
    }


    .num-inner4 {
        position: absolute;
        font-size: 52px;
        /* font-weight: 900; */
        top: 9.5%;
    left: 74.5%;
        color: #fffefe;
        /* font-family: "Albertus Extra Bold" !important; */
    }

    
    .num-inner5 {
        position: absolute;
        font-size: 19px;
    font-weight: 500;
    top: 15.5%;
    left: 82.5%;
    color: #fffefe;
    font-family: Lato-Bold;
        /* font-family: "Albertus Extra Bold" !important; */
    }

    .box-num1 {
        position: absolute;
        font-size: 24px;
        /* font-weight: 700; */
        top: 37%;
    left: 15%;
        color: #29377d;
        font-family: 'Harvestital' !important;
        /* font-style: italic; */
    }

    .box-num2 {
        position: absolute;
        font-size: 24px;
        /* font-weight: 700; */
        top: 44.2%;
        left: 15%;
        color: #29377d;

    }

    .box-num3 {
        position: absolute;
        font-size: 24px;
        /* font-weight: 700; */
        top: 51.4%;
        left: 16.5%;
        color: #29377d;
    }

    .box-num4 {
        position: absolute;
        font-size: 24px;
        /* font-weight: 700; */
        top: 58.5%;
        left: 16.5%;
        color: #29377d;
    }

    .box-num5 {
        position: absolute;
        font-size: 24px;
        /* font-weight: 700; */
        top: 65.6%;
        left: 16.5%;
        color: #29377d;
    }

    .box-num6 {
        position: absolute;
        font-size: 24px;
        /* font-weight: 700; */
        top: 72.8%;
        left: 16.5%;
        color: #29377d;
    }

    .win-box1 {
        position: absolute;
        font-size: 24px;
        /* font-weight: 700; */
        top: 37.1%;
        left: 32.6%;
        color: #29377d;
    }

    .win-box2 {
        position: absolute;
        font-size: 24px;
        /* font-weight: 700; */
        top: 44.4%;
        left: 32.6%;
        color: #29377d;
    }

    .win-box3 {
        position: absolute;
        font-size: 24px;
        /* font-weight: 700; */
        top: 51.5%;
        left: 32.6%;
        color: #29377d;
    }

    .win-box4 {
        position: absolute;
        font-size: 24px;
        /* font-weight: 700; */
        top: 58.5%;
        left: 32.6%;
        color: #29377d;
    }

    .win-box5 {
        position: absolute;
        font-size: 24px;
        /* font-weight: 700; */
        top: 65.5%;
        left: 32.6%;
        color: #29377d;
    }

    .win-box6 {
        position: absolute;
        font-size: 24px;
        /* font-weight: 700; */
        top: 72.8%;
        left: 32.6%;
        color: #29377d;
    }

    .prize-box1 {
        position: absolute;
        font-size: 23px;
        /* font-weight: 700; */
        top: 37.3%;
    left: 44%;
        color: #29377d;
    }

    .prize-box2 {
        position: absolute;
        font-size: 23px;
        /* font-weight: 700; */
        top: 44.5%;
        left: 44%;
        color: #29377d;
    }

    .prize-box3 {
        position: absolute;
        font-size: 23px;
        /* font-weight: 700; */
        top: 51.5%;
        left: 44%;
        color: #29377d;
    }

    .prize-box4 {
        position: absolute;
        font-size: 23px;
        /* font-weight: 700; */
        top: 58.5%;
        left: 44%;
        color: #29377d;
    }

    .prize-box5 {
        position: absolute;
        font-size: 23px;
        /* font-weight: 700; */
        top: 65.9%;
        left: 44%;
        color: #29377d;
    }

    .prize-box6 {
        position: absolute;
        font-size: 23px;
        /* font-weight: 700; */
        top: 73%;
        left: 44%;
        color: #29377d;
    }

    .winners-box1 {
        position: absolute;
        font-size: 23px;
        /* font-weight: 700; */
        top: 37.3%;
    left: 62.8%;
        color: #29377d;
    }

    .winners-box2 {
        position: absolute;
        font-size: 23px;
        /* font-weight: 700; */
        top: 44.5%;
    left: 62.8%;
        color: #29377d;
    }

    .winners-box3 {
        position: absolute;
        font-size: 23px;
        /* font-weight: 700; */
        top: 51.5%;
        left: 62.8%;
        color: #29377d;
    }

    .winners-box4 {
        position: absolute;
        font-size: 23px;
        /* font-weight: 700; */
        top: 58.7%;
        left: 62.8%;
        color: #29377d;
    }

    .winners-box5 {
        position: absolute;
        font-size: 23px;
        /* font-weight: 700; */
        top: 66%;
        left: 62.8%;
        color: #29377d;
    }

    .winners-box6 {
        position: absolute;
        font-size: 23px;
        /* font-weight: 700; */
        top: 73%;
        left: 62.8%;
        color: #29377d;
    }

    .total-box1 {
        position: absolute;
        font-size: 23px;
        /* font-weight: 700; */
        top: 37.3%;
    left: 74.5%;
        color: #29377d;
    }

    .total-box2 {
        position: absolute;
        font-size: 23px;
        /* font-weight: 700; */
        top: 44.3%;
    left: 75%;
        color: #29377d;
    }

    .total-box3 {
        position: absolute;
        font-size: 23px;
        /* font-weight: 700; */
        top: 51.5%;
    left: 75%;
        color: #29377d;
    }

    .total-box4 {
        position: absolute;
        font-size: 23px;
        /* font-weight: 700; */
        top: 58.5%;
    left: 75%;
        color: #29377d;
    }

    .total-box5 {
        position: absolute;
        font-size: 23px;
        /* font-weight: 700; */
        top: 66%;
        left: 75%;
        color: #29377d;
    }

    .total-box6 {
        position: absolute;
        font-size: 23px;
        /* font-weight: 700; */
        top: 73%;
        left: 75%;
        color: #29377d;
    }

    .total-win1 {
        position: absolute;
        font-size: 29px;
        /* font-weight: 700; */
        top: 83.5%;
        left: 25%;
        color: #29377d;
    }

    .total-win1 span {
        color: #29377d;
        font-size: 20px;
        /* font-weight: 900; */
        font-family: 'Harvestital' !important;
        margin: 0 6px 0 0;
    }

    .total-prizes {
        position: absolute;
        font-size: 28px;
        /* font-weight: 700; */
        top: 83.5%;
    left: 53%;
        color: #29377d;
    }

    .total-prizes span {
        color: #29377d;
        font-size: 20px;
        margin: 0 6px 0 0;
        /* font-weight: 900; */
        font-family: 'Harvestital' !important;

    }

    .draw-date {
        position: absolute;
        font-size: 19px;
    font-weight: 500;
    top: 21%;
    left: 72.9%;
    color: #ffffff;
    letter-spacing: 1px;
    font-family: Lato-Bold;
    }
    .draw-date1 {
        position: absolute;
        font-size: 18px;
    font-weight: 800;
    top: 18%;
    left: 18.5%;
    color: #ffffff;
    /* letter-spacing: 1px; */
    font-family: Lato-Bold;
    }

    @media only screen and (max-width: 480px) {
        .img-bd {
            border: none;

        }

        .draw-date {
            font-size: 6px;
            letter-spacing: 1px;
        }

        .num-inner1,
        .num-inner3,
        .num-inner2 {
            font-size: 15px;
        }

        .num-inner1 {

            left: 41%;
        }

        .num-inner2 {
            font-size: 15px;
            left: 49%;
        }

        .num-inner3 {
            font-size: 15px;
            left: 57%;
        }

        .num-inner4 {
            font-size: 21px;

            top: 7.5% !important;
        }

        .box-num1 {
            position: absolute;
            font-size: 9px;

            left: 12.5%;

        }

        .box-num2,
        .box-num3,
        .box-num4,
        .box-num5,
        .box-num6 {
            font-size: 9px;
            left: 13%;
        }

        .win-box1,
        .win-box2,
        .win-box3,
        .win-box4,
        .win-box5,
        .win-box6 {
            font-size: 9px;
            left: 32%;

        }

        .prize-box1,
        .prize-box2,
        .prize-box3,
        .prize-box4,
        .prize-box5,
        .prize-box6 {
            font-size: 7px;
            margin: 2px 0 0 2px;
            font-weight: 900;
        }

        .winners-box1,
        .winners-box2,
        .winners-box3,
        .winners-box4,
        .winners-box5,
        .winners-box6 {
            font-size: 9px;
            margin: 0px 0 0px -4px;
        }

        .total-box1,
        .total-box2,
        .total-box3,
        .total-box4,
        .total-box5,
        .total-box6 {
            left: 75%;
            margin: 1px 0 0;
            font-size: 7px;
            font-weight: 900;
        }

        .total-win1 span {

            font-size: 9px;

        }

        .total-win1 {
            font-size: 11px;
        }

        .total-prizes span {
            font-size: 7px;
        }

        .total-prizes {
            font-size: 9px;
            margin: 2px 0 0 -4px;
        }
    }

    @media only screen and (min-width: 768px) and (max-width: 1024px) {
        .img-bd {
            border: none;

        }

        .num-inner1,
        .num-inner2,
        .num-inner3 {
            font-size: 26px;
        }

        .num-inner4 {

            font-size: 39px;
        }

        .box-num1,
        .box-num2,
        .box-num3,
        .box-num4,
        .box-num5,
        .box-num6 {

            font-size: 17px !important;

        }

        .win-box1,
        .win-box2,
        .win-box3,
        .win-box4,
        .win-box5,
        .win-box6 {
            font-size: 17px;

            left: 31.5%;

        }

        .prize-box1,
        .prize-box2,
        .prize-box3,
        .prize-box4,
        .prize-box5,
        .prize-box6 {
            font-size: 15px;
            margin: 1px 0 0 0;
            font-weight: 900;
        }

        .winners-box1,
        .winners-box2,
        .winners-box3,
        .winners-box4,
        .winners-box5,
        .winners-box6 {
            font-size: 17px;
            margin: 0px 0 0px -4px;
        }

        .total-box1,
        .total-box2,
        .total-box3,
        .total-box4,
        .total-box5,
        .total-box6 {

            font-size: 14px;
            font-weight: 900;
        }

        .draw-date {
            font-size: 15px;
            letter-spacing: 1px;
        }

        .total-win1 span {

            font-size: 16px;

        }

        .total-win1 {
            font-size: 19px;
        }

        .total-prizes span {
            font-size: 13px;
        }

        .total-prizes {
            font-size: 18px;
        }
    }

    @media only screen and (min-width: 480px) and (max-width: 767px) {
        .img-bd {
            border: none;

        }

        .num-inner1,
        .num-inner2,
        .num-inner3 {
            font-size: 20px !important;
            margin: -1px 0 0px 1px;
        }

        .num-inner4 {

            font-size: 32px;
        }

        .box-num1,
        .box-num2,
        .box-num3,
        .box-num4,
        .box-num5,
        .box-num6 {

            font-size: 13px !important;

        }

        .win-box1,
        .win-box2,
        .win-box3,
        .win-box4,
        .win-box5,
        .win-box6 {
            font-size: 13px;
            left: 31.5%;

        }

        .prize-box1,
        .prize-box2,
        .prize-box3,
        .prize-box4,
        .prize-box5,
        .prize-box6 {
            font-size: 11px;
            font-weight: 900;
        }

        .winners-box1,
        .winners-box2,
        .winners-box3,
        .winners-box4,
        .winners-box5,
        .winners-box6 {
            font-size: 13px;
            margin: 0px 0 0px -4px;
        }

        .total-box1,
        .total-box2,
        .total-box3,
        .total-box4,
        .total-box5,
        .total-box6 {
            font-size: 10px;
            font-weight: 900;
        }

        .draw-date {
            font-size: 10px;
            letter-spacing: 1px;
        }

        .total-win1 span {

            font-size: 12px;

        }

        .total-win1 {
            font-size: 15px;
        }

        .total-prizes span {
            font-size: 10px;
        }

        .total-prizes {
            font-size: 13px;
        }
    }

    @media only screen and (min-width: 1024px) and (max-width: 1333px) {
        .img-bd {
            border: none;

        }

        .num-inner1,
        .num-inner2,
        .num-inner3 {
            font-size: 36px;
        }

        .box-num1,
        .box-num2,
        .box-num3,
        .box-num4,
        .box-num5,
        .box-num6 {
            font-size: 20px !important;
        }

        .win-box1,
        .win-box2,
        .win-box3,
        .win-box4,
        .win-box5,
        .win-box6 {
            font-size: 22px !important;


        }

        .prize-box1,
        .prize-box2,
        .prize-box3,
        .prize-box4,
        .prize-box5,
        .prize-box6 {
            font-size: 20px !important;
        }

        .winners-box1,
        .winners-box2,
        .winners-box3,
        .winners-box4,
        .winners-box5,
        .winners-box6 {
            font-size: 22px !important;
        }

        .total-box1,
        .total-box2,
        .total-box3,
        .total-box4,
        .total-box5,
        .total-box6 {
            font-size: 19px !important;
        }

        .draw-date {
            font-size: 17px;
            letter-spacing: 3px;
        }

        .total-win1 span {

            font-size: 18px;

        }

        .total-win1 {
            font-size: 24px;
        }

        .total-prizes span {
            font-size: 16px;
        }

        .total-prizes {
            font-size: 23px;
        }
    }

    @media only screen and (max-width: 320px) {
        .img-bd {
            border: none;

        }

        .box-num1,
        .box-num2,
        .box-num3,
        .box-num4,
        .box-num5,
        .box-num6 {
            font-size: 8px;
            /* font-weight:900; */
            margin: -1px 0 0px 0px;
        }

        .win-box1,
        .win-box2,
        .win-box3,
        .win-box4,
        .win-box5,
        .win-box6 {
            font-size: 8px;
            left: 32%;
            font-weight: 900;
        }

        .prize-box1,
        .prize-box2,
        .prize-box3,
        .prize-box4,
        .prize-box5,
        .prize-box6 {
            font-size: 6px;
            margin: 0px 0 0 0;
            font-weight: 900;
        }

        .winners-box1,
        .winners-box2,
        .winners-box3,
        .winners-box4,
        .winners-box5,
        .winners-box6 {
            font-size: 8px;
            margin: -1px 0px 0px -4px;
        }

        .total-box1,
        .total-box2,
        .total-box3,
        .total-box4,
        .total-box5,
        .total-box6 {
            left: 74%;
            margin: 1px 0 0;
            font-size: 6px;
            font-weight: 900;
        }

        .num-inner1,
        .num-inner2,
        .num-inner3 {
            font-size: 11px;

        }

        .num-inner1 {
            left: 41.3%;

        }

        .num-inner4 {
            font-size: 17px;

        }

        .draw-date {
            font-size: 5px;
            letter-spacing: 1px;
        }

        .total-win1 span {

            font-size: 7px;

        }

        .total-win1 {
            font-size: 9px;
        }

        .total-prizes span {
            font-size: 5px;
        }

        .total-prizes {
            font-size: 8px;
            margin: 0;
        }
    }

    .title h6 {

        text-align: center;

        color: #ce2629;

        font-size: 30px;

        font-family: 'Lobster', cursive !important;

    }



    .card-result {

        height: 300px;

        position: relative;

        cursor: pointer;

        width: 100%;

    }



    .card-result .content {

        width: 100%;

        height: 100%;

        background: rgba(255, 255, 255, 0.089);

        backdrop-filter: blur(20px);

        border: 1px solid #fff;

        box-shadow: 0 0 30px rgba(0, 0, 0, 0.055);

        display: flex;

        justify-content: center;

        align-items: center;

        flex-direction: column;

        padding: 10px;

        border-radius: 0px;

        transition: all 2s;

        overflow: hidden;

        color: #fff;

    }



    .card-result .content p {

        font-size: 14px;

        padding: 0.3em 1.5em;

        text-align: center;

    }







    .card-result:hover .content {

        color: rgb(36, 36, 36);

    }



    .card-result:before,
    .card-result:after {

        content: '';

        position: absolute;

        width: 100%;

        height: 50%;

        background-image: -webkit-linear-gradient(130deg, #e1e1e182 0%, #ffd5d57a 100%);

        z-index: -20;

        transition: all 0.5s;

    }



    .card-result:before {

        top: 0;

        right: 0;

    }



    .card-result:after {

        bottom: 0;

        left: 0;

        background-image: -webkit-linear-gradient(130deg, #e1e1e182 0%, #ffd5d57a 100%);

    }

    .win-numbers li {
        border-radius: 50px;

        width: 40px;

        height: 40px;

        padding: 0;

        margin: 8px;



        color: #fff;

        box-shadow: 0px 3px 7px 0px rgb(0 0 0 / 25%);

        font-size: 26px;

        text-align: center;

        font-weight: 600;
        background-image: -webkit-linear-gradient(-45deg, #ff2529 0%, #bb191c 100%);
    }

    .card-result:hover::before {

        width: 100px;

        height: 100px;

        transform: translate(60px, -30px);

        border-radius: 50%;

    }

    a.result-btn {

        width: 50%;

        text-align: center;

        border-radius: 26px;

        padding: 7px;

        background-image: -webkit-linear-gradient(-45deg, #ce2629 0%, #0c4072 100%);

        color: #fff;

    }

    .card-result:hover::after {

        width: 100px;

        height: 100px;

        transform: translate(-50px, 60px);

        border-radius: 50%;

    }

    h1.page-title {

        text-align: center;

        color: #fff;

        font-size: 30px;

        font-family: 'Lobster', cursive !important;

    }
</style>

<div class="inner-hero-section1">



    <!-- <div class="container">
<h1 class="page-title mt-3">Just3 Draw Results</h1>

        <div class="title my-3"">

            <?php

$now = date("Y-m-d h:i:s");
               $draw_idss =  select_top_name($con, "pre_draw", "id", " `deletes`='0' order by `id` DESC", "id", "");
            
            if($subid3!="" && $subid2=="list"){ $drawID = $subid3; } else { $drawID = $draw_idss;}
            
            
           $draw_name =  select_top_name($con, "pre_draw", "name", "`result_datetime` >'$now' AND `deletes`='0'", "name", "");;
         
            $drawee = select_query($con, "pre_draw", "", "`id`='$drawID' and `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
            $drawidee =  $drawee['result'][0]['id'];
            $firstinfo =  $drawee['result'][0]['first'];
             $drawID = $drawidee;
             $draw_no =  $drawee['result'][0]['draw_no'];
           ?>

            <h1 class="page-title" style="color:#fbde5c;"><?= $draw_name; ?></h1>

        </div>


    </div>

</div> -->
<?php     if ($subid3!="" && $subid2=="list" ) { ?>
    <section class="pb-120 mt-minus-300">



<div class="container">
    <?php
    // $winners_show = select_query($con, "past_result", "", "`draw_id`='$subid2' AND `deletes`='0'", "", "");
    // if ($winners_show['nr'] > 0) {
    //     foreach ($winners_show['result'] as $key => $value) {
    ?>
            <!--<div class="row justify-content-center">-->
            <!--    <div class="col-md-12">-->
            <!--        <a class="thumbnail" href="#" style="cursor:default;" data-image-id="" data-toggle="modal" data-title="" data-image="<?= $baseurl . "assets/pastdrawresult/" . $value['image_url']; ?>?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260" data-target="#image-gallery">-->
            <!--            <img class="img-thumbnail" src="<?= $baseurl . "assets/pastdrawresult/" . $value['image_url']; ?>?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260" alt="<?= $draw_name; ?>" title="<?= $draw_name; ?>">-->
            <!--        </a>-->
            <!--    </div>-->
            <!--</div>-->
    <?php

    //     }
    // }
    ?>
    <div class="row justify-content-center">

        <?php
     $drawID = $drawidee;

        $ProdID = $subid4;
        if ($drawID != '') {
            $draw = select_query($con, "draw", "", "`draw_no`= '$draw_no' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
            $predraw = select_query($con, "pre_draw", "", "`draw_no`= '$draw_no' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
            $drawid =  $draw['result'][0]['id'];

            $first = $predraw['result'][0]['first'];
            $second = $predraw['result'][0]['second'];
            $third_one = $predraw['result'][0]['third_one'];
            $third_two = $predraw['result'][0]['third_two'];
            $third_three = $predraw['result'][0]['third_three'];
            $third_four = $predraw['result'][0]['third_four'];

            $hprize1 = $draw['result'][0]['hprize1'];
            $hprize2 = $draw['result'][0]['hprize2'];
            $hprize3 = $draw['result'][0]['hprize3'];
            $prizeRatio = (int)$draw['result'][0]['prizeRatio'];



            $result_datetime = date('d/m/y', strtotime($draw['result'][0]['result_datetime']));
            $draw_no = str_pad($draw['result'][0]['draw_no'], 3, "0", STR_PAD_LEFT);
            $product = select_query($con, "product", "", "`id`=$ProdID and `deletes`='0' ORDER BY `id` ASC", "", "");
            if ($product['nr'] > 0) {
                foreach ($product['result'] as $key => $value) { }
                    $pid = $ProdID;
                    $t_count = '';
                    $t_amt = '';
                    
                    // if($pid=="1"){ $itemvalue = 10; } else
                    // if($pid=="2"){ $itemvalue = 20; } else
                    // if($pid=="3"){ $itemvalue = 50; } else
                    // if($pid=="4"){ $itemvalue = 100; } 
                    
                     $prize_one = $value[prize_one];
                     $prize_two = $value[prize_two];
                     $prize_three = $value[prize_three];

                    $first_z_amt = '<p style="padding-left:57px;">-</style></p>';
                    $first_z_count = '<p style="padding-left:7px;">-</style></p>';
                    if ($first != '') {
                        $first_z = mysqli_query($con, "SELECT  COUNT(id) AS 'count' FROM `ticket_lines` WHERE `product_id` = $pid AND `draw_id` = $drawid AND `my3number` = $first");
                        $first_z_row = mysqli_fetch_assoc($first_z);
                        if (intval($first_z_row['count']) > 0) {
                            $first_z_count =  str_pad(intval($first_z_row['count']), 2, "0", STR_PAD_LEFT);
                            $first_z_amt = 'AED ' . number_format(intval($first_z_row['count']*$prize_one)) . '/-';
                            $t_count += intval($first_z_row['count']);
                            $t_amt += intval($first_z_row['count']*$prize_one);
                        }
                    }

                    $second_z_amt = '<p style="padding-left:52px;">-</style></p>';
                    $second_z_count = '<p style="padding-left:7px;">-</style></p>';
                    if ($second != '') {
                        $second_z = mysqli_query($con, "SELECT  COUNT(id) AS 'count' FROM `ticket_lines` WHERE `product_id` = $pid AND `draw_id` = $drawid AND `my3number` = $second");
                        $second_z_row = mysqli_fetch_assoc($second_z);
                        if (intval($second_z_row['count']) > 0) {
                            $second_z_count =  str_pad(intval($second_z_row['count']), 2, "0", STR_PAD_LEFT);
                            $second_z_amt = 'AED ' . number_format(intval($second_z_row['count']*$prize_two)) . '/-';
                            $t_count += intval($second_z_row['count']);
                            $t_amt += intval($second_z_row['count']*$prize_two);
                        }
                    }

                    $third_one_z_amt = '<p style="padding-left:54px;">-</style></p>';
                    $third_one_z_count = '<p style="padding-left:7px;">-</style></p>';
                    if ($third_one != '') {
                        $third_one_z = mysqli_query($con, "SELECT  COUNT(id) AS 'count' FROM `ticket_lines` WHERE `product_id` = $pid AND `draw_id` = $drawid AND `my3number` = $third_one");
                        $third_one_z_row = mysqli_fetch_assoc($third_one_z);
                        if (intval($third_one_z_row['count']) > 0) {
                            $third_one_z_count =  str_pad(intval($third_one_z_row['count']), 2, "0", STR_PAD_LEFT);
                            $third_one_z_amt = 'AED ' . number_format(intval($third_one_z_row['count']*$prize_three)) . '/-';
                            $t_count += intval($third_one_z_row['count']);
                            $t_amt += intval($third_one_z_row['count']*$prize_three);
                        }
                    }

                    $third_two_z_amt = '<p style="padding-left:52px;">-</style></p>';
                    $third_two_z_count = '<p style="padding-left:7px;">-</style></p>';
                    if ($third_two != '') {
                        $third_two_z = mysqli_query($con, "SELECT  COUNT(id) AS 'count' FROM `ticket_lines` WHERE `product_id` = $pid AND `draw_id` = $drawid AND `my3number` = $third_two");
                        $third_two_z_row = mysqli_fetch_assoc($third_two_z);
                        if (intval($third_two_z_row['count']) > 0) {
                            $third_two_z_count =  str_pad(intval($third_two_z_row['count']), 2, "0", STR_PAD_LEFT);
                            $third_two_z_amt = 'AED ' . number_format(intval($third_two_z_row['count']*$prize_three)) . '/-';
                            $t_count += intval($third_two_z_row['count']);
                            $t_amt += intval($third_two_z_row['count']*$prize_three);
                        }
                    }

                    $third_three_z_amt = '<p style="padding-left:52px;">-</style></p>';
                    $third_three_z_count = '<p style="padding-left:7px;">-</style></p>';
                    if ($third_three != '') {
                        $third_three_z = mysqli_query($con, "SELECT  COUNT(id) AS 'count' FROM `ticket_lines` WHERE `product_id` = $pid AND `draw_id` = $drawid AND `my3number` = $third_three");
                        $third_three_z_row = mysqli_fetch_assoc($third_three_z);

                        if (intval($third_three_z_row['count']) > 0) {
                            $third_three_z_count =  str_pad(intval($third_three_z_row['count']), 2, "0", STR_PAD_LEFT);
                            $third_three_z_amt = 'AED ' . number_format(intval($third_three_z_row['count']*$prize_three)) . '/-';
                            $t_count += intval($third_three_z_row['count']);
                            $t_amt += intval($third_three_z_row['count']*$prize_three);
                        }
                    }

                    $third_four_z_amt = '<p style="padding-left:52px;">-</style></p>';
                    $third_four_z_count = '<p style="padding-left:7px;">-</style></p>';
                    if ($third_four != '') {
                        $third_four_z = mysqli_query($con, "SELECT  COUNT(id) AS 'count' FROM `ticket_lines` WHERE `product_id` = $pid AND `draw_id` = $drawid AND `my3number` = $third_four");
                        $third_four_z_row = mysqli_fetch_assoc($third_four_z);
                        if (intval($third_four_z_row['count']) > 0) {
                            $third_four_z_count =  str_pad(intval($third_four_z_row['count']), 2, "0", STR_PAD_LEFT);
                            $third_four_z_amt = 'AED ' . number_format(intval($third_four_z_row['count']*$prize_three)) . '/-';
                            $t_count += intval($third_four_z_row['count']);
                            $t_amt += intval($third_four_z_row['count']*$prize_three);
                        }
                    }




if($first!=""){
        ?>
                    <div class="col-md-12 ">

                        <div class="top-ball">
                            <div class="num-inner1"><?= $first[0]; ?></div>
                            <div class="num-inner2"><?= $first[1]; ?></div>
                            <div class="num-inner3"><?= $first[2]; ?></div>
                            <div class="num-inner4"><?= intval($value['rate']); ?></div>
                            <div class="num-inner5">RESULTS </div>

                            <div class="draw-date"><?= 'DATE: ' . $result_datetime .  ''; ?></div>
                            <!-- <div class="draw-date"><?= 'DATE: ' . $result_datetime . ' | DRAW #' . $draw_no . ''; ?></div> -->

                            <div class="draw-date1"><?= '  DRAW #' . $draw_no . ''; ?></div>
                            <div class="box-num1">STRAIGHT</div>
                            <div class="box-num2">REVERSE</div>
                            <div class="box-num3">MIX-1</div>
                            <div class="box-num4">MIX-2</div>
                            <div class="box-num5">MIX-3</div>
                            <div class="box-num6">MIX-4</div>
                            <div class="win-box1"><?= ($first != '' && $first != '-') ? $first : '<p style="padding-left:16px;">-</style></p>'; ?></div>
                            <div class="win-box2"><?= ($second != '' && $second != '-') ? $second : '<p style="padding-left:16px;">-</style></p>'; ?></div>
                            <div class="win-box3"><?= ($third_one != '' && $third_one != '-') ? $third_one : '<p style="padding-left:16px;">-</style></p>';  ?></div>
                            <div class="win-box4"><?= ($third_two != '' && $third_two != '-') ? $third_two : '<p style="padding-left:16px;">-</style></p>';  ?></div>
                            <div class="win-box5"><?= ($third_three != '' && $third_three != '-') ? $third_three : '<p style="padding-left:16px;">-</style></p>';  ?></div>
                            <div class="win-box6"><?= ($third_four != '' && $third_four != '-') ? $third_four : '<p style="padding-left:16px;">-</style></p>'; ?></div>
                            <div class="prize-box1"><?= 'AED ' . number_format(intval($value['rate']) * $prizeRatio) . '/-'; ?></div>
                            <?php
                            $prizeRatio2 = $prizeRatio / 10;
                            ?>
                            <div class="prize-box2"><?= 'AED ' . number_format(intval($value['rate']) * $prizeRatio2) . '/-'; ?></div>
                            <?php
                            if ($prizeRatio === 250) {
                                $prizeRatio3 = $prizeRatio2 / 10;
                            } else if ($prizeRatio === 300) {
                                $prizeRatio3 = $prizeRatio2 / 3;
                            }

                            ?>
                            <div class="prize-box3"><?= 'AED ' . number_format(intval($value['rate']) * $prizeRatio3) . '/-'; ?></div>
                            <div class="prize-box4"><?= 'AED ' . number_format(intval($value['rate']) * $prizeRatio3) . '/-'; ?></div>
                            <div class="prize-box5"><?= 'AED ' . number_format(intval($value['rate']) * $prizeRatio3) . '/-'; ?></div>
                            <div class="prize-box6"><?= 'AED ' . number_format(intval($value['rate']) * $prizeRatio3) . '/-'; ?></div>
                            <div class="winners-box1"><?= $first_z_count; ?></div>
                            <div class="winners-box2"><?= $second_z_count; ?></div>
                            <div class="winners-box3"><?= $third_one_z_count; ?></div>
                            <div class="winners-box4"><?= $third_two_z_count; ?></div>
                            <div class="winners-box5"><?= $third_three_z_count; ?></div>
                            <div class="winners-box6"><?= $third_four_z_count; ?></div>
                            <div class="total-box1"><?= $first_z_amt; ?></div>
                            <div class="total-box2"><?= $second_z_amt; ?></div>
                            <div class="total-box3"><?= $third_one_z_amt; ?></div>
                            <div class="total-box4"><?= $third_two_z_amt; ?></div>
                            <div class="total-box5"><?= $third_three_z_amt; ?></div>
                            <div class="total-box6"><?= $third_four_z_amt; ?></div>
                            <div class="total-win1"><span>TOTAL WINNERS:</span><?= ($t_count == '') ? '00' : $t_count;  ?></div>
                            <div class="total-prizes"><span>TOTAL PRIZES</span><?= ($t_amt == '') ?  '000' : 'AED ' . number_format($t_amt) . '/-'; ?></div>

                            <img class="img-bd" src="img/new-bg.png" width="100%" height="auto">
                        </div>


                    </div>
        <?php
} else { echo '<div class="col-md-12 "><center><h1><a href="https://admin.nationaldraw.ae/drawprewinners/">Kindly complete the draw</a></h1></center></div>';}
               // }
            }
        }
        ?>
    </div>
</div>
</section>



<?php } else { ?>
    <section class="pb-120 mt-minus-300">

     <div class="container">
<h1 class="page-title mt-3">Just3 Draw Results</h1>

        <div class="title my-3"">

        <?php

$now = date("Y-m-d h:i:s");
               $draw_idss =  select_top_name($con, "pre_draw", "id", " `deletes`='0' order by `id` DESC", "id", "");
            
            if($subid3!="" && $subid2=="list"){ $drawID = $subid3; } else { $drawID = $draw_idss;}
            
            
           $draw_name =  select_top_name($con, "pre_draw", "name", "`result_datetime` >'$now' AND `deletes`='0'", "name", "");;
         
            $drawee = select_query($con, "pre_draw", "", "`id`='$drawID' and `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
            $drawidee =  $drawee['result'][0]['id'];
            $firstinfo =  $drawee['result'][0]['first'];
             $drawID = $drawidee;
             $draw_no =  $drawee['result'][0]['draw_no'];
           ?>

            <h1 class="page-title" style="color:#fbde5c;"><?= $draw_name; ?></h1>

        </div>


    </div>

</div>

<div class="container">
<div class="row justify-content-center">
<!-- <div class="col-md-2"></div> -->
<div class="col-md-3"><a target="_blank" href="<?php echo "https://admin.nationaldraw.ae/winningnumber/" ?> "><button class="btn-primary1 py-2" type="button"><div class="primary11 ">All combination </div></button></a></div>
<!-- <div class="col-md-2"></div> -->
<!--<div class="col-md-3"><a target="_blank" href="<?php echo "https://admin.nationaldraw.ae/lowerthird/"?>"><button class="btn-primary1 py-2"onclick="winner()" type="button"><div class="primary11 ">Lower third of straight</div></button></a></div>-->

</div>
<div class="row justify-content-center">
<div class="col-md-3"><a target="_blank" href="<?php echo "https://admin.nationaldraw.ae/drawwinners/list/".$drawidee."/1"; ?>"><button class="btn-primary1 py-2"onclick="winner()" type="button"><div class="primary11 ">Result of AED 10</div></button></a></div>

<div class="col-md-3"><a target="_blank" href="<?php echo "https://admin.nationaldraw.ae/drawwinners/list/".$drawidee."/2"; ?>"><button class="btn-primary1 py-2"onclick="winner()" type="button"><div class="primary11 ">Result of AED 20</div></button></a></div>
<div class="col-md-3"><a target="_blank" href="<?php echo "https://admin.nationaldraw.ae/drawwinners/list/".$drawidee."/3"; ?>"><button class="btn-primary1 py-2"onclick="winner()" type="button"><div class="primary11 ">Result of AED 50</div></button></a></div>
<div class="col-md-3"><a target="_blank" href="<?php echo "https://admin.nationaldraw.ae/drawwinners/list/".$drawidee."/4"; ?>"><button class="btn-primary1 py-2"onclick="winner()" type="button"><div class="primary11 ">Result of AED 100</div></button></a></div>


</div>
</div>
</section>

<?php }?>
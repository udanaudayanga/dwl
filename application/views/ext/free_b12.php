<!DOCTYPE html>
<html>

    <head>
        <title>Admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Fonts -->
        <!--    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300,400' rel='stylesheet' type='text/css'>
            <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>-->
        <link href='https://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Ranga:400,700' rel='stylesheet' type='text/css'>

        <!-- CSS Libs -->
        <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/animate.min.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-switch.min.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/checkbox3.min.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/dataTables.bootstrap.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/select2.min.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/select2-bootstrap.css">
        <link rel="stylesheet" type="text/css" href="/assets/js/datepicker/css/bootstrap-datepicker.min.css">
        <link rel="stylesheet" type="text/css" href="/assets/js/jqueryui/jquery-ui.css">
        <!-- CSS App -->
        <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/themes/flat-blue.css">
        <script type="text/javascript" src="/assets/js/jquery.min.js"></script>

        <script type="text/javascript">
            var BASE_URL = "<?php echo base_url(); ?>";
        </script>

        <style>
            .checkbox3 label, .radio3 label{font-size: 16px;}
            .checkbox3 label::before, .radio3 label::before {
                top: 9px;
            }
            .checkbox3 input:checked + label::after {margin-top: 5px;}
            .radio3.radio-check label::after, .radio3.radio-check.radio-light label::after {
                height: 26px;
                line-height: 26px;
            }
        </style>
        <style>
            .skillbar {
                position: relative;
                display: inline-block;
                margin: 15px 0;
                width: 100%;
                background: #fff;
                height: 156px;
                width: 100%;
                border: 1px solid #ccc;
            }

            .skillbar-title {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                font-weight: bold;
                font-size: 80px;
                color: #dc143c;
                letter-spacing: 10px;
                border-top-left-radius: 3px;
                border-bottom-left-radius: 3px;
                text-align: center;
                padding: 0 20px;
                height: 156px;
                line-height: 156px;
            }

            .skillbar-bar {
                height: 154px;
                width: 0px;
                background: #6adcfa;
                display: inline-block;
                border-right: 1px solid black;
            }

            .skill-bar-percent {
                position: absolute;
                right: 10px;
                top: 0;
                font-size: 11px;
                height: 156px;
                line-height: 35px;
                color: #ffffff;
                color: rgba(0, 0, 0, 0.4);
            }
            
            .scratchpad{
                width: 60vw;
                height: 60vw;
                border: solid 1px;
                max-width: 554px;
                max-height: 511px;
            }

            @media (min-width: 1200px) {
                p.current_fs{font-size:62px;}
                p.other_fs{font-size: 42px;}
                .fbox{height: 303px;}
            }
            @media (max-width: 767px) {
                p.current_fs{font-size:34px;}
                p.other_fs{font-size: 22px;}
                div.side_panels{display: none;}
                
            }
            @media (min-width: 768px) and (max-width: 991px) {
                p.current_fs{font-size:40px;}
                p.other_fs{font-size: 26px;}
                .fbox{height: 257px;}
                div.side_panels{display: none;}
                
            }
            @media (min-width: 992px) and (max-width: 1199px) {
                p.current_fs{font-size:50px;}
                p.other_fs{font-size: 32px;}
                .fbox{height: 295px;}
            }
            

            

        </style>

    </head>

    <body class="">
        <div class="app-container" style="">
            <div class="row content-container">

                <div class="row">
                    
                    <div class="col-xs-12">
                        <div class="card">

                            <div class="card-body" style="padding: 10px;">
                                <div  style="margin:0px auto;width:60vw;max-width: 530px;">
                                    <h4>Hi <?php echo $patient->fname;?></h4>
                                    <h4>Scratch the box below to reveal your free gift.</h4>
                                </div>
                                
                                <div id="demo1" class="scratchpad" style="margin:0px auto;">
            
                                </div>  
                                <?php if($won == 1){?>
                                <div id="below_msg" class="hide"  style="margin:0px auto;width:60vw;max-width: 530px;">
                                    <h4>Please ask for your free gift at the counter on your next weekly visit. Stay tuned for more.</h4>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>


            <!-- Javascript Libs -->
            <script type="text/javascript" src="/assets/js/scratchpad/wScratchPad.js"></script>
<script type="text/javascript">
            var _top_img = '<?php echo $won==1?'scratch_win.png':'scratch_lost.png';?>';
            var _pid = '<?php echo $patient->id;?>';
            var _promo_id = '<?php echo $promo_id;?>';
            var _won = '<?php echo $won;?>';
            var _scratched = false;
            $('#demo1').wScratchPad({
              bg: '/assets/js/scratchpad/images/'+_top_img,
              fg: '/assets/js/scratchpad/images/scratch_top.png',
              cursor: 'url("/assets/js/scratchpad/cursors/hand2.png") 10 5, default',
              scratchMove: function (e, percent) {
                  
                if (percent > 40) {
                    this.clear();
                    $('#below_msg').removeClass('hide');
                }
                
                if(percent > 20 && !_scratched && _won ==1)
                {
                    _scratched = true;
                    $.post(BASE_URL+'ext/updateScratch',{patient_id:_pid,promo_id:_promo_id},function(data){
                        console.log(data);                        
                    });
                }
              }
            });
//            $(window).on('resize', function () {
//                $('#demo1').wScratchPad('reset');
//            });
            window.addEventListener('resize', function(event){
            $('#demo1').wScratchPad('reset');
            $('#below_msg').addClass('hide');
          });
      
      </script>
            <script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>

            <script type="text/javascript" src="/assets/js/bootstrap-switch.min.js"></script>
            <script type="text/javascript" src="/assets/js/jquery.matchHeight-min.js"></script>
            <script type="text/javascript" src="/assets/js/jquery.dataTables.min.js"></script>
            <script type="text/javascript" src="/assets/js/dataTables.bootstrap.min.js"></script>
            <script type="text/javascript" src="/assets/js/select2.full.min.js"></script>
<!--            <script type="text/javascript" src="/assets/js/mode-html.js"></script>
            <script type="text/javascript" src="/assets/js/theme-github.js"></script>-->
            <script type="text/javascript" src="/assets/js/datepicker/js/bootstrap-datepicker.min.js"></script>
            <script type="text/javascript" src="/assets/js/jqueryui/jquery-ui.js"></script>
            <script type="text/javascript" src="/assets/js/bootbox.min.js"></script>
            <!-- Javascript -->
            
    </body>

</html>
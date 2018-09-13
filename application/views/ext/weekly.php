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
    
    <script src="/assets/js/skill.bars.jquery.js"></script>
    <script type="text/javascript" src="/assets/js/Chart.min.js"></script>
    <script type="text/javascript" src="/assets/js/scratch.min.js"></script>
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
/*.scratchpad{
          width: 82%;
          height: 100px;
          border: solid 1px;
        }*/

</style>
    
</head>

<body class="flat-blue">
    <div class="app-container" style="">
        <div class="row content-container">
            
    <div class="row">
        <div class="col-lg-4 col-sm-12 col-md-3 col-xs-12 side_panels">&nbsp;</div>
    <div class="col-lg-4 col-sm-12 col-md-6 col-xs-12">
        <div class="card">
            
            <div class="card-body" style="padding: 10px;">
                <h2>Your Journey So Far </h2>
                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, </p>
                <div class="col-xs-12" style="padding: 25px;border: 1px solid black;background-color: #efefff;">
                    <h3 style="margin-top: 0px;">Phase Analysis > 16.<?php echo $phase->phase;?></h3>
                    <div class="" style="border: 2px solid black;overflow: auto;padding: 15px 10px;background-color: white;">
                        <div class="col-xs-4" style="text-align: center;padding: 0px 15px;">
                        <h4 style="text-align: center;border-bottom: 2px solid #999;padding-bottom: 5px;font-size: 18px;margin: 10px 10px 0px 10px;">START</h4>
                        <p class="other_fs" ><?php //echo $phase->start_weight;?></p>
                        <h4 style="text-align: center;border-bottom: 2px solid #999;padding-bottom: 5px;font-size: 18px;margin: 15% 10px 0px 10px;"><?php echo date('M m, Y',strtotime($phase->start));?></h4>
                        </div>
                        <div class="col-xs-4"  style="border: 2px solid black;padding: 0px;">
                            <div style="padding: 0px 5px;text-align: center;">
                                <h4 style="text-align: center;border-bottom: 2px solid #999;padding-bottom: 5px;font-size: 18px;margin: 10px 5px 0px 5px;">CURRENT</h4>
                                <p class="current_fs" style="margin-top: 10%;"><?php if($last_visit)echo $last_visit->weight;?></p>
                            </div>
                            <?php
                                $lw_percent = 0;
                                if($last_visit)
                                {
                                    $lost_weight = $phase->start_weight - $last_visit->weight;
                                    $lw_percent = round(($lost_weight/$expect_gap)*100);
                                }
                            ?>
                            <div class="skillbar" data-percent="<?php echo $lw_percent;?>" style="height: 50px;margin-bottom: 0px;bottom: 0px;border: none;border-top: 2px solid black;display: inherit;margin-top: 15%;">                                
                            <p class="skillbar-bar" style="background-color: #04A76C;height: 48px;margin: 0px;"></p>
                            <span style="color: black;font-size: 30px;height: 58px;line-height: 48px;" class="skill-bar-percent"></span>
                          </div>
                        </div>
                        <div class="col-xs-4"  style="text-align: center;padding: 0px 15px;">
                            <h4 style="text-align: center;border-bottom: 2px solid #999;padding-bottom: 5px;font-size: 18px;margin: 10px 10px 0px 10px;">TARGET</h4>
                            <p class="other_fs" ><?php //echo $phase->target_weight;?></p>
                            <h4 style="text-align: center;border-bottom: 2px solid #999;padding-bottom: 5px;font-size: 18px;margin: 15% 10px 0px 10px;"><?php echo date('M m, Y',strtotime($phase->end));?></h4>
                        </div>
                        <div class="col-xs-12" style="margin-top: 5%;">
                            <h4 style="text-align: center;border-bottom: 2px solid black;padding-bottom: 5px;font-size: 18px;">PROGRESS > YOUR WEIGHT LOSS JOURNEY</h4>
                        </div>
                        
                        <div class="col-xs-12" style="padding: 0px;margin-top: 1%;">
                            <div class="col-xs-4" style="padding: 0px;"><h4 style="text-align: right;font-size: 17px;">WEIGHT LOSS</h4></div>
                            <div class="col-xs-8">
                                <div class="skillbar" data-percent="<?php echo $lw_percent;?>" style="height: 50px;margin: 0px;bottom: 0px;border: none;border: 2px solid black;display: inherit;">                                
                                <p class="skillbar-bar" style="background-color: #04A76C;height: 46px;margin: 0px;"></p>
                                <span style="color: black;font-size: 30px;height: 56px;line-height: 46px;" class="skill-bar-percent"></span>
                              </div>
                            </div>
                        </div>
                        <?php 
                            $percent = 0;
                            if($last_visit)
                            {
                                $percent = ($last_visit->week_no / 16)*100;
                            }
                        ?>
                        <div class="col-xs-12" style="padding: 0px;margin-top: 3%;">
                            <div class="col-xs-4" style="padding: 0px;"><h4 style="text-align: right;font-size: 17px;">16 WK TIME PHASE</h4></div>
                            <div class="col-xs-8">
                                <div class="skillbar" data-percent="<?php echo $percent;?>" style="height: 50px;margin: 0px;bottom: 0px;border: none;border: 2px solid black;display: inherit;">                                
                                <p class="skillbar-bar" style="background-color: #04A76C;height: 46px;margin: 0px;"></p>
                                <span style="color: black;font-size: 30px;height: 56px;line-height: 46px;" class="skill-bar-percent"></span>
                              </div>
                            </div>
                        </div>
                    </div>                    
<!--                </div>
                <div class="col-xs-12">-->
                    <div style="border: 2px solid black;overflow: auto;padding: 15px 10px;background-color: white;margin-top: 10px;">
                        
                        <h4 style="text-align: center;border-bottom: 2px solid black;padding-bottom: 5px;font-size: 20px;margin: 0 10px;">BEST WEIGHT LOSS TREND</h4>
                        
                        <?php if(count($best_weeks)>0){?>
                        <div class="col-xs-12">
                            <?php foreach($best_weeks as $key=>$week){
                                    if($week < 0) continue;
                                ?> 
                                <div class="col-xs-4" style="text-align: center;padding: 0px 15px;">
                                    <h4 style="text-align: center;border-bottom: 2px solid #999;padding-bottom: 5px;font-size: 16px;margin: 10px 5px 0px 5px;">WK <?php echo $key;?></h4>
                                    <p class="other_fs" ><?php echo $week;?></p>
                                </div>
                            <?php } ?>
                        
                        </div>
                        <?php } ?>
                        
                        <div class="col-xs-3" style="text-align: center;padding: 0px 5px;">
                            <h4 style="text-align: center;border-bottom: 2px solid #999;padding-bottom: 5px;font-size: 16px;margin: 10px 5px 0px 5px;">AVG DAILY LOSS</h4>
                            <p class="other_fs" ><?php echo (isset($daily_avg))?number_format($daily_avg,1):'-';?></p>
                        </div>
                        <div class="col-xs-3" style="text-align: center;padding: 0px 5px;">
                            <h4 style="text-align: center;border-bottom: 2px solid #999;padding-bottom: 5px;font-size: 16px;margin: 10px 5px 0px 5px;">AVG WEEKLY LOSS</h4>
                            <p class="other_fs" ><?php echo (isset($weekly_avg))?number_format($weekly_avg,1):'-';?></p>
                        </div>
                        <div class="col-xs-3" style="text-align: center;padding: 0px 5px;">
                            <h4 style="text-align: center;border-bottom: 2px solid #999;padding-bottom: 5px;font-size: 16px;margin: 10px 5px 0px 5px;">WEIGHT LOSS TO COMPLETE</h4>
                            <p class="other_fs" ><?php echo ($last_visit)?round(100 - $lw_percent)." %":'-';?> </p>
                        </div>
                        <div class="col-xs-3" style="text-align: center;padding: 0px 5px;">
                            <h4 style="text-align: center;border-bottom: 2px solid #999;padding-bottom: 5px;font-size: 16px;margin: 10px 5px 0px 5px;">WEIGHT LOSS TO GO</h4>
                            <p class="other_fs" ><?php echo (isset($weight_loss_to_go))?number_format($weight_loss_to_go,1):'-';?></p>
                        </div>
                        
                    </div>
                </div>
                <br>
                
                <canvas id="line-chart" class="chart" height="200" style="padding: 0px;margin-top: 20px;"></canvas>
                
                
                <div style="width: 100%;background: url('/assets/img/pihatu.jpg') no-repeat no-repeat center center;overflow: auto;background-size: 100% 100%;"> 
                <div id="elem" class="scratchpad" style="width: 300px;height: 200px;margin: 20% auto;"> </div>
                </div>
                
                
        </div>
    </div>
        <div class="col-lg-4 col-sm-12 col-md-3 col-xs-12 side_panels">&nbsp;</div>
</div>
             </div>
        </div>
   

    <!-- Javascript Libs -->
            
            <script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
            
            <script type="text/javascript" src="/assets/js/bootstrap-switch.min.js"></script>
            <script type="text/javascript" src="/assets/js/jquery.matchHeight-min.js"></script>
            <script type="text/javascript" src="/assets/js/jquery.dataTables.min.js"></script>
            <script type="text/javascript" src="/assets/js/dataTables.bootstrap.min.js"></script>
            <script type="text/javascript" src="/assets/js/select2.full.min.js"></script>
            <script type="text/javascript" src="/assets/js/ace.js"></script>
            <script type="text/javascript" src="/assets/js/mode-html.js"></script>
            <script type="text/javascript" src="/assets/js/theme-github.js"></script>
	    <script type="text/javascript" src="/assets/js/datepicker/js/bootstrap-datepicker.min.js"></script>
	    <script type="text/javascript" src="/assets/js/jqueryui/jquery-ui.js"></script>
            <script type="text/javascript" src="/assets/js/bootbox.min.js"></script>
            <!-- Javascript -->
            <script type="text/javascript" src="/assets/js/app.js"></script>
           
	    <!--<script type="text/javascript" src="/assets/js/modal.js"></script>-->
 <script type="text/javascript">
    $('.skillbar').skillBars({
        speed: 4000
    });
    $(function() {
        var ctx, data, myLineChart, options;
        Chart.defaults.global.responsive = true;
        Chart.defaults.global.tooltips.backgroundColor = "rgba(255,255,0,1)";
        Chart.defaults.global.tooltips.titleColor = "rgba(0,0,0,1)";
        Chart.defaults.global.tooltips.bodyColor = "rgba(0,0,0,1)";
        Chart.defaults.global.tooltips.mode = "label";
        Chart.defaults.global.tooltips.bodyFontSize = 13;
        Chart.defaults.global.tooltips.titleFontSize = 13;
        Chart.defaults.global.tooltips.bodySpacing = 4;
        Chart.defaults.global.tooltips.titleSpacing = 4;
        ctx = $('#line-chart');
        options = {   
             scales: {
                yAxes: [{
                  scaleLabel: {
                    display: true,
                    labelString: 'WEIGHT'
                  }
                }],
                xAxes: [{
                  scaleLabel: {
                    display: true,
                    labelString: 'VISITS DURING PHASE'
                  }
                }]
             },
          scaleShowGridLines: true,
          scaleGridLineColor: "rgba(0,0,0,.05)",
          scaleGridLineWidth: 1,
          scaleShowHorizontalLines: true,
          scaleShowVerticalLines: true,
//          bezierCurve: false,
//          bezierCurveTension: 0.4,
//          pointDot: true,
//          pointDotRadius: 4,
//          pointDotStrokeWidth: 1,
//          pointHitDetectionRadius: 20,
//          datasetStroke: true,
//          datasetStrokeWidth: 2,
//          datasetFill: true,
//          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
  };
  data = {
    labels: ['1', '2', '3', '4', '5', '6', '7','8','9','10','11','12','13','14','15','16'],
    datasets: [
      {
        label: "Expected Weight",
        backgroundColor: "rgba(26, 188, 156,0.2)",
        borderColor: "#1ABC9C",
        pointBorderColor: "#1ABC9C",
        pointBackgroundColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "#1ABC9C",
        lineTension: 0,
        borderDash: [10,10],
        data: [<?php echo $expect;?>]
      }, {
        label: "Actual Weight",
        backgroundColor: "rgba(255,99,132,0.4)",
        borderColor: "rgba(255,99,132,1)",
        pointBorderColor: "rgba(0,0,0,1)",
        pointBackgroundColor: "rgba(255,99,132,1)",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "#22A7F0",
        pointBorderWidth: 2,
        pointHoverRadius: 8,
        pointRadius: 5,
            pointHitRadius: 10,
        data: [<?php echo $actual;?>]
      }
    ]
  };
  myLineChart = new Chart(ctx,{type:'line',data:data, options:options});
  
//  $('#elem').wScratchPad({
//  size        : 10,          // The size of the brush/scratch.
//  bg          : '#cacaca',  // Background (image path or hex color).
//  fg          : '#04A76C',  // Foreground (image path or hex color).
//  realtime    : true,       // Calculates percentage in realitime.
//  scratchDown : null,       // Set scratchDown callback.
//  scratchUp   : null,       // Set scratchUp callback.
//  scratchMove : null,       // Set scratcMove callback.
//  cursor      : 'crosshair' // Set cursor.
//});
  
  createScratchCard({
            'container':document.getElementById('elem'), 
            'background':'/assets/img/demo1-background.png', 
            'foreground':'/assets/img/demo1-foreground.png', 
            'percent':90, 
            'coin':'/assets/img/coin2.png', 
            'thickness':18
    });
  
});




</script>           
	    

</body>

</html>
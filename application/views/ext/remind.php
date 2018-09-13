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

<body class="flat-blue">
    <div class="app-container" style="">
        <div class="row content-container">
            
    <div class="row">
        <div class="col-lg-4 col-sm-12 col-md-3 col-xs-12 side_panels">&nbsp;</div>
    <div class="col-lg-4 col-sm-12 col-md-6 col-xs-12">
        <div class="card">
            
            <div class="card-body" style="padding: 10px;">
                <?php if($success){?>
                <div role="alert" class="alert fresh-color alert-success">
                    <strong>Success!</strong> We will remind you on your next visit.
                </div>
                <?php } ?>
                <form method="POST">
                <h2>Remind me on my next visit </h2>
                <?php foreach($reminders as $r){?>
                <div class="checkbox3 checkbox-round">
                    <input type="checkbox" name="reminder[<?php echo $r->id;?>]" value="<?php echo $r->id;?>" id="checkbox-<?php echo $r->id;?>">
                    <label for="checkbox-<?php echo $r->id;?>">
                      <?php echo $r->text;?>
                    </label>
                  </div>
                <?php } ?>
                
                <p style="text-align: center;">
                    <button type="submit" class="btn btn-success">Submit</button>
                </p>
                </form>
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
           
        
 

</body>

</html>
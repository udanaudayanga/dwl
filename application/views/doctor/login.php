<!DOCTYPE html>
<html>

<head>
    <title>--Doctor Login --</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fonts -->
<!--    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300,400' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>-->
    <link href='https://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700' rel='stylesheet' type='text/css'>
    <!-- CSS Libs -->
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-switch.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/checkbox3.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/select2.min.css">
    <!-- CSS App -->
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/themes/flat-blue.css">
    <style>
	.login-body{border: 8px solid #ccc;}
    </style>
    <script type="text/javascript">
    var BASE_URL = "<?php echo base_url(); ?>";
    </script>
</head>

<body class="flat-blue login-page">
    <div class="container">
        <div class="login-box" style="max-width: 500px;">
            <div>
                <div class="login-form row">
                    <div class="col-sm-12 text-center login-header">
                        <img src="/assets/img/dwlclogo.png" style="width: 100%;" >
                    </div>
                    <div class="col-sm-12">
                        <div id="sign_in_form" class="login-body" style="padding: 1em;">
<!--                            <div class="loader-container text-center color-white">
                                                    <div><i class="fa fa-spinner fa-pulse fa-3x"></i></div>
                                                    <div>Loading</div>
                                                </div>-->
                            <div id="sign_in_form_errors"></div>
                            <form methot="POST">
                                <p style="color: #666;">DOCTOR LOGIN</p>
                                <div class="control">
                                    <input style="padding: 10px;border: 1px solid #ccc;height: 45px;" type="text" class="form-control" name="username" value="" placeholder="Username"/>
                                </div>
                                <div class="control">
                                    <input style="padding: 10px;border: 1px solid #ccc;height: 45px;" type="password" class="form-control" name="password" value="" placeholder="Password"/>
                                </div>
				
                                <div class="login-button text-center">
                                    <input id="sign_in" type="button"  class="btn btn-primary" value="Login">
                                </div>
                            </form>
                        </div>
<!--                        <div class="login-footer">
                            <span class="text-right"><a href="#" class="color-white">Forgot password?</a></span>
                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Javascript Libs -->
            <script type="text/javascript" src="/assets/js/jquery.min.js"></script>
            <script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
            <script type="text/javascript" src="/assets/js/Chart.min.js"></script>
            <script type="text/javascript" src="/assets/js/bootstrap-switch.min.js"></script>
            <script type="text/javascript" src="/assets/js/jquery.matchHeight-min.js"></script>
            <script type="text/javascript" src="/assets/js/jquery.dataTables.min.js"></script>
            <script type="text/javascript" src="/assets/js/dataTables.bootstrap.min.js"></script>
            <script type="text/javascript" src="/assets/js/select2.full.min.js"></script>
            <script type="text/javascript" src="/assets/js/ace.js"></script>
            <script type="text/javascript" src="/assets/js/mode-html.js"></script>
            <script type="text/javascript" src="/assets/js/theme-github.js"></script>
	    <script type="text/javascript" src="/assets/js/bootbox.min.js"></script>
            <script type="text/javascript" src="/assets/js/jqueryui/jquery-ui.js"></script>
            <!-- Javascript -->
            <script type="text/javascript" src="/assets/js/app.js"></script>
            <!--<script type="text/javascript" src="/assets/js/index.js"></script>-->
	    
	<script type="text/javascript">
	    $(function(){
	    $('#sign_in').on('click',function(e){
		e.preventDefault();
                
                $('#sign_in_form').addClass('loader');
        
	    //        $('#sign_in_form').block({ 
	    //            message: '<img src="/assets/images/loader.gif" />', 
	    //            css: { border: '0px;',background:'none' } 
	    //        }); 

		    var username = $("#sign_in_form input[name='username']").val();
		    var password = $("#sign_in_form input[name='password']").val();
		    

		    $.post(BASE_URL+"doctor/login/",
		    {username:username,password:password},
		    function(data)  {
	                data = JSON.parse(data);
	                if(data.status == 'success')
	                {
	                    setTimeout(function(){
	    //                  $('#sign_in_form').unblock();
	                      window.location = BASE_URL+'doctor';
	                    },2000);		    
	                }			
	                else if(data.status == 'error')
	                {
                             $('#sign_in_form').removeClass('loader');
	                    $('#sign_in_form_errors').html(data.errors);
	                }
		    });          
		});
	    });

	</script>    
</body>

</html>

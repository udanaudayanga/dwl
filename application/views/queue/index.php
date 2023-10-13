
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Patients Line Up</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    
    <!-- CSS Libs -->
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css">
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
    <script type="text/javascript" src="/assets/js/bootstrap.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
    var BASE_URL = "<?php echo base_url(); ?>";
    </script>
    <style>
        .btn-gray{background-color: gray;border-color: gray;color: white;}
        div.container{font-family: 'Open Sans', sans-serif;}
        div:where(.swal2-container) div:where(.swal2-popup) {font-size: inherit !important;}
    </style>
  </head>

  <body>
      <div class="container" style="padding: 20px 25px 0px;">

        <div class="page-header" style="margin-top: 80px;margin-bottom: 10px;overflow: auto;">
            
            <div class="col-12" style="background: url(/assets/img/new_logo.png) no-repeat center center;height: 100px;background-size: contain;"></div>
            
        </div>
        <div id="ex_frm" style="overflow: auto;padding: 35px 35px 20px 35px;background-color: #ccc;margin-top: 30px;">
        <form method="post" id="log_frm">
            <div class="col-xs-12" style="padding: 20px;background-color: white;box-shadow: 5px 5px 5px #888888;">
            <div class="row" style="padding:15px;">
                <div class="col-xs-7 col-md-6 col-md-offset-2" style="padding-left: 0px;">
                    <input type="text" name="last" required class="form-control input-lg" style="font-size: 36px;height: 60px;padding: 10px 10px;text-align: left;text-transform: uppercase;box-shadow: 3px 3px 3px #ccc;">
                </div>
                <div class="col-xs-5 col-md-3" style="color: gray;padding-top:20px;font-size: 1.1em;">                    
                        LAST NAME
                </div>
            </div>
            <div class="row" style="padding:15px;">
                <div class="col-xs-7 col-md-6 col-md-offset-2" style="padding-left: 0px;">
                    <input type="text" name="first" required class="form-control input-lg" style="font-size: 36px;height: 60px;padding: 15px 10px;text-align: left;text-transform: uppercase;box-shadow: 3px 3px 3px #ccc;">
                </div>
                <div class="col-xs-5 col-md-3" style="color: gray;padding-top:20px;font-size: 1.1em;">                    
                        FIRST NAME
                </div>
            </div>
            <div class="row" style="padding:15px;">
                <div class="col-xs-6 col-md-3 col-md-offset-3 text-center">
                    <label class="radio-inline" style="color:gray;font-weight: 600;">
                        <input type="radio" checked required name="type" id="inlineRadio1" value="1"> WEIGHTLOSS
                    </label>
                </div>
                <div class="col-xs-6 col-md-3 text-center">
                    <label class="radio-inline"  style="color:gray;font-weight: 600;">
                        <input type="radio" required name="type" id="inlineRadio2" value="2"> MEDSPA
                    </label>
                </div>
            </div>
        </div>
        <div class="col-xs-12" style="padding: 0px;text-align: center;margin-top: 20px;">            
                <button type="submit" class="btn btn-lg btn-default" style="padding: 12px 30px;margin: 0px;font-size: 20px;background-color: red;color: white;box-shadow: 0px 0px 5px #000;border-color: red;">Submit</button>
           
        </div>
        </form>
    </div>


    </div> <!-- /container -->7

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
        
        <script type="text/javascript">

            
            $(function(){
                
                $('#log_frm').on('submit',function(e){
                    e.preventDefault();

                    _this = $(this);

                    $.post(BASE_URL+'queue/addQueue', _this.serialize(),function(data){
                        data = JSON.parse(data);

                        if(data.status == 'success')
                        {
                            Swal.fire({
                                icon: 'success',
                                title: 'Please take a seat and someone will be with you shortly.',
                                text: 'Thank You',
                                timer: 4000,
                                timerProgressBar: true,
                                showConfirmButton: false,
                            }).then((result)=>{
                                _this.trigger('reset');
                            });
                        }
                    });




                });

                
                
                $('#addq_new_btn').on('click',function(e){
                    e.preventDefault();
                    _first = $.trim($('#first_new').val());
                    _last = $.trim($('#last_new').val());
                    
                    if(_first.length > 0 && _last.length > 0)
                    {
                        $.post(BASE_URL+'queue/addNewToQueue',$('#new_p_frm').serialize(),function(data){
                            $('#new_p_frm').trigger('reset');
                            bootbox.alert('Please take a seat and someone will be with you shortly.');
                            $('#ex_frm').removeClass('hide');
                            $('#new_frm').addClass('hide');
                            $('#ex').removeClass('btn-gray').addClass('btn-success');
                            $('#new').removeClass('btn-success').addClass('btn-gray');
                            window.setTimeout(function(){
                                bootbox.hideAll();
                            }, 3000);
                        });
                    }
                    else
                    {
                        bootbox.alert('First Name Or Last Name cannot be empty');
                    }
                });
                
                $('#sbm_btns').on('click',function(e){
                    e.preventDefault();
                    
                    $.post(BASE_URL+'queue/checkUser',$('#log_frm').serialize(),function(data){
                        data = JSON.parse(data);
                        if(data.status == 'success')
                        {
                            _modal = $('#patient_info');
                            _modal.find('.modal-body').html(data.ui);
                            
                            _modal.find('#ui_no').unbind('click').bind('click', function(e) {
                                _modal.modal('hide');
                                bootbox.alert('User Not Found');
                                $('#log_frm').trigger('reset');
                            });
                            
                            _modal.find('#ui_yes').unbind('click').bind('click', function(e) {
                                _modal.modal('hide');
                                _pid = data.pid;
                                visitModel(_pid);
                            });
                            
                            _modal.modal();
                        }
                        else if(data.status == 'sn')
                        {
                            bootbox.prompt({ 
                                size: "small",
                                title: "What is your street Number? Eg. 768", 
                                callback: function(res)
                                { 
                                    if(res)
                                    {
                                        $.post(BASE_URL+'queue/checkWithSN',{sn:res,ids:data.ids},function(dt){
                                            dt = JSON.parse(dt);
                                            if(dt.status == 'success')
                                            {
                                                _modal = $('#patient_info');
                                                _modal.find('.modal-body').html(dt.ui);

                                                _modal.find('#ui_no').unbind('click').bind('click', function(e) {
                                                    _modal.modal('hide');
                                                    bootbox.alert('User Not Found');
                                                    $('#log_frm').trigger('reset');
                                                });

                                                _modal.find('#ui_yes').unbind('click').bind('click', function(e) {
                                                    _modal.modal('hide');
                                                    _pid = dt.pid;
                                                    visitModel(_pid);
                                                });

                                                _modal.modal();
                                            }
                                            else
                                            {
                                                $('#log_frm').trigger('reset');
                                                bootbox.alert("User not found");
                                            }
                                        });
                                    }
                                }
                            })
                        }
                        else
                        {
                            $('#log_frm').trigger('reset');
                            bootbox.alert("User not found");
                        }
                    });
                });
                
                $('#cart_add_cat_q').on('change',function(e){
                    
                     $.get(BASE_URL+'queue/getCatProDropDown/'+this.value,function(data){            
                         $('#cart_pro_dd_div').html(data);
                     });

                });
                 
                function visitModel(pid)
                {
                    _modal = $('#visit_info');
                    _modal.find('#pid').val(pid);
                    
                    $.get(BASE_URL+'queue/getQueue/'+pid,function(data){
                        _modal.find('#type_'+data).prop('checked',true);
                    });
                    
                    _modal.find('#add_to_queue').unbind('click').bind('click', function(e) {
                        _type = _modal.find("input[name='type']:checked").val();
                        $.post(BASE_URL+'queue/addToQueue',{pid:pid,type:_type},function(data){
                            data = JSON.parse(data);
                            if(data.status == 'success')
                            {
                                _modal.modal('hide');
                                
                                $('#log_frm').trigger('reset');
                                
                                bootbox.alert('Please take a seat and someone will be with you shortly.');
                                window.setTimeout(function(){
                                    bootbox.hideAll();
                                }, 3000);
                            }                            
                        });
                    });
                    
                    _modal.modal();
                }
                
                function itemModel(pid)
                {
                    _modal = $('#items_info');
                    _modal.find('#pid').val(pid);
                    
                    $.get(BASE_URL+'queue/getItems/'+pid,function(data){
                        _modal.find('#order_cart_div').html(data);
                    });
                    
                    _modal.find('#add_item_btn').unbind('click').bind('click', function(e) {
                        $.post(BASE_URL+'queue/addItem',$('#add_pro_cart').serialize(),function(data){
                            data = JSON.parse(data);
                            if(data.status == 'success')
                            {
                                _modal.find('#order_cart_div').html(data.cart);
                            }                            
                        });
                    });
                    
                    _modal.find('.id').unbind('click').bind('click', function(ex) {
                        ex.preventDefault();
                       alert('delete'); 
                    });
                    
                    _modal.find('#add_to_queue').unbind('click').bind('click', function(e) {
                        $.post(BASE_URL+'queue/addToQueue',{pid:pid},function(data){
                            data = JSON.parse(data);
                            if(data.status == 'success')
                            {
                                _modal.modal('hide');
                                _modal.find('#add_pro_cart').trigger('reset');
                                $('#log_frm').trigger('reset');
                                
                                bootbox.alert('Added to Queue Successfully.');
                                window.setTimeout(function(){
                                    bootbox.hideAll();
                                }, 3000);
                            }                            
                        });
                    });
                    
                    _modal.modal();
                }
            });
        </script>
  </body>
</html>

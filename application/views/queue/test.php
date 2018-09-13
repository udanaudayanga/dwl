
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

    <title>Existing Patients Line Up</title>
    <link href='https://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Ranga:400,700' rel='stylesheet' type='text/css'>
    
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
    
    <script type="text/javascript">
    var BASE_URL = "<?php echo base_url(); ?>";
    </script>
    <style>
        .btn-gray{background-color: gray;border-color: gray;color: white;}
        
    </style>
  </head>

  <body>
      <div class="container" style="padding: 30px 20px;">

          <div style="border: 1px solid black;width: 100%;height: 52vh;background-color: black;margin-top: 15px;">
              
          </div>
    
    </div> <!-- /container -->
    
    
        

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
                $('#new_frm').hide();
                $('.chng_btn').on('click',function(e){
                    e.preventDefault();
                    _target = $(this)
                    _id = _target.attr('id')
                    if(_id == 'new')
                    {
                        $('#ex_frm').hide();
                        $('#new_frm').show();
                        _target.removeClass('btn-gray').addClass('btn-success');
                        $('#ex').removeClass('btn-success').addClass('btn-gray');
                    }
                    else
                    {
                        $('#ex_frm').show();
                        $('#new_frm').hide();
                        _target.removeClass('btn-gray').addClass('btn-success');
                        $('#new').removeClass('btn-success').addClass('btn-gray');
                    }
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
                            $('#ex_frm').show();
                            $('#new_frm').hide();
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
                
                $('#sbm_btn').on('click',function(e){
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
                        else
                        {
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

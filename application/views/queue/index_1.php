
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
      <div class="container" style="padding: 20px 25px 0px;">

        <div class="page-header" style="margin-top: 10px;margin-bottom: 10px;overflow: auto;">
            
            <div class="col-xs-6" style="background: url(/assets/img/new_logo_queue1.png) no-repeat center left;height: 48px;background-position-x: 15px;"></div>
            <div class="col-xs-3" style="text-align: right;"><a class="btn btn-gray btn-lg chng_btn" id="new" style="font-size: 26px;margin: 0;padding: 8px 15px;width: 154px;" href=""><span style="font-size: 22px;" class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;&nbsp;NEW</a></div>
            <div class="col-xs-3" style="text-align: right;"><a class="btn btn-success btn-lg chng_btn" id="ex" style="font-size: 26px;margin: 0;padding: 8px 15px;width: 154px;" href=""><span style="font-size: 22px;" class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;&nbsp;EXISTING</a></div>
        </div>
        <div id="ex_frm">
        <form method="post" id="log_frm">
        <div class="col-xs-12">
            <div class="col-xs-4" style="padding: 0px 20px 0px 0px">
                <input type="text" maxlength="3" name="last" class="form-control input-lg" style="font-size: 36px;height: 60px;padding: 10px 10px;text-align: center;text-transform: uppercase;width: 75%;">
                <p style="font-size: 18px;text-align: left;line-height: 1.1;font-weight: bold;margin: 5px 0px;">
                    First 3 Characters of LAST Name
                </p>
                <p style="font-size: 25px;text-align: left;line-height: 1.1;padding-top: 0px;">LARSON = LAR</p>
            </div>
            <div class="col-xs-4" style="padding: 0px 20px">
                <input type="text" maxlength="3" name="first" class="form-control input-lg" style="font-size: 36px;height: 60px;padding: 15px 10px;text-align: center;text-transform: uppercase;width: 75%;">
                <p style="font-size: 18px;text-align: left;line-height: 1.1;font-weight: bold;margin: 5px 0px;">
                    First 3 Characters of FIRST Name
                </p>
                <p style="font-size: 25px;text-align: left;line-height: 1.1;padding-top: 0px;">ANGELA = ANG</p>
            </div>
            <div class="col-xs-4" style="padding: 0px 0px 0px 20px">
                <input type="text" placeholder="" maxlength="10" name="dob" class="form-control input-lg" style="font-size: 36px;height: 60px;padding: 15px 10px;text-align: left;text-transform: uppercase;">
                <p style="font-size: 18px;text-align: center;line-height: 1.1;font-weight: bold;margin: 5px 0px;">
                    DATE OF BIRTH
                </p>
                <p style="font-size: 25px;text-align: center;line-height: 1.1;padding-top: 0px;">10/18/1985</p>
            </div>
        </div>
        <div class="col-xs-12" style="padding-top: 15px;">
            <div class="col-xs-4" style="padding: 0px;">
                <input type="text" name="sn" class="form-control input-lg" style="font-size: 36px;height: 60px;padding: 10px 10px;text-align: center;text-transform: uppercase;">
                <p style="font-size: 18px;text-align: left;line-height: 1.1;font-weight: bold;margin: 5px 0px;">                    
                    Type Your STREET Number. Eg: 3295
                </p>
            </div>
            <div class="col-xs-4">
                 
            </div>
            <div class="col-xs-3 pull-right">
                <a href="" id="sbm_btn" class="btn btn-lg btn-info pull-right" style="padding: 12px 30px;margin: 0px;font-size: 26px;">SUBMIT</a>
            </div>
        </div>
        </form>
    </div>
    <div id="new_frm">
        <form method="post" id="new_p_frm">
        <div class="col-xs-12">
            <div class="col-xs-5" style="padding: 0px 20px 0px 0px">
                <input type="text" id="last_new"  name="last" class="form-control input-lg" style="font-size: 36px;height: 60px;padding: 10px 10px;text-align: center;text-transform: uppercase;width: 75%;">
                <p style="font-size: 26px;text-align: center;line-height: 1.1;width: 75%;margin: 5px 0px;">
                    <span style="color: red;">LAST NAME</span>
                </p>
                
            </div>
            <div class="col-xs-5" style="padding: 0px 20px 0px 0px;">
                <input type="text" id="first_new" name="first" class="form-control input-lg" style="font-size: 36px;height: 60px;padding: 15px 10px;text-align: center;text-transform: uppercase;width: 75%;">
                <p style="font-size: 26px;text-align: center;line-height: 1.1;width: 75%;margin: 5px 0px;">
                    <span style="color: red;">FIRST NAME</span>
                </p>
            </div>
            <div class="col-xs-2" style="padding: 0px">
                <a href="" id="addq_new_btn" class="btn btn-lg btn-warning pull-right" style="padding: 12px 10px;margin: 0px;font-size: 26px;">ADD TO QUEUE</a>
            </div>
        </div>
        
        </form>
    </div>
    </div> <!-- /container -->
    
    <div class="modal fade modal-info" id="patient_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 60%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                    <h3 class="modal-title" id="myModalLabel">IS THIS YOU?</h3>
                </div>
                <div class="modal-body" style='overflow: auto;'>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-lg" style="background-color: #979797;" id='ui_no'>NO</button>
                    <button type="button"  class="btn btn-success btn-lg" id='ui_yes'>YES</button>
                </div>
            </div>
        </div>
    </div>
<!--    <div class="modal fade modal-info" id="items_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                    <h3 class="modal-title" id="myModalLabel">SELECT ITEMS YOU HOPE TO PURCHASE TODAY, IF NOT CLICK ADD TO QUEUE</h3>
                </div>
                <div class="modal-body" style='overflow: auto;'>
                    <div class="row">
                        <div class="col-xs-12" style="margin-bottom: 10px;">
                            <div class="card">              
                                <div class="card-body" style="padding: 0px;background-color: #CAF0FE;">
                                    <form id="add_pro_cart" method="POST">
                                        <input type="hidden" name="pid" id="pid" value=""/>
                                        <div class="form-group form-group-lg col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                            <label for="cat">Category</label>
                                            <select class="form-control not_select2" name="cat_id" id="cart_add_cat_q" tabindex="-1" aria-hidden="true" style="width: 100%;">
                                                <option>Select</option>
                                               <?php foreach($categories as $cat){?>
                                                <option <?php echo set_select('cat_id', $cat->id); ?> value="<?php echo $cat->id;?>"><?php echo $cat->name;?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-lg col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                            <label for="name">Name</label>
                                            <div id="cart_pro_dd_div">
                                            <select class="form-control not_select2" id="name" name="pro_id" tabindex="-1" aria-hidden="true" style="width: 100%;">

                                            </select>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-lg col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <label for="qty">Quantity</label>
                                            <input type="text" class="form-control" name="qty" value="1" id="qty" placeholder="Quantity">
                                        </div>
                                        <div class="form-group col-lg-2 col-md-6 col-xs-12">
                                            <label for="day">Days</label>
                                            <input type="text" class="form-control" name="days" id="day" placeholder="Days">
                                        </div>

                                        <div style="margin-bottom: 0px;" class="form-group form-group-lg col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                            <label for="day">&nbsp;</label>
                                            
                                        <button style="margin-top: 0px;" id="add_item_btn" type="button" class="btn btn-lg btn-success form-control">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12" style="margin-bottom: 10px;">
                            <div class="card"> 
                                <div class="card-body" id="order_cart_div" style="padding: 10px;">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal" style="background-color: #979797;" >NO</button>
                    <button type="button" id="add_to_queue" class="btn btn-success btn-lg">ADD TO QUEUE</button>
                </div>
            </div>
        </div>
    </div>-->
    
        <div class="modal fade modal-info" id="visit_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                    <h3 class="modal-title" id="myModalLabel">YOU CAME FOR? </h3>
                </div>
                <div class="modal-body" style='overflow: auto;'>
                    
                    <div class="row">
                        <div class="col-xs-12" style="margin-bottom: 10px;">
                            <div class="card"> 
                                <div class="card-body" id="order_cart_div" style="padding: 10px;">
                                    <label class="radio-inline">
                                    <input type="radio" name="type" id="type_1" value="1"> VISIT
                                  </label>
                                  <label class="radio-inline">
                                    <input type="radio" name="type" id="type_2" value="2"> SHOTS/PRODUCTS ONLY
                                  </label>
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal" style="background-color: #979797;" >NO</button>
                    <button type="button" id="add_to_queue" class="btn btn-success btn-lg">ADD TO QUEUE</button>
                </div>
            </div>
        </div>
    </div>

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

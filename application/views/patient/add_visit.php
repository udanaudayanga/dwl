<?php $this->load->view('template/header');?>
<style>
    #prepaid_history .row > [class*="col-"] { margin-bottom: 10px;}
    div#check_prepaid table tr td input {font-size: 16px !important;}
    div#check_prepaid table tr td select {font-size: 16px !important;}
</style>

<div class="row">
        <div class="card">

            <div class="card-body" style="overflow: auto;padding:15px;">
                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12" style="height: 220px;border: 0.5px solid #ccc;padding-top: 2px;">
                    <div class="thumbnail" style="border: none;margin-bottom: 0px;margin-top: 0px;">
                        <a target="_blank" href="<?php echo site_url();?>/assets/upload/patients/<?php echo $patient->photo;?>">
                            <img style="position: absolute;top:34%;left:35%;" src="/phpThumb/phpThumb.php?src=/assets/upload/patients/<?php echo $patient->photo;?>&amp;w=60&amp;h=70&amp;zc=1&amp;f=png" />
                        </a>
                    </div>
                </div>
                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12" style="height: 110px;border: 0.5px solid #ccc;padding: 0px;">
                    <div class="col-xs-6" style="padding-right:0px;">
                        <h3 style="margin-top:10px;"><a href="<?php echo site_url("patient/view/$patient->id");?>" target="_blank"><?php echo $patient->lname." ".$patient->fname;?></a></h3>
                        <h4 style="width: 80%;margin-bottom: 10px;"><span class="label label-success"><?php echo getPatientStatus($patient->status);?></span> </h4>
                        <span style="font-size: 15px;"><abbr title="Date of birth">DOB:</abbr>  <?php if($patient->dob) echo date('m/d/Y',strtotime($patient->dob));?></span>
                    </div>
                    <div class="col-xs-6" style="padding-right:0px;">
                        <address style="margin-top: 10px;">
                            <?php echo $patient->address;?>&nbsp;
                            <?php echo $patient->city.", ".getStateAbbr($patient->state)." ".$patient->zip;?><br>
                            <?php echo "(".substr($patient->phone, 0, 3).") ".substr($patient->phone, 3, 3)."-".substr($patient->phone,6);?><?php if($patient->sms == 0){?>&nbsp;<span style="color:red;font-size: 16px;cursor: pointer;" title="SMS has stopped" class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><?php } ?><br>
                            
                        </address>	
                    </div>		
                </div>
                <div class="col-lg-6 col-md-5 col-sm-5 col-xs-12" style="height: 110px;border: 0.5px solid #ccc;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 50%;height: 30px;">
                                <h4 style="margin-bottom: 5px;margin-top: 5px;font-size: 16px;">Medications (Last Visit): </h4>
                            </td>
                            <td style="width: 50%;height: 30px;">
                                <span style="font-size: 16px;margin-left: 10px;"><?php if($last_visit){?>
                        <?php echo ($last_visit->med3)? getProduct($last_visit->med3)->name : ($last_visit->med1?getProduct($last_visit->med1)->name:"-")." / ".($last_visit->med2?getProduct($last_visit->med2)->name:"-");?>
                        <?php } ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 30px;">
                                <h4 style="margin-bottom: 5px;margin-top: 5px;font-size: 16px;">Next Visit:</h4>
                            </td>
                            <td style="height: 30px;">
                                <?php
                                    $nvd = getNextVisitDate($patient->id);
                                ?>
                                <span style="font-size: 16px;text-align: center;margin-left: 10px;"><?php echo $nvd['status']=='success'?date("M d, Y",strtotime($nvd['nvd'])):$nvd['msg'];?></span>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 45px;">
                                <h4 style="margin-bottom: 5px;margin-top: 5px;font-size: 16px;">Coming After:</h4>
                            </td>
                            <td style="height: 45px;">
                                <?php if($last_visit){
                                                  $days = ($last_visit->is_med == 1)? $last_visit->med_days : $last_visit->no_med_days;
                                                  $diff = time() - strtotime($last_visit->visit_date);
                                                  $diff = floor($diff/(60*60*24));
                                                  $diff = $diff - $days;
                                              ?>
                                <?php 
                                    $ed = getNextVisitDate($patient->id, 5);
                                    $last_ed = "";
                                    if($ed['status']=='success')
                                    {
                                        if($ed['vc']>3 && $ed['ed']>0)
                                        {
                                            $vcc = $ed['vc']-1;
                                            $last_ed = "In Last $vcc visits : -".$ed['ed']." days";
                                        }
                                    }
                                ?>
                                <p style="font-size: 16px;margin-left: 10px;margin-bottom: 0px;"><?php echo $diff;?> Days</p>
                                <?php if($last_ed != ""){?><p style="font-size: 13px;margin-left: 10px;margin-bottom: 0px;">(<?php echo $last_ed;?>)</p><?php } ?>
                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                    
                </div>
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12" style="height: 110px;border: 0.5px solid #ccc;text-align: center;padding-top: 2%;">
                    
                    <div class="col-xs-6" style="">
                        <?php if(!empty($prepaids)){?>
                        <a href="" style="font-size: 1.1em;width: 100%;padding: 7px 16px;" data-id="<?php echo $patient->id;?>" class="btn btn-lg btn-info" id="check_prepaid_btn">CHECK PP</a>
                        <?php } ?>
                    </div>
                    
                    
                     <div  class="col-xs-6">
                        <?php if($patient->freezed == 0){?>
                         <?php if($today_order){?>
                         <a style="font-size: 1.1em;width: 100%;padding: 7px 16px;" href="<?php echo site_url("order/edit/$today_order->id");?>" class="btn btn-lg btn-success">Edit Order</a>
                         <?php }else{?>  
                                <?php if($patient->status==2){?>
                                    <a style="font-size: 1.1em;width: 100%;padding: 7px 16px;background-color: #d9534f;color: #fff;" href="<?php echo site_url("order/add/$patient->id");?>" class="btn btn-lg">Restart</a>
                                <?php }elseif($patient->status==3){?> 
                                    <a style="font-size: 1.1em;width: 100%;padding: 7px 16px;background-color: #88C100;color: #fff;" href="<?php echo site_url("order/add/$patient->id");?>"  class="btn btn-lg">New Start</a>
                                <?php }else{?> 
                                    <a style="font-size: 1.1em;width: 100%;padding: 7px 16px;" href="<?php echo site_url("order/add/$patient->id");?>" class="btn btn-lg btn-success">Place Order</a>
                                <?php } ?>
                         <?php } ?> 
                        <?php } ?>
                     </div>
                    
                </div>
            </div>
        </div>
</div>
<?php if($this->session->flashdata('message')){?>
<div role="alert" class="alert fresh-color alert-success" style="margin-top:10px;">
          <strong><?php echo $this->session->flashdata('message');?></strong>
</div>
<?php } ?>
<?php if($this->session->flashdata('error')){?>
    <div role="alert" class="alert fresh-color alert-danger" style="margin-top:10px;">
                    <?php echo $this->session->flashdata('error');?>
    </div>
<?php } ?>

<div class="row" style="margin-top: 15px;">
    <div class="col-md-5 col-sm-12 col-xs-12" style="padding: 0px 5px;">
        <div class="card">
           
            <div class="card-body" style="overflow: auto;padding: 0px;">
                <?php foreach($prepaids as $pp){?>
                <div class="col-lg-3 col-md-6 col-sm-4 col-xs-12" style="padding: 5px;">
                    <div class="thumbnail" style="background-color: #efefef;padding: 5px; margin-bottom: 0px;">
                        <h4 style="font-size: 16px;"><?php echo $pp->name;?></h4>
                    <input type="text" class="form-control input-lg" readonly="readonly" value="<?php echo $pp->remaining;?>" style="text-align: center;background-color: #f9f9f9;padding: 6px;font-size: 16px;height: 30px;"/>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-md-1 col-sm-0">
        &nbsp;
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12" style="padding: 0px 5px;">
        <div class="card">
            <div class="card-header">
                <div class="card-title" style="padding:0px 10px;">
                    <div class="title" style="font-size: 17px;">Dr Alerts</div>
                </div>
            </div>
            <div class="card-body" style="overflow: auto;padding:0px;">
                              
                <?php foreach($dralerts as $alert){?>
                <div class="alert fresh-color alert-danger" role="alert" style="margin: 10px;padding: 5px;background-color: rgba(255, 0, 0, 1);font-size: 12px;line-height: 1.1;">
                    <p><span style="font-weight: bold;font-size: 12px;"><?php echo date('m/d/Y',strtotime($alert->created));?>:</span><a onclick="return confirm('Are you sure?');" style="cursor: pointer;" href="<?php echo site_url("patient/delAlert/$alert->id/$patient->id");?>"><span class="glyphicon glyphicon-remove pull-right" style="color: #4a4a4a;" aria-hidden="true"></span></a></p>
                     <?php echo nl2br_except_pre($alert->msg);?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12" style="padding: 0px 5px;">
        <div class="card">
            <div class="card-header">
                <div class="card-title" style="padding:0px 10px;">
                    <div class="title" style="font-size: 17px;">General Alerts</div>
                </div>
            </div>
            <div class="card-body" style="overflow: auto;padding: 0px;">
                <?php if(empty($patient->dob) || empty($patient->goal_weight) || empty($patient->address)){?>
                <div class="alert fresh-color alert-danger" role="alert" style="margin: 10px;padding: 5px;background-color: rgb(36, 4, 216);font-size: 12px;line-height: 1.1;">
                     This patient's profile is incomplete. Click <a style="color: yellow;" href="<?php echo site_url("patient/edit/$patient->id");?>">here</a> to update profile, before create order.
                </div>
                <?php } ?>
                
                <?php foreach($alerts as $alert){?>
                <div class="alert fresh-color alert-info" role="alert" style="margin:10px;padding: 5px;background-color: rgb(36, 4, 216);font-size: 13px;line-height: 1.1;">
                    <p><span style="font-weight: bold;font-size: 12px;"><?php echo date('m/d',strtotime($alert->created));?>:</span><a onclick="return confirm('Are you sure?');" style="cursor: pointer;" href="<?php echo site_url("patient/delAlert/$alert->id/$patient->id");?>"><span class="glyphicon glyphicon-remove pull-right" style="color: #4a4a4a;" aria-hidden="true"></span></a></p>
                   <?php echo nl2br_except_pre($alert->msg);?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
        
</div>

<div class="row" style="margin-top: 20px;">
    <?php if($last_visit && trim($last_visit->alerts) != ''){?>
    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
        <div role="alert" class="alert fresh-color alert-warning">
            <strong>ALERT!</strong> <?php echo $last_visit->alerts;?>
        </div>
    </div>
    <?php } ?>
    
    <?php foreach($reminders as $r){?>
    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
        <div role="alert" class="alert fresh-color alert-warning ">
        <strong>Reminder</strong> <?php echo $r->text;?>
        </div>
    </div>
    
<?php } ?>
</div>




<div class="modal fade modal-info"  id="check_prepaid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">PRE-PAID - <?php echo $patient->lname." ".$patient->fname;?></h4>
            </div>
            <div class="modal-body" id="pp_history_body_<?php echo $patient->id;?>">
                <div id="pp_error_div" style="padding:5px;font-weight: bold" role="alert" class="alert fresh-color alert-danger"></div>
                <div id="pp_success_div" style="padding:5px;font-weight: bold" role="alert" class="alert fresh-color alert-success"></div>
                <div class="loader-container text-center color-white">
                    <div><i class="fa fa-spinner fa-pulse fa-4x"></i></div>
                    <div>Loading...</div>
                </div>
                <div class="ppredeemtbl">
                    <?php $this->load->view('patient/_pp_redeem_tbl');?>
                </div>
                <div class="today_redeem" style="margin-top: 20px;border-top: 1px solid #999;overflow: auto;">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-info" id="prepaid_history" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Prepaid History Breakdown</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){

        
    function ppUpdate(_modal)
    {
        _modal.find('.pp_update_new').on('click', function(ee) {
            ee.preventDefault();
            
            _row = $(this);
            _pro_id = _row.data('proid');
            _pp_id = _row.data('ppid');
            _patient = _row.data('patient');
            _quantity = parseInt($('#pp_today_'+_pp_id).val());
            _pro_id = _row.data('proid');
            _today = parseInt($('#pp_today_'+_pp_id).val());
            _remaining = parseInt($('#pp_remaining_'+_pp_id).val());

            
            if($.trim(_today) == '' || $.trim(_today) < 1)
            {            
                _modal.find('.pp_error_div').html('Today value cannot be empty!').show().delay(2000).hide(0);
            }
            else if(_today > _remaining)
            {
                _modal.find('.pp_error_div').html('Not enough Qty for that item to redeem today').show().delay(2000).hide(0);
                $('#pp_at_'+_pp_id).val('');
                $('#pp_today_'+_pp_id).val('')
            }
            else
            {
                _data = {pp_id:_pp_id,qnty:_quantity};
                _modal.find('.modal-body').addClass('loader');
                $.post(BASE_URL+'patient/update_prepaid',_data,function(data){
                    _modal.find('.modal-body').removeClass('loader');
                    data = JSON.parse(data);
                    if(data.status == 'success')
                    {
                        getTodayReedeemed(_pid,_modal);
                        _modal.find('.pp_success_div').html(data.msg).show().delay(5000).hide(0);
                        $('#pp_remaining_'+_pp_id).val($('#pp_at_'+_pp_id).val());
                        $('#pp_at_'+_pp_id).val('');
                        $('#pp_today_'+_pp_id).val('')
                    }
                    else if(data.status == 'error')
                    {
                        _modal.find('.pp_error_div').html(data.error).show().delay(2000).hide(0);
                        $('#pp_at_'+_pp_id).val('');
                        $('#pp_today_'+_pp_id).val('')
                    }
                });
            }
        });
    }
        function ppRemain(_modal)
        {
            _modal.find(".ppt").on('change keyup paste', function() {
                _pp_id = $(this).data('ppid');
                _today = parseInt($(this).val());
                _remaining = parseInt($('#pp_remaining_'+_pp_id).val());
                _balance = 0;
                if(_today > _remaining)
                {
                    $(this).val(_remaining);
                }
                else
                {
                    _balance = _remaining - _today;
                }

                $('#pp_at_'+_pp_id).val(_balance);
            });
        }
        
        $("#check_prepaid").find(".ppt").on('change keyup paste', function() {
	     _pp_id = $(this).data('ppid');
            _today = parseInt($(this).val());
            _remaining = parseInt($('#pp_remaining_'+_pp_id).val());
            _balance = 0;
            if(_today > _remaining)
            {
                $(this).val(_remaining);
            }
            else
            {
                _balance = _remaining - _today;
            }
            
            $('#pp_at_'+_pp_id).val(_balance);
	});
        
        $('#check_prepaid').on('click','.check_history_btn_new',function(e){
            e.preventDefault();
            _target = $(this);

            var pp_id = _target.data('ppid');
            _modal_pph = $('#prepaid_history');

            $.get(BASE_URL+'patient/getPPHistory/'+pp_id,function(data){
                _modal_pph.find('.modal-body').html(data);
                _modal_pph.find('.datatable').dataTable();

            });

             _modal_pph.modal();
        });
        
        $('#check_prepaid_btn').on('click',function(e)
        {
            e.preventDefault();    
            _target = $(this);
            _pid = _target.data('id');     
            _modal = $('#check_prepaid');
            _modal.find('#pp_error_div').html('').hide();
            _modal.find('#pp_success_div').html('').hide();
            _modal.find(".today_redeem").html('');
            
//            _modal.find('.pp_update_new').on('click', function(ee) {
//                ee.preventDefault();
//                _modal.find('#pp_error_div').html('').hide();
//                _row = $(this);
//                 _pro_id = _row.data('proid');
//                _pp_id = _row.data('ppid');
//                _patient = _row.data('patient');
//                _quantity = parseInt($('#pp_today_'+_pp_id).val());
//                _pro_id = _row.data('proid');
//                _today = parseInt($('#pp_today_'+_pp_id).val());
//                _remaining = parseInt($('#pp_remaining_'+_pp_id).val());
//
//                
//
//                if($.trim(_today) == '' || $.trim(_today) < 1)
//                {   
//                    _modal.find('#pp_error_div').html('Today value cannot be empty!').show().delay(2000).hide(0);                    
//                }
//                else if(_today > _remaining)
//                {
//                    _modal.find('#pp_error_div').html('Not enough Qty for that item to redeem today').show().delay(2000).hide(0);
//                    $('#pp_at_'+_pp_id).val('');
//                    $('#pp_today_'+_pp_id).val('')
//                }
//                else
//                {
//                    _data = {pp_id:_pp_id,qnty:_quantity};
//                    _modal.find('.modal-body').addClass('loader');
//
//                    $.post(BASE_URL+'patient/update_prepaid',_data,function(data){
//                        _modal.find('.modal-body').removeClass('loader');
//                        data = JSON.parse(data);
//                        if(data.status == 'success')
//                        {
//                            getTodayReedeemed(_pid,_modal);
//                            _modal.find('#pp_success_div').html(data.msg).show().delay(5000).hide(0);
//                            $('#pp_remaining_'+_pp_id).val($('#pp_at_'+_pp_id).val());
//                            $('#pp_at_'+_pp_id).val('');
//                            $('#pp_today_'+_pp_id).val('')
//                        }
//                        else if(data.status == 'error')
//                        {
//                            _modal.find('#pp_error_div').html(data.error).show().delay(2000).hide(0);
//                            $('#pp_at_'+_pp_id).val('');
//                            $('#pp_today_'+_pp_id).val('')
//                        }
//                        
//                    });
//                }
//            });
            
            ppUpdate(_modal);
            
            _modal.on("hidden.bs.modal", function () {
                _modal.find('#pp_error_div').html('').hide();
                _modal.find('#pp_success_div').html('').hide();
                _modal.find(".today_redeem").html('');
            });
            
            getTodayReedeemed(_pid,_modal);
            _modal.modal();
        });
        
        function getTodayReedeemed(pid,_mod)
        {
            $.get(BASE_URL+"patient/todayRedeem/"+pid,{id:pid},function(data){

                _element = _mod.find(".today_redeem");
                _element.html(data);

                _element.find('.update_ppr').on('click', function(e) {
                    e.preventDefault();
                    _target = $(this);
                    _id = _target.data('id');
                    _ori = _target.data('ori');
                    _val = _element.find('#pprd_'+_id).val();

                    if(_val >= _ori)
                    {
                        bootbox.alert('New value cannot be larger than redeemed amount');
                    }
                    else
                    {
                        $.post(BASE_URL+'patient/adjustPP',{id:_id,ori:_ori,val:_val},function(res){
                            res = JSON.parse(res);
                            if(res.status == 'success')
                            {
                                _mod.find('.ppredeemtbl').html(res.pp_tbl);
                                ppUpdate(_mod);
                                ppRemain(_mod);
                                getTodayReedeemed(pid,_mod);                            
                            }
                        });
                    }

                });

            });
        }


        
        $('#check_prepaid').on('hidden.bs.modal', function () {
            document.location.reload(true);
        })
        

    });
</script>
<?php $this->load->view('template/footer');?>

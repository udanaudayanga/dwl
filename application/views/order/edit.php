<?php $this->load->view('template/header');?>
<div class="row">
    <div class="col-xs-12">
        <div class="card">                                
            <div class="card-body" style="padding:10px 0px;">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="thumbnail" style="border: none;margin-bottom: 0px;">
                        <img style="margin-left: 0px;"  src="/phpThumb/phpThumb.php?src=/assets/upload/patients/<?php echo $patient->photo;?>&amp;h=90&amp;zc=1&amp;f=png" />
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="">
                    <h3 style="margin-top: 0px;"><?php echo $patient->lname." ".$patient->fname;?></h3>
                    <h5><abbr title="Date of birth">DOB:</abbr>  <?php echo date('m/d/Y',strtotime($patient->dob));?></h5>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="">
                    
                    <address style="margin-top: 0px;margin-bottom: 0px;">
                        <?php echo $patient->address;?><br>
                        <?php echo $patient->city.", ".getStateAbbr($patient->state)." ".$patient->zip;?><br>
                        <?php echo "(".substr($patient->phone, 0, 3).") ".substr($patient->phone, 3, 3)."-".substr($patient->phone,6);?>
                    </address>					
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="">
                    <h4 style="margin-top: 0px;"><?php echo $location->name;?></h4>
                    <h4 style="margin-top: 10px;font-size: 16px;">Patient Category: &nbsp;&nbsp;<span style="text-transform: uppercase;"><?php echo $patient->patient_category;?></span></h4>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php if(!empty($prepaids) && FALSE){?>
<div class="row">
    <div class="col-xs-12">
        <div class="card">   
            <div class="card-body" style="padding: 10px 0;font-size: 18px;">
                <div style="font-weight: bold;" class="col-lg-1 col-md-2 col-sm-3 col-xs-4">Not Redeem:</div>
                <?php foreach($prepaids as $pp){
                 echo $pp->name." [$pp->remaining]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                 } ?> 
                <a href="" id="check_prepaid_btn" style="color: #4cae4c;font-size: 18px;">WANT TO REDEEM ?</a>
            </div>
        </div>
    </div>
</div>    
<?php } ?>
<?php if(!empty($rt) && FALSE){?>
<div class="row">
    <div class="col-xs-12">
        <div class="card">   
            <div class="card-body" style="padding: 10px 0;font-size: 18px;">
                <div style="font-weight: bold;" class="col-lg-1 col-md-2 col-sm-3 col-xs-4">Redeemed:</div>
                <?php foreach($rt as $pp){ 
                    $order_link = (!empty($pp->order))?"[<a style='color: #4cae4c;' href='".site_url("order/view/$pp->order")."' target='_blank'>#".str_pad($pp->order, 5, '0', STR_PAD_LEFT)."</a>]": '';
                 echo $pp->name." [$pp->quantity]$order_link&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
                 } ?> 
                
            </div>
        </div>
    </div>
</div>    
<?php } ?>
<div class="row">
    <div class="col-xs-12">
        <div class="card">   
            <div class="card-header">
                <div class="card-title" style="padding: 0.3em 15px;">
                    <div class="title">Add Product to Order</div>
                </div>
            </div>
            <div class="card-body" style="padding: 10px 0;">
                <form id="add_pro_cart" method="POST">
                    <div class="form-group  col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label for="cat">Category</label>
                        <select class="form-control" name="cat_id" id="cart_add_cat" tabindex="-1" aria-hidden="true" style="width: 100%;">
                            <option>Select</option>
                           <?php foreach($categories as $cat){?>
			    <option <?php echo set_select('cat_id', $cat->id); ?> value="<?php echo $cat->id;?>"><?php echo $cat->name;?></option>
			    <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label for="name">Name</label>
                        <div id="cart_pro_dd_div">
                        <select class="form-control" id="name" name="pro_id" tabindex="-1" aria-hidden="true" style="width: 100%;">
                           
                        </select>
                        </div>
                    </div>
                    <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label for="qty">Quantity</label>
                        <input type="text" class="form-control" name="qty" value="1" id="qty" placeholder="Quantity">
                    </div>
<!--                    <div class="form-group col-lg-2 col-md-6 col-xs-12">
                        <label for="day">Days</label>
                        <input type="text" class="form-control" name="days" id="day" placeholder="Days">
                    </div>-->

                    <div style="" class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label for="day">&nbsp;</label>
                    <button style="margin-top: 0px;" id="add_pro_cart_btn" type="button" class="btn btn-success form-control">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div role="alert" id="js_cart_error" class="alert fresh-color alert-danger hide cart_error">
    <strong></strong>
</div>
<?php if($this->session->flashdata('error')){?>
<div role="alert" class="alert fresh-color alert-danger cart_error">
      <strong><?php echo $this->session->flashdata('error');?></strong>
</div>
<?php } ?>
<form method="POST" >
<div class="row">
    <div class="col-xs-12">
        <div class="card"> 
            <div class="card-body" id="order_cart_div" style="padding: 10px;">
                <?php $this->load->view('order/_cart');?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="card"> 
            <div class="card-body">
                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                    <div class="col-lg-4 col-md-6 col-xs-12" style="padding-left: 0px;">
                    <label for="exampleInputName2">Order made by: </label>
                        <select name="staff_id" class="form-control" id="exampleInputName2">
                            <?php foreach($staff as $s){?>
                            <option <?php if($s->id == $order->staff_id){?>selected="selected"<?php } ?> value="<?php echo $s->id;?>"><?php echo $s->lname." ".$s->fname;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-6 col-xs-12" style="padding-left: 0px;">
                    <label for="exampleInputName3">Order Type: </label>
                        <select name="status" class="form-control" id="exampleInputName3" style="width: 100%;">
                            <option <?php if($order->status == 0){?>selected="selected"<?php } ?> value="0">Normal</option>
                            <option <?php if($order->status == 3){?>selected="selected"<?php } ?> value="3">SO/PrO</option>
                            
                        </select>
                    </div>
                </div>
                <div class="form-group col-lg-2 col-md-6 col-xs-12">

                        <label for="cc_payment">Payment Type</label>
                        <div>
                            <select id="payment_type" name="payment_type" class="form-control">
                                <option <?php if($order->payment_type == 'cash'){?>selected="selected"<?php } ?> value="cash">CASH</option>
                                <option <?php if($order->payment_type == 'credit'){?>selected="selected"<?php } ?> value="credit">CREDIT</option>
                                <option <?php if($order->payment_type == 'mix'){?>selected="selected"<?php } ?> value="mix">MIX</option>
                            </select>
                        </div>
                </div>
                <div class="form-group col-lg-2 col-md-6 col-xs-12 cash_div">
                        <label for="Received">Received</label>
                        <input type="text" class="form-control" value="" id="Received" placeholder="">
                </div>
                <div class="form-group col-lg-2 col-md-6 col-xs-12 cash_div">
                        <label for="Balance">Balance</label>
                        <input type="text" class="form-control" id="Balance" readonly="readonly" value="" placeholder="">
                </div>
                <div class="form-group col-lg-2 col-md-6 col-xs-12 mix_div">
                        <label for="Received">Credit Amount</label>
                        <input type="text" name="credit_amount" class="form-control" value="<?php echo $order->credit_amount;?>" id="mix_received" placeholder="">
                </div>
                <div class="form-group col-lg-2 col-md-6 col-xs-12 mix_div">
                        <label for="Balance">Cash Amount</label>
                        <input type="text" class="form-control" id="mix_balance" readonly="readonly" value="" placeholder="">
                </div>
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-lg btn-success pull-right">Update Order</button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<div class="modal fade modal-info"  id="check_prepaid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">PRE-PAID - <?php echo $patient->lname." ".$patient->fname;?></h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>                                                        
                            <th rowspan="2" style="width: 25%;text-align: center;vertical-align: middle;">Name</th>
                            <th style="text-align: center;" colspan="3">Status</th>
                            <th rowspan="2" style="width: 25%;text-align: center;vertical-align: middle;">Actions</th>
                        </tr>
                        <tr>
                            <th style="text-align: center;">Remaining</th>
                            <th style="text-align: center;">Today (Days / Qty)</th>
                            <th style="text-align: center;">After Today</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($prepaids as $pp){?>
                            <tr>
                                <td><?php echo $pp->name;?></td>
                                <td style="text-align: center;"><input type="text" id="pp_remaining_<?php echo $pp->id;?>" readonly="readonly" class="form-control input-sm" style="width: 100%;text-align: center;" value="<?php echo $pp->remaining;?>"/></td>
                                <td style="text-align: center;">
                                    <?php if($pp->cat_id == 4){?>
                                    <select onselect="return validateQty(event);" class="form-control not_select2 ppt" data-ppid="<?php echo $pp->id;?>" id="pp_today_<?php echo $pp->id;?>"  data-proid="<?php echo $pp->pro_id;?>">
                                        <option value="0">SELECT</option>
                                        <option value="7">7</option>
                                        <option value="14">14</option>
                                        <option value="21">21</option>
                                        <option value="28">28</option>
                                    </select>
                                    <?php }else{?> 
                                    <input onkeypress='return validateQty(event);' type="number" data-ppid="<?php echo $pp->id;?>" id="pp_today_<?php echo $pp->id;?>"  data-proid="<?php echo $pp->pro_id;?>" class="form-control input-sm ppt" style="width: 100%;text-align: center;" />
                                    <?php } ?>
                                </td>
                                <td style="text-align: center;"><input type="text" id="pp_at_<?php echo $pp->id;?>" readonly="readonly" class="form-control input-sm" style="width: 100%;text-align: center;" /></td>
                                <td>
                                    <a class="hover check_history_btn" data-ppid="<?php echo $pp->id;?>" data-proid="<?php echo $pp->pro_id;?>" style="font-weight: 500;color: #31708f;" href="">HISTORY</a>&nbsp;&nbsp;
                                    <a data-ppid="<?php echo $pp->id;?>" data-proid="<?php echo $pp->pro_id;?>" class="hover pp_update" style="font-weight: 500;color: #31708f;" href="">UPDATE</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
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
        $("#Received").on('change keyup paste', function() {
	    _total = $('#cart_total').val();
            _rec = $(this).val();
            _bal = _rec -_total;
            $('#Balance').val(_bal.toFixed(2));
            
            if(!_rec)$('#Balance').val('');
	});
        
        $("#cc_payment").change(function() {
           if($(this).prop('checked'))
            {
                $('#Received').attr('readonly', true);
            }
            else
            {
                $('#Received').attr('readonly', false);
            }
        });
        
        $("#mix_received").on('change keyup paste', function() {
	    _total = $('#cart_total').val();
            _rec = $(this).val();
            _bal = _total - _rec;
            $('#mix_balance').val(_bal.toFixed(2));
            
            if(!_rec)$('#mix_balance').val('');
	});
        
        $('#payment_type').on('change',function(){
            _payment_type = $(this).val();
            
            if(_payment_type == 'cash')
            {
                $('.mix_div').hide();
                $('.cash_div').show();
            }
            else if(_payment_type == 'credit')
            {
                $('.mix_div').hide();
                $('.cash_div').hide();
            }
            else if(_payment_type == 'mix')
            {
                $('.mix_div').show();
                $('.cash_div').hide();
            }
            
        });
        
        _payment_type = $('#payment_type').val();
        if(_payment_type == 'cash')
        {
            $('.mix_div').hide();
            $('.cash_div').show();
        }
        else if(_payment_type == 'credit')
        {
            $('.mix_div').hide();
            $('.cash_div').hide();
        }
        else if(_payment_type == 'mix')
        {
            $('.mix_div').show();
            $('.cash_div').hide();
        }
        
        _total = $('#cart_total').val();
        _rec = $('#mix_received').val();
        _bal = _total - _rec;
        $('#mix_balance').val(_bal.toFixed(2));
        
        $(".ppt").on('change keyup paste', function() {
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
            console.log(_pp_id + ' '+_today + ' ' + _remaining);
            $('#pp_at_'+_pp_id).val(_balance);
	});
        
        $('#check_prepaid').on('hidden.bs.modal', function () {
            document.location.reload(true);
        })
    });
</script>

<?php $this->load->view('template/footer');?>
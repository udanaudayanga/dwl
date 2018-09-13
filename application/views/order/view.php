<?php $this->load->view('template/header');?>

<div class="row">
    <div class="col-xs-12">
        <div class="card">                                
            <div class="card-body" style="padding:15px 0px;">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="thumbnail" style="border: none;margin-bottom: 0px;">
                        <img  src="/phpThumb/phpThumb.php?src=/assets/upload/patients/<?php echo $patient->photo;?>&amp;w=64&amp;h=54&amp;zc=1&amp;f=png" />
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="">
                    <h3 style="margin-top: 0px;"><?php echo $patient->lname." ".$patient->fname;?></h3>
                    <h5><abbr title="Date of birth">DOB:</abbr>  <?php echo date('m/d/Y',strtotime($patient->dob));?></h5>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="">
                    <h4 style="margin-top: 0px;">Order ID:  <?php echo str_pad($order->id, 5, '0', STR_PAD_LEFT);?></h4>
                    <h4 style="font-size: 16px;">Order Date:  <?php echo date('m/d/Y',strtotime($order->created));?></h4>			
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="">
                    <h4 style="margin-top: 0px;"><?php echo $location->name;?></h4>
                    <h4 style="margin-top: 10px;font-size: 16px;">Patient Category: &nbsp;&nbsp;<span style="text-transform: uppercase;"><?php echo $patient->patient_category;?></span></h4>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<form method="POST" >
<div class="row">
    <div class="col-xs-12">
        <div class="card"> 
            <div class="card-body" id="order_cart_div" style="padding: 10px;">
                <?php $this->load->view('order/_view_cart');?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="card"> 
            <div class="card-body">
                <div class="form-group col-lg-6 col-md-6 col-xs-12"><h4>Payment:</h4></div>
                <div class="form-group col-lg-2 col-md-6 col-xs-12">
                        <!--<label>&nbsp;</label>-->
<!--                    <div class="checkbox">
                      <div class="checkbox3 checkbox-round">
                        <input type="checkbox" name="cc_payment" value="1" id="cc_payment" <?php echo set_checkbox('cc_payment',1,$order->payment_type == 'credit');?> >
                        <label for="cc_payment">
                          Credit Card Payment
                        </label>
                      </div>
                    </div>-->
                        <label for="cc_payment">Credit Card Payment?</label>
                        <div class="checkbox3 checkbox-round">
                            <input type="checkbox" value="1" id="cc_payment" readonly="readonly" class="pull-right" name="cc_payment" <?php echo set_checkbox('cc_payment',1,$order->payment_type == 'credit');?>>
                            <label style="font-weight: 500;" for="cc_payment">
                                
                            </label>
                          </div>
                </div>
                <div class="form-group col-lg-2 col-md-6 col-xs-12" style="border-left: 1px solid #ccc;">
<!--                        <label for="Received">Received</label>
                        <input type="text" class="form-control" value="" id="Received" placeholder="">-->
                </div>
                <div class="form-group col-lg-2 col-md-6 col-xs-12">
<!--                        <label for="Balance">Balance</label>
                        <input type="text" class="form-control" id="Balance" readonly="readonly" value="" placeholder="">-->
                </div>
<!--                <div class="col-xs-12">
                    <button type="submit" class="btn btn-lg btn-success pull-right">Update Order</button>
                </div>-->
            </div>
        </div>
    </div>
</div>
</form>
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
    });
</script>

<?php $this->load->view('template/footer');?>
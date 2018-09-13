<?php $this->load->view('template/header');?>

<style>
	div.dataTables_wrapper div.dataTables_filter input{width: 400px;}   
        .flat-blue .btn.btn-bw{
            background-color: #ffffff;
            border-color: #a0a0a0;
            color: #000;
        }
        .flat-blue .btn.btn-light{
            background-color: #A8FDE6;
            border-color: #A8FDE6;
            color: #009b6c;
        }
        .flat-blue .btn.btn-dark{
            background-color: #578477;
            border-color: #578477;
            color: #ffffff;
        }
        .flat-blue .btn.btn-inactive{
            background-color: #a0a0a0;
            border-color: #a0a0a0;
            color: #ffffff;
        }
        table.dataTable{border-spacing: 0px 5px;}
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">

                <div class="card-title" style="padding: 12px;">
                <div class="title">Orders to Prescriptions</div>
                </div>
            </div>
            <div class="card-body" id="pending_pris_div" style="padding: 15px;">
                <?php if($this->session->flashdata('message')){?>
                <div role="alert" class="alert fresh-color alert-success">
                      <strong><?php echo $this->session->flashdata('message');?></strong>
                </div>
                <?php } ?>
                 <?php if($this->session->flashdata('error')){?>
                <div role="alert" class="alert fresh-color alert-danger">
                      <strong><?php echo $this->session->flashdata('error');?></strong>
                </div>
                <?php } ?>
                <table class="table table-striped" id="pending_odr" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Name</th>
                            <th></th>
                            <th>Wks Meds</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($orders as $order){?>
                        <tr class="<?php if($order->status==1){ echo 'success'; }elseif($order->status == 4){ echo "warning"; } ?>">
                            <td><?php echo str_pad($order->id, 5, '0', STR_PAD_LEFT);?></td>
                            <td><?php echo date('m/d/y',strtotime($order->created));?></td>
                            <td><a target="_blank" href="<?php echo site_url("patient/view/$order->patient_id");?>"><?php echo $order->patient_name;?></a></td>
                            <td style="padding: 2px;">
                                <?php if($order->photo){?>
                                <img class="thumbnail qm_photo" style="margin-left: 0px;margin-bottom: 0px;" src="/phpThumb/phpThumb.php?src=/assets/upload/patients/<?php echo $order->photo;?>&amp;w=64&amp;h=64&amp;zc=1&amp;f=png" />
                                <?php } ?>
                            </td>
                            <td>
                                <?php $wks = getMedWeeks($order->id);
                                    if($wks > 0) echo $wks." wk";
                                ?>
                            </td>
                            <td>&#36;<?php echo $order->net_total;?></td>
                            <td style="padding:0px;">
                                
                                <?php if($order->status == 3){?>
                                <label>SO/PrO</label>&nbsp;&nbsp;|&nbsp;&nbsp;                                
                                 <?php }elseif($order->status == 4){?>
                                <label>PENDING</label>&nbsp;&nbsp;|&nbsp;&nbsp;
                                <?php } ?>
                                <?php if($order->status == 0){?>                                
                                 <a class="btn btn-bw btn-sm" onclick="return confirm('Are you sure?');" title="SO/PrO" id="select_<?php echo $order->id;?>" href="<?php echo site_url("order/productsOnly/$order->id");?>" style="font-size: 15px;padding: 3px;margin: 5px;width:65px;">SO/PrO</a>
                                 <a class="btn btn-bw btn-sm" onclick="return confirm('Are you sure?');" title="Mark as PENDING" id="select_<?php echo $order->id;?>" href="<?php echo site_url("order/pendingOrder/$order->id");?>" style="font-size: 15px;padding: 3px;margin: 5px;width:65px;">Pndg</a> 
                                 <?php if(!$order->visit_id && date('Y-m-d',strtotime($order->created)) == date('Y-m-d') && $user->type == '1'){?><a class="btn btn-bw btn-sm" title="Delete Order"  href="<?php echo site_url("order/pendingRemoveOrder/$order->id");?>" style="font-size: 15px;padding: 3px;margin: 5px;width:65px;">DelOrd</a><?php } ?>
                                <?php } ?>
                                 <a title="Edit Order" class="btn btn-bw btn-sm" id="select_<?php echo $order->id;?>" href="<?php echo site_url("order/edit/$order->id");?>" style="font-size: 15px;padding: 3px;margin: 5px;width:65px;">EditOrd</a>                             
                                 <a   href="" style="font-size: 15px;padding: 3px;margin: 5px;width:65px;" data-done="<?php echo $order->status;?>" data-id="<?php echo $order->id;?>" class="done btn btn-bw btn-sm"><?php echo $order->status==0?'Done':'Activate';?></a>
                                 
                                 <a title="Redeem PrePaid after Order Created" id="cp_<?php echo $order->patient_id;?>" class="check_prepaid btn btn-bw btn-sm" data-pid="<?php echo $order->patient_id;?>"  href="" style="font-size: 15px;padding: 3px;margin: 5px;width:65px;">[RedPrPd]</a>
                                 
                                 
                                 <a class="btn btn-sm getupdate <?php echo $order->done_vp==1? 'btn-dark':'btn-light';?>" data-column="done_vp" data-oid="<?php echo $order->id;?>" title="Visit Page"  target="_blank" href="<?php echo site_url("order/visitpage/$order->id");?>" style="font-size: 15px;padding: 3px;margin: 5px;width:65px;">1-VisitPg</a>
                                
                                
                                <?php if($order->status == 0 || $order->status == 1){?> 
                                <a class="btn btn-sm <?php echo $order->prescription_no > 0? 'btn-dark':'btn-light';?>" title="Create Prescription" id="select_<?php echo $order->id;?>" href="<?php echo site_url("patient/prescription/$order->id");?>" style="font-size: 15px;padding: 3px;margin: 5px;width:65px;">2-Presp</a>  
                                <?php }else{?>
                                <a href="" onclick="return false;" class="btn btn-inactive btn-sm" style="font-size: 15px;padding: 3px;margin: 5px;width:65px;">2-Presp</a>
                                <?php } ?>
                                
                                <?php if($order->prescription_no > 0){?>
                                <a title="Print Label" class="btn btn-sm getupdate <?php echo $order->done_lb==1? 'btn-dark':'btn-light';?>" data-column="done_lb" data-oid="<?php echo $order->id;?>" target="_blank" href="<?php echo site_url("order/print_label/$order->visit_id");?>" style="font-size: 15px;padding: 3px;margin: 5px;width:65px;">3-PrntLabl</a>
                                <?php }else{ ?>
                                <a href="" onclick="return false;" class="btn btn-inactive btn-sm" style="font-size: 15px;padding: 3px;margin: 5px;width:65px;">3-PrntLabl</a>
                                <?php } ?>
                                <?php if($order->visit_id){?>
                                <a class="btn btn-sm getupdate <?php echo $order->done_fn==1? 'btn-dark':'btn-light';?>" title="Final Page" data-column="done_fn" data-oid="<?php echo $order->id;?>" target="_blank" href="<?php echo site_url("order/finalpage/$order->id");?>" style="font-size: 15px;padding: 3px;margin: 5px;width:65px;">4-FinalPg</a>
                                <?php }else{?>
                                <a href="" onclick="return false;" class="btn btn-inactive btn-sm" style="font-size: 15px;padding: 3px;margin: 5px;width:65px;">4-FinalPg</a>
                                <?php } ?>
                                <a class="btn btn-bw btn-sm" title="Sales Receipt"  target="_blank" href="<?php echo site_url("order/receipt/$order->id");?>" style="font-size: 15px;padding: 3px;margin: 5px;width:65px;">Receipt</a>
                                <?php if($order->payment_type != 'credit'){?>
                                <a class="btn btn-bw btn-sm" title="Cash Receipt"  target="_blank" href="<?php echo site_url("order/cash_receipt/$order->id");?>" style="font-size: 15px;padding: 3px;margin: 5px;width:65px;">Cash</a>
                                <?php } ?>
                                <?php if(FALSE){?>
                                    <?php if($order->status == 3){?>
                                    <label>SO/PrO</label>&nbsp;&nbsp;|&nbsp;&nbsp;                                
                                     <?php }elseif($order->status == 4){?>
                                    <label>PENDING</label>&nbsp;&nbsp;|&nbsp;&nbsp;
                                    <?php } ?>
                                    <?php if($order->status == 0){?>                                
                                     <a class="" onclick="return confirm('Are you sure?');" title="SO/PrO" id="select_<?php echo $order->id;?>" href="<?php echo site_url("order/productsOnly/$order->id");?>" style="color: #31b0d5;">SO/PrO</a> &nbsp;&nbsp;|&nbsp;&nbsp;
                                     <a class="" onclick="return confirm('Are you sure?');" title="Mark as PENDING" id="select_<?php echo $order->id;?>" href="<?php echo site_url("order/pendingOrder/$order->id");?>" style="color: #31b0d5;">Pndg</a> &nbsp;&nbsp;|&nbsp;&nbsp;
                                     <?php if(!$order->visit_id && date('Y-m-d',strtotime($order->created)) == date('Y-m-d') && $user->type == '1'){?><a class="" title="Delete Order"  href="<?php echo site_url("order/pendingRemoveOrder/$order->id");?>" style="color: #31b0d5;">DelOrd</a>&nbsp;&nbsp;|&nbsp;&nbsp;<?php } ?>
                                    <?php } ?>
                                     <a title="Edit Order" class="" id="select_<?php echo $order->id;?>" href="<?php echo site_url("order/edit/$order->id");?>" style="color: #31b0d5;">EditOrd</a>&nbsp;&nbsp;|&nbsp;&nbsp;                             
                                     <a   href="" style="color: #31b0d5;" data-done="<?php echo $order->status;?>" data-id="<?php echo $order->id;?>" class="done"><?php echo $order->status==0?'Done':'Activate';?></a> &nbsp;&nbsp;<span style="color: red;">||</span>&nbsp;&nbsp;

                                     <a title="Redeem PrePaid after Order Created" id="cp_<?php echo $order->patient_id;?>" class="check_prepaid" data-pid="<?php echo $order->patient_id;?>"  href="" style="color: #31b0d5;">[RedPrPd]</a>&nbsp;&nbsp;|&nbsp;&nbsp;

                                     <?php if(!$order->visit_id){?>
                                    <a class="" title="Visit Page"  target="_blank" href="<?php echo site_url("order/visitpage/$order->id");?>" style="color: #31b0d5;">1-VisitPg</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                                    <?php }else{?>
                                    <a class="" title="Visit Page"  target="_blank" href="<?php echo site_url("order/visitpage/$order->id");?>" style="color: darkgray;">1-VisitPg</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                                    <?php } ?>

                                    <?php if($order->status == 0){?> 
                                    <a class="" title="Create Prescription" id="select_<?php echo $order->id;?>" href="<?php echo site_url("patient/prescription/$order->id");?>" style="color: #31b0d5;">2-Presp</a> &nbsp;&nbsp;|&nbsp;&nbsp;  
                                    <?php }else{?>
                                    <span style="color: darkgray;">2-Presp</span>&nbsp;&nbsp;|&nbsp;&nbsp;
                                    <?php } ?>

                                    <?php if($order->prescription_no > 0){?>
                                    <a title="Print Label" class=""  target="_blank" href="<?php echo site_url("order/print_label/$order->visit_id");?>" style="color: #31b0d5;">3-PrntLabl</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                                    <?php }else{ ?>
                                    <span style="color: darkgray;">3-PrntLabl</span>&nbsp;&nbsp;|&nbsp;&nbsp;
                                    <?php } ?>
                                    <?php if($order->visit_id){?>
                                    <a class="" title="Final Page"  target="_blank" href="<?php echo site_url("order/finalpage/$order->id");?>" style="color: #31b0d5;">4-FinalPg</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                                    <?php }else{?>
                                    <span style="color: darkgray;">4-FinalPg</span>&nbsp;&nbsp;|&nbsp;&nbsp;
                                    <?php } ?>
                                    <a class="" title="Sales Receipt"  target="_blank" href="<?php echo site_url("order/receipt/$order->id");?>" style="color: #31b0d5;">Receipt</a>
                                <?php } ?>
                                
                                <?php $this->load->view('patient/_prepaid_modal',array('patient_id'=>$order->patient_id,'patient_name'=>$order->patient_name));?>
                                
                            </td>
                            
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div style="margin-top: -20px;">
                    <a href="" class="btn btn-dark">&nbsp;&nbsp;&nbsp;</a>&nbsp; Shows step completed.
                </div>
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
        
        var table = $('#pending_odr').dataTable();
        table.fnPageChange( 'last' );
        
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
        
        $('#pending_pris_div').on('click','.getupdate',function(e)
        { 
            _target = $(this);
            _column = _target.data('column');
            _oid = _target.data('oid');
            $.post(BASE_URL+'order/updateGenStatus',{column:_column,oid:_oid},function(data){
                if(data=='success')
                {
                    _target.removeClass('btn-light').addClass('btn-dark');
                }
            });
        });  
        
        
         $('#pending_pris_div').on('click','.done',function(e)
         {
             e.preventDefault();
            if(confirm('Are you sure?'))
            {                
                _done = $(this).data('done');
                _id = $(this).data('id');
                _t = $(this);
                $.post(BASE_URL+'order/updateDone',{done:_done,id:_id},function(data){
                    if(data=='success')
                    {
                        if(_done == 0)
                        {
                            _t.text('UNDONE');
                            _t.closest('tr').addClass('success');
                            _t.data('done',1);

                        }
                        else
                        {
                           _t.text('DONE');
                            _t.closest('tr').removeClass('success');
                            _t.data('done',0);

                        }
                        document.location.reload();
                    }
                });
            }
            
        });
        
        if(_pid = GetURLParameter('pid'))
        {
            bootbox.dialog({
                message: "Do you want to Redeem any PREPAID items from Today's/Previous order?",
                title: "Redeem Prepaid",
                buttons: {
                  success: {
                    label: "Redeem",
                    className: "btn-success",
                    callback: function(e) {
                        e.preventDefault();
                         $("#cp_"+_pid).trigger("click");
//                        _modal = $('#check_prepaid_'+_pid);
//                        _modal.find('.pp_error_div').html('').hide();
//                        _modal.find('.pp_success_div').html('').hide();
//                        _modal.modal();
                        bootbox.hideAll()
                      return false;
                    }
                  },
                  danger: {
                    label: "No",
                    className: "btn-danger",
                    callback: function() {
                      window.location = BASE_URL+'order/pending';
                    }
                  }
              }
            });
            
            
        }
   
    $('#pending_pris_div').on('click','.check_history_btn_new',function(e){
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

    $('#pending_pris_div').on('click','.check_prepaid',function(e){
        e.preventDefault();            
        _pid = $(this).data('pid');
        
        _modal = $('#check_prepaid_'+_pid);
        
        _modal.find('.pp_error_div').html('').hide();
        _modal.find('.pp_success_div').html('').hide();
        _modal.find(".today_redeem").html('');
        
        ppUpdate(_modal);
        ppRemain(_modal);
        
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
    
    
    });
</script>
<?php $this->load->view('template/footer');?>

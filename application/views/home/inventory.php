<?php $this->load->view('template/header');?>
<link href="/assets/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="/assets/js/bootstrap-editable.min.js"></script>
<style>
    .dataTables_length select{font-size: 16px;}
    .dataTables_wrapper .editable-container{position: absolute;}
    div.editable-buttons button{margin: 0px;}
</style>
<div class="row">
    <div class="card">
        <div class="card-body" id="all_inventory" style="padding: 10px 15px;">
            <a href="<?php echo site_url('home/printinv');?>" target="_blank" style="margin-bottom: 10px;" class="btn btn-success pull-right">Print PDF</a>
            <table class="datatable table table-bordered table-striped">
                <thead>
                        <tr>
                            <td style="font-weight: bold;">Products</td>
                            <td style="text-align: center;font-weight: bold;">MAIN STORE</td>
                            <?php foreach($locations as $loc){?>
                            <td style="text-align: center;font-weight: bold;"><?php echo !empty($loc->abbr)?$loc->abbr:$loc->name;?></td>
                            <?php } ?>
                            <td>Actions</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($products as $pro){?>
                        <tr>
                            <td style="font-size: 18px;"><?php echo $pro->name;?></td>
                            <td style="text-align: center;">
                                <a style="color: #006400;" data-pk="<?php echo $pro->id;?>" class="sms" href=""><?php echo empty($pro->stock)? 0 : $pro->stock;?></a>
                            </td>
                             <?php foreach($locations as $loc){
                                 $ls = $pro->ls;
                            ?>
                            <td style="text-align: center;">
                                <a style="color: #006400;" data-pk="<?php echo $pro->id;?>" data-name="<?php echo $loc->id;?>" class="sps" href=""><?php echo isset($ls[$loc->id])?$ls[$loc->id]['qty']:0;?></a>
                                
                                &nbsp;<span style="color: #0088cc;">(<a style="color: #0088cc;" data-pk="<?php echo $pro->id;?>" data-name="<?php echo $loc->id;?>" class="msl" href=""><?php echo isset($ls[$loc->id])?$ls[$loc->id]['msl']:0;?></a>)</span></td>
                            <?php } ?>
                            <td>
                                <?php if($pro->is_stock){?> <a title="Add Stock" href="" data-name="<?php echo $pro->name;?>" data-id="<?php echo $pro->id;?>" class="add_main_stock" style="color: #31b0d5;margin-left: 20px;"><span aria-hidden="true" class="glyphicon glyphicon-plus-sign" style="font-size: 1.2em;"></span></a> <?php } ?>
				<?php if($pro->is_stock){?>&nbsp;<a title="Stock History" href="" data-id="<?php echo $pro->id;?>" class="view_stock"  style="color: #31b0d5;"><span aria-hidden="true" class="glyphicon glyphicon-info-sign" style="font-size: 1.2em;"></span></a><?php } ?>
			   
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade modal-info" id="add_main_stock"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Add Main Stock: <span id="as_pro_name_h"></span></h4>
            </div>
            <div class="modal-body">
                <form id="add_main_stock_form" class="form-horizontal">
                    <input type="hidden" name="pro_id" id="as_pro_id" />
                    <div class="col-xs-12 no-padding" id="as_errors"></div>
                    <div class="row">
                        <div class="col-md-12 no-padding" style="margin-bottom: 10px;">

                            <div class="col-md-6">
                              <div class="form-group" style="margin: 0 0 0 0px;">
                                        <label for="as_pro_name">Name</label>
                                        <input type="text" readonly="readonly" value="" id="as_pro_name" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="form-group" style="margin: 0px;">
                                    <label for="bfi">Quantity</label>
                                    <input type="text" name="quantity" placeholder="" id="bfi" class="form-control">
                                </div>
                            </div>
                        </div>
                      </div>
                    <div class="row">
                        <div class="col-md-12 no-padding" style="margin-bottom: 10px;">
                            <div class="col-md-6">

                                <div class="form-group" style="margin: 0px;">
                                    <label for="bmi">Batch Date</label>
                                    <input type="text" name="date" id="bmi" data-date-autoclose="true" class="form-control date_picker" >
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="form-group" style="margin: 0px;">
                                    <label for="expire_date">Expire Date</label>
                                    <input type="text" name="exp_date"  data-date-autoclose="true"  placeholder="" id="expire_date" class="form-control date_picker">
                                </div>
                            </div>
                            
                        </div>
                      </div>
                    <div class="row">
                        <div class="col-md-12 no-padding" style="margin-bottom: 10px;">
                            <div class="col-md-6">

                                <div class="form-group" style="margin: 0px;">
                                    <label for="lot_no">Lot #</label>
                                    <input type="text" name="lot_no" placeholder="" id="lot_no" class="form-control">
                                </div>
                            </div>
                            
                        </div>
                      </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="add_stock_btn" class="btn btn-info">ADD</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-info" id="view_main_stock" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Stock History</h4>
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
        $('#all_inventory').on('click','.add_main_stock',function(e){
            e.preventDefault();
            _target = $(this);
            _modal = $('#add_main_stock');

            _modal.find('#as_pro_name').val(_target.data('name'));
            _modal.find('#as_pro_name_h').html(_target.data('name'));
            _modal.find('#as_pro_id').val(_target.data('id'));
            _modal.find('#as_errors').html('');
    //        _modal.find('select').select2();

            _modal.find('#add_stock_btn').unbind('click').bind('click', function(e) {
                _modal.find('#as_errors').html('');
                $.post(BASE_URL+'product/addMainStock',$('#add_main_stock_form').serialize(),function(data){
                    data = JSON.parse(data);
                    if(data.status == 'error')
                    {
                        _modal.find('#as_errors').html(data.errors);
                    }
                    else if(data.status == 'success')
                    {
                        _modal.find('#as_errors').html(data.msg);
                        _modal.find('input').val('');
                        document.location.reload(true);
                        setTimeout(function(){_modal.modal('hide')},2000);
                    }
                });
            });

            _modal.on('hidden', function() {
                    _modal.find('input').val('');
                    _modal.find('#add_stock_btn').unbind('click');
            });
            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
            _modal.modal('show');
        });
        
        $('#all_inventory').on('click','.view_stock',function(e){
            e.preventDefault();
            _target = $(this);
            _modal = $('#view_main_stock');

            var pro_id = _target.data('id');
            $.get(BASE_URL+'product/viewMainStock/'+pro_id,function(data){
                _modal.find('.modal-body').html(data);
                _modal.find('.datatable').dataTable();

                _modal.find('.rem_stock').unbind('click').bind('click', function(e) {
                    e.preventDefault();
                    if(confirm('Are you sure?'))
                    {
                        _del_link = $(this);
                        var stkId = _del_link.data('id');
                        $.post(BASE_URL+'product/remMainStock/',{stk_id:stkId},function(data){
                            data = JSON.parse(data);
                            if(data.status == 'success')
                            {
                                _del_link.closest("tr").remove();
                            }
                            else
                            {
                                alert(data.msg);
                            }
                        });
                    }
                })
            });
            _modal.on('hidden', function() {
                    _modal.find('.modal-body').html('');
            });
            _modal.modal();
        });
        
        $(".date_picker").datepicker( {
            format: "yyyy-mm",
            startView: "months", 
            minViewMode: "months"
        });
        
        $('.msl').editable({
            type: 'text',  
            ajaxOptions: {
                type: 'post',
                dataType: 'json'
            },
            url: BASE_URL+'home/updatemsl',
            title: 'Set Min Stock Level',
            success: function(response, newValue) {
                if(!response.succes) return response.msg; //msg will be shown in editable form
            }
        });
        
        $('.sps').editable({
            type: 'text',  
            ajaxOptions: {
                type: 'post',
                dataType: 'json'
            },
            url: BASE_URL+'home/updatePS',
            title: 'Set Product Stock Level',
            success: function(response, newValue) {
                if(!response.succes) return response.msg; //msg will be shown in editable form
            }
        });
        
        $('.sms').editable({
            type: 'text',  
            ajaxOptions: {
                type: 'post',
                dataType: 'json'
            },
            url: BASE_URL+'home/updateMS',
            title: 'Set Main Stock Level',
            success: function(response, newValue) {
                if(!response.succes) return response.msg; //msg will be shown in editable form
            }
        });
        
        $('.msl').on('shown', function(e, editable) {
            $('.editable-submit').removeClass('btn-primary').addClass('btn-success');
        });
        
    });
</script>
<?php $this->load->view('template/footer');?>
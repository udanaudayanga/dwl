<?php $this->load->view('template/header');?>
<link rel="stylesheet" type="text/css" href="/assets/js/lightbox/css/lightbox.css">
<script type="text/javascript" src="/assets/js/lightbox/js/lightbox.js"></script>
<style>
	.product_list div.dataTables_wrapper div.dataTables_filter input{width: 400px;}
        #view_stock .row > [class*="col-"] { margin-bottom: 10px;}
 </style>
<div class="row">
    <div class="col-xs-12">
	<div class="card">
	    <div class="card-header">

		<div class="card-title">
		    <div class="title">Products</div>
		</div>
	    </div>
            <div class="card-body product_list" id="all_products" style="padding: 15px;">
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
		<table class="table table-striped" id="product_table" cellspacing="0" width="100%">
		    <thead>
			<tr>
			    <th>Category</th>
			    <th>Name</th>
                            <th style="width: 100px;">Stock On Hand</th>
			    <th style="text-align: center;">Unit</th>
                            <th>$</th>
                            <th style="width: 5%;">Low</th>
			    
                            <th style="width: 100px;">Actions</th>
			</tr>
		    </thead>

		    <tbody>
			<?php foreach($products as $pro){?>
			<tr class="black">
                            <td><?php echo $pro->cat_name;?></td>
			    <td><?php echo $pro->name;?></td>
                            <td style="text-align: center;">
                               <?php
                                if(isset($pro->all_stock) && isset($pro->stock_count))
                                {
                                ?>
                                 <a href="" data-toggle="modal" data-target="#all_loc_<?php echo $pro->id;?>"  style="color: #31b0d5;"><?php echo $pro->stock_count->quantity; ?></a>
                                 <?php $this->load->view('product/pro_all_stock_inv_modal',array('pro'=> $pro));?>
                                 <?php
                                }
                                else 
                                {
                                    echo isset($pro->stock_count)?$pro->stock_count->quantity:'';
                                }
                               ?>
                            
                            </td>
                            <td style="text-align: center;"><?php echo $pro->quantity;?></td>
                            <td><?php echo $pro->price;?></td>
                            <td align="center"><?php echo $pro->lsa;?></td>
                            
                            <td style="padding: 8px 0px 8px 8px;">                                
                                <a title="Edit" href="<?php echo site_url("product/edit/$pro->id");?>" style="color: #31b0d5;"><span aria-hidden="true" style="font-size: 1.1em;" class="glyphicon glyphicon-edit"></span></a> &nbsp;
                                <a title="Delete" data-status="weekly" class="add_visit" href="<?php echo site_url("product/delete/$pro->id");?>" style="color: red;" data-toggle="modal" onclick="return confirm('Please double check before delete. This action not reversible.Are you sure?');"><span style="font-size: 1.1em;" aria-hidden="true" class="glyphicon glyphicon-trash"></span></a> 
                            
                                <?php if($pro->is_stock){?> <a title="Add Stock" href="" data-ms="<?php echo empty($pro->stock)? 0 : $pro->stock;?>" data-name="<?php echo $pro->name;?>" data-id="<?php echo $pro->id;?>" class="add_stock" style="color: #31b0d5;margin-left: 20px;"><span aria-hidden="true" class="glyphicon glyphicon-plus-sign" style="font-size: 1.2em;"></span></a> <?php } ?>
				<?php if($pro->is_stock){?>&nbsp;<a title="Stock History" href="" data-id="<?php echo $pro->id;?>" class="view_stock"  style="color: #31b0d5;"><span aria-hidden="true" class="glyphicon glyphicon-info-sign" style="font-size: 1.2em;"></span></a><?php } ?>
			    </td>
			</tr>
			<?php } ?>

		    </tbody>
		</table>
	    </div>
	</div>
    </div>
</div>
	<div class="modal fade modal-info" id="add_stock"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
	    <div class="modal-dialog">
		<div class="modal-content">
		    <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                        <h4 class="modal-title" id="myModalLabel">Allocate Stock: <span id="as_pro_name_h"></span><label id="main_stock_val" style="float: right;margin-right: 10px;"></label></h4>
		    </div>
		    <div class="modal-body">
			<form id="add_stock_form" class="form-horizontal">
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
				      <div class="form-group" style="margin: 0 0 0 0px;">
						<label for="as_pro_name">Location</label>
                                                <select name="location_id" class="form-control not_select2" >
                                                    <option value="" >Select</option>
                                                    <?php foreach($locations as $location){?>
                                                    <option value="<?php echo $location->id;?>" ><?php echo $location->name;?></option>
                                                    <?php } ?>
                                                </select>
					</div>
				    </div>
				</div>
			      </div>
			    <div class="row">
				<div class="col-md-12 no-padding" style="margin-bottom: 10px;">
				    <div class="col-md-6">
				       
					<div class="form-group" style="margin: 0px;">
                                            <label for="bd">Batch Date</label>
                                            <input type="text" name="date" readonly="readonly" id="bd" data-date-autoclose="true" class="form-control date_picker" >
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
                                            <label for="lot_no">Lot #</label>
                                            <input type="text" name="lot_no" readonly="readonly" placeholder="" id="lot_no" class="form-control">
                                        </div>
				    </div>
				    <div class="col-md-6">
				      
					<div class="form-group" style="margin: 0px;">
                                            <label for="expire_date">Expire Date</label>
                                            <input type="text" name="exp_date" readonly="readonly"  data-date-autoclose="true"  placeholder="" id="expire_date" class="form-control date_picker">
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
<div class="modal fade modal-info" id="view_stock" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
$(document).ready(function() {
    $('#product_table').dataTable({
        "aLengthMenu": [[5, 7, 10, -1], [5, 7, 10, "All"]],
        "iDisplayLength": 5
    });
    
    $(".date_picker").datepicker( {
        format: "yyyy-mm",
        startView: "months", 
        minViewMode: "months"
    });
    
    $('#all_products').on('click','.add_stock',function(e){
	e.preventDefault();
	_target = $(this);
	_modal = $('#add_stock');
        _pro_id = _target.data('id');
	
	_modal.find('#as_pro_name').val(_target.data('name'));
        _modal.find('#as_pro_name_h').html(_target.data('name'));
        _modal.find('#as_pro_id').val(_pro_id);
	_modal.find('#as_errors').html('');
        _modal.find('#main_stock_val').html('Main Stock: '+_target.data('ms'));
//        _modal.find('select').select2();

        $.get( BASE_URL+'product/getLastMainStockLog/'+_pro_id, function( data ) {
            data = JSON.parse(data);
            if(data)
            {
                _modal.find('#bd').val(data.date.slice(0,-3));
                _modal.find('#lot_no').val(data.lot_no);
                _modal.find('#expire_date').val(data.exp_date.slice(0,-3));
            }
            else
            {
                _modal.find('#bd').val('');
                _modal.find('#lot_no').val('');
                _modal.find('#expire_date').val('');
            }
        });
	
	_modal.find('#add_stock_btn').unbind('click').bind('click', function(e) {
	    _modal.find('#as_errors').html('');
	    $.post(BASE_URL+'product/addStock',$('#add_stock_form').serialize(),function(data){
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
                _modal.find('#bd').val('');
                _modal.find('#lot_no').val('');
                _modal.find('#expire_date').val('');
	});
	$.fn.modal.Constructor.prototype.enforceFocus = function() {};
	_modal.modal('show');
    });
    
    $('#all_products').on('click','.view_stock',function(e){
        e.preventDefault();
        _target = $(this);
	_modal = $('#view_stock');
        
        var pro_id = _target.data('id');
        $.get(BASE_URL+'product/viewStock/'+pro_id,function(data){
            _modal.find('.modal-body').html(data);
            _modal.find('.datatable').dataTable();
            
            _modal.find('.rem_stock').unbind('click').bind('click', function(e) {
                e.preventDefault();
                if(confirm('Are you sure?'))
                {
                    _del_link = $(this);
                    var stkId = _del_link.data('id');
                    $.post(BASE_URL+'product/remStock/',{stk_id:stkId},function(data){
                        data = JSON.parse(data);
                        if(data.status == 'success')
                        {
                            _del_link.closest("tr").remove();
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
    
} );
</script>
<?php $this->load->view('template/footer');?>

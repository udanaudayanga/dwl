<?php $this->load->view('template/header');?>

<div class="row">

	<div class="card">
	    <div class="card-header">
		<div class="card-title">
		    <div class="title">Add new product</div>
		</div>
	    </div>
	    <div class="card-body">
		<div class="col-xs-12">
                    <?php echo $errors; ?>
                </div>
		<form  method="POST" enctype="multipart/form-data">
		    <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<label for="cat">Category</label>
                        <select name="cat_id" id="cat" class="form-control" style="width: 100%;">
			    <?php foreach($categories as $cat){?>
			    <option <?php echo set_select('cat_id', $cat->id); ?> value="<?php echo $cat->id;?>"><?php echo $cat->name;?></option>
			    <?php } ?>
			</select>
		    </div>
		    <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<label for="name">Name</label>
			<input type="text" placeholder="Name" name="name" value="<?php echo set_value('name');?>" id="name" class="form-control">
		    </div>
		    <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<label for="price">Price</label>
			<input type="text" placeholder="Price" name="price" id="price" value="<?php echo set_value('price');?>" class="form-control">
		    </div>
                    <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<label for="quantity">Quantity</label>
			<input type="text" placeholder="" name="quantity" id="quantity" value="<?php echo set_value('quantity','1');?>" class="form-control">
		    </div>
                    <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12" style="padding-bottom: 6px;">
			<label for="cat">Measured In</label>
                        <select name="measure_in" id="measure_in" class="form-control" style="width: 100%;">			    
			    <option <?php echo set_select('measure_in', 'Quantity'); ?> value="Quantity">Physical Qty</option>
			    <option <?php echo set_select('measure_in', 'Days'); ?> value="Days">Days</option>
			</select>
		    </div>
                    <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<label for="low_stock">Low Stock Alert</label>
			<input type="number" placeholder="" id="low_stock" name="lsa" value="<?php echo set_value('lsa');?>" class="form-control">
		    </div>
                    
		    <!-- <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12" style="height: 62px;">
			<label for="photo">Photo</label>
			<input name="photo" type="file" id="photo">
		    </div> -->
                     <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12"  style="height: 62px;">
			<label for="pre_paid">&nbsp;</label>
                        <div class="checkbox3 checkbox-round">
                            <input name="prepaid" type="checkbox" value="1" <?php echo set_checkbox('prepaid', '1'); ?> id="checkbox-1">
                            <label for="checkbox-1">
                                Pre Paid
                            </label>
                          </div>
		    </div>
                    <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<label for="ufn">User Friendly Name</label>
			<input type="text" placeholder="User Friendly Name" name="friendly_name" value="<?php echo set_value('friendly_name');?>" id="ufn" class="form-control">
		    </div>
                    
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 15px;">
                        <h4 style="font-family: 'Roboto Condensed',sans-serif;font-size: 1.4em;text-decoration: none;">Inventory Info</h4>
                        <hr style="margin: 10px 0px;">
                    </div>
                        
                    <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<label for="is_stock">Is this a Inventory item?</label>
                        <div class="checkbox3 checkbox-round">
                            <input name="is_stock" type="checkbox" class="pull-right" id="is_stock" value="1" <?php echo set_checkbox('is_stock', '1', TRUE); ?>>
                            <label for="is_stock" style="font-weight: 700;">
                                
                            </label>
                          </div>
		    </div>	
                    <div id="stock_item_div" class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<label for="stock_item">Select Related Inventory Item</label>
                        <select name="stock_item" id="stock_item" class="form-control" style="width: 100%;">
                            <option value="">Select</option>
			    <?php foreach($stockPros as $cats){
                                $pros = $cats['pros'];
                                ?>
                            <optgroup label="<?php echo $cats['name'];?>">
                                <?php foreach($pros as $pro){?>
                                <option <?php echo set_select('stock_item', $pro['id']); ?> value="<?php echo $pro['id'];?>"><?php echo $pro['name'];?></option>
                                <?php } ?>
                            </optgroup>
			    
			    <?php } ?>
			</select>
		    </div>
                     <div id="is_combo_div" class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<label for="is_combo">Is this a combo product?</label>
                        <div class="checkbox3 checkbox-round">
                            <input name="is_combo" type="checkbox" class="pull-right" id="is_combo" value="1" <?php echo set_checkbox('is_combo', '1'); ?>>
                            <label for="is_combo" style="font-weight: 700;">
                                
                            </label>
                          </div>
		    </div>
                    <div id="combo_item_div" class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<label for="combo_item">Select Second Stock Item</label>
                        <select name="combo_item" id=combo_item" class="form-control" style="width: 100%;">
                            <option value="">Select</option>
			    <?php foreach($stockPros as $cats){
                                $pros = $cats['pros'];
                                ?>
                            <optgroup label="<?php echo $cats['name'];?>">
                                <?php foreach($pros as $pro){?>
                                <option <?php echo set_select('stock_item', $pro['id']); ?> value="<?php echo $pro['id'];?>"><?php echo $pro['name'];?></option>
                                <?php } ?>
                            </optgroup>
			    
			    <?php } ?>
			</select>
		    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 15px;">
                        <h4 style="font-size: 1.4em;text-decoration: none;"><div class="col-lg-2 col-md-3 col-sm-3 col-xs-7 no-padding">Any additional free products</div> &nbsp; 
                            <div class="checkbox3 checkbox-round col-lg-3 col-md-4 col-sm-4 col-xs-3">
                                <input type="checkbox" class="pull-right" id="attach_free" name="free_product" value="1" <?php echo set_checkbox('free_product', '1'); ?>>
                                <label for="attach_free" style="font-weight: 700;">
                                </label>
                            </div>
                        </h4>
                            <hr style="margin: 10px 0px;">
                    </div>
                    <div id="attach_free_div">
                        <?php for($i=0;$i<4;$i++){?>
                            <div class="col-xs-12">
                                <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                   <label for="free_pro_id">Select free product</label>
                                   <select name="free_pro[<?php echo $i;?>]" id="free_pro_id" class="form-control" style="width: 100%;">
                                       <option value="">Select</option>
                                       <?php foreach($allPros as $cats){
                                           $pros = $cats['pros'];
                                           ?>
                                       <optgroup label="<?php echo $cats['name'];?>">
                                           <?php foreach($pros as $pro){?>
                                           <option <?php echo set_select("free_pro[$i]", $pro['id']); ?> value="<?php echo $pro['id'];?>"><?php echo $pro['name'];?></option>
                                           <?php } ?>
                                       </optgroup>

                                       <?php } ?>
                                   </select>
                               </div>
                               <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                   <label for="free_quantity">Free Quantity</label>
                                   <input type="text" placeholder="" name="free_quantity[<?php echo $i;?>]" id="free_quantitys" value="<?php echo set_value("free_quantity[$i]",'1');?>" class="form-control">
                               </div>
                            </div>
                        <?php } ?>
                    </div>
                    
		    	    
		    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="">
		    <button class="btn btn-success pull-right" type="submit">Add</button>
		    </div>
		</form>
	    </div>
	</div>

</div>

<script type="text/javascript">
    $(function(){
        
        function checkStock()
        {
            if($('#is_stock').prop('checked'))
            {
                $('#stock_item_div').hide();
                $('#is_combo_div').hide();
                $('#combo_item_div').hide();
            }
            else
            {
                $('#stock_item_div').show();
                $('#is_combo_div').show();
                
                if($('#is_combo').prop('checked'))
                $('#combo_item_div').show();
            }
        }
        
        function checkFree()
        {
            if($('#attach_free').prop('checked'))
            {
                $('#attach_free_div').show();
            }
            else
            {
                $('#attach_free_div').hide();
            }
        }
        
        function checkCombo()
        {
            if($('#is_combo').prop('checked'))
            {
                $('#combo_item_div').show();
            }
            else
            {
                $('#combo_item_div').hide();
            }
        }
        
        $("#is_stock").change(function() {
            //checkStock();
        });
        
        $("#attach_free").change(function() {
            checkFree();
        });
        
        $("#is_combo").change(function() {
            checkCombo();
        });
        
        //checkStock();
        checkFree();
        checkCombo();
    });
</script>
               
<?php $this->load->view('template/footer');?>
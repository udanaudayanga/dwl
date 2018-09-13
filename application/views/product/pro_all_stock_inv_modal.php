<div class="modal fade modal-info"  id="all_loc_<?php echo $pro->id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Product Inventory All Locations</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="">
                        <label class="col-sm-4 col-xs-6">MAIN STOCK </label> <?php echo empty($pro->stock)?0:$pro->stock;?>
                    </div>
                    
                    <?php foreach($pro->all_stock as $stock){?>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="">
                        <label class="col-sm-4 col-xs-6"><?php echo $stock->name;?> </label> <?php echo $stock->quantity;?>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
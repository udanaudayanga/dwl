<p style="font-size: 20px;font-weight: bold;text-align: left;">Redeemed from above table:</p>
<?php if(!empty($pros)){?>
    <?php foreach($pros as $key=>$val){?>
        <div class="col-lg-4 col-sm-6 col-xs-12" style="padding-left: 0px;margin-bottom: 15px;">
            <div class="input-group">
                <span class="input-group-addon" ><?php echo $val['name'].":";?></span>
                <?php if($val['measure_in'] == 'Days'){?>
                    <?php $wks = array(0,7,14,21,28);?>
                    <select id="pprd_<?php echo $key;?>" style="width: 100%;" class="form-control not_select2" >
                        <?php foreach($wks as $w){?>
                            <?php if($w <= $val['qty']){?>
                                <option <?php if($val['qty'] == $w){?>selected="selected"<?php } ?> value="<?php echo $w;?>"><?php echo $w;?></option>
                            <?php } ?>
                        <?php } ?>
                        
                    </select>
                <?php }else{?>
                <input type="text" class="form-control" style="text-align: center;" id="pprd_<?php echo $key;?>" value="<?php echo $val['qty'];?>"  >
                <?php } ?>
                <span class="input-group-btn">
                    <a  class="btn btn-default update_ppr" data-ori="<?php echo $val['qty'];?>" data-id="<?php echo $key;?>"  style="margin: 0px;" href=""><span style="color: #4cae4c;" class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>
                </span>
            </div>
        </div>

    <?php } ?>
<?php }else{?> 
    <p style="text-align: center;">No Redeems</p>
<?php } ?>
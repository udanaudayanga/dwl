<h3>Existing Order Items</h3>
<table class="table table-bordered">
    <thead>

        <tr>
            <th style="text-align: center;">Name</th>   
            <th style="text-align: center;">Qty</th>
            <th style="text-align: center;">Type</th>
            <th style="text-align: center;">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php 

        foreach($loi as $oi){?>
            <tr>
                <td><?php echo $oi->name;?></td>
                <td style="text-align: center;"><?php echo $oi->quantity;?></td>
                <td style="text-align: center;"><?php echo $oi->prepaid==1?"PP":"";?></td>                
                <td style="text-align: center;">
                    <?php if($oi->prepaid ==1){?>
                    <a class="hover remove_pp_btn" data-proid="<?php echo $oi->product_id;?>" style="font-weight: 500;color: #FF4500;" href=""><span aria-hidden="true" class="glyphicon glyphicon-trash" style="font-size: 1em;"></a>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
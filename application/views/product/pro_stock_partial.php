<table class="datatable table table-striped" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Location</th>
            <th>Batch #</th>
            <th>Batch Date</th>
            <th>Quantity</th>
            <th>Lot #</th>
            <th>Exp. Date</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach($stocks as $stk){?>
                <tr>
                    <td><?php echo $stk->name;?></td>
                    <td><?php echo str_pad($stk->id, 5, '0', STR_PAD_LEFT);?></td>
                    <td><?php echo date('m/Y',  strtotime($stk->date)) ;?></td>
                    <td><?php echo $stk->quantity;?></td>
                    <td><?php echo $stk->lot_no;?></td>
                    <td><?php echo date('m/Y',  strtotime($stk->exp_date));?></td>
                    <td style="text-align: center;">
                        <?php if($stk->created == date('Y-m-d')){?>
                        <a class="rem_stock" title="Remove Stock" href="" data-id="<?php echo $stk->id;?>" style="color: #31b0d5;"  ><span aria-hidden="true" class="glyphicon glyphicon-remove"></span></a>
                        <?php } ?>
                    </td>
                </tr>

        <?php } ?>

    </tbody>
</table>
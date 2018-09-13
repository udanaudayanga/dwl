<table class="datatable table table-striped" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Date</th>
            <th>Order#</th>
            <th>Loc</th>
            <th>Type</th>
            <th>Mode</th>
            <th>Qty</th>            
        </tr>
    </thead>

    <tbody>
        <?php foreach($pph as $h){?>
                <tr>
                    <td><?php echo date('m/d/Y',  strtotime($h->created)) ;?></td>
                    <td>
                        <?php if(!empty($h->order_id)){?> <a target="_blank" href="<?php echo site_url("order/view/$h->order_id");?>" style="color: #5cb85c;"> #<?php echo str_pad($h->order_id, 5, '0', STR_PAD_LEFT);?></a>
                        <?php } ?>
                    </td>
                    <td><?php echo $h->abbr;?></td>
                    <td><?php echo $h->type=='add'?"BOUGHT":"REDEEM";?></td>
                    <td style="text-transform: uppercase;"><?php echo $h->add_type;?></td>    
                    <td><?php echo $h->quantity;?></td>                    
                </tr>

        <?php } ?>

    </tbody>
</table>
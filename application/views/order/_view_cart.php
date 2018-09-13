<table class="table table-bordered">
    <thead>
        <tr class="active">
            <th>Category</th>
            <th>Name</th>
            <th>Qty</th>
            <th>Days</th>
            <th>Item Price</th>
            <th>Sub Total</th>
            <th style="width: 5%;"></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $grosstotal = 0;
        foreach ($order_items as $key=>$item){ 
            $product = $item['product'];
            ?>
        <tr>
            <th><?php echo $product->cat_name;?></th>
            <td><?php echo $item['name'];?></td>
            
            <?php 
            $days = '';
            if($product->measure_in == 'Days') $days = $item['qty'] * $product->quantity;
            ?>
            <td><?php echo $item['qty']; ?></td>
            <td><?php echo $days; ?></td>
            <td>&#36;<?php echo $this->cart->format_number($item['price']); ?></td>
            <td>&#36;<?php echo $this->cart->format_number($item['subtotal']); ?></td>
            <td></td>
        </tr>
        <?php
        $grosstotal += $this->cart->format_number($item['subtotal']);
        
        } ?> 
        <?php if(count($order_items)==0){?>
            <tr>
                <td colspan="8" style="text-align: center;">CART EMPTY</td>
            </tr>
        <?php } ?>
        <?php if(count($order_items)>0){?>
            <?php
	    $discount = 0;
            if($patient_category == 'staff')
            {
                $discount = $grosstotal * 0.2;
            }
            elseif($patient_category == 'family')
            {
                $discount = $grosstotal;
            }
	    $netTotal = $grosstotal - $discount;
            
            ?>
        <tr>
            <td colspan="4" rowspan="3"></td>
            <td class="active">Gross Total</td>
            <td colspan="2" style="font-weight: bold;">&#36;<?php echo $this->cart->format_number($grosstotal); ?></td>
        </tr>
        <tr>
            <td class="active">Discount</td>
            <td colspan="2" style="font-weight: bold;">&#36;<?php echo $this->cart->format_number($discount); ?></td>
        </tr>
        <tr>
            <td class="active">Net Total</td>
            <td colspan="2" style="font-weight: bold;" >&#36;<?php echo $this->cart->format_number($netTotal); ?></td>            
        </tr>
        <input type="hidden" id="cart_total" value="<?php echo $this->cart->format_number($netTotal); ?>"/>
        <?php } ?>
    </tbody>
</table>

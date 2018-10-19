<table class="table table-bordered" style="margin-bottom: 0px;">
    <thead>
        <tr class="active">
            <th>Category</th>
            <th>Name</th>
            <th>Qty</th>
            <th>Inc. Days/Qty</th>
            <th>Item Price</th>
            <th>Sub Total</th>
            <th style="width: 5%;"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->cart->contents() as $item){ 
            $product = $item['product'];
            ?>
        <tr>
            <th><?php echo $item['product']->cat_name;?></th>
            <td><?php echo $item['name'];?></td>
            
            <?php 
            $days = '';
            if($product->quantity > 1) $days = $item['qty'] * $product->quantity;
            ?>
            <td><?php echo $item['qty']; ?></td>
            <td><?php echo $days; ?></td>
            <td>&#36;<?php echo $this->cart->format_number($item['price']); ?></td>
            <td>&#36;<?php echo $this->cart->format_number($item['subtotal']); ?></td>
            <td><a href="" data-rowid="<?php echo $item['rowid'];?>" data-combineid="<?php echo $item['combined_id'];?>" class="del_cart_pro"  style="color: red;" title="Remove"><span aria-hidden="true" class="glyphicon glyphicon-trash"></span></a></td>
        </tr>
        <?php } ?> 
        <?php if(!$this->cart->contents()){?>
            <tr>
                <td colspan="8" style="text-align: center;">CART EMPTY</td>
            </tr>
        <?php } ?>
        <?php if($this->cart->contents()){?>
            <?php
	    $discount = getCartDicount();
	    $cartTotal = $this->cart->total();?>
        <tr>
            <td colspan="4" rowspan="3"></td>
            <td class="active">Gross Total</td>
            <td colspan="2" style="font-weight: bold;">&#36;<?php echo $this->cart->format_number($cartTotal); ?></td>
        </tr>
        <tr>
            <td class="active">Discount</td>
            <td colspan="2" style="font-weight: bold;">&#36;<?php echo $this->cart->format_number($discount); ?></td>
        </tr>
        <tr>
            <td class="active">Net Total</td>
            <td colspan="2" style="font-weight: bold;" >&#36;<?php echo $this->cart->format_number($cartTotal - $discount); ?></td>            
        </tr>
        <input type="hidden" id="cart_total" value="<?php echo ($cartTotal - $discount); ?>"/>
        <?php } ?>
    </tbody>
</table>

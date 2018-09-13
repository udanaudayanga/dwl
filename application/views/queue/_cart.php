<table class="table table-bordered" style="margin-bottom: 0px;">
    <thead>
        <tr class="active">
            <th>Category</th>
            <th>Name</th>
            <th>Qty</th>
            <th style="width: 5%;"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($ois as $key=>$item){ 
            $product = $item['pro'];
            $qty = $item['qty'];
        ?>
        <tr>
            <td><?php echo $product->cat_name;?></td>
            <td><?php echo $product->name;?></td>
            <td><?php echo $qty;?></td>
            <td>
                <!--<a title="Remove"   href="" style="color: red;" class="id" onclick="return confirm('Are you sure?');"><span aria-hidden="true" class="glyphicon glyphicon-trash"></span></a>-->
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

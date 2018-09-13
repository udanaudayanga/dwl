<?php $this->load->view('template/header');?>
<div class="row">

	<div class="card">
	    <div class="card-header">
		<div class="card-title">
		    <div class="title">Edit product</div>
		</div>
	    </div>
	    <div class="card-body">
		<div class="col-xs-12">
	<?php echo $errors; ?>
    </div>
		<form  method="POST" enctype="multipart/form-data">
		    <?php if($product->photo){?>
		    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12" style="">
			<img src="/phpThumb/phpThumb.php?src=/assets/upload/products/<?php echo $product->photo;?>&amp;w=300&amp;f=png" />
		    </div>
		    <?php } ?>
		    <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
			<label for="cat">Category</label>
			<select name="cat_id" id="cat" class="form-control" style="width: 100%;">
			    <?php foreach($categories as $cat){?>
			    <option <?php echo set_select('cat_id', $cat->id,$cat->id == $product->cat_id); ?> value="<?php echo $cat->id;?>"><?php echo $cat->name;?></option>
			    <?php } ?>
			</select>
		    </div>
		    <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
			<label for="name">Name</label>
			<input type="text" placeholder="Name" name="name" value="<?php echo set_value('name',$product->name);?>" id="name" class="form-control">
		    </div>
		   
<!--					<div class="form-group col-xs-4">
			<label for="ln">Lot No</label>
			<input type="text" placeholder="Lot No" id="ln" class="form-control">
		    </div>
		    <div class="form-group col-xs-4">
			<label for="ed">Expire Date</label>
			<input type="text" placeholder="Expire Date" id="ed" class="form-control">
		    </div>-->
		    <div class="form-group  col-lg-4 col-md-6 col-sm-6 col-xs-12">
			<label for="price">Price</label>
			<input type="text" placeholder="Price" name="price" id="price" value="<?php echo set_value('price',$product->price);?>" class="form-control">
		    </div>
		    <div class="form-group  col-lg-4 col-md-6 col-sm-6 col-xs-12" style="">
			<label for="photo">Photo</label>
			<input name="photo" type="file" id="photo">
		    </div>
		    <div class="form-group  col-lg-4 col-md-6 col-sm-6 col-xs-12">
			<label for="low_stock">Low Stock Alert</label>
			<input type="number" placeholder="" id="low_stock" name="lsa" value="<?php echo set_value('lsa',$product->lsa);?>" class="form-control">
		    </div>

		    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12" style="">
		    <button class="btn btn-success pull-right" type="submit">Update</button>
		    </div>
		</form>
	    </div>
	</div>

</div>
<?php $this->load->view('template/footer');?>
<?php $this->load->view('template/header');?>
<div class="row">
    <div class="col-xs-12">
	<div class="card">
	    <div class="card-header">
		<div class="card-title">
		    <div class="title">Add New Location</div>
		</div>
	    </div>
            <div class="card-body" style="padding: 15px;">
		<div class="col-sm-1"></div>
		<div class="col-sm-11">
		    <?php echo validation_errors('<div role="alert" class="alert fresh-color alert-danger">', '</div>');?>
		</div>
		
		<form method="POST" class="form-horizontal">
		    <div class="form-group">
			<label class="col-sm-2 control-label" for="name">Name</label>
			<div class="col-sm-10">
			    <input type="text" placeholder="Location Name" id="name" name="name" class="form-control">
			</div>
		    </div>
		    <div class="form-group">
			<label class="col-sm-2 control-label" for="ip">IP</label>
			<div class="col-sm-10">
			    <input type="text" placeholder="0.0.0.0" id="ip" name="ip" class="form-control">
			</div>
		    </div>	
                    <div class="form-group">
			<label class="col-sm-2 control-label" for="abbr">ABBR</label>
			<div class="col-sm-10">
			    <input type="text" placeholder="Abbriviation" id="abbr" name="abbr" class="form-control">
			</div>
		    </div>	
                    <div class="form-group">
			<label class="col-sm-2 control-label" for="dea">DEA#</label>
			<div class="col-sm-10">
			    <input type="text" placeholder="DEA Number" id="dea" name="dea" class="form-control">
			</div>
		    </div>	
                    <div class="form-group">
			<label class="col-sm-2 control-label" for="address">Address</label>
			<div class="col-sm-10">
			    <input type="text" placeholder="Address" id="address" name="address" class="form-control">
			</div>
		    </div>	
		    <div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
			    <button class="btn btn-default" type="submit">Add</button>
			</div>
		    </div>
		</form>
	    </div>
	</div>
    </div>
</div>
<?php $this->load->view('template/footer');?>
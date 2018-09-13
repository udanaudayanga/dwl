<?php $this->load->view('template/header');?>
<div class="row">
    <div class="col-xs-12">
	<div class="card">
	    <div class="card-header">
		<div class="card-title">
		    <div class="title">Add New User</div>
		</div>
	    </div>
	    <div class="card-body" style='padding: 15px;'>
		<div class="col-sm-12">
		    <?php echo validation_errors('<div role="alert" class="alert fresh-color alert-danger">', '</div>');?>
		</div>
		
		<form method="POST" class="form-horizontal">
		    <div class="form-group">
			<label class="col-sm-2 control-label" for="name">Username <en>*</en></label>
			<div class="col-sm-10">
			    <input value="<?php echo set_value('username');?>" type="text" placeholder="Username" id="fname" name="username" class="form-control">
			</div>
		    </div>
		    <div class="form-group">
			<label class="col-sm-2 control-label" for="name">First Name <en>*</en></label>
			<div class="col-sm-10">
			    <input value="<?php echo set_value('fname');?>" type="text" placeholder="First Name" id="fname" name="fname" class="form-control">
			</div>
		    </div>
		    <div class="form-group">
			<label class="col-sm-2 control-label" for="name">Last Name <en>*</en></label>
			<div class="col-sm-10">
			    <input value="<?php echo set_value('lname');?>" type="text" placeholder="Last Name" id="lname" name="lname" class="form-control">
			</div>
		    </div>
		    <div class="form-group">
			<label class="col-sm-2 control-label" for="name">Phone <en>*</en></label>
			<div class="col-sm-10">
			    <input value="<?php echo set_value('phone');?>" type="text" placeholder="Phone" id="phone" name="phone" class="form-control">
			</div>
		    </div>
		    <div class="form-group">
			<label class="col-sm-2 control-label" for="name">Email <en>&nbsp;</en></label>
			<div class="col-sm-10">
			    <input value="<?php echo set_value('email');?>" type="email" placeholder="Email" id="email" name="email" class="form-control">
			</div>
		    </div>
		    <div class="form-group">
			<label class="col-sm-2 control-label" for="type">Type <en>&nbsp;</en></label>
			<div class="col-sm-10">
			    <select class="form-control" name="type" style='max-width: 100%;'>
				<option value="2" <?php echo  set_select('type', 2); ?>>Staff</option>
                                <option value="3" <?php echo  set_select('type', 3); ?>>Doctor</option>
                                <option value="4" <?php echo  set_select('type', 4); ?>>Supervisor</option>
				<option value="1" <?php echo  set_select('type', 1); ?>>Admin</option>
			    </select>
			</div>
		    </div>
		    <div class="form-group">
			<label class="col-sm-2 control-label" for="ip">Password <en>*</en></label>
			<div class="col-sm-10">
			    <input type="password" placeholder="Password" id="password" name="password" class="form-control">
                            <p style="color:#666;font-size: 13px;">Min 8 chars. At least one uppercase,lowercase,number and special character.</p>
			</div>
		    </div>
		    <div class="form-group">
			<label class="col-sm-2 control-label" for="ip">Re Password <en>*</en></label>
			<div class="col-sm-10">
			    <input type="password" placeholder="Re Password" id="repassword" name="repassword" class="form-control">
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
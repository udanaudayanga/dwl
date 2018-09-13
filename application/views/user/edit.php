<?php $this->load->view('template/header');?>

<div class="row">
    <div class="col-xs-12">
	<div class="card">
	    <div class="card-header">
		<div class="card-title">
		    <div class="title">Edit User</div>
		</div>
	    </div>
            <div class="card-body" style="padding: 15px;">
		<div class="col-sm-1"></div>
		<div class="col-sm-11">
		    <?php echo validation_errors('<div role="alert" class="alert fresh-color alert-danger">', '</div>');?>
		</div>
		
		<form method="POST" class="form-horizontal">
		    <div class="form-group">
			<label class="col-sm-2 control-label" for="name">Username <en>*</en></label>
			<div class="col-sm-10">
			    <input readonly="readonly" value="<?php echo set_value('username',$usere->username);?>" type="text" placeholder="Username" id="fname" name="username" class="form-control">
			</div>
		    </div>
		    <div class="form-group">
			<label class="col-sm-2 control-label" for="name">First Name <en>*</en></label>
			<div class="col-sm-10">
			    <input value="<?php echo set_value('fname',$usere->fname);?>" type="text" placeholder="First Name" id="fname" name="fname" class="form-control">
			</div>
		    </div>
		    <div class="form-group">
			<label class="col-sm-2 control-label" for="name">Last Name <en>*</en></label>
			<div class="col-sm-10">
			    <input value="<?php echo set_value('lname',$usere->lname);?>" type="text" placeholder="Last Name" id="lname" name="lname" class="form-control">
			</div>
		    </div>
		    <div class="form-group">
			<label class="col-sm-2 control-label" for="name">Phone <en>*</en></label>
			<div class="col-sm-10">
			    <input value="<?php echo set_value('phone',$usere->phone);?>" type="text" placeholder="Phone" id="phone" name="phone" class="form-control">
			</div>
		    </div>
		    <div class="form-group">
			<label class="col-sm-2 control-label" for="name">Email <en>&nbsp;</en></label>
			<div class="col-sm-10">
			    <input value="<?php echo set_value('email',$usere->email);?>" type="email" placeholder="Email" id="email" name="email" class="form-control">
			</div>
		    </div>
		    <div class="form-group">
			<label class="col-sm-2 control-label" for="type">Type <en>&nbsp;</en></label>
			<div class="col-sm-10">
                            <select class="form-control" name="type" style="width: 100%;">
				<option value="2" <?php echo  set_select('type', 2,$usere->type == 2); ?>>Staff</option>
                                <option value="3" <?php echo  set_select('type', 3,$usere->type == 3); ?>>Doctor</option>
                                <option value="4" <?php echo  set_select('type', 4,$usere->type == 4); ?>>Supervisor</option>
				<option value="1" <?php echo  set_select('type', 1,$usere->type == 1); ?>>Admin</option>
			    </select>
			</div>
		    </div>
		    
		    <div class="col-sm-2">&nbsp</div>
		    <div class="checkbox3 checkbox-round col-sm-10">
			<input value="1" type="checkbox" <?php echo set_checkbox('chng_psw_chkbox', '1'); ?> id="chng_psw_chkbox" name="chng_psw_chkbox">
			<label for="chng_psw_chkbox">
			  Change password
			</label>
		    </div>
			    
			
		    <div id="chng_psw" style="display: none;">
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
		    </div>  
		    <div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
			    <button class="btn btn-default" type="submit">Update</button>
			</div>
		    </div>
		</form>
	    </div>
	</div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
	if($("#chng_psw_chkbox").prop('checked') == true){
	    $('#chng_psw').show();
	}
	
	$("#chng_psw_chkbox").change(function() {
	    if(this.checked) {
		$('#chng_psw').show();
	    }else{
		$('#chng_psw').hide();
	    }
	});
    });
</script>
<?php $this->load->view('template/footer');?>
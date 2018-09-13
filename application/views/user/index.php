<?php $this->load->view('template/header'); ?>
<div class="row">
    <div class="col-xs-12">
	<div class="card">
	    <div class="card-header">

		<div class="card-title">
		    <div class="title">All Users</div>
		</div>
	    </div>
	    <div class="card-body" style='padding: 15px;'>
		<?php if($this->session->flashdata()){?>
		<div role="alert" class="alert fresh-color alert-success">
                      <strong><?php echo $this->session->flashdata('message');?></strong>
		</div>
		<?php } ?>
		<table class="datatable table table-striped" cellspacing="0" width="100%">
		    <thead>
			<tr>
			    <th>Username</th>
			    <th>Full Name</th>
			    <th>Phone</th>
			    <th>Email</th>
			    <th>Type</th>
			    <th>Actions</th>
			</tr>
		    </thead>
<!--		    <tfoot>
			<tr>
			    <th>Name</th>
			    <th>Position</th>
			    <th>Office</th>
			    <th>Age</th>
			    <th>Start date</th>
			    <th>Salary</th>
			</tr>
		    </tfoot>-->
		    <tbody>
			<?php foreach($users as $user){?>
			<tr>
			    <td><?php echo $user->username;?></td>
			    <td><?php echo $user->fname." ".$user->lname;?></td>
			    <td><?php echo $user->phone;?></td>
			    <td><?php echo $user->email;?></td>
			    <td><?php echo $user->type==1 ? "ADMIN":($user->type==2 ? "STAFF":($user->type==3 ? "DOCTOR":"Supervisor"));?></td>		    
			    <td>
				<a title="edit" href="<?php echo site_url("user/edit/$user->id");?>" style="color: #31b0d5;"><span aria-hidden="true" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;
				<a onclick="return confirm('Are you sure?');" title="Remove" href="<?php echo site_url("user/remove/$user->id");?>" style="color: #31b0d5;"><span aria-hidden="true" class="glyphicon glyphicon-remove"></span></a>
			    </td>
			</tr>
			<?php } ?>
		    </tbody>
		</table>
	    </div>
	</div>
    </div>
</div>
<?php $this->load->view('template/footer'); ?>
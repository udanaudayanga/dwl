<?php $this->load->view('template/header'); ?>
<div class="row">
    <div class="col-xs-12">
	<div class="card">
	    <div class="card-header">

		<div class="card-title">
		    <div class="title">Active Patients with at least 12 weeks</div>
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
			    
			    <th>Patient Name</th>
			    <th>Status</th>
			    <th>Last visit</th>
			    <th>Weeks</th>
                <th>Actions</th>
			</tr>
		    </thead>

		    <tbody>
			<?php foreach($patients as $id => $patient){?>
			<tr>
			    
			    <td><?php echo $patient['name'];?></td>
			    <td><?php echo $patient['status']?></td>
			    <td><?php echo $patient['last']?></td>
                <td><?php echo $patient['turns']?></td>
                <td>
				<a style="color: #31b0d5;" target="_blank" href="<?php echo site_url('patient/view/'.$id);?>"><span aria-hidden="true" style="font-size: 1.3em;" class="glyphicon glyphicon-new-window"></span></a>
				<a style="color: purple;" target="_blank" href="<?php echo site_url('evaluate/view/'.$id);?>"><span aria-hidden="true" style="font-size: 1.3em;margin-left:20px;" class="glyphicon glyphicon-calendar"></span></a>
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
<?php $this->load->view('template/header'); ?>
<div class="row">
    <div class="col-xs-12">
	<div class="card">
	    <div class="card-header">

		<div class="card-title">
		    <div class="title">User Activities</div>
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
			    <th>Name</th>
			    <th>Activity</th>
			    <th>Remote IP</th>			    
			    <th>Date/Time</th>
			</tr>
		    </thead>

		    <tbody>
			<?php foreach($activities as $act){?>
			<tr>
			    <td><?php echo $act->fname." ".$act->lname;?></td>
			    <td><?php echo $act->event;?></td>
			    <td><?php echo $act->remote_ip;?></td>
			    <td><?php echo  date("Y-m-d H:i:s", strtotime('+3 hours' , strtotime($act->created)));?></td>
			</tr>
			<?php } ?>
		    </tbody>
		</table>
	    </div>
	</div>
    </div>
</div>
<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/header'); ?>
<div class="row">
    <div class="col-xs-12">
	<div class="card">
	    <div class="card-header">

		<div class="card-title">
		    <div class="title">Removed Orders</div>
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
			    <th>Order ID</th>
			    <th>Patient Name</th>
			    <th>Location</th>
			    <th>Total</th>
			    <th>Created</th>
			</tr>
		    </thead>

		    <tbody>
			<?php foreach($orders as $order){?>
			<tr>
			    <td><?php echo str_pad($order->id, 5, '0', STR_PAD_LEFT);?></td>
			    <td><?php echo $order->fname." ".$order->lname;?></td>
			    <td><?php echo $order->loc_name;?></td>
			    <td><?php echo $order->net_total;?></td>
			    <td><?php echo $order->created;?></td>
			</tr>
			<?php } ?>
		    </tbody>
		</table>
	    </div>
	</div>
    </div>
</div>
<?php $this->load->view('template/footer'); ?>
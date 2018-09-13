<?php $this->load->view('template/header'); ?>
<div class="page-title text-right">
    <a href="<?php echo site_url('locations/add');?>" class="btn btn-success">Add Location</a>
</div>
<div class="row">
    <div class="col-xs-12">
	<div class="card">
	    <div class="card-header">

		<div class="card-title">
		    <div class="title">All Locations</div>
		</div>
	    </div>
            <div class="card-body" style="padding: 15px;">
                <?php if($this->session->flashdata('message')){?>
                <div role="alert" class="alert fresh-color alert-success">
                      <strong><?php echo $this->session->flashdata('message');?></strong>
                </div>
                <?php } ?>
		<table class="datatable table table-striped" cellspacing="0" width="100%">
		    <thead>
			<tr>
			    <th>Name</th>
			    <th>IP</th>
                            <th>DEA</th>
                            <th>ABBR</th>
                            <th>Address</th>
                            <th>Status</th>
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
			<?php foreach($locations as $location){?>
			<tr>
			    <td><?php echo $location->name;?></td>
			    <td><?php echo $location->ip;?></td>			    
			   <td><?php echo $location->dea;?></td>
                           <td><?php echo $location->abbr;?></td>
                           <td><?php echo $location->address;?></td>
                           <td><?php echo $location->status ==1 ? "ACTIVE":"CLOSED";?></td>
                            <td><a title="Edit" href="<?php echo site_url("locations/edit/$location->id");?>" style="color: #31b0d5;"><span aria-hidden="true" class="glyphicon glyphicon-edit"></span></a> &nbsp;&nbsp;
                                <a title="Remove"   href="<?php echo site_url("locations/delete/$location->id");?>" style="color: red;" class="" onclick="return confirm('Are you sure? This will make all location references invalid');"><span aria-hidden="true" class="glyphicon glyphicon-trash"></span></a>
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
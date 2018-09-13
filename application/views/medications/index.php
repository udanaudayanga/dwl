<?php $this->load->view('template/header');?>
<style>
	div.dataTables_wrapper div.dataTables_filter input{width: 400px;}
    .dataTables_length select{font-size: 16px;}
</style>
<div class="page-title text-right">
    <a href="<?php echo site_url('medications/add');?>" class="btn btn-success">Add Current Medication</a>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">

                <div class="card-title">
                <div class="title">Current medications</div>
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
                            <th>Id</th>
                            <th>Med</th>
                            <th>Ailment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($medications as $med){?>
                        <tr class="black">
                            <td><?php echo $med->id;?></td>
                            <td><?php echo $med->med;?></td>
                            <td><?php echo $med->ailment;?></td>
                            <td><a title="Edit" href="<?php echo site_url("medications/edit/$med->id");?>" style="color: #31b0d5;"><span aria-hidden="true" class="glyphicon glyphicon-edit"></span></a>
                                <a title="Remove"   href="<?php echo site_url("medications/delete/$med->id");?>" style="color: red;margin-left: 20px;" class="" onclick="return confirm('Are you sure?');"><span aria-hidden="true" class="glyphicon glyphicon-trash"></span></a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('template/footer');?>
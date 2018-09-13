<?php $this->load->view('template/header');?>
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-timepicker.min.css">
<script type="text/javascript" src="/assets/js/bootstrap-timepicker.min.js"></script>
<div class="page-title text-right">
    <a href="<?php echo site_url('promos/add');?>" class="btn btn-success">Add Promotion</a>
</div>
<style>
    #add_patient_photo .modal-dialog{width:40%;}
    .patients_list div.dataTables_wrapper div.dataTables_filter input{width: 400px;}
    @media screen and (max-width: 768px) {	
	#add_patient_photo .modal-dialog{width:80%;}
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">

                <div class="card-title">
                    <div class="title">All Promotions <span style="font-size: 20px;color: gray;margin-left: 80px;">Create a time based promotion and give it a time</span></div>
                </div>
            </div>
            <div class="card-body patients_list" style="padding: 15px;">
                <?php if($this->session->flashdata('message')){?>
                <div role="alert" class="alert fresh-color alert-success">
                      <strong><?php echo $this->session->flashdata('message');?></strong>
                </div>
                <?php } ?>
                <?php if($this->session->flashdata('error')){?>
                <div role="alert" class="alert fresh-color alert-danger">
                      <strong><?php echo $this->session->flashdata('error');?></strong>
                </div>
                <?php } ?>
                <table class="datatable table table-striped" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($promos as $promo){?>
                        <tr class="black">
                            <td><?php echo $promo->name;?></td>
                            <td><?php echo date("m/d/Y",strtotime($promo->start));?></td>
                            <td><?php echo date("m/d/Y",strtotime($promo->end));?></td>
                            <td><?php echo date('m/d/Y',strtotime($promo->created));?></td>
                            <td>
                                <!--<a title="Manage List" href="<?php echo site_url("marketing/manageCL/$promo->id");?>"><span style="color:#31b0d5;font-size: 17px;" aria-hidden="true" class="glyphicon glyphicon-list"></span></a>-->
                                <a onclick="return confirm('Are you sure?');" title="Remove Promotion" href="<?php echo site_url("promos/remove/$promo->id");?>"><span style="color:red;font-size: 17px;margin-left: 20px;" aria-hidden="true" class="glyphicon glyphicon-trash"></span></a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(function(){
       
    });
</script>
<?php $this->load->view('template/footer');?>

<?php $this->load->view('template/header');?>
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-timepicker.min.css">
<script type="text/javascript" src="/assets/js/bootstrap-timepicker.min.js"></script>

<style>
    #add_patient_photo .modal-dialog{width:40%;}
    .patients_list div.dataTables_wrapper div.dataTables_filter input{width: 400px;}
    tr.claimed td{background-color: #88F159;}
    @media screen and (max-width: 768px) {	
	#add_patient_photo .modal-dialog{width:80%;}
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">

                <div class="card-title">
                    <div class="title">Promo Win List </div>
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
                            <th>Id</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($patients as $p){?>
                        <tr class="black <?php if($p->claimed == 1){?>claimed<?php } ?>">
                            <td><?php echo $p->pid;?></td>
                            <td><?php echo $p->fname;?></td>
                            <td><?php echo $p->lname;?></td>
                            <td><?php echo $p->email;?></td>
                            <td><?php echo  date("m/d/Y",strtotime($p->created));?></td>
                            <td><?php if($p->claimed == 0){?><a style="color:red;font-weight: bold;" href="<?php echo site_url("promos/claimed/$p->id/$p->promo_id");?>">Claim</a><?php } ?></td>
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

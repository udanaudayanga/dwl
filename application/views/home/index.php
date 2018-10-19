<?php $this->load->view('template/header');?>
<div class="page-title text-right">
    <a href="<?php echo site_url('patient/add');?>" class="btn btn-success">Add Patient</a>
</div>
<style>
    #add_patient_photo .modal-dialog{width:40%;}
    @media screen and (max-width: 768px) {	
	#add_patient_photo .modal-dialog{width:80%;}
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">

                <div class="card-title">
                <div class="title">Patients</div>
                </div>
            </div>
            <div class="card-body" style="padding: 15px;">
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
                            <th>Telephone</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($patients as $patient){?>
                        <tr class="black">
                            <td><?php echo $patient->lname.' '.$patient->fname;?></td>
                            <td><?php echo "(".substr($patient->phone, 0, 3).") ".substr($patient->phone, 3, 3)."-".substr($patient->phone,6);?></td>
                            <td><?php echo $patient->email;?></td>
                            <td>Weekly</td>
                            <td><a href="<?php echo site_url("patient/view/$patient->id");?>" style="color: #31b0d5;">View</a> &nbsp;&nbsp;|&nbsp;&nbsp;
                                <a  data-status="weekly" class="add_visit" href="<?php echo site_url("patient/addVisit/$patient->id");?>" style="color: #31b0d5;" >Add Visit</a>
                                &nbsp;&nbsp;|&nbsp;&nbsp;<a href="" data-id="<?php echo $patient->id;?>" data-name="<?php echo $patient->lname.' '.$patient->fname;?>" class="add_photo" style="color: #31b0d5;">Add Photo</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-info" id="add_patient_photo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Add Patient Photo - <span id="add_img_modal_name"></span></h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo site_url('patient/upload_patient_photo');?>"  method="POST" enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" name="pro_id" id="iu_pro_id" />
                    <div class="col-xs-12 no-padding" id="as_errors"></div>
                    <div class="row">
                        <div class="col-md-12 no-padding" style="margin-bottom: 10px;">

                            <div class="col-xs-12">
                              <div class="form-group" style="margin: 0 0 0 0px;">
                                        
                                        <input type="file" name="photo" value="" id="" class="">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <button class="btn btn-success">Add Photo</button>
                            </div>
                        </div>
                      </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        
        $('.add_photo').on('click',function(e){
            e.preventDefault();
            _modal = $('#add_patient_photo');            
            _modal.find('#add_img_modal_name').html($(this).data('name'));
            _modal.find('#iu_pro_id').val($(this).data('id'));
            _modal.modal();
        });
        _modal = $('#add_patient_photo');
    });
</script>
<?php $this->load->view('template/footer');?>

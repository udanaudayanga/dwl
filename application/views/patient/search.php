<?php $this->load->view('template/header');?>
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-timepicker.min.css">
<script type="text/javascript" src="/assets/js/bootstrap-timepicker.min.js"></script>
<div class="page-title text-right">
    <a href="<?php echo site_url('patient/add');?>" class="btn btn-success">Add Patient</a>
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
                <div class="title">Patients Search</div>
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
                <div class="col-lg-4 col-md-5 col-sm-8 col-xs-12 form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="inputEmail3">Patient Name:</label>
                        <div class="col-sm-8">
                            <input type="text" id="search_phase" class="form-control"/>
                        </div>
                    </div>
                </div>
                <button class="btn btn-success" id="search_btn" style="margin: 0px;">Search</button>
                <div class="col-xs-12" id="result_div"></div>
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
<div class="modal fade modal-info" id="add_pp_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Add Existing Patients Pre-paid Remaining - <span id="add_rpp_modal_name"></span></h4>
            </div>
            <div class="modal-body" style="overflow: auto;">
                <div id="as_errors"></div>
                <form id="assign_pp_frm">
                    <input type="hidden" name="patient_id" id="pp_patient_id" />
                    
                        <div class="col-xs-12" style="padding: 0px;">
                            <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <label for="qty">PP Product</label>
                                <select name="pro_id" class="form-control not_select2">
                                    <option value="">Select ...</option>
                                    <?php foreach($pps as $pp){?>                                     
                                    <option value="<?php echo $pp->id;?>"><?php echo $pp->name;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <label for="qty">Remaining</label>
                                <input type="text" class="form-control" name="remaining" id="remaining_inp"/>
                            </div>
                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding-top: 20px;text-align: center;">
                            <a href="" class="btn btn-success" id="assign_pp_btn">Assign</a>
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
<div class="modal fade modal-info" id="add_appoint_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 65%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Add Appointment</h4>
            </div>
            <div class="modal-body" style="overflow: auto;">
                <div class="loader-container text-center color-white">
                    <div><i class="fa fa-spinner fa-pulse fa-4x"></i></div>
                    <div>Loading...</div>
                </div>
                <div id="as_errors"></div>
                <form id="add_appoint_form" >
                    <div class="col-xs-12" id="add_appoint_errors" style=""></div>
                    <div class="col-xs-12" id="next_visit_date" style="font-size: 20px;color: red;"></div>
                    <div class="col-xs-12">
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label style="font-size: 20px;" for="patient_name">Patient</label>
                            <input type="text" class="form-control input-lg" readonly="readonly" value="" id="patient_name" placeholder="Patient Name">
                            <input type="hidden" name="patient_id" id="patient_id"/>
                        </div>
                        
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label style="font-size: 20px;" for="date">Date</label>
                            <input type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY" class="form-control input-lg" name="date" value="" id="date" placeholder="Quantity">
                    
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label style="font-size: 20px;" for="qty">Time</label>
                            <div class="input-group bootstrap-timepicker timepicker">
                                <input readonly="readonly" name="time" id="timepicker1" type="text" class="form-control  input-lg ">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xs-12">
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label style="font-size: 20px;" for="cat">Location</label>
                            <select class="form-control not_select2  input-large" name="location_id" id="location_id" tabindex="-1" aria-hidden="true" style="width: 100%;font-size: 18px;height: 46px;">       

                               <?php foreach($locations as $loc){?>
                                <option  value="<?php echo $loc->id;?>"><?php echo $loc->name;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label style="font-size: 20px;" for="type">Type</label>
                            <select class="form-control not_select2  input-large" name="type" id="type" tabindex="-1" aria-hidden="true" style="width: 100%;font-size: 18px;height: 46px;">    
                                <option  value="1">New</option>
                                <option  value="2">Weekly</option>
                                <option  value="3">Dr.Consult</option>
                                <option  value="4">Shots Only</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12"  >
                            <label style="font-size: 20px;" for="type">Appt made by</label>
                            <select class="form-control not_select2  input-large" name="staff_id" id="staff_id" tabindex="-1" aria-hidden="true" style="width: 100%;font-size: 18px;height: 46px;">    
                                <?php foreach($staff as $s){?>
                                    <option value="<?php echo $s->id;?>"><?php echo $s->lname." ".$s->fname;?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-xs-12">
                        <div class="form-group col-lg-10 col-md-9 col-sm-8 col-xs-12">
                            <label style="font-size: 20px;" for="note">Note</label>
                            <textarea  id="note" style="font-size: 18px;" name="note" class="form-control  input-lg" rows="1"></textarea>
                        </div>
                        
                        <div class="form-group col-lg-2 col-md-3 col-sm-4 col-xs-12 pull-right" style="text-align: right;padding-top: 25px;"> 
                            
                            <a href="" class="btn btn-success btn-lg" id="add_app_btn_modal">ADD</a>
                        </div>
                    </div>
                    <div class="col-xs-12" style="font-size: 20px;">
                        <div class="col-sm-3 col-xs-12"><strong>STATUS:</strong> &nbsp;&nbsp;<span id="appt_status"></span></div>
                        <div class="col-sm-4 col-xs-12"><strong>LAST STATUS DATE:</strong>&nbsp;&nbsp;<span id="appt_lsd"></span></div>
                        <div class="col-sm-5 col-xs-12"><strong>NEXT VISIT DATE:</strong>&nbsp;&nbsp;<span id="appt_nvd"></span></div>
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
        
        $('#result_div').on('click','.appt',function(e){
             e.preventDefault();
            _modal = $('#add_appoint_modal'); 
            _target = $(this);
            
            $('#timepicker1').timepicker({
                minuteStep: 30,
                showSeconds: false,
                showMeridian: true,
                defaultTime:'08:00',
                showInputs:false
            });
            _pid = _target.data('id');
            _modal.find('#patient_name').val(_target.data('name'));
            _modal.find('#patient_id').val(_pid);
            _modal.find('#appt_status').html(_target.data('status'));
            _modal.find('#appt_lsd').html(_target.data('lsd'));
            
            $.post(BASE_URL+'appoint/getNextVisitDate',{id:_pid},function(data){
              $('#appt_nvd').html(data);
            });
            
            _modal.find('#add_app_btn_modal').unbind('click').bind('click', function(e) {
                e.preventDefault();
                _modal.find('#add_appoint_errors').html('');
                _modal.find('.modal-body').addClass('loader');
                
                $.post(BASE_URL+'appoint/addAppointPatient',$('#add_appoint_form').serialize(),function(data){
                    data = JSON.parse(data);
                    if(data.status == 'error')
                    {
                        _modal.find('#add_appoint_errors').html(data.errors);
                    }
                    else
                    {
                        _modal.find('#add_appoint_errors').html(data.msg);
                        setTimeout(function(){
                           _modal.find('#add_appoint_errors').html('');
                           _modal.find('#add_appoint_form').trigger('reset');
                           _modal.modal('hide');
                        },2000);
                    }
                    _modal.find('.modal-body').removeClass('loader');
                });
            });
            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
            
            _modal.modal();
        });
        
        $('#result_div').on('click','.add_photo',function(e){
            e.preventDefault();
            _modal = $('#add_patient_photo');            
            _modal.find('#add_img_modal_name').html($(this).data('name'));
            _modal.find('#iu_pro_id').val($(this).data('id'));
            _modal.modal();
        });
        
        $('#result_div').on('click','.add_pp',function(e){
            e.preventDefault();
            _target = $(this);
            _modal = $('#add_pp_model');
            _modal.find('#pp_patient_id').val(_target.data('patientid'));
            _modal.find('#add_rpp_modal_name').html($(this).data('name'));
            _modal.find('#assign_pp_btn').unbind('click').bind('click', function(ex) {
                ex.preventDefault();
                _modal.find('#as_errors').html('');
                $.post(BASE_URL+'patient/assignInitPP',$('#assign_pp_frm').serialize(),function(data){
                    data = JSON.parse(data);
                    if(data.status == 'error')
                    {
                        _modal.find('#as_errors').html(data.errors);
                    }
                    else if(data.status == 'success')
                    {
                        _modal.find('#as_errors').html(data.msg);
                        _modal.find('#remaining_inp').val('');
                        setTimeout(function(){
                            _modal.find('#as_errors').html('');                            
                        },2000);
                    }
                });
            });
            
            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
            
            _modal.modal();
        });
        
        $("#search_btn").on('click',function(){
            do_search();
        });
        
        $('#search_phase').on("keypress", function(e) {
            if (e.which == 13) {
                do_search();
            }
       });
        
        function do_search()
        {
            _name = $('#search_phase').val();
            $.post(BASE_URL+'patient/dosearch',{phase:_name},function(data){
                data = JSON.parse(data);
                if(data.status == 'error')
                {
                    alert(data.msg);
                }
                else
                {
                    
                    $('#result_div').html(data.table);
                    $('#result_div').find('.datatable').dataTable();
                }
            });
        }
    });
</script>
<?php $this->load->view('template/footer');?>

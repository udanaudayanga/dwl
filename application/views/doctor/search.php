<?php $this->load->view('template/header');?>
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-timepicker.min.css">
<script type="text/javascript" src="/assets/js/bootstrap-timepicker.min.js"></script>
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
                <div class="col-lg-5 col-md-6 col-sm-8 col-xs-12 form-horizontal">
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
<div class="modal fade modal-info" id="add_patient_alert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">    
    <div class="modal-dialog" >
        <div class="loader-container text-center color-white">
                <div><i class="fa fa-spinner fa-pulse fa-4x"></i></div>
                <div>Loading...</div>
        </div>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Add Patient Alert - <span id="add_img_modal_name"></span></h4>
            </div>
            <div class="modal-body">
                <form  method="POST" id="add_alert_frm" class="form-horizontal">
                    <input type="hidden" name="patient_id" id="iu_pro_id" />
                    <div class="col-xs-12 no-padding" id="alert_errors"></div>
                    <div class="row">
                        <div class="col-md-12" style="">
                            <textarea class="form-control" name="msg" rows="2" placeholder="Patient Alert"></textarea>
                        </div>
                      </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="add_alert_btnn" class="btn btn-success">ADD ALERT</button>
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
            
            _modal.find('#add_app_btn_modal').on('click', function(ex) {
                ex.preventDefault();
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
        
         $('#result_div').on('click','.add_alert',function(e){
            e.preventDefault();            
            _modal = $('#add_patient_alert'); 
            _modal.find('#add_img_modal_name').html($(this).data('name'));
            _modal.find('#iu_pro_id').val($(this).data('id'));
            _modal.find('#alert_errors').html('');
            _modal.find('#add_alert_btnn').unbind('click').bind('click',function(ex){
                ex.preventDefault();
                _modal.find('.modal-dialog').addClass('loader');
                
                $.post(BASE_URL+'doctor/addAlert',_modal.find('#add_alert_frm').serialize(),function(data){
                    data = JSON.parse(data);
                    if(data.status == 'error')
                    {
                        _modal.find('#alert_errors').html(data.errors);
                    }
                    else
                    {  
                        _modal.find('#alert_errors').html(data.msg);
                        setTimeout(function(){
                            _modal.modal('hide');                            
                        },2000);
                    }
                    _modal.find('.modal-dialog').removeClass('loader');
                });
            });
            
            _modal.on("hidden.bs.modal", function () {           
                _modal.find('#alert_errors').html('');
                _modal.find("#add_alert_frm").trigger('reset'); 
            });
            
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
            $.post(BASE_URL+'doctor/dosearch',{phase:_name},function(data){
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

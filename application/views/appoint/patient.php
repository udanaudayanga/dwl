<?php $this->load->view('template/header');?>
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-timepicker.min.css">
<script type="text/javascript" src="/assets/js/bootstrap-timepicker.min.js"></script>
<div class="row">
    <div class="col-xs-12">
        <div class="card">   
            <div class="card-header">
                <div class="card-title" style="padding: 10px;">
                    <div class="title">Select Patient</div>
                </div>
                <a href="" style="margin: 10px;display:none;" id="search_new" class="btn btn-info pull-right">Search New</a>
            </div>
            <div class="card-body" style="padding: 10px;">
                <div class="col-xs-12">
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
                    <div class="col-xs-12" id="add_freeze_errors"></div>
                </div>
                
                
                    
                    
                    <div class="form-inline col-xs-12">
                        <div class="form-inline col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                            <label class="col-lg-4 col-sm-6 col-xs-6" for="exampleInputName3">Patient Name : </label>
                            <input type="text" class="form-control" style="width: 200px;" value="" id="patient_name" placeholder="Patient Name">
                            <input type="hidden" name="patient_id" id="patient_id"/>
                        </div>
                         
                        
                    </div>

                    
               
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="card" id="patient_appts_table">   

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
            var cache = {};
            $("#patient_name" ).autocomplete({
              minLength: 2,
              source: function( request, response ) {
                var term = request.term;
                if ( term in cache ) {
                  response( cache[ term ] );
                  return;
                }

                $.getJSON( BASE_URL+'patient/getReferalPatient', request, function( data, status, xhr ) {
                  cache[ term ] = data;
                  response( data );
                });
              },
              select: function( event, ui ) {
                  $('#patient_id').val(ui.item.id);
                  loadAppts(ui.item.id);
                  $('#search_new').show();
              }
            });
            
            
            function loadAppts(patient_id)
            {
                 $.post(BASE_URL+'appoint/patientApptTable',{patient_id:patient_id},function(data){
                     data = JSON.parse(data);
                     
                     $('#patient_appts_table').html(data.table);
                 });
            }
        
        $('#patient_appts_table').on('click','.del_appoint',function(e){
             e.preventDefault();
            _id = $(this).data('id');
            _pid = $(this).data('pid');
            
            if(confirm('Are you sure?'))
            {
                $.post(BASE_URL+'appoint/delPatientAppt',{id:_id,patient_id:_pid},function(data){
                    data = JSON.parse(data);
                    $('#patient_appts_table').html(data.table);
                });
            }
        });
        
        $('#search_new').on('click',function(e){
            e.preventDefault();
            $(this).hide();
            $('#patient_appts_table').html('');
            $('#patient_id').val('');
            $('#patient_name').val('');
        });
        
        $('#patient_appts_table').on('click','#add_appt',function(e){
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
                        loadAppts(_pid);
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
    });
</script>
<?php $this->load->view('template/footer');?>
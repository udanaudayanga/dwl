<?php $this->load->view('template/header');?>
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-timepicker.min.css">
<link rel="stylesheet" type="text/css" href="http://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.css">
<script type="text/javascript" src="/assets/js/bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="http://cdn.jsdelivr.net/qtip2/3.0.3/jquery.qtip.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery.maskedinput.min.js"></script>
<style>
    .select2{height: 34px;}
    td.striped{background: repeating-linear-gradient(
        45deg,
        #ddd,
        #ddd 3px,
        #fff 5px,
        #fff 10px
      );}
    td.striped_red{background: repeating-linear-gradient(
        45deg,
        #ffc6c6,
        #ffc6c6 3px,
        #fff 5px,
        #fff 10px
      );}
    
</style>

<div class="card">
    <div class="card-header">

        <div class="card-title" style="padding: 0.5em 15px;">
            <div class="title">All Appointments</div>
        </div>
        <div class="pull-right">
            <a href="" title="Print Appointments" id="app_print" class="btn btn-info" style="margin-right: 10px;padding: 6px 8px;">Print</a>
            <a href="" id="np_app_btn" class="btn btn-success" style="margin-right: 10px;padding: 6px 8px;">New Patient</a>
            <a href="" id="add_app_btn" class="btn btn-success" style="margin-right: 10px;padding: 6px 8px;">Add Appointment</a>
            <label for="set_date">Set Date: </label>
            <input readonly="readonly" style="width: 100px;display: inline-block;margin-right: 10px;" type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY" class="form-control" name="date" value="<?php echo date("m/d/Y",strtotime($appdate));?>" id="set_date" />
            
            
            <label>Location: </label>
            <select id="set_location" class="form-control" style="width: 80px;margin-right: 10px;">
                <option value="0">ALL</option>
                <?php foreach($locations as $loc){?>
                <option <?php if($loc->id == $apploc){?> selected="selected" <?php } ?> value="<?php echo $loc->id;?>"><?php echo $loc->abbr;?></option>
                <?php } ?>
            </select>
            &nbsp;&nbsp;
            
            <a href="" id="add_block_btn" class="btn btn-danger" style="margin-right: 10px;padding: 6px 8px;">Add Block</a>
            <label class="">View as:</label>
            <div class="btn-group" role="group" aria-label="...">
                <button type="button" class="btn btn-info" id="cal_view">Calendar</button>
                <button type="button" id="table_view" class="btn btn-warning">Table</button>
              </div>
        </div>

    </div>
    <div class="card-body" style="padding: 15px;">
        
        <div role="alert" class="alert fresh-color alert-success" id="appoint_success" style="display: none;"></div>
        <div id="table_con">
            <?php $this->load->view('appoint/_cal_partial');?>
        </div>
        
    </div>
</div>
<div class="modal fade modal-info" id="add_block" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Add Block</h4>
            </div>
            <div class="modal-body" style="overflow: auto;">
                <div class="loader-container text-center color-white">
                    <div><i class="fa fa-spinner fa-pulse fa-4x"></i></div>
                    <div>Loading...</div>
                </div>
                <div id="as_errors"></div>
                <form id="add_block_frm" >
                    <div class="col-xs-12">
                        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <label for="qty">Date</label>
                            <input type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY" class="form-control" name="date" value="" id="as_date" placeholder="Quantity">
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <label for="shift_pop">Location</label>
                            <select id="shift_pop" name="location_id" class="form-control not_select2" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <?php foreach($locations as $loc){?>
                                <option value="<?php echo $loc->id;?>"><?php echo $loc->name;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <label for="qty">Start</label>
                            <div class="input-group bootstrap-timepicker timepicker">
                                <input readonly="readonly" name="start" id="timepicker3" type="text" class="form-control input-small ">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            </div>
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <label for="qty">End</label>
                            <div class="input-group bootstrap-timepicker timepicker">
                                <input readonly="readonly" name="end" id="timepicker4" type="text" class="form-control input-small ">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            </div>
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <label for="shift_pop2">Block Type</label>
                            <select id="shift_pop2" name="type" class="form-control not_select2" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <option value="1">Block All</option>
                                <option value="2">Block New Patients</option>                                
                            </select>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="shift_pop3">Reason</label>
                            <textarea class="form-control" rows="1" name="reason"></textarea>
                        </div>
                    </div>
                    <div class="col-xs-12">

                        <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12 pull-right" style="text-align: right;">                                    
                            <a href="" class="btn btn-success" id="add_block_btn_modal">Add Block</a>
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
    <div class="modal-dialog" style="width: 80%;">
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
                    
                    <div class="col-xs-12">
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label style="font-size: 20px;" for="patient_name">Patient</label>
                            <input type="text" class="form-control input-lg" value="" id="patient_name" placeholder="Patient Name">
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
                                <option <?php if($loc->id == 3){?> selected='selected' <?php } ?>  value="<?php echo $loc->id;?>"><?php echo $loc->abbr;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label style="font-size: 20px;" for="type">Type</label>
                            <select class="form-control not_select2  input-large" name="type" id="type" tabindex="-1" aria-hidden="true" style="width: 100%;font-size: 18px;height: 46px;">    
                                <option  value="2">Weekly</option>
                                <option  value="1">New</option>                                
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
                </form>
                <div class="col-xs-12" id="next_visit_date" style="font-size: 20px;color: red;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-info" id="edit_app" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 75%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Edit Appointment</h4>
            </div>
            <div class="modal-body" style="overflow: auto;">
                <div class="loader-container text-center color-white">
                    <div><i class="fa fa-spinner fa-pulse fa-4x"></i></div>
                    <div>Loading...</div>
                </div>
                <div id="ea_errors"></div>
                <form id="edit_app_frm" >
                    <input type="hidden" id="ea_id" name="id"/>
                    <div class="col-xs-12" style="padding: 0px;">
                        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <label for="ea_date">Date</label>
                            <input type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY"  class="form-control" name="date" value="" id="ea_date" placeholder="Quantity">
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <label for="ea_location">Location</label>
                            <select id="ea_location" name="location_id" class="form-control not_select2" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <?php foreach($locations as $loc){?>
                                <option value="<?php echo $loc->id;?>"><?php echo $loc->name;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <label for="ea_time">Time</label>
                            <div class="input-group bootstrap-timepicker timepicker">
                                <input readonly="readonly" name="time" id="ea_time" type="text" class="form-control input-small ">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            </div>
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <label for="ea_type">Type</label>
                            <select class="form-control not_select2" name="type" id="ea_type" tabindex="-1" aria-hidden="true" style="width: 100%;">    
                                <option  value="1">New</option>
                                <option  value="2">Weekly</option>
                                <option  value="3">Dr.Consult</option>
                                <option  value="4">Shots Only</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12" style="padding: 0px;">
                        <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12"  >
                            <label style="" for="type">Appt made by</label>
                            <select class="form-control not_select2" name="staff_id" id="ea_staff_id" tabindex="-1" aria-hidden="true" style="width: 100%;">    
                                <?php foreach($staff as $s){?>
                                    <option value="<?php echo $s->id;?>"><?php echo $s->lname." ".$s->fname;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="text-align: right;">   
                            <label style="text-align: left;float: left;" for="ea_note">Note</label>
                            <textarea class="form-control" id="ea_note" name="note" rows="1"></textarea>
                        </div>
                        <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12 " style="text-align: right;padding-top: 20px;">                                    
                            <a href="" class="btn btn-success" id="edit_app_btn_modal">Update</a>
                        </div>
                    </div>                    
                </form>
                <div class="col-xs-12" style="padding: 0px;">
                        <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12 " style="text-align: right;margin-bottom: 0px;">                                    
                            <a href="" class="btn pull-left" data-ens="" id="no_show"></a>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-info" id="app_history" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="app_history_title">Appointment History</h4>
            </div>
            <div class="modal-body" id="app_history_body" style="overflow: auto;">
                
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-info" id="add_np_appoint_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Add New Patient Appointment</h4>
            </div>
            <div class="modal-body" style="overflow: auto;">
                <div class="loader-container text-center color-white">
                    <div><i class="fa fa-spinner fa-pulse fa-4x"></i></div>
                    <div>Loading...</div>
                </div>
                <div id="as_errors"></div>
                <form id="add_np_appoint_form" >
                    <input type="hidden" name="type" value="1" />
                    
                    
                    <div class="col-xs-12">
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label style="font-size: 20px;" for="">Last Name</label>
                            <input type="text" class="form-control input-lg" value="" name="last_name" id="np_lname" placeholder="Last Name">
                        </div>
                        
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label style="font-size: 20px;" for="">First Name</label>
                            <input type="text" class="form-control input-lg" value="" id="np_fname" name="first_name" placeholder="First Name">
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label style="font-size: 20px;" for="patient_name">Phone</label>
                            <input type="text" class="form-control input-lg" value="" name="phn" id="np_phone" placeholder="(___)___-____">
                        </div>
<!--                    </div>
                    <div class="col-xs-12">-->
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label style="font-size: 20px;" for="date">Date</label>
                            <input type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY" class="form-control input-lg" name="date" value="" id="np_date" placeholder="Quantity">
                    
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label style="font-size: 20px;" for="qty">Time</label>
                            <div class="input-group bootstrap-timepicker timepicker">
                                <input readonly="readonly" name="time" id="timepicker2" type="text" class="form-control input-lg ">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            </div>
                        </div>
                        
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label style="font-size: 20px;" for="cat">Location</label>
                            <select class="form-control not_select2  input-large" name="location_id" id="location_id" tabindex="-1" aria-hidden="true" style="width: 100%;font-size: 18px;height: 46px;">       

                               <?php foreach($locations as $loc){?>
                                <option  <?php if($loc->id == 3){?> selected='selected' <?php } ?>  value="<?php echo $loc->id;?>"><?php echo $loc->abbr;?></option>
                                <?php } ?>
                            </select>
                        </div>
<!--                    </div>
                    
                    <div class="col-xs-12">-->
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12"  >
                            <label style="font-size: 20px;" for="type">Appt made by</label>
                            <select class="form-control not_select2  input-large" name="staff_id" id="staff_id" tabindex="-1" aria-hidden="true" style="width: 100%;font-size: 18px;height: 46px;">    
                                <?php foreach($staff as $s){?>
                                    <option value="<?php echo $s->id;?>"><?php echo $s->lname." ".$s->fname;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            
                        </div>                        
                    </div>
                    
                    <div class="col-xs-12">
                        <div class="form-group col-lg-10 col-md-9 col-sm-8 col-xs-12">
                            <label style="font-size: 20px;" for="note">Note</label>
                            <textarea  id="note" style="font-size: 18px;" name="note" class="form-control  input-lg" rows="1"></textarea>
                        </div>
                        <div class="form-group col-lg-2 col-md-3 col-sm-4 col-xs-12 pull-right" style="text-align: right;padding-top: 25px;"> 
                            
                            <a href="" class="btn btn-success btn-lg" id="add_np_app_btn_modal">ADD</a>
                        </div>
                    </div>
                    <div class="col-xs-12" id="add_np_appoint_errors" style=""></div>
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
      appendTo:'#add_appoint_modal',
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
          $.post(BASE_URL+'appoint/getNextVisitDate',{id:ui.item.id},function(data){
              $('#next_visit_date').html(data);
          });
      }
    });
    
   
    
    $('#add_appoint').on('click',function(e){
        $('#add_appoint_errors').html('');
        $('#table_con').addClass('loader');
        $.post(BASE_URL+'appoint/addAppoint',$('#add_appoint_form').serialize(),function(data){
            data = JSON.parse(data);
            if(data.status == 'error')
            {
                $('#add_appoint_errors').html(data.errors);
            }
            else
            {
                $( "#set_date" ).datepicker( "setDate", $('#date').val());
                $('#table_con').html(data.html);
                $('#table_con').find('.datatable').dataTable();
                $("#add_appoint_form").trigger('reset');
                $("#location_id").select2('val','');
                $('#next_visit_date').html("");
                setSuccAlert('Appointment Added Successfully');                
            }
            $('#table_con').removeClass('loader');
        });
    });
    
    $('#table_con').on('click','.show_history',function(e){
        e.preventDefault();
        
         _target = $(this);
        _modal = $('#app_history');
        _pid = _target.data('id');
        $.get(BASE_URL+'appoint/getHistory/'+_pid,function(data){
            data = JSON.parse(data);
            _modal.find('#app_history_title').html("Appointment History of "+data.pname);
            _modal.find('#app_history_body').html(data.table);
        });
        
         $.fn.modal.Constructor.prototype.enforceFocus = function() {};
        _modal.modal(); 

    });
    
    $('#app_history').on('show.bs.modal', function () {
        $('.modal .modal-body').css('overflow-y', 'auto'); 
        $('.modal .modal-body').css('max-height', $(window).height() * 0.7);
    });
    
    $('#table_con').on('click','.del_appoint',function(e){
        e.preventDefault();
        if(confirm("Are you sure?"))
        {
            _id = $(this).data('id');
            $('#add_appoint_errors').html('');
            $('#table_con').addClass('loader');
            $.post(BASE_URL+'appoint/delAppoint',{id:_id},function(data){
                data = JSON.parse(data);
                
                $('#table_con').html(data.html);
                $('#table_con').find('.datatable').dataTable();
                setSuccAlert('Appointment Removed Successfully');
                $('[data-toggle="tooltip"]').tooltip();

                $('#table_con').removeClass('loader');
            });
        }
    });
    
    $('#table_con').on('click','.rem_app',function(e){
        e.preventDefault();
        if(confirm("Are you sure?"))
        {
            _id = $(this).data('id');
            $('#add_appoint_errors').html('');
            $('#table_con').addClass('loader');
            $.post(BASE_URL+'appoint/delAppCal',{id:_id},function(data){
                data = JSON.parse(data);
                
                $('#table_con').html(data.html);
                setSuccAlert('Appointment Removed Successfully');

                $('#table_con').removeClass('loader');
            });
        }
    });
    
    $('#table_con').on('click','.nav_date',function(e){
        e.preventDefault();
        
        _date = $(this).data('date');
        $('#add_appoint_errors').html('');
        $('#table_con').addClass('loader');
        $.post(BASE_URL+'appoint/changeDate',{date:_date},function(data){
            data = JSON.parse(data);
            $('#table_con').html(data.html);
            $('#table_con').removeClass('loader');     
            $( "#set_date" ).datepicker( "setDate", _date);
        });
    });
    
    $('#table_con').on('click','.rem_block',function(e){
        e.preventDefault();
        if(confirm("Are you sure?"))
        {
            _id = $(this).data('id');
            $('#add_appoint_errors').html('');
            $('#table_con').addClass('loader');
            $.post(BASE_URL+'appoint/removeBlock',{id:_id},function(data){
                data = JSON.parse(data);
                $('#table_con').html(data.html);
                $('#table_con').removeClass('loader');
                setSuccAlert('Block Slot Removed Successfully');
            });
        }
    });
    
    $('#table_con').on('click','.info_block',function(e){
        e.preventDefault();
        _reason = $(this).data('reason');
        bootbox.alert("Blocked Reason: "+_reason);
        
    });
    
    $('#table_view').on('click',function(e){
        e.preventDefault();
        
        $('#table_con').addClass('loader');
        $.post(BASE_URL+'appoint/tableView',function(data){
            data = JSON.parse(data);
            $('#table_con').html(data.html);
            $('#table_con').find('.datatable').dataTable();
            $('#table_con').removeClass('loader');
        });
    });
    
    $('#cal_view').on('click',function(e){
        e.preventDefault();
        
        $('#table_con').addClass('loader');
        $.post(BASE_URL+'appoint/calView',function(data){
            data = JSON.parse(data);
            $('#table_con').html(data.html);
            $('#table_con').removeClass('loader');
        });
    });
    
    $('#table_con').on('click','.edit_app',function(e){
        e.preventDefault();
        _target = $(this);
        _modal = $('#edit_app');

        $('#ea_time').timepicker({
            minuteStep: 30,
            showSeconds: false,
            showMeridian: true,
            defaultTime:'08:00',
            showInputs:false
        });
        
        _id = _target.data('id');
        
        _modal.find('#ea_date').val(_target.data('date'));
        _modal.find('#ea_id').val(_target.data('id'));
        _modal.find('#ea_location').val(_target.data('location'));
        $('#ea_time').timepicker('setTime', _target.data('time'));
        _modal.find('#ea_type').val(_target.data('type'));
        _modal.find('#ea_staff_id').val(_target.data('staff'));
         _modal.find('#ea_note').val(_target.data('note'));
         
         _ns = _target.data('ns') ==1 ? 0 : 1;
         _modal.find('#no_show').html(_ns==1?"Mark as No Show":"Mark as Show");
         _modal.find('#no_show').val(_ns);
         _modal.find('#no_show').addClass(_ns==1?"btn-danger":"btn-success");
         
         _modal.find('#edit_app_btn_modal').unbind('click').bind('click', function(e) {
            e.preventDefault();
            _modal.find('#as_errors').html('');
            _modal.find('.modal-body').addClass('loader');
            $.post(BASE_URL+'appoint/updateAppoint',$('#edit_app_frm').serialize(),function(data){
                data = JSON.parse(data);
                _modal.find('.modal-body').removeClass('loader');
                if(data.status == 'error')
                {
                    _modal.find('#ea_errors').html(data.errors);
                }
                else if(data.status == 'success')
                {

                    $('#table_con').html(data.html);

                    _modal.find('#add_block_frm').trigger("reset");
                    _modal.find('#aa_errors').html('');
                    _modal.modal('hide');

                    setSuccAlert('Appointment Updated Successfully');
                }
            });
        });
           
         _modal.find('#no_show').unbind('click').bind('click', function(e) {
            e.preventDefault(); 
            _ns = $(this).val();
            if(confirm("Are you sure?"))
            {
                _modal.find('.modal-body').addClass('loader');
                $.post(BASE_URL+'appoint/setNoShow',{id:_id,ns:_ns},function(data){
                    data = JSON.parse(data);
                    _modal.find('.modal-body').removeClass('loader');
                    if(data.status == 'success')
                    {
                        _rns = _ns ==1 ? 0 : 1;
                        _modal.find('#no_show').html(_ns==0?"Mark as No Show":"Mark as Show");
                        _modal.find('#no_show').val(_rns);
                        _modal.find('#no_show').removeClass(_rns==0?"btn-danger":"btn-success");
                        _modal.find('#no_show').addClass(_rns==1?"btn-danger":"btn-success");
                        $('#table_con').html(data.html);
                    }
                });
            }
        });
            
        $.fn.modal.Constructor.prototype.enforceFocus = function() {};
        _modal.modal();    
    });
    
      $('#timepicker2').timepicker({
            minuteStep: 30,
            showSeconds: false,
            showMeridian: true,
            defaultTime:'08:00',
            showInputs:false
        });
        
        
    
    $('#np_app_btn').on('click',function(e){
        e.preventDefault();
        _target = $(this);
        _modal = $('#add_np_appoint_modal');
        $("#np_phone").mask("(999) 999-9999");
        
      $('#timepicker2').timepicker('setTime', '08:00 AM');
      
//        _modal.find('#np_phone').on('blur',function(){
//            _fn = _modal.find('#np_fname').val();
//            _ln = _modal.find('#np_lname').val();
//            _phn = _modal.find('#np_phone').val();
//            
//            if($.trim(_fn).length && $.trim(_ln).length)
//            {
//                $.post(BASE_URL+'appoint/isPatientExist',{fname:_fn,lname:_ln,phone:_phn},function(data){
//                    data = JSON.parse(data);
//                    if(data.status == 'exist')
//                    {
//                        _modal.find('#add_np_appoint_errors').html(data.msg);
//                    }
//                    else
//                    {
//                        _modal.find('#add_np_appoint_errors').html('');
//                    }
//                });
//                
//            }
//        });
        
        _modal.find('#add_np_app_btn_modal').on('click',function(e){
            e.preventDefault();
            $('#add_np_appoint_errors').html('');
            _modal.find('.modal-body').addClass('loader');
            _add = $(this);
            _add.unbind('click');
            
            $.post(BASE_URL+'appoint/addNpAppoint',$('#add_np_appoint_form').serialize(),function(data){
                data = JSON.parse(data);
                if(data.status == 'error')
                {
                    _modal.find('#add_np_appoint_errors').html(data.errors);
                }
                else
                {                    
                    _modal.find( "#set_date" ).datepicker( "setDate", $('#date').val());
                    $('#table_con').html(data.html);
                    $('#table_con').find('.datatable').dataTable();
                    _modal.find("#add_np_appoint_form").trigger('reset');  
                    _modal.modal('hide');
                    setSuccAlert('Appointment Added Successfully');                
                }
                _modal.find('.modal-body').removeClass('loader');
                _add.bind('click');
            });
        });
        

        _modal.on("hidden.bs.modal", function () {           
            _modal.find('#add_np_appoint_errors').html('');
            _modal.find("#add_np_appoint_form").trigger('reset');  
            _modal.find('#add_np_appoint_form').unbind('click');
        });
        
        $.fn.modal.Constructor.prototype.enforceFocus = function() {};
        _modal.modal();
    });
    
    $('#add_app_btn').on('click',function(e){
            e.preventDefault();
            _target = $(this);
            _modal = $('#add_appoint_modal');
            
             $('#timepicker1').timepicker({
                minuteStep: 30,
                showSeconds: false,
                showMeridian: true,
                defaultTime:'08:00',
                showInputs:false
            });
            
            _modal.find('#add_app_btn_modal').unbind('click').bind('click', function(e) {
                e.preventDefault();
                $('#add_appoint_errors').html('');
                _modal.find('.modal-body').addClass('loader');
                $.post(BASE_URL+'appoint/addAppoint',$('#add_appoint_form').serialize(),function(data){
                    data = JSON.parse(data);
                    if(data.status == 'error')
                    {
                        $('#add_appoint_errors').html(data.errors);
                    }
                    else
                    {                        
                        _modal.find( "#set_date" ).datepicker( "setDate", $('#date').val());
                        $('#table_con').html(data.html);
                        $('#table_con').find('.datatable').dataTable();
                        _modal.find("#add_appoint_form").trigger('reset');                        
                        _modal.find('#next_visit_date').html("");
                        _modal.modal('hide');
                        setSuccAlert('Appointment Added Successfully');                
                    }
                    _modal.find('.modal-body').removeClass('loader');
                });
            });
            _modal.on("hidden.bs.modal", function () {                
                _modal.find('#add_appoint_errors').html('');
                _modal.find('#add_appoint_form').unbind('click');
                _modal.find("#add_appoint_form").trigger('reset'); 
                _modal.find('next_visit_date').html('');
            });
            
            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
            _modal.modal();
    });
    $('#add_block_btn').on('click',function(e){
            e.preventDefault();
            _target = $(this);
            _modal = $('#add_block');
            
            $('#timepicker3,#timepicker4').timepicker({
                minuteStep: 30,
                showSeconds: false,
                showMeridian: true,
                defaultTime:'08:00',
                showInputs:false
            });
            
            _modal.find('#add_block_btn_modal').unbind('click').bind('click', function(e) {
                e.preventDefault();
                _modal.find('#as_errors').html('');
                $.post(BASE_URL+'appoint/addBlock',$('#add_block_frm').serialize(),function(data){
                    data = JSON.parse(data);
                    if(data.status == 'error')
                    {
                        _modal.find('#as_errors').html(data.errors);
                    }
                    else if(data.status == 'success')
                    {
                        
                        $('#table_con').html(data.html);
                        
                            _modal.find('#add_block_frm').trigger("reset");
                            _modal.find('#as_errors').html('');
                            _modal.modal('hide');
                        
                        setSuccAlert('Block period added Successfully');
                    }
                });
            });
            
            _modal.on("hidden.bs.modal", function () {               
                _modal.find('#as_errors').html('');
                _modal.find('#add_block_btn_modal').unbind('click');
            });
            
            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
            _modal.modal();
    });
    
    $('#table_con').on('mouseover click','.tt',function(e){
        $(this).qtip({ // Grab all elements with a non-blank data-tooltip attr.
            content: {
                attr: 'data-title', // Tell qTip2 to look inside this attr for its content           
                title: 'Contact Info:'
            },
            show: {
                event: e.type, // Use the same show event as the one that triggered the event handler
                ready: true, // Show the tooltip as soon as it's bound, vital so it shows up the first time you hover!
                solo: true
            },
            style: {
                classes: 'qtip-bootstrap qtip-shadow'
            },
            hide: 'unfocus'
        },e);
    });
    
    $("#set_location").on('change',function(e){
        _loc_id = $(this).val();
        
        $('#add_appoint_errors').html('');
        $('#table_con').addClass('loader');
        $.post(BASE_URL+'appoint/changeLocation',{loc_id:_loc_id},function(data){
            data = JSON.parse(data);
            $('#table_con').html(data.html);
            $('#table_con').removeClass('loader');            
        });
    });
    
    $('#set_date').datepicker().on('changeDate', function(e) {
        
        _date = e.format(0,"yyyy-mm-dd");
        $('#add_appoint_errors').html('');
        $('#table_con').addClass('loader');
        $.post(BASE_URL+'appoint/changeDate',{date:_date},function(data){
            data = JSON.parse(data);
            $('#table_con').html(data.html);
            $('#table_con').removeClass('loader');            
        });
    });

    
    $('#app_print').on('click',function(e){
        e.preventDefault();
        _date = $('#set_date').val();
        _loc = $('#set_location').val();        
        _date = convertDate(_date);
        
        if(_loc == 0)
        {
            bootbox.alert('Please Select a Location');
        }
        else if(!_date)
        {
            bootbox.alert('Please set a date');
        }
        else
        {
            var win = window.open(BASE_URL+'appoint/app_print/'+_loc+'/'+_date, '_blank');
            if(win){
                //Browser has allowed it to be opened
                win.focus();
            }
        }
        
    });
    
    
    
});

function convertDate(inputFormat) {
  function pad(s) { return (s < 10) ? '0' + s : s; }
  var d = new Date(inputFormat);
  return [d.getFullYear(), pad(d.getMonth()+1),pad(d.getDate())].join('-');
}

function setSuccAlert(msg)
{
    $("#appoint_success").show();
    $("#appoint_success").html("<strong>"+msg+"</strong>");
    setTimeout(function(){$("#appoint_success").hide();},3000);
}
</script>
<?php $this->load->view('template/footer');?>


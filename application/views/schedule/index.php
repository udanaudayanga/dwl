<?php $this->load->view('template/header');?>
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-timepicker.min.css">
<script type="text/javascript" src="/assets/js/bootstrap-timepicker.min.js"></script>
<style>
	div.dataTables_wrapper div.dataTables_filter input{width: 400px;font-size: 16px;}
        div.dataTables_wrapper div.dataTables_length select{font-size: 16px;}
</style>

<div class="row">
    <div class="col-xs-12">
        <div class="card">   
            <div class="card-header">
                <div class="card-title">
                    <div class="title">Add Employee Shift</div>
                </div>
            </div>
            <div class="card-body">
                <form id="add_shift_form" method="POST">
                    <div class="col-xs-12" id="add_shift_errors"></div>
                    <div class="form-group  col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label for="cat">Location</label>
                        <select class="form-control" name="location_id" id="location_id" tabindex="-1" aria-hidden="true" style="width: 100%;">       
                            
                           <?php foreach($locations as $loc){?>
			    <option  value="<?php echo $loc->id;?>"><?php echo $loc->name;?></option>
			    <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label for="name">Employee</label>
                        <div id="cart_pro_dd_div">
                        <select class="form-control" id="user_id" name="user_id" tabindex="-1" aria-hidden="true" style="width: 100%;">
                            <?php foreach($employees as $emp){?>
			    <option <?php echo set_select('user_id', $emp->id); ?> value="<?php echo $emp->id;?>"><?php echo $emp->lname." ".$emp->fname;?></option>
			    <?php } ?>
                        </select>
                        </div>
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label for="qty">Date</label>
                        <input type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" placeholder="YYYY-MM-DD" class="form-control" name="date" value="" id="qty" placeholder="Quantity">
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label for="qty">Start</label>
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input name="start" id="timepicker1" type="text" readonly="readonly" class="form-control input-small ">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                        </div>
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label for="qty">End</label>
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input name="end" id="timepicker2" readonly="readonly" type="text" class="form-control input-small ">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                        </div>
                    </div>
                    <div class="form-group col-lg-1 col-md-2 col-sm-6 col-xs-12">
                        <label for="shift">Shift</label>
                        <select id="shift" name="shift" class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option value="1">1st Shift</option>
                            <option value="2">2nd Shift</option>
                            <option value="3">3rd Shift</option>
                        </select>
                    </div>

                    <div style="" class="form-group col-lg-1 col-md-2 col-sm-6 col-xs-12">
                        <label for="day">&nbsp;</label>
                        <button style="margin-top: 0px;" id="add_shift_btn" type="button" class="btn btn-success form-control">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12" id="table_con">       
        
                <?php $this->load->view('schedule/_shifts');?>
            
    </div>
</div>
<div class="modal fade modal-info" id="edit_shift" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 55%;">
		<div class="modal-content">
		    <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
			<h4 class="modal-title" id="myModalLabel">Edit Shift</h4>
		    </div>
                    <div class="modal-body" style="overflow: auto;">
                        <div id="as_errors"></div>
                        <form id="edit_shift_frm" >
                            <input type="hidden" id="shift_id" name="shift_id" value="" />
                            <div class="col-xs-12">
                                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <label for="qty">Start</label>
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <input name="start" id="timepicker3" type="text" class="form-control input-small ">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                    </div>
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <label for="qty">End</label>
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <input name="end" id="timepicker4" type="text" class="form-control input-small ">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                    </div>
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <label for="shift_pop">Shift</label>
                                    <select id="shift_pop" name="shift" class="form-control not_select2" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        <option value="1">1st Shift</option>
                                        <option value="2">2nd Shift</option>
                                        <option value="3">3rd Shift</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <label for="qty">Date</label>
                                    <input type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" placeholder="YYYY-MM-DD" class="form-control" name="date" value="" id="as_date" placeholder="Quantity">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                    <label for="qty">Note</label>
                                    <textarea id="note" class="form-control" name="note"></textarea>
                                    <!--<input type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" placeholder="YYYY-MM-DD" class="form-control" name="date" value="" id="as_date" placeholder="Quantity">-->
                                </div>
                                <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12" style="padding-top: 20px;text-align: center;">
                                    <div class="checkbox" style="margin-bottom: 0px;">
                                          <div class="checkbox3 checkbox-round">
                                            <input type="checkbox" id="checkbox-1">
                                            <label for="checkbox-1">
                                              Send SMS alert
                                            </label>
                                          </div>
                                        </div>
                                    <a href="" class="btn btn-success" id="edit_shift_btn">Update</a>
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
        
        $('[data-toggle="tooltip"]').tooltip(); 
        
        $('#table_con').on('click','.nav_week',function(e){
            e.preventDefault();
            _nav = $(this).data('val');
            
            $.post(BASE_URL+'schedule/changeWeek',{nav:_nav},function(data){
                $('#table_con').html(data);
                $('#shift_location').select2({
                    width: '100%'
                });    
                $('[data-toggle="tooltip"]').tooltip(); 
            });
        });
        $('#timepicker1,#timepicker2,#timepicker3,#timepicker4').timepicker({
                minuteStep: 30,
                showSeconds: false,
                showMeridian: false,
                defaultTime:'08:00',
                showInputs:false
            });
            
        $('#add_shift_btn').on('click',function(e){
            $('#add_shift_errors').html('');
            $('#table_con').addClass('loader');
            $.post(BASE_URL+'schedule/addShift',$('#add_shift_form').serialize(),function(data){
                data = JSON.parse(data);
		if(data.status == 'error')
		{
		    $('#add_shift_errors').html(data.errors);
		}
                else
                {
                    $('#table_con').html(data.table);
                    $("#add_shift_form").trigger('reset');
                    $("#location_id").select2('val','');
                    $("#user_id").select2('val','');
                    setSuccessAlert('Shift Added Successfully');
                    $('[data-toggle="tooltip"]').tooltip();
                }
                $('#table_con').removeClass('loader');
            });
        });
        
        $('#table_con').on('change','#shift_location',function(e){
            e.preventDefault();
            _loc = $(this).val();
            $('#table_con').addClass('loader');
            $.post(BASE_URL+'schedule/changeLocation',{location:_loc},function(data){
                $('#table_con').html(data);
                $('#shift_location').select2({
                    width: '100%'
                });
                $('#table_con').removeClass('loader');
                $('[data-toggle="tooltip"]').tooltip();
            });
        });
        
        $('#table_con').on('click','.rem_shift',function(e){
            e.preventDefault();
            _id = $(this).data('id');
            if(confirm('Are you sure?'))
            {
                $('#table_con').addClass('loader');
                $.post(BASE_URL+'schedule/remShift',{id:_id},function(data){
                    $('#table_con').html(data);
                    $('#table_con').removeClass('loader');
                    setSuccessAlert('Shift Removed Successfully');
                    $('[data-toggle="tooltip"]').tooltip();
                });
            }
        });
        
        $('#table_con').on('click','#copy_next',function(e){
            e.preventDefault();
            _id = $(this).data('id');
            if(confirm('Are you sure that you want to copy all selected week shifts to next week?'))
            {
                $('#table_con').addClass('loader');
                $.post(BASE_URL+'schedule/copyNext',function(data){
                    $('#table_con').html(data);
                    $('#table_con').removeClass('loader');
                    setSuccessAlert('Schedule Copied Successfully');
                });
            }
            
        });
        
        $('#table_con').on('click','.edit_shift',function(e){
            e.preventDefault();
            _target = $(this);
            _modal = $('#edit_shift');
            
            $('#timepicker3,#timepicker4').timepicker({
                minuteStep: 30,
                showSeconds: false,
                showMeridian: false,
                defaultTime:'08:00',
                showInputs:false
            });
            
            $('#timepicker3').timepicker('setTime', _target.data('start'));
            $('#timepicker4').timepicker('setTime', _target.data('end'));            
            _modal.find('#as_date').val(_target.data('date'));
            _modal.find('#shift_id').val(_target.data('id'));
            
            _modal.find('#note').val(_target.data('title'));
            _modal.find('#shift_pop').val(_target.data('shift'));
            
            _modal.find('#edit_shift_btn').unbind('click').bind('click', function(e) {
                e.preventDefault();
                _modal.find('#as_errors').html('');
                $.post(BASE_URL+'schedule/editShift',$('#edit_shift_frm').serialize(),function(data){
                    data = JSON.parse(data);
                    if(data.status == 'error')
                    {
                        _modal.find('#as_errors').html(data.errors);
                    }
                    else if(data.status == 'success')
                    {
                        _modal.find('#as_errors').html(data.msg);
                        $('#table_con').html(data.table);
                        setTimeout(function(){
                            _modal.find('#as_errors').html('');
                            _modal.modal('hide');
                        },1500);
                        setSuccessAlert('Shift Updated Successfully');
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                });
            });
            
            _modal.on('hidden', function() {
                _modal.find('input').val('');
                _modal.find('#as_errors').html('');
                _modal.find('.edit_shift').unbind('click');
            });
            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
            _modal.modal();
        });
        
        function setSuccessAlert(msg)
        {
            $("#table_con #shift_success").show();
            $("#table_con #shift_success").html("<strong>"+msg+"</strong>")
            setTimeout(function(){$("#table_con #shift_success").hide();},3000);
        }
    });
    
    
</script>
<?php $this->load->view('template/footer');?>
<?php $this->load->view('template/header'); ?>

<div class="row">
    <div class="col-xs-12">
	<div class="card">
	    <div class="card-header">

                <div class="card-title"  style="padding: 10px;width: 100%;">
		    <div class="title">Queue Messages
                        <a href="" class="btn btn-success pull-right" id="add_alert_msg" style="margin: 0px;">Add Queue Alert</a>    
                    </div>                
		</div>
                
	    </div>
	    <div class="card-body" style='padding: 15px;' id="queue_items_tbl">
		<?php $this->load->view('queue/_queue_msg_tbl');?>
	    </div>
	</div>
    </div>
</div>

<div class="modal fade modal-success" id="add_alert_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 400px;">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h3 class="modal-title" id="myModalLabel">Add Queue Alert</h3>
            </div>
            <div class="modal-body" style='overflow: auto;'>
                <div id="add_alert_errors"></div>
                <form method="POST">
                    <div class="form-group col-xs-12">
                        <label for="qty">Date</label>
                        <div class="input-daterange input-group col-xs-12" id="datepicker">
                            <input type="text" class="input-sm form-control" readonly="readonly" id="start" name="start" value="<?php echo set_value('start');?>"/>
                            <span class="input-group-addon">to</span>
                            <input type="text" class="input-sm form-control" readonly="readonly" id="end" name="end" value="<?php echo set_value('end');?>"/>
                        </div>
                        <!--<input readonly="readonly" type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" placeholder="YYYY-MM-DD" class="form-control" name="date" value="" id="as_date" placeholder="Quantity">-->
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="type">Type</label>
                        <select class="form-control not_select2" name="type">
                            <option value="1">Default</option>
                            <option value="2">2nd Visit</option>
                        </select>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="qty">Message</label>
                        <textarea class="form-control" id="add_msg" name="msg"></textarea>
                    </div>
<!--                    <div class="form-group col-xs-12">
                        <label for="qty">Image</label>
                        <input type="file" id="add_file" name="photo"></input>
                    </div>-->
                    <div class="form-group col-lg-3 col-md-3 col-sm-4 col-xs-12">
                            <label class="">Images Preview</label>
                            <div style="padding: 0px;">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 320px; height: 100px;">
                                        
                                    </div>
                                    <div> 
                                        <span class="btn btn-default btn-file">
                                            <span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span>
                                            <input type="file" name="photo">
                                        </span> 
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a> 
                                    </div>
                                </div><!-- //fileinput-->
                            </div>
                        </div>
                </form>
            </div>
            <div class="modal-footer">
                <label id="char_count" class="pull-left">0</label>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="add_queue_msg_btn" class="btn btn-success">Add</button>             
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-success" id="edit_alert_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 400px;">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h3 class="modal-title" id="myModalLabel">Update Queue Alert</h3>
            </div>
            <div class="modal-body" style='overflow: auto;'>
                <div id="edit_alert_errors"></div>
                <form method="POST">
                    <div class="form-group col-xs-12">
                        <label for="qty">Date</label>
                        <div class="input-group col-xs-12" id="">
                            <input type="text" class="input-sm form-control" readonly="readonly" id="edit_start" name="start" value="<?php echo set_value('start');?>"/>
                            <span class="input-group-addon">to</span>
                            <input type="text" class="input-sm form-control" readonly="readonly" id="edit_end" name="end" value="<?php echo set_value('end');?>"/>
                        </div>
                        <!--<input readonly="readonly" type="text" placeholder="YYYY-MM-DD" class="form-control" name="date" value="" id="edit_date" >-->
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="type">Type</label>
                        <select class="form-control not_select2" id="edit_type" name="type">
                            <option value="1">Default</option>
                            <option value="2">2nd Visit</option>
                        </select>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="qty">Message</label>
                        <textarea class="form-control" id="edit_msg" name="msg"></textarea>
                    </div>
                    <div class="form-group col-lg-3 col-md-3 col-sm-4 col-xs-12">
                        <input type="hidden" id="rem_photo" name="rem_photo" value="0"/>
                            <label class="">Images Preview</label>
                            <div style="padding: 0px;">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 320px; height: 100px;">
                                        
                                    </div>
                                    <div> 
                                        <span class="btn btn-default btn-file">
                                            <span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span>
                                            <input type="file" name="photo">
                                        </span> 
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a> 
                                    </div>
                                </div><!-- //fileinput-->
                            </div>
                        </div>
                </form>
            </div>
            <div class="modal-footer">
                <label id="char_count_edit" class="pull-left">0</label>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="update_queue_msg_btn" class="btn btn-success">Update</button>             
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/assets/js/fileinput.js"></script>
<script type="text/javascript"> 
    $(function(){
        $('.input-daterange').datepicker({
            maxViewMode: 0,
            autoclose: true,
            format: "yyyy-mm-dd"
        });
        
        $('#add_alert_msg').on('click',function(e){
            e.preventDefault();
            _modal = $('#add_alert_modal');
            _form = _modal.find('form');
            
            _modal.find('#add_queue_msg_btn').unbind('click').bind('click',function(ee){   
                ee.stopImmediatePropagation();
                _this = $(this);
                _this.hide();
                 var formData = new FormData(_form[0]);
                 
                 $.ajax({
                    url : BASE_URL+'promos/addQueueMsg',  // Controller URL
                    type : 'POST',
                    data : formData,
                    async : false,
                    cache : false,
                    contentType : false,
                    processData : false,
                    success : function(data) {
                        data = JSON.parse(data);
                        if(data.status == 'success')
                        {                        
                            _msgs_div = $('#queue_items_tbl');
                            _msgs_div.html(data.msgs);
                            _msgs_div.find('.datatable').dataTable({
                                "order": [[ 0, "desc" ]]
                            }); 
                            _modal.modal('hide');
                            setToolTip();
                            _this.show();
                        }
                        else 
                        {
                            _modal.find('#add_alert_errors').html(data.errors);
                            _this.show();
                        }
                    }
                });

            });
            
            _modal.on("hidden.bs.modal", function() {               
                
                 _modal.find('#add_alert_errors').html('');
                 _modal.find('#add_queue_msg_btn').show();
                 _modal.find("#char_count").text(0);
                _modal.find('form').trigger('reset');
            });
            
            _modal.modal();
        });
        
        $("#add_msg").keyup(function(){
            $("#char_count").text($(this).val().length);
          });
          
          $("#edit_msg").keyup(function(){
            $("#char_count_edit").text($(this).val().length);
          });
          
        $('#queue_items_tbl').on('click','.rem_msg',function(e){
            e.preventDefault();
            _id = $(this).data('id');
            
            if(confirm('Are you sure?'))
            {
                $.get(BASE_URL+'/promos/remQM/'+_id,function(data){
                    data = JSON.parse(data);
                    if(data.status == 'success')
                    {                        
                        _msgs_div = $('#queue_items_tbl');
                        _msgs_div.html(data.msgs);
                        _msgs_div.find('.datatable').dataTable({
                            "order": [[ 0, "desc" ]]
                        }); 
                        setToolTip();
                    }
                });
            }
        });  
        
        $('#queue_items_tbl').on('click','.edit_msg',function(e){
            e.preventDefault();
            _id = $(this).data('id');
            
            _modal = $('#edit_alert_modal');
            _modal.find('#edit_msg').val($(this).data('msg'));
            _modal.find('#edit_start').val($(this).data('start'));
            _modal.find('#edit_end').val($(this).data('end'));
            _modal.find('#edit_type').val($(this).data('type'));
            $("#char_count_edit").text($('#edit_msg').val().length);
            
            _form = _modal.find('form');
            
            _photo = $(this).data('photo');
            if(_photo)
            {
                _img = '<img src="/assets/upload/queue_alerts/'+ _photo+'" />';
                _modal.find('.fileinput-preview').html(_img);
                _modal.find('.fileinput').removeClass('fileinput-new').addClass('fileinput-exist');
                
            }
            
            _modal.find('a.fileinput-exists').on('click',function(){
                _modal.find('#rem_photo').val(1);
            });
            
            
            _modal.find('#update_queue_msg_btn').unbind('click').bind('click',function(ee){   
                ee.stopImmediatePropagation();
                _this = $(this);
                _this.hide();
                
                var formData = new FormData(_form[0]);
                 
                 $.ajax({
                    url : BASE_URL+'promos/updateQueueMsg/'+_id,  // Controller URL
                    type : 'POST',
                    data : formData,
                    async : false,
                    cache : false,
                    contentType : false,
                    processData : false,
                    success : function(data) {
                        data = JSON.parse(data);
                        if(data.status == 'success')
                        {                        
                            _msgs_div = $('#queue_items_tbl');
                            _msgs_div.html(data.msgs);
                            _msgs_div.find('.datatable').dataTable({
                                "order": [[ 0, "desc" ]]
                            }); 
                            _modal.modal('hide');
                            setToolTip();
                            _this.show();
                        }
                        else 
                        {
                            _modal.find('#add_alert_errors').html(data.errors);
                            _this.show();
                        }
                    }
                });

            });
            
            _modal.on("hidden.bs.modal", function() { 
                 _modal.find('#edit_alert_errors').html('');
                 _modal.find('#update_queue_msg_btn').show();
                 _modal.find('.fileinput-preview').html('');
                _modal.find('.fileinput').removeClass('fileinput-exist').addClass('fileinput-new');
                 _modal.find('#rem_photo').val(0);
            });
            
            _modal.modal();
            
        });
        
        function setToolTip()
        {
            $('#queue_items_tbl').find('.qm_photo').each(function() {
               _photo = $(this).data('photo');
                
                $(this).tooltip({
                    content: '<img style="max-height: 200px;" src="'+BASE_URL+'assets/upload/queue_alerts/'+_photo+'" />'
                });
            });
        }
        
        setToolTip();
       
    });
</script>
<?php $this->load->view('template/footer'); ?>
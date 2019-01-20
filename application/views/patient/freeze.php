<?php $this->load->view('template/header');?>
<div class="row">
    <div class="col-xs-12">
        <div class="card">   
            <div class="card-header">
                <div class="card-title" style="padding: 10px;">
                    <div class="title">Freeze a Patient</div>
                </div>
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
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                        <label class="col-lg-1 col-md-2 col-sm-3 col-xs-4" for="exampleInputName3">Patient Name : </label>
                        <input type="text" class="form-control" style="width: 200px;" value="" id="patient_name" placeholder="Patient Name">
                        <input type="hidden" name="patient_id" id="patient_id"/>
                    </div>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                        <label class="col-lg-1 col-md-2 col-sm-3 col-xs-4" for="exampleInputName3">Reason : </label>
                        <textarea name="reason" id="freezed_reason" class="form-control" style="width:200px;" placeholder="Reason"></textarea>
                    </div>
                        
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                        <button class="btn btn-success" id="patient_freeze_btn" style="margin: 0px;font-size: 16px;margin-left:15px;">Freeze</button>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="card">   
            <div class="card-header">
                <div class="card-title" style="padding: 10px;">
                    <div class="title">Freezed Patients</div>
                </div>
            </div>
            <div class="card-body" id="freezed_table" style="padding: 10px;overflow: hidden;">
                <?php $this->load->view('patient/_freeze_table');?>
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
              }
            });
            
            
            $('#patient_freeze_btn').on('click',function(){
                
                _pid = $('#patient_id').val();
                _fr = $('#freezed_reason').val();

                if($.trim(_pid) == '')
                {
                    bootbox.alert('Please select patient from list');
                }
                else
                {
                   $.post(BASE_URL+'patient/addFreeze',{id:_pid,fr:_fr},function(data){
                       data = JSON.parse(data);
                       $('#freezed_table').html(data.table);
                       $('#freezed_table').find('.datatable').dataTable();
                       $('#add_freeze_errors').html('<div role="alert" class="alert fresh-color alert-success"><strong>Patient Freezed Successfully.</strong></div>');
                       setTimeout(function(){$('#add_freeze_errors').html('');},2000);
                       $('#patient_id').val('');
                       $('#patient_name').val('');
                       $('#freezed_reason').val('');
                   });
                }
            });
            
            $('#freezed_table').on('click','.del_freeze',function(e){
                e.preventDefault();
                _id = $(this).data('id');
                
                if(confirm('Are you sure?'))
                {
                    $.post(BASE_URL+'patient/removeFreeze',{id:_id},function(data){
                        data = JSON.parse(data);
                        $('#freezed_table').html(data.table);
                        $('#freezed_table').find('.datatable').dataTable();
                         $('#add_freeze_errors').html('<div role="alert" class="alert fresh-color alert-success"><strong>Patient Unreezed Successfully.</strong></div>');
                           setTimeout(function(){$('#add_freeze_errors').html('');},2000);
                    });
                }
            });
    });
</script>
<?php $this->load->view('template/footer');?>
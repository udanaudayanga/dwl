<?php $this->load->view('template/header');?>
<div class="row">
    <div class="col-xs-12">
        <div class="card">   
            <div class="card-header">
                <div class="card-title" style="padding: 10px;">
                    <div class="title">Prescriptions for a patient</div>
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
                </div>
                
                
                    <div class="col-xs-12" id="add_shift_errors"></div>
                    
                    <div class="form-inline col-xs-12">
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                            <label class="col-lg-1 col-sm-2 col-xs-2" for="exampleInputName3">Patient Name : </label>
                            <input type="text" class="form-control" style="width: 300px;" value="" id="patient_name" placeholder="Patient Name">
                            <input type="hidden" name="patient_id" id="patient_id"/>
                        </div>
                         <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"  style="margin-bottom: 10px;">
                            <label class="col-lg-1 col-sm-2 col-xs-2" for="exampleInputName3">Date : </label>
                            <!--<input id="date" type="text" class="form-control" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" placeholder="YYYY-MM-DD"/>-->
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="input-sm form-control" id="start" />
                                <span class="input-group-addon">to</span>
                                <input type="text" class="input-sm form-control" id="end" />
                            </div>
                        </div>
    <!--                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                            <label class="col-lg-1 col-sm-2 col-xs-2" for="type">Type : </label>
                            <select class="form-control" id="type">
                                <option value="1">Original</option>
                                <option value="2">Refill</option>
                            </select>
                        </div>-->
                        <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12" style="">
                            <button class="btn btn-success" id="patient_log_btn" style="margin: 10px 0 0 20px;font-size: 16px;">Print Prescriptions</button>
                        </div>
                    </div>
                    
<!--                    <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
                        <label for="from">Patient Name</label>
                        <input type="text" class="form-control" value="" id="patient_name" placeholder="Patient Name">
                        <input type="hidden" name="patient_id" id="patient_id"/>
                    </div>
                    <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
                        <label for="from">Select a date</label>
                        <input readonly="readonly" type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" placeholder="YYYY-MM-DD" class="form-control dp" name="date" value="" id="date" placeholder="From">
                    </div>
                                      

                    <div style="" class="form-group col-lg-1 col-md-2 col-sm-6 col-xs-12">
                        <label for="day">&nbsp;</label>
                        <button style="margin-top: 0px;"  type="button" id="patient_log_btn" class="btn btn-success form-control">SELECT</button>
                    </div>-->
                    
               
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
            
            
            $('#patient_log_btn').on('click',function(){
                
                _pid = $('#patient_id').val();
                _start = $('#start').val();
                _end = $('#end').val();

                if($.trim(_pid) == '')
                {
                    bootbox.alert('Please select patient from list');
                }
                else
                {
                    var win = window.open(BASE_URL+'logs/patientlog/'+_pid+'/'+_start+'/'+_end, '_blank');
                    if(win){
                        //Browser has allowed it to be opened
                        win.focus();
                    }
                }
            });
            
            $('.input-daterange').datepicker({
            maxViewMode: 0,
            autoclose: true,
            format: "yyyy-mm-dd"
        });
    });
</script>
<?php $this->load->view('template/footer');?>
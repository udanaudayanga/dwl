<?php $this->load->view('template/header');?>
<div class="row">
        <div class="card">                                
            <div class="card-body" style="padding: 15px 0px;">
                 <div class="form-inline col-xs-12">
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                        <label class="col-lg-1 col-sm-2 col-xs-2" for="exampleInputName3">Location : </label>
                        <select class="form-control" id="location">
                            <?php foreach($locations as $location){?>
                            <option value="<?php echo $location->id;?>"><?php echo $location->name;?></option>
                            <?php } ?>
                        </select>
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

                    <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12" style="">
                        <button class="btn btn-success" id="prescription_print" style="margin: 10px 0 0 20px;font-size: 16px;">Print Prescription</button>
                    </div>
                </div>
            </div>
        </div>
</div>
<script type="text/javascript">
    $(function(){
        $('.input-daterange').datepicker({
            maxViewMode: 0,
            autoclose: true,
            format: "yyyy-mm-dd"
        });
        
        $('#prescription_print').on('click',function(){
            _start = $('#start').val();
            _end = $('#end').val();
            _location = $('#location').val();
//            _type = $('#type').val();

            if($.trim(_start) == '' || $.trim(_end) == '')
            {
                bootbox.alert('Date range is a required field.');
            }
            else
            {
                var win = window.open(BASE_URL+'logs/print_prescription/'+_location+'/'+_start+'/'+_end, '_blank');
                if(win){
                    //Browser has allowed it to be opened
                    win.focus();
                }
            }
        });
    });

</script>
<?php $this->load->view('template/footer');?>
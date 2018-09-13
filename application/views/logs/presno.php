<?php $this->load->view('template/header');?>
<div class="row">
        <div class="card">                                
            <div class="card-body" style="padding: 15px 0px;">
                 <div class="form-inline col-xs-12">
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                        <label class="col-lg-1 col-md-2 col-sm-3 col-xs-6" for="exampleInputName3">Prescription # : </label>
                        <input type="text" id="pres_no" class="form-control"/>
                    </div>
                    
                    <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12" style="">
                        <button class="btn btn-success" id="prescription_print" style="margin: 10px 0 0 20px;font-size: 16px;">Show Info</button>
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
            _pres = $('#pres_no').val();
            if($.trim(_pres) == '')
            {
                bootbox.alert('Prescription # is a required field.');
            }
            else
            {
                var win = window.open(BASE_URL+'logs/print_presno/'+_pres, '_blank');
                if(win){
                    //Browser has allowed it to be opened
                    win.focus();
                }
            }
        });
    });

</script>
<?php $this->load->view('template/footer');?>
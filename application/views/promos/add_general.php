<?php $this->load->view('template/header');?>

<style>
        .checkbox3 label, .radio3 label{font-size: 16px;}
        .checkbox3 label::before, .radio3 label::before {
            top: 9px;
        }
        .checkbox3 input:checked + label::after {margin-top: 5px;}
        .radio3.radio-check label::after, .radio3.radio-check.radio-light label::after {
            height: 26px;
            line-height: 26px;
        }
        
        table.meds_tbl tr td,table.meds_tbl tr th{padding: 4px 8px;}
        table.meds_tbl tr td,table.meds_tbl tr th{padding: 4px 8px;}
        
@media (min-width: 1200px) {
  table{width: 80%;}
}
@media (max-width: 767px) {
  table{width: 100%;}
}
@media (min-width: 768px) and (max-width: 991px) {
  table{width: 90%;}
}
@media (min-width: 992px) and (max-width: 1199px) {
  table{width: 80%;}
}
    </style>
<div class="row">
    
        <div class="card"> 
           
            <div class="card-body" style="padding: 15px 0px;">
                <form method="POST" id="add_promo_frm">
                    <div id="ap_errors" class="col-xs-12">
                    <?php echo validation_errors('<div class="col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;"><div role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');?>
                    </div>
                <div class="form-inline col-xs-12">                    
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 15px;">                         
                         <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="name">Name: </label>
                         <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                             <input type="text" class="form-control input-sm" style="width: 100%;" id="name" name="name" value="<?php echo set_value('name');?>"/>                            
                        </div>                         
                    </div>
                     
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 15px;">                         
                         <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="promo_id">Promotion : </label>
                        <div class="input-group col-lg-2 col-md-6 col-sm-6 col-xs-6" id="">
                            <select class="form-control not_select2" name="promo_id" style="width: 100%;" id="promo_id">
                                <option value="">Select </option>
                                <?php foreach($promos as $promo){?>
                                    <option value="<?php echo $promo->id;?>"><?php echo $promo->name;?></option>
                                <?php } ?>
                            </select>
                        </div>                         
                    </div>  
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 15px;">                         
                         <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="code">Code: </label>
                         <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                             <input type="text" class="form-control input-sm" style="width: 100%;" id="code" name="code" value="<?php echo set_value('code');?>"/>                            
                        </div>                         
                    </div>
                </div>
                
                    <div class="form-inline col-xs-12" style="padding-left: 40px;">                    
                        <button class="btn btn-success" id="" type="submit" style="font-size: 16px;">Save General Promotion</button>
                </div>
                </form>
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
        
        
        
    });

</script>
<?php $this->load->view('template/footer');?>
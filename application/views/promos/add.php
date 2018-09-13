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
                    
                    </div>
                <div class="form-inline col-xs-12">                    
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 15px;">                         
                         <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Name: </label>
                         <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                             <input type="text" class="form-control input-sm" style="width: 100%;" id="name" name="name" value="<?php echo set_value('name');?>"/>                            
                        </div>                         
                    </div>
                     <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 15px;">                         
                         <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Date Range: </label>
                        <div class="input-daterange input-group col-lg-2 col-md-6 col-sm-6 col-xs-6" id="datepicker">
                            <input type="text" class="input-sm form-control" id="start" name="start" value="<?php echo set_value('start');?>"/>
                            <span class="input-group-addon">to</span>
                            <input type="text" class="input-sm form-control" id="end" name="end" value="<?php echo set_value('end');?>"/>
                        </div>                         
                    </div>     
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 15px;">                         
                         <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Type : </label>
                        <div class="input-group col-lg-2 col-md-6 col-sm-6 col-xs-6" id="">
                            <select class="form-control not_select2" name="type" style="width: 100%;" id="promo_type">
                                <option value="">Select </option>
                                <?php foreach($promo_types as $type){?>
                                    <option value="<?php echo $type->id;?>"><?php echo $type->name;?></option>
                                <?php } ?>
                            </select>
                        </div>                         
                    </div>  
                    <div class="col-xs-12" id="ps_div" style="padding: 0px;">
                        
                    </div>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 15px;">                         
                         <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Info: </label>
                         <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                             <textarea class="form-control input-sm" style="width:100%;" placeholder="Explain Promotion in Plain text to show in order page." name="info"><?php echo set_value('info');?></textarea>                        
                        </div>                         
                    </div>
                </div>
                
                    <div class="form-inline col-xs-12" style="padding-left: 40px;">                    
                    <button class="btn btn-success" id="save_promo_btn" type="button" style="font-size: 16px;">Save Promotion</button>
                </div>
                </form>
            </div>
        </div>
    
</div>
<div style="display: none;">
    <div id="ps_div_1">
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"  style="margin-bottom: 15px;">
            <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Wave Off Type:</label>
            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                <select name="wave_off_type" class="form-control not_select2" style="width: 100%;" >
                    <option value="flat">Flat Amount </option>
                    <option value="percentage">Percentage</option>
                </select>                          
           </div> 
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"  style="margin-bottom: 15px;">
            <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Wave Off Amount: </label>
            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                <input type="text" class="form-control input-sm" style="width: 100%;" id="name" name="wave_off_amount" value=""/>                            
           </div> 
        </div>
    </div>
    <div id="ps_div_2">
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"  style="margin-bottom: 15px;">
            <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Product :</label>
            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                <select name="product" id="" class="form-control not_select2" style="width: 100%;">
                    <option value="">Select</option>
                    <?php foreach($allPros as $cats){
                        $pros = $cats['pros'];
                        ?>
                    <optgroup label="<?php echo $cats['name'];?>">
                        <?php foreach($pros as $pro){?>
                        <option value="<?php echo $pro['id'];?>"><?php echo $pro['name'];?></option>
                        <?php } ?>
                    </optgroup>

                    <?php } ?>
                </select>
           </div> 
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"  style="margin-bottom: 15px;">
            <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Wave Off Type:</label>
            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                <select name="wave_off_type" class="form-control not_select2" style="width: 100%;" >
                    <option value="flat">Flat Amount </option>
                    <option value="percentage">Percentage</option>
                </select>                          
           </div> 
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"  style="margin-bottom: 15px;">
            <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Wave Off Amount: </label>
            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                <input type="text" class="form-control input-sm" style="width: 100%;" id="name" name="wave_off_amount" value=""/>                            
           </div> 
        </div>
    </div>
    <div id="ps_div_3">
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"  style="margin-bottom: 15px;">
            <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Product :</label>
            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                <select name="product" id="" class="form-control not_select2" style="width: 100%;">
                    <option value="">Select</option>
                    <?php foreach($allPros as $cats){
                        $pros = $cats['pros'];
                        ?>
                    <optgroup label="<?php echo $cats['name'];?>">
                        <?php foreach($pros as $pro){?>
                        <option value="<?php echo $pro['id'];?>"><?php echo $pro['name'];?></option>
                        <?php } ?>
                    </optgroup>

                    <?php } ?>
                </select>
           </div> 
        </div>
        
    </div>
    <div id="ps_div_4">
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"  style="margin-bottom: 15px;">
            <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Product :</label>
            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                <select name="product" id="" class="form-control not_select2" style="width: 100%;">
                    <option value="">Select</option>
                    <?php foreach($allPros as $cats){
                        $pros = $cats['pros'];
                        ?>
                    <optgroup label="<?php echo $cats['name'];?>">
                        <?php foreach($pros as $pro){?>
                        <option value="<?php echo $pro['id'];?>"><?php echo $pro['name'];?></option>
                        <?php } ?>
                    </optgroup>

                    <?php } ?>
                </select>
           </div> 
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"  style="margin-bottom: 15px;">
            <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Wave Off %: </label>
            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                <input type="text" class="form-control input-sm" style="width: 100%;" name="wave_off_amount" value=""/>                            
           </div> 
        </div>
    </div>
    <div id="ps_div_5">
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"  style="margin-bottom: 15px;">
            <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Product One:</label>
            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                <select name="pro_1" id="" class="form-control not_select2" style="width: 100%;">
                    <option value="">Select</option>
                    <?php foreach($allPros as $cats){
                        $pros = $cats['pros'];
                        ?>
                    <optgroup label="<?php echo $cats['name'];?>">
                        <?php foreach($pros as $pro){?>
                        <option value="<?php echo $pro['id'];?>"><?php echo $pro['name'];?></option>
                        <?php } ?>
                    </optgroup>

                    <?php } ?>
                </select>
           </div> 
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"  style="margin-bottom: 15px;">
            <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Wave Off %: </label>
            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                <input type="number" class="form-control input-sm" style="width: 100%;"  name="wave_off_1" value=""/>                            
           </div> 
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"  style="margin-bottom: 15px;">
            <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Product Two:</label>
            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                <select name="pro_2" id="" class="form-control not_select2" style="width: 100%;">
                    <option value="">Select</option>
                    <?php foreach($allPros as $cats){
                        $pros = $cats['pros'];
                        ?>
                    <optgroup label="<?php echo $cats['name'];?>">
                        <?php foreach($pros as $pro){?>
                        <option value="<?php echo $pro['id'];?>"><?php echo $pro['name'];?></option>
                        <?php } ?>
                    </optgroup>

                    <?php } ?>
                </select>
           </div> 
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"  style="margin-bottom: 15px;">
            <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Wave Off %: </label>
            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                <input type="number" class="form-control input-sm" style="width: 100%;"  name="wave_off_2" value=""/>                            
           </div> 
        </div>
    </div>
        <div id="ps_div_6">
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"  style="margin-bottom: 15px;">
            <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Product One:</label>
            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                <select name="pro_1" id="" class="form-control not_select2" style="width: 100%;">
                    <option value="">Select</option>
                    <?php foreach($allPros as $cats){
                        $pros = $cats['pros'];
                        ?>
                    <optgroup label="<?php echo $cats['name'];?>">
                        <?php foreach($pros as $pro){?>
                        <option value="<?php echo $pro['id'];?>"><?php echo $pro['name'];?></option>
                        <?php } ?>
                    </optgroup>

                    <?php } ?>
                </select>
           </div> 
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"  style="margin-bottom: 15px;">
            <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Wave Off %: </label>
            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                <input type="number" class="form-control input-sm" style="width: 100%;"  name="wave_off_1" value=""/>                            
           </div> 
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"  style="margin-bottom: 15px;">
            <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Product Two:</label>
            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                <select name="pro_2" id="" class="form-control not_select2" style="width: 100%;">
                    <option value="">Select</option>
                    <?php foreach($allPros as $cats){
                        $pros = $cats['pros'];
                        ?>
                    <optgroup label="<?php echo $cats['name'];?>">
                        <?php foreach($pros as $pro){?>
                        <option value="<?php echo $pro['id'];?>"><?php echo $pro['name'];?></option>
                        <?php } ?>
                    </optgroup>

                    <?php } ?>
                </select>
           </div> 
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"  style="margin-bottom: 15px;">
            <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Wave Off %: </label>
            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                <input type="number" class="form-control input-sm" style="width: 100%;"  name="wave_off_2" value=""/>                            
           </div> 
        </div>
            
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"  style="margin-bottom: 15px;">
            <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Product Three:</label>
            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                <select name="pro_3" id="" class="form-control not_select2" style="width: 100%;">
                    <option value="">Select</option>
                    <?php foreach($allPros as $cats){
                        $pros = $cats['pros'];
                        ?>
                    <optgroup label="<?php echo $cats['name'];?>">
                        <?php foreach($pros as $pro){?>
                        <option value="<?php echo $pro['id'];?>"><?php echo $pro['name'];?></option>
                        <?php } ?>
                    </optgroup>

                    <?php } ?>
                </select>
           </div> 
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"  style="margin-bottom: 15px;">
            <label style="font-size: 18px;" class="col-lg-1 col-md-3 col-sm-3 col-xs-4" for="exampleInputName3">Wave Off %: </label>
            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6" id="" style="padding: 0px;">
                <input type="number" class="form-control input-sm" style="width: 100%;"  name="wave_off_3" value=""/>                            
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
        
        $('#promo_type').on('change',function(){
            _val = $(this).val();
            if(_val)
            {
                _html = $('#ps_div_'+_val).html();
                $('#ps_div').html(_html);
//                $('#ps_div').find('select').select2();
            }
            else
            {
                $('#ps_div').html('');
            }
        });
        
        $('#save_promo_btn').on('click',function(){
            $('#ap_errors').html('');
            $.post(BASE_URL+'promos/addPromo',$('#add_promo_frm').serialize(),function(data){
                data = JSON.parse(data);
                if(data.status == 'success')
                {
                    window.location = BASE_URL+"promos";
                }
                else
                {
                    $('#ap_errors').html(data.errors);
                }
            });
        });
    });

</script>
<?php $this->load->view('template/footer');?>
<?php $this->load->view('template/header');?>
<style>
    .radio3.radio-check label::after, .radio3.radio-check.radio-light label::after {
            height: 26px;
            line-height: 26px;
        }
        .checkbox3 label, .radio3 label{font-size: 16px;}
        .checkbox3 label::before{
            top: 9px;
        }
        .radio3 label::before {
            border: 1px solid #333;
        }
        .radio3 label{margin-top: 0px;}
        .checkbox3 input:checked + label::after {margin-top: 5px;}
        .radio3.radio-check label::after, .radio3.radio-check.radio-light label::after {
            height: 26px;
            line-height: 26px;
        }
        .form-control{font-size: 18px;}
        .select2{font-size: 18px;}
        label{font-size: 18px;font-weight: normal;}
        
</style>
<div class="row">
    <div class="col-sm-5" style="margin-bottom: 20px;">
        <div class="card">                                
            <div class="card-body" style="padding:5px 0px;min-height: 135px;">
                <div class="col-sm-5 col-xs-12" style="padding-right: 0px;">
                    <div class="thumbnail" style="border: none;margin-bottom: 0px;">
                    <a target="_blank" href="<?php echo site_url();?>/assets/upload/patients/<?php echo $patient->photo;?>">
                        <img style="position: absolute;top:34%;left:35%;" src="/phpThumb/phpThumb.php?src=/assets/upload/patients/<?php echo $patient->photo;?>&amp;w=60&amp;h=70&amp;zc=1&amp;f=png" />
                    </a>
                    </div>
                </div>
                <div class="col-sm-7 col-xs-12" style="">
                    <h3 style="margin-top: 0px;font-size: 20px;color: gray;"><?php echo $patient->lname." ".$patient->fname;?></h3>
                    <h5 style="font-size: 14px;"><abbr title="Date of birth" style="text-decoration: none;">DOB:</abbr>  <?php echo date('m/d/Y',strtotime($patient->dob));?></h5>
                    <address style="margin-top: 0px;margin-bottom: 0px;font-size: 12px;color: gray;">
                        <?php echo $patient->address;?>&nbsp;
                        <?php echo $patient->city.", ".getStateAbbr($patient->state)." ".$patient->zip;?><br>
                        <?php echo "(".substr($patient->phone, 0, 3).") ".substr($patient->phone, 3, 3)."-".substr($patient->phone,6);?>
                    </address>	
                    <div class="col-xs-6" style="padding: 0px;"><h4 style="margin-top: 10px;font-size: 14px;color: gray;margin-bottom: 0px;">Order ID:  <?php echo str_pad($order->id, 5, '0', STR_PAD_LEFT);?></h4></div>
                    <div class="col-xs-6" style="padding: 0px;"><h4 style="margin-top: 10px;font-size: 14px;color: gray;margin-bottom: 0px;"><?php echo $location->name;?></h4></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4" style="margin-bottom: 20px;padding: 0px 5px;">
        <div class="card">                                
            <div class="card-body" style="padding:0px;min-height: 135px;">
                <h4 style="font-size: 20px;padding-left: 15px;">Last Visit Meds:&nbsp; <span style="font-weight: normal;"><?php if(!$last_visit)echo "No Last Visit";?></span></h4>
                <?php if($last_visit){?>
                <?php if($last_visit->is_med == 0){?>
                <div class="form-group col-xs-5" style="margin-bottom: 0px;margin-top: 0px;">
                        <!--<label>&nbsp;</label>-->
                     <div class="form-inline" style="margin-left: 10px;">
                            <label for="exampleInputName0">No Meds: </label>
                    </div>
                </div> 
                <div class="form-group col-xs-7 " style="">
                    <div class="form-inline" style="margin-left: 10px;">
                            <label for="exampleInputName0">No of days: </label>
                            <input style="width: 40px;padding: 5px;" type="text"  id="exampleInputName0" class="form-control" value="<?php echo $last_visit->no_med_days;?>"  readonly="readonly">
                    </div>
                </div>
                <?php } ?>
                <?php if($last_visit->is_med == 1){?>
                <div class="form-group col-xs-12" style="margin-bottom: 10px;margin-top: 0px;"> 
                    
                    <form class="form-inline">
                    <div class="form-group" style="font-size: 18px;margin-right: 10px;">Meds:</div>
                    <div class="form-group">
                        <input style="width: 80px;text-align: center;padding: 5px;" type="text"  id="exampleInputName2" class="form-control" value="<?php echo ($last_visit->med1)?getProduct($last_visit->med1)->name:'-';?>"  readonly="readonly">
                    </div> 
                    <!-- <div class="form-group" style="font-size: 26px;">/</div>
                    <div class="form-group">
                        <input style="width: 40px;text-align: center;padding: 5px;" type="text"  id="exampleInputEmail3" class="form-control" value="<?php echo ($last_visit->med2)?getProduct($last_visit->med2)->name:'-';?>" readonly="readonly">
                    </div>
                    <div class="form-group" style="margin-left: 10px;">
                        <input style="width: 40px;text-align: center;padding: 5px;" type="text"  id="exampleInputEmail4" class="form-control" value="<?php echo ($last_visit->med3)?getProduct($last_visit->med3)->name:'-';?>" readonly="readonly">
                    </div> -->
                    </form>
                </div>
                <!-- <div class="form-group col-xs-6" style="margin-bottom: 10px;padding-right: 0px;">
                    <form class="form-inline">    
                    <div class="form-group " style="margin-left: 0px;margin-top: 5px;">
                        <label for="exampleInputName3">Meds per day: </label>
                        <input style="width: 40px;text-align: center;padding: 5px;" type="text"  id="exampleInputName6" class="form-control" value="<?php echo $last_visit->meds_per_day;?>" readonly="readonly">
                    </div>
                        </form>
                </div> -->
                <div class="form-group  col-xs-6" style="margin-bottom: 10px;">
                <form class="form-inline">
                    <div class="form-group " style="margin-top: 5px;">
                        <label for="exampleInputName2">No of days: </label>
                        <input style="width: 40px;text-align: center;padding: 5px;" type="text"  id="exampleInputName5" class="form-control" value="<?php echo $last_visit->med_days;?>" readonly="readonly">
                    </div>
                </form>
                </div>
                <?php } ?>
                <?php }?>
            </div>
        </div>
    </div>
    <div class="col-sm-3" style="margin-bottom: 20px;">
        <div class="card">                                
            <div class="card-body" style="padding:0px;min-height: 135px;background-color: #CAF0FE;">
                <h4 style="font-size: 20px;padding-left: 15px;">Today Visit:</h4>
                <div class="col-xs-6">Paid:</div><div class="col-xs-6" style="padding-right: 0px;">$<?php echo $order->net_total;?> &nbsp;(<?php echo $order->payment_type == 'cash'?'Csh':($order->payment_type == 'credit'?'Cr':'Mix'); ?>)</div>
                <?php $ti = getTodayInfo($order->id);?>
                <div class="col-xs-6">Bought:</div><div class="col-xs-6"><?php echo $ti['bought'];?>&nbsp;wk</div>
                <div class="col-xs-6">Redeemed:</div><div class="col-xs-6"><?php echo $ti['redeem'];?>&nbsp;wk</div>
            </div>
        </div>
    </div>
    <?php
        $pmt = $ti['bought'] + $ti['redeem'];
    ?>
    
<!--    <div class="col-xs-12" style="margin-bottom: 20px;">
        <div class="card">                                
            <div class="card-body" style="padding:0px">
                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                    <div class="thumbnail" style="border: none;margin-bottom: 0px;">
                        <img style="margin-left: 0px;" src="/phpThumb/phpThumb.php?src=/assets/upload/patients/<?php echo $patient->photo;?>&amp;h=70&amp;zc=1&amp;f=png" />
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="">
                    <h3 style="margin-top: 0px;font-size: 20px;color: gray;"><?php echo $patient->lname." ".$patient->fname;?></h3>
                    <h5 style="font-size: 14px;"><abbr title="Date of birth">DOB:</abbr>  <?php echo date('m/d/Y',strtotime($patient->dob));?></h5>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12" style="padding: 0px;">
                    
                    <address style="margin-top: 0px;margin-bottom: 0px;font-size: 14px;color: gray;">
                        <?php echo $patient->address;?>&nbsp;
                        <?php echo $patient->city.", ".getStateAbbr($patient->state)." ".$patient->zip;?><br>
                        <?php echo "(".substr($patient->phone, 0, 3).") ".substr($patient->phone, 3, 3)."-".substr($patient->phone,6);?>
                    </address>					
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="">
                    <h4 style="margin-top: 0px;font-size: 14px;color: gray;"><?php echo $location->name;?></h4>
                    <h4 style="margin-top: 10px;font-size: 14px;color: gray;">Order ID:  <?php echo str_pad($order->id, 5, '0', STR_PAD_LEFT);?></h4>
                </div>
            </div>
        </div>
    </div>-->
</div>

<?php if($last_visit && FALSE){?>
<div class="row">
    <div class="col-xs-12" style="margin-bottom: 50px;">
        <div class="card">

            <div class="card-body" style="overflow: auto;padding: 10px 0px 8px 0px;border: 1px solid #ccc;color: gray;">
                <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xs-12" style="margin-bottom: 0px;">
                    <!--<label for="exampleInputName2">Today's Info: </label>-->
                    <h4 style="font-weight: bold;">Last Visit Meds:</h4>
                </div>
                <?php if($last_visit->is_med == 0){?>
                <div class="form-group col-lg-1 col-md-2 col-sm-3 col-xs-6" style="margin-bottom: 0px;margin-top: 5px;">
                        <!--<label>&nbsp;</label>-->
                    <div class="checkbox"  style="margin:0px;">
                        <div class="checkbox3 checkbox-round">
                          <input type="checkbox" id="checkbox-1" checked="checked" readonly="readonly">
                            <label for="checkbox-1"  style="font-size: 18px;">
                              No Meds
                            </label>
                        </div>
                    </div>

                </div> 
                <div class="form-group col-lg-9 col-md-8 col-sm-6 col-xs-6 " style="">
                    <div class="form-inline" style="margin-left: 20px;">
                            <label for="exampleInputName0">No of days: </label>
                            <input style="width: 60px;" type="text"  id="exampleInputName0" class="form-control" value="<?php echo $last_visit->no_med_days;?>"  readonly="readonly">
                    </div>
                </div>
                <?php } ?>
                <?php if($last_visit->is_med == 1){?>
                <div class="form-group col-lg-1 col-md-1 col-sm-1 col-xs-12" style="margin-bottom: 0px;margin-top: 5px;">
                        <!--<label>&nbsp;</label>-->
                    <div class="checkbox" style="margin:0px;">
                      <div class="checkbox3 checkbox-round">
                        <input type="checkbox" id="checkbox-2" checked="checked"  readonly="readonly">
                        <label for="checkbox-2" style="font-size: 18px;">
                          Meds
                        </label>
                      </div>
                    </div>
                </div> 
                <div class="form-group col-lg-4 col-md-9 col-sm-10 col-xs-12" style="margin-bottom: 0px;">
                    <form class="form-inline">
                    <div class="form-group">
                        <input style="width: 60px;text-align: center;" type="text"  id="exampleInputName2" class="form-control" value="<?php echo ($last_visit->med1)?getProduct($last_visit->med1)->name:'-';?>"  readonly="readonly">
                    </div> <div class="form-group" style="font-size: 30px;">/</div>
                    <div class="form-group">
                        <input style="width: 60px;text-align: center;" type="text"  id="exampleInputEmail3" class="form-control" value="<?php echo ($last_visit->med2)?getProduct($last_visit->med2)->name:'-';?>" readonly="readonly">
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <input style="width: 60px;text-align: center;" type="text"  id="exampleInputEmail4" class="form-control" value="<?php echo ($last_visit->med3)?getProduct($last_visit->med3)->name:'-';?>" readonly="readonly">
                    </div>
                    
                    <div class="form-group pull-right" style="margin-left: 20px;margin-top: 5px;">
                        <label for="exampleInputName3">Meds per day: </label>
                        <input style="width: 60px;text-align: center;" type="text"  id="exampleInputName6" class="form-control" value="<?php echo $last_visit->meds_per_day;?>" readonly="readonly">
                    </div>
                    <div class="form-group pull-right" style="margin-top: 5px;">
                        <label for="exampleInputName2">No of days: </label>
                        <input style="width: 60px;text-align: center;" type="text"  id="exampleInputName5" class="form-control" value="<?php echo $last_visit->med_days;?>" readonly="readonly">
                    </div>
                </form>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<form method="POST">
    <input type="hidden" name="patient_id"  value="<?php echo $patient->id;?>" />
<div class="row">
    <?php if(isset($errors)){?>
     <div class="col-xs-12">
                <?php echo $errors; ?>
    </div>
    <?php } ?>
    <div class="col-xs-12" style="margin-bottom: 20px;">
        <div class="card">                                
            <div class="card-body form-inline" style="overflow: auto;padding: 10px 0px 10px 0px;">
                <div class="form-group form-group-lg form-group-lg col-lg-2 col-md-12 col-sm-12 col-xs-12">
                    <!--<label for="exampleInputName2">Today's Info: </label>-->
                    <h4 style="font-weight: bold;margin-top: 0px;">Today's Info:</h4>
                </div>
                <div class="form-group form-inline form-group-lg col-lg-2 col-md-3 col-sm-3 col-xs-6" style="padding-left: 0px;text-align: right;">
                    <label style="padding-right: 10px;" for="weight">Weight: </label>
                        <input style="width: 100px;text-align: center;" type="text" name="weight" value="<?php echo set_value('weight',($tv)?$tv->weight:'');?>" id="weight" class="form-control decimal_field" pattern="^[0-9]*(\.[0-9]{1}?)?$">
                </div>
                <div class="form-group form-group-lg col-lg-2 col-md-3 col-sm-3 col-xs-6" style="padding-left: 0px;text-align: right;">
                    <label style="padding-right: 10px;" for="bfi">BFI %: </label>
                        <input style="width: 100px;text-align: center;" type="text"  id="bfi" value="<?php echo set_value('bfi',($tv)?$tv->bfi:'');?>" name="bfi" pattern="^[0-9]*(\.[0-9]{1}?)?$" class="form-control decimal_field">
                </div>
                <div class="form-group form-group-lg col-lg-2 col-md-3 col-sm-3 col-xs-6" style="padding-left: 0px;text-align: right;">
                    <label style="padding-right: 10px;" for="bmi">BMI %: </label>
                        <input style="width: 100px;text-align: center;" type="text"  id="bmi" value="<?php echo set_value('bmi',($tv)?$tv->bmi:'');?>" name="bmi" pattern="^[0-9]*(\.[0-9]{1}?)?$" class="form-control decimal_field">
                </div>                
                <div class="form-group form-group-lg col-lg-2 col-md-3 col-sm-3 col-xs-6" style="padding-left: 0px;text-align: right;">
                    <label style="padding-right: 10px;" for="bp">BP: </label>
                        <input style="width: 100px;text-align: center;" type="text"  id="bp" value="<?php echo set_value('bp',($tv)?$tv->bp:'');?>" name="bp" class="form-control">
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="row">
    <div class="col-xs-12" style="margin-bottom: 20px;">
        <div class="card" style="background-color: transparent;">

            <div class="card-body" style="overflow: auto;padding: 0px;">

                <div class="col-lg-12" style="overflow: auto;background-color:#CAF0FE;margin-bottom: 15px;padding: 10px 0px;">
                    <div class="form-group form-group-lg col-lg-2 col-md-2 col-sm-2 col-xs-6" style="padding-top: 5px;margin-bottom: 0px;">
                            <!--<label>&nbsp;</label>-->
                        <div class="radio3">
                            <input type="radio" value="0" name="is_med" id="radio1" <?php echo  set_radio('is_med', '0',($tv)?$tv->is_med==0:FALSE); ?>>
                            <label for="radio1" style="font-size: 18px;padding-top: 2px;">
                              Natural
                            </label>
                          </div>

                    </div> 
                    <div class="form-inline col-lg-4 col-md-4 col-sm-8 col-xs-6" id="today_no_meds" style="">
                         <div class="form-group form-group-lg" style="margin-left: 118px;">
                                <label for="no_med_days">No of days: </label>
                                <input style="width: 60px;" <?php if($user->type != 1){?> readonly="readonly" <?php } ?> type="text"  id="no_med_days" value="<?php echo set_value('no_med_days',($pmt)?$pmt *7:'');?>" name="no_med_days" class="form-control">
<!--                                <select readonly style="padding: 3px 10px;width: 80px;" id="no_med_days" name="no_med_days" class="form-control not_select2">
                                    <option <?php echo set_select('no_med_days',7,$pmt == 1 ? TRUE : FALSE);?> value="7" >7</option>
                                    <option <?php echo set_select('no_med_days',14,$pmt == 2 ? TRUE : FALSE);?> value="14" >14</option>
                                    <option <?php echo set_select('no_med_days',21,$pmt == 3 ? TRUE : FALSE);?> value="21" >21</option>
                                    <option <?php echo set_select('no_med_days',28,$pmt == 4 ? TRUE : FALSE);?> value="28" >28</option>
                                </select>-->
                            </div>
                    </div>
                </div>

                <div class="col-lg-12" style="overflow: auto;background-color: #CAF0FE;padding: 10px 0px;">
                        <div class="form-group form-group-lg col-lg-2 col-md-2 col-sm-1 col-xs-12"  style="padding-top: 5px;margin-bottom: 0px;">
                                <!--<label>&nbsp;</label>-->
                            <div class="radio3">
                                <input type="radio" value="1" name="is_med" id="radio2" <?php echo  set_radio('is_med', '1',($tv)?$tv->is_med==1:FALSE); ?>>
                                <label for="radio2" style="font-size: 18px;padding-top: 2px;">
                                  Meds
                                </label>
                              </div>
                        </div> 
                    <div class="form-group col-lg-10 col-md-10 col-sm-11 col-xs-12" id="today_meds" style="margin-bottom: 0px;">
                            <div class="form-inline">
                                <div class="form-group form-group-lg">
                                    <select name="med1" style="padding: 3px 10px;width: 120px;" class="not_select2 form-control">
                                        <option value="0">-</option>
                                        <?php foreach($med_pros as $pro){
                                            if(strtolower($pro->name) == 'di')continue;
                                        ?>
                                        <option <?php echo set_select('med1',$pro->id,($tv)?$tv->med1==$pro->id:FALSE);?> value="<?php echo $pro->id;?>"><?php echo $pro->name;?></option>
                                        <?php } ?>
                                    </select>
                                   
                                </div> 
                                <!-- <div class="form-group" style="font-size: 30px;">/</div>
                                <div class="form-group form-group-lg">
                                    <select name="med2" style="padding: 3px 10px;width: 80px;" class="not_select2 form-control">
                                        <option value="0">-</option>
                                        <?php foreach($med_pros as $pro){
                                            if(strtolower($pro->name) == 'di' || strtolower($pro->name) == 'p 30' || strtolower($pro->name) == 'p 37')continue;
                                        ?>
                                        <option <?php echo set_select('med2',$pro->id,($tv)?$tv->med2==$pro->id:FALSE);?> value="<?php echo $pro->id;?>"><?php echo $pro->name;?></option>
                                        <?php } ?>
                                    </select>
                                    
                                </div>
                                <div class="form-group form-group-lg" style="margin-left: 15px;">
                                    <select name="med3" style="padding: 3px 10px;width: 80px;" class="not_select2 form-control">
                                        <option value="0">-</option>
                                        <?php foreach($med_pros as $pro){
                                            if(strtolower($pro->name) == 'p 30' || strtolower($pro->name) == 'p 37')continue;
                                        ?>
                                        <option <?php echo set_select('med3',$pro->id,($tv)?$tv->med3==$pro->id:FALSE);?> value="<?php echo $pro->id;?>"><?php echo $pro->name;?></option>
                                        <?php } ?>
                                    </select>
                                    
                                </div> -->
                                <!-- <div class="form-group form-group-lg pull-right" style="margin-left: 15px;">
                                    <label for="med_days">Meds per day: </label>
                                    <select name="meds_per_day" style="padding: 3px 10px;width: 80px;" class="not_select2 form-control">    
                                        <option <?php echo set_select('meds_per_day',0.5,($tv)?$tv->meds_per_day==0.5:FALSE);?> value="0.5">0.5</option>
                                        <option <?php echo set_select('meds_per_day',1,($tv)?$tv->meds_per_day==1:TRUE);?> value="1">1</option>
                                        <option <?php echo set_select('meds_per_day',1.5,($tv)?$tv->meds_per_day==1.5:TRUE);?> value="1.5">1.5</option>
                                        <option <?php echo set_select('meds_per_day',2,($tv)?$tv->meds_per_day==2:FALSE);?> value="2">2</option>
                                    </select>
                                </div> -->
                                <div class="form-group form-group-lg pull-right" style="margin-left: 15px;">
                                    <label for="med_days">No of days: </label>
                                    <input style="width: 60px;" <?php if($user->type != 1){?> readonly="readonly" <?php } ?> type="text"  id="med_days" value="<?php echo set_value('no_med_days',($pmt)?$pmt *7:'');?>" name="med_days" class="form-control">

                                </div>
                                
                            </div>
                        </div>
                </div>
               
            </div>
        </div>
    </div>
     <div class="col-xs-12">
                    <button type="submit" class="btn btn-lg btn-success pull-right">Save</button>
                </div>
</div>
</form>
<script type="text/javascript">
    $(function(){
        function checkMeds()
        {
            if($("input[name=is_med]:checked").val() == 0)
            {
                $('#today_meds').hide();
                $('#today_no_meds').show();
            }
            else if($("input[name=is_med]:checked").val() == 1)
            {
                $('#today_meds').show();
                $('#today_no_meds').hide();
            }
        }
        
        $('input[type=radio][name=is_med]').on('change', function() {
            checkMeds();
        });
        
        checkMeds();
    });
</script>
<?php $this->load->view('template/footer');?>
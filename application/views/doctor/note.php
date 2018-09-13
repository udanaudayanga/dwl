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
    </style>
<div class="row">

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12" style="padding-left: 0px;">
                <img src="/phpThumb/phpThumb.php?src=/assets/upload/patients/<?php echo $patient->photo;?>&amp;w=64&amp;h=54&amp;zc=1&amp;f=png" class="thumbnail"  style="margin-bottom: 0px;max-width: 100%;float: left;"/>
                
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="padding-left: 0px;">
                <div class="col-xs-7" style="padding-left: 0px;">
                    <h3 style="margin-top: 0px;font-size: 20px;font-weight: bold;margin-bottom: 15px;"><?php echo $patient->lname." ".$patient->fname;?></h3>
                    <?php if($this_visit && $this_visit->is_med == 1){?>
                                  <h4 style="font-size: 18px;text-align: left;padding: 0px;margin: 0px;font-weight: normal;">MEDS: &nbsp;  <?php echo ($this_visit->med3)? getProduct($this_visit->med3)->name : ($this_visit->med1?getProduct($this_visit->med1)->name:"-")." / ".($this_visit->med2?getProduct($this_visit->med2)->name:"-");?></h4>
                    <?php }elseif($this_visit && $this_visit->is_med == 0){?>
                                  <h4 style="font-size: 18px;text-align: left;padding: 0px;margin: 0px;font-weight: normal;">MEDS: &nbsp;  NO MEDS</h4>
                    <?php } ?>
                    <?php if($last_visit){
                        $days = ($last_visit->is_med == 1)? $last_visit->med_days : $last_visit->no_med_days;
                        $diff = strtotime($this_visit->visit_date) - strtotime($last_visit->visit_date);
                        $diff = floor($diff/(60*60*24));
                        $diff = $diff - $days;
                    ?>
                    <h4 style="margin-top: 45px;font-size: 16px;font-weight: bold;border: 1px solid black;padding: 3px;width: 140px;">Coming After: <?php echo $diff;?> days</h4>
                    <?php } ?>
                </div>
                <div class="col-xs-5" style="font-size: 18px;padding-left: 0px;">
                    <?php $height = $patient->height;
                                    $feet = floor($height/12);$inches = ($height%12);
                                ?>
                    <p style="overflow: auto;"><label class="col-lg-4 col-md-6 col-sm-6 col-xs-12" style="margin-bottom: 0px;padding-left: 0px;">Height</label> <?php echo $feet."'";?> <?php if($inches > 0) echo $inches.'"';?></p>
                    <?php $age = ($patient->dob)? date_diff(date_create($patient->dob), date_create('now'))->y:0; ?>
                    <p style="overflow: auto;"><label class="col-lg-4 col-md-6 col-sm-6 col-xs-12" style="margin-bottom: 0px;padding-left: 0px;">Age</label> <?php echo $age;?></p>
                    <p style="overflow: auto;"><label class="col-lg-4 col-md-6 col-sm-6 col-xs-12" style="margin-bottom: 0px;padding-left: 0px;"></label><span class="badge" style="font-size: 15px;"><?php echo $patient->gender==1? 'M':'F';?></span></p>
            
                </div>
<!--                <h3 style="margin-top: 0px;"><?php echo $patient->lname." ".$patient->fname;?></h3>
                <?php $age = ($patient->dob)? date_diff(date_create($patient->dob), date_create('now'))->y:0; ?>
                <h4 style="width: 20%;float: left;margin-top: 0px;"><span class="label label-success"  >Weekly</span> </h4><?php if($age > 1){?><h4 style="float: left;width: 80%;margin-top: 0px;"> <?php echo $age;?> Years</h4><?php } ?>
                
                <?php $height = $patient->height;
                $feet = floor($height/12);$inches = ($height%12);
                ?>
                <br>
                <h4  style="width: 20%;float: left;"><?php echo $feet;?>" <?php if($inches > 0) echo $inches."'";?></h4>  <h4 style="float: left;width: 80%;"><span class="badge" style="font-size: 15px;"><?php echo $patient->gender==1? 'M':'F';?></span></h4>
               -->
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"  style="font-size: 18px;padding-left: 0px;">
                <p style="overflow: auto;"><label class="col-lg-6 col-sm-6 col-xs-12" style="margin-bottom: 0px;padding-left: 0px;">Initial weight</label> <?php echo isset($first_visit)?$first_visit->weight:'-';?></p>
                
                <p style="overflow: auto;"><label class="col-lg-6 col-sm-6 col-xs-12" style="margin-bottom: 0px;padding-left: 0px;">Goal weight</label> <?php echo $patient->goal_weight;?></p>
                <p style="overflow: auto;"><label class="col-lg-6 col-sm-6 col-xs-12" style="margin-bottom: 0px;padding-left: 0px;">Last 2 visit</label> <?php echo ($last_visit)? ($this_visit->weight - $last_visit->weight): "-";  ?></p>
                <p style="overflow: auto;"><label class="col-lg-6 col-sm-6 col-xs-12" style="margin-bottom: 0px;padding-left: 0px;">Today's Date</label> <?php echo date('m/d/Y');  ?></p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"  style="font-size: 18px;padding-left: 0px;">
                <table  class="align-left" style="font-size: 18px;width: 100%;">
                          <tr style="height: 32px;">
                              <td class="bold aright" style="text-align: right;padding-right: 10px;width: 50%">Today's Wt</td>
                              <td class="aleft" style="padding-left: 5px;width: 50%;border: 1px solid black;"><?php echo $this_visit->weight;?></td>
                          </tr>
                          <tr style="height: 32px;">
                              <td class="bold"  style="text-align: right;padding-right: 10px;">Today's BMI%</td>
                              <td class="aleft" style="padding-left: 5px;border: 1px solid black;"><?php echo $this_visit->bmi;?></td>
                          </tr>
                          <tr style="height: 32px;">
                              <td class="bold"  style="text-align: right;padding-right: 10px;">Today's BFI%</td>
                              <td class="aleft" style="padding-left: 5px;border: 1px solid black;"><?php echo $this_visit->bfi;?></td>
                          </tr>
                          <tr style="height: 32px;">
                              <td class="bold"  style="text-align: right;padding-right: 10px;">Today's BP</td>
                              <td class="aleft" style="padding-left: 5px;border: 1px solid black;"><?php echo $this_visit->bp;?></td>
                          </tr>
                      </table>
            </div>
            <!--<a href="<?php echo site_url("patient/edit/$patient->id");?>" class="btn btn-default " title="edit"><span class="glyphicon glyphicon-edit"></a>-->
        </div>
    </div>

</div>

<div class="row">
    
	<div class="card">
	    
            <div class="card-body" style="padding: 15px;">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <h4 style="font-weight: bold;margin-top: 0px;">Allergies:</h4>
                    <p style="margin-bottom: 0px;"><?php echo $patient->allergies;?></p>
                </div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <h4 style="font-weight: bold;margin-top: 0px;">Current Medications:</h4>
                    <p style="margin-bottom: 0px;"><?php echo $patient->previous_medication;?></p>
                </div>
	    </div>
	</div>
    
</div>
    
    <div class="row" style="margin-top: 10px;">
    <?php echo validation_errors('<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;margin-bottom:0px;"><div role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>'); ?>
</div>

<div class="row" style="margin-top: 10px;">    
	<div class="card">	    
            <div class="card-body" style="">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <?php if($last_visit){?>
                    <div class="panel panel-default">
                        <div class="panel-heading" style="font-size: 20px;text-align: center;">LAST VISIT</div>
                        <div class="panel-body" style="padding-left: 50px;background-color: #fafafa;">
                            
                            <h4 style="margin-top:  0px;margin-left: -35px;">Recommendations:</h4>
                            <div class="checkbox3 checkbox-round">
                                <?php $last_change_meds = ($last_visit->change_meds)? explode(',', $last_visit->change_meds):array(); ?>
                                <input type="checkbox" id="last_change_meds"  <?php echo set_checkbox('change_meds',1,  count($last_change_meds)>0);?> disabled="disabled">
                                <label for="last_change_meds">
                                  Change Meds:
                                </label>
                                <div style="margin-left: 60px;">
                                          <div class="checkbox3 checkbox-inline checkbox-check checkbox-light">
                                            <input type="checkbox"  id="last_37.5"  <?php echo set_checkbox('change_meds_options[]',37.5, in_array('37.5', $last_change_meds));?> disabled="disabled">
                                            <label for="last_37.5">
                                              37.5
                                            </label>
                                          </div>
                                          <div class="checkbox3 checkbox-inline checkbox-check checkbox-light">
                                            <input type="checkbox"  id="last_30"   <?php echo set_checkbox('change_meds_options[]',30, in_array('30', $last_change_meds));?> disabled="disabled">
                                            <label for="last_30">
                                              Option1
                                            </label>
                                          </div>
                                        <div class="checkbox3 checkbox-inline checkbox-check checkbox-light">
                                            <input type="checkbox"  id="last_15" <?php echo set_checkbox('change_meds_options[]',15, in_array('15', $last_change_meds));?> disabled="disabled">
                                            <label for="last_15">
                                              15
                                            </label>
                                          </div>
                                        <div class="checkbox3 checkbox-inline checkbox-check checkbox-light">
                                            <input type="checkbox"  id="last_di" <?php echo set_checkbox('change_meds_options[]','di', in_array('di', $last_change_meds));?> disabled="disabled">
                                            <label for="last_di">
                                              DI
                                            </label>
                                          </div>
                                        </div>
                            </div>
                            <div class="checkbox3 checkbox-round">
                                <input type="checkbox" id="last_change_meds_status" disabled="disabled" <?php echo set_checkbox('change_meds_status',1,$last_visit->change_meds_status!=NULL);?>>
                                <label for="last_change_meds_status">
                                  Change Meds Status:
                                </label>
                                <div style="margin-left: 60px;">
                                          <div class="radio3 radio-check radio-inline">
                                            <input type="radio"  value="0" disabled="disabled" id="last_cms_yes" name="change_meds_status_options" <?php echo set_radio('change_meds_status_options',0,$last_visit->change_meds_status== 0);?>>
                                            <label for="last_cms_yes">
                                              No Meds
                                            </label>
                                          </div>
                                          <div class="radio3 radio-check radio-inline">
                                              <input type="radio"  value="1" disabled="disabled" id="last_cms_no" name="change_meds_status_options" <?php echo set_radio('change_meds_status_options',1,$last_visit->change_meds_status == 1 );?>>
                                            <label for="last_cms_no">
                                              Meds
                                            </label>
                                          </div>
                                        
                                </div>
                            </div>
                            <div class="checkbox3 checkbox-round">
                                <input type="checkbox" id="last_wld" disabled="disabled" <?php echo set_checkbox('wld',1,$last_visit->wld > 0);?>>
                                <label for="last_wld">
                                  Weight Loss Duration (Months):
                                </label>
                                <div style="margin-left: 60px;">
                                          <div class="checkbox3 checkbox-inline checkbox-check checkbox-light">
                                            <input type="checkbox"  id="last_1" disabled="disabled" <?php echo set_radio('wld_options',1,$last_visit->wld == 1);?>>
                                            <label for="last_1">
                                              1
                                            </label>
                                          </div>
                                          <div class="checkbox3 checkbox-inline checkbox-check checkbox-light">
                                            <input type="checkbox"  id="last_2" disabled="disabled" <?php echo set_radio('wld_options',2,$last_visit->wld == 2);?>>
                                            <label for="last_2">
                                              2
                                            </label>
                                          </div>
                                        <div class="checkbox3 checkbox-inline checkbox-check checkbox-light">
                                            <input type="checkbox"  id="last_3" disabled="disabled" <?php echo set_radio('wld_options',3,$last_visit->wld == 3);?>>
                                            <label for="last_3">
                                              3
                                            </label>
                                          </div>
                                </div>
                            </div>
                            <div class="checkbox3 checkbox-round">
                                <input type="checkbox" id="last_bpnc" disabled="disabled" <?php echo set_radio('bp_not_controlled',1,$last_visit->bp_not_controlled == 1);?>>
                                <label for="last_bpnc">
                                  BP not controlled
                                </label>
                            </div>
                            <div class="checkbox3 checkbox-round">
                                <input type="checkbox" id="last_gbpl" disabled="disabled" <?php echo set_radio('give_blood_pressure_log',1,$last_visit->give_blood_pressure_log == 1);?>>
                                <label for="last_gbpl">
                                  Give Blood Pressure Log
                                </label>
                            </div>
                           <div class="checkbox3 checkbox-round">
                                <input type="checkbox" id="last_ne" disabled="disabled" <?php echo set_radio('new_ekg',1,$last_visit->new_ekg == 1);?>>
                                <label for="last_ne">
                                  New EKG
                                </label>
                            </div>
                            <div class="checkbox3 checkbox-round">
                                <input type="checkbox" id="last_nbw" disabled="disabled" <?php echo set_radio('new_blood_work',1,$last_visit->new_blood_work == 1);?>>
                                <label for="last_nbw">
                                  New Blood Work
                                </label>
                            </div>
                            <div class="checkbox3 checkbox-round">
                                <input type="checkbox" id="last_sypcp" disabled="disabled" <?php echo set_radio('see_your_primary_care_physician',1,$last_visit->see_your_primary_care_physician == 1);?>>
                                <label for="last_sypcp">
                                  See your Primary Care Physician
                                </label>
                            </div>
                            <div class="checkbox3 checkbox-round">
                                <input type="checkbox" id="last_alw" disabled="disabled" <?php echo set_radio('abnormal_lab_work',1,$last_visit->abnormal_lab_work == 1);?>>
                                <label for="last_alw">
                                  Abnormal Lab Work
                                </label>
                            </div>
                            <div class="checkbox3 checkbox-round">
                                <input type="checkbox" id="last_ststd" disabled="disabled" <?php echo set_radio('clearance_letter',1,$last_visit->clearance_letter == 1);?>>
                                <label for="last_ststd">
                                  Shedule to see the DR
                                </label>
                            </div>
                            <div class="checkbox3 checkbox-round">
                                <input type="checkbox" id="last_cl" disabled="disabled" <?php echo set_radio('clearance_letter',1,$last_visit->clearance_letter == 1);?>>
                                <label for="last_cl">
                                  Clearance Letter
                                </label>
                            </div>
                            <div class="checkbox3 checkbox-round">
                                <input type="checkbox" id="last_rdocm" disabled="disabled" <?php echo set_radio('reduce_dosage_of_current_medication',1,$last_visit->reduce_dosage_of_current_medication == 1);?>>
                                <label for="last_rdocm">
                                  Reduce dosage of current medication
                                </label>
                            </div>
                            
                      </div>
                </div>
                    
                <div class="panel panel-default">
                    <div class="panel-body" style="background-color: #fafafa;">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Assessment & Plan</label>
                            <textarea class="form-control" disabled="disabled"><?php echo set_value('assessment_and_plan',$last_visit->assessment_and_plan);?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Meds OK to continue</label>                            
                            <div>
                                    <div class="radio3 radio-check radio-inline">
                                      <input type="radio"  disabled="disabled" value="0"  id="last_med_yes" <?php echo set_radio('meds_ok_to_continue',0,$last_visit->meds_ok_to_continue == 0);?>>
                                      <label for="last_med_yes">
                                        No Meds
                                      </label>
                                    </div>
                                    <div class="radio3 radio-check radio-inline">
                                      <input type="radio"  disabled="disabled" value="1"  id="last_med_no" <?php echo set_radio('meds_ok_to_continue',1,$last_visit->meds_ok_to_continue == 1);?>>
                                      <label for="last_med_no">
                                        Meds
                                      </label>
                                    </div>

                          </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Alerts</label>
                            <textarea disabled="disabled" class="form-control"><?php echo set_value('alerts',$last_visit->alerts);?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Other Instructions</label>
                            <textarea disabled="disabled" class="form-control"><?php echo set_value('other_instructions',$last_visit->other_instructions);?></textarea>
                        </div>
                    </div>
                </div>
                   <?php }else{?>
                <div class="panel panel-default">
                        <div class="panel-heading" style="font-size: 20px;text-align: center;">NO LAST VISIT</div>
                </div>
                <?php } ?> 
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <form method="POST">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="font-size: 20px;text-align: center;">THIS VISIT</div>
                        <div class="panel-body"  style="padding-left: 50px;">
                          <h4 style="margin-top: 0px;margin-left: -35px;">Recommendations:</h4>
                            <div class="checkbox3 checkbox-round">
                                <?php $this_change_meds = ($this_visit->change_meds)? explode(',', $this_visit->change_meds):array(); ?>
                                <input type="checkbox" id="this_change_meds" name="change_meds" <?php echo set_checkbox('change_meds',1,  count($this_change_meds)>0);?>>
                                <label for="this_change_meds">
                                  Change Meds:
                                </label>
                                <div style="margin-left: 60px;">
                                          <div class="checkbox3 checkbox-inline checkbox-check checkbox-light">
                                              <input type="checkbox" name="change_meds_options[]" value="37.5" id="this_37.5" <?php echo set_checkbox('change_meds_options[]',37.5, in_array('37.5', $this_change_meds));?> >
                                            <label for="this_37.5">
                                              37.5
                                            </label>
                                          </div>
                                          <div class="checkbox3 checkbox-inline checkbox-check checkbox-light">
                                              <input type="checkbox" name="change_meds_options[]" value="30" id="this_30" <?php echo set_checkbox('change_meds_options[]',30, in_array('30', $this_change_meds));?> >
                                            <label for="this_30">
                                              30
                                            </label>
                                          </div>
                                        <div class="checkbox3 checkbox-inline checkbox-check checkbox-light">
                                            <input type="checkbox" name="change_meds_options[]" value="15" id="this_15" <?php echo set_checkbox('change_meds_options[]',15, in_array('15', $this_change_meds));?> >
                                            <label for="this_15">
                                              15
                                            </label>
                                          </div>
                                        <div class="checkbox3 checkbox-inline checkbox-check checkbox-light">
                                            <input type="checkbox" name="change_meds_options[]" value="di" id="this_di" <?php echo set_checkbox('change_meds_options[]','di', in_array('di', $this_change_meds));?> >
                                            <label for="this_di">
                                              DI
                                            </label>
                                          </div>
                                        </div>
                            </div>
                            <div class="checkbox3 checkbox-round">
                                <input type="checkbox" value="1" id="this_change_meds_status" name="change_meds_status" <?php echo set_checkbox('change_meds_status',1,$this_visit->change_meds_status!=NULL);?>>
                                <label for="this_change_meds_status">
                                  Change Meds Status:
                                </label>
                                <div style="margin-left: 60px;">
                                          <div class="radio3 radio-check radio-inline">
                                            <input type="radio"  value="0" name="change_meds_status_options" id="this_cms_yes" <?php echo set_radio('change_meds_status_options',0,$this_visit->change_meds_status == 0);?>>
                                            <label for="this_cms_yes">
                                              No Meds
                                            </label>
                                          </div>
                                          <div class="radio3 radio-check radio-inline">
                                            <input type="radio"  value="1" name="change_meds_status_options" id="this_cms_no" <?php echo set_radio('change_meds_status_options',1,$this_visit->change_meds_status==1);?>>
                                            <label for="this_cms_no">
                                              Meds
                                            </label>
                                          </div>
                                        
                                </div>
                            </div>
                            <div class="checkbox3 checkbox-round">
                                <input type="checkbox" value="1" id="this_wld" name="wld" <?php echo set_checkbox('wld',1,$this_visit->wld > 0);?> >
                                <label for="this_wld">
                                  Weight Loss Duration (Months):
                                </label>
                                <div style="margin-left: 60px;">
                                        <div class="radio3 radio-check radio-inline">
                                            <input type="radio"  value="1" name="wld_options" id="this_wld_1" <?php echo set_radio('wld_options',1,$this_visit->wld == 1);?>>
                                            <label for="this_wld_1">
                                              1
                                            </label>
                                        </div>
                                        <div class="radio3 radio-check radio-inline">
                                            <input type="radio"  value="2" name="wld_options" id="this_wld_2" <?php echo set_radio('wld_options',2,$this_visit->wld == 2);?>>
                                            <label for="this_wld_2">
                                              2
                                            </label>
                                        </div>
                                        <div class="radio3 radio-check radio-inline">
                                            <input type="radio"  value="3" name="wld_options" id="this_wld_3" <?php echo set_radio('wld_options',3,$this_visit->wld == 3);?>>
                                            <label for="this_wld_3">
                                              3
                                            </label>
                                        </div>

                                </div>
                            </div>
                            <div class="checkbox3 checkbox-round">
                                <input type="checkbox" id="this_bpnc" name="bp_not_controlled" value="1" <?php echo set_radio('bp_not_controlled',1,$this_visit->bp_not_controlled == 1);?>>
                                <label for="this_bpnc">
                                  BP not controlled
                                </label>
                            </div>
                            <div class="checkbox3 checkbox-round">
                                <input type="checkbox" id="this_gbpl" name="give_blood_pressure_log" value="1" <?php echo set_radio('give_blood_pressure_log',1,$this_visit->give_blood_pressure_log == 1);?> >
                                <label for="this_gbpl">
                                  Give Blood Pressure Log
                                </label>
                            </div>
                           <div class="checkbox3 checkbox-round">
                               <input type="checkbox" id="this_ne" name="new_ekg"  value="1" <?php echo set_radio('new_ekg',1,$this_visit->new_ekg == 1);?> >
                                <label for="this_ne">
                                  New EKG
                                </label>
                            </div>
                            <div class="checkbox3 checkbox-round">
                                <input type="checkbox" id="this_nbw" name="new_blood_work"  value="1" <?php echo set_radio('new_blood_work',1,$this_visit->new_blood_work == 1);?> >
                                <label for="this_nbw">
                                  New Blood Work
                                </label>
                            </div>
                            <div class="checkbox3 checkbox-round">
                                <input type="checkbox" id="this_sypcp" name="see_your_primary_care_physician" value="1" <?php echo set_radio('see_your_primary_care_physician',1,$this_visit->see_your_primary_care_physician == 1);?> >
                                <label for="this_sypcp">
                                  See your Primary Care Physician
                                </label>
                            </div>
                            <div class="checkbox3 checkbox-round">
                                <input type="checkbox" id="this_alw" name="abnormal_lab_work"  value="1" <?php echo set_radio('abnormal_lab_work',1,$this_visit->abnormal_lab_work == 1);?>>
                                <label for="this_alw">
                                  Abnormal Lab Work
                                </label>
                            </div>
                            <div class="checkbox3 checkbox-round">
                                <input type="checkbox" id="this_ststd" name="schedule_to_see_the_dr"  value="1" <?php echo set_radio('schedule_to_see_the_dr',1,$this_visit->schedule_to_see_the_dr == 1);?>>
                                <label for="this_ststd">
                                  Schedule to see the DR
                                </label>
                            </div>
                            <div class="checkbox3 checkbox-round">
                                <input type="checkbox" id="this_cl" name="clearance_letter"  value="1" <?php echo set_radio('clearance_letter',1,$this_visit->clearance_letter == 1);?>>
                                <label for="this_cl">
                                  Clearance Letter
                                </label>
                            </div>
                            <div class="checkbox3 checkbox-round">
                                <input type="checkbox" id="this_rdocm"  name="reduce_dosage_of_current_medication"  value="1" <?php echo set_radio('reduce_dosage_of_current_medication',1,$this_visit->reduce_dosage_of_current_medication == 1);?>>
                                <label for="this_rdocm">
                                  Reduce dosage of current medication
                                </label>
                            </div>
                        </div>
                      </div>
                    <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Assessment & Plan</label>
                            <textarea class="form-control" name="assessment_and_plan"><?php echo set_value('assessment_and_plan',$this_visit->assessment_and_plan);?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Meds OK to continue</label>                            
                            <div>
                                <div class="radio3 radio-check radio-inline">
                                  <input type="radio"  value="0" name="meds_ok_to_continue" <?php echo set_radio('meds_ok_to_continue',0,$this_visit->meds_ok_to_continue == 0);?> id="this_med_yes">
                                  <label for="this_med_yes">
                                    No Meds
                                  </label>
                                </div>
                                <div class="radio3 radio-check radio-inline">
                                  <input type="radio"  value="1" name="meds_ok_to_continue" <?php echo set_checkbox('meds_ok_to_continue',1,$this_visit->meds_ok_to_continue == 1);?> id="this_med_no">
                                  <label for="this_med_no">
                                    Meds
                                  </label>
                                </div>
                          </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Alerts</label>
                            <textarea class="form-control" name="alerts"><?php echo set_value('alerts',$this_visit->alerts);?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Other Instructions</label>
                            <textarea class="form-control" name="other_instructions"><?php echo set_value('other_instructions',$this_visit->other_instructions);?></textarea>
                        </div>
                    </div>
                </div>
                    <div class="col-lg-12 col-xs-12">
                        <button class="btn btn-success pull-right btn-lg" type="submit" >Save</button>
                    </div>
                    </form>
                </div>
               
            </div>
        </div>            
</div>
  
<script type="text/javascript">
    $(function(){
        
        if($('#this_change_meds').is(":checked"))
        {
            $('input[name="change_meds_options[]"]').removeAttr("disabled");
        }
        else
        {
            $('input[name="change_meds_options[]"]').attr("disabled", true);
                $('input[name="change_meds_options[]"]').attr('checked',false);
        }
        
        $('#this_change_meds').on('change',function(){
            if($(this).is(":checked")) 
            {
                $('input[name="change_meds_options[]"]').removeAttr("disabled");
            }
            else
            {
                $('input[name="change_meds_options[]"]').attr("disabled", true);
                $('input[name="change_meds_options[]"]').attr('checked',false);
            }
        });
    });
</script>
<?php $this->load->view('template/footer');?>
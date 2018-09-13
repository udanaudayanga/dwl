<!DOCTYPE html>
<html lang="en-US">
    <head> 
        <link rel="stylesheet" type="text/css" href="/assets/css/doc_note.css">
  </head>
    <body>
      <div id="container" style="margin: 0px;">
          <table style="width: 100%;">
              <tr>
                  <td style="width: 45%;padding-right: 10px;border-right: 1px solid #ccc;vertical-align: top;">
                      <table>
                          <tr>
                              <td style="width: 40%;text-align: left;">                                  
                                  <img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/upload/patients/<?php echo $patient->photo;?>&amp;w=64&amp;h=54&amp;zc=1&amp;f=png" />
                              </td>
                              <td style="width: 60%;vertical-align: top;padding-top: 0px;padding-left: 10px;">
                                <table class="align-left" style="font-size: 20px;">
                                      <tr>
                                          <td style="height: 45px;">
                                              <h4 style="font-size: 23px;text-align: left;padding: 0px;margin: 0px;"><?php echo $patient->lname." ".$patient->fname;?></h4>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td style="height: 45px;">
                                               
                                                <?php if($last_visit && $last_visit->is_med == 1){?>
                                                <h4 style="font-size: 20px;text-align: left;padding: 0px;margin: 0px;font-weight: normal;">&#8864;  MEDS &nbsp;  <?php echo ($last_visit->med3)? getProduct($last_visit->med3)->name : ($last_visit->med1?getProduct($last_visit->med1)->name:"-")." / ".($last_visit->med2?getProduct($last_visit->med2)->name:"-");?></h4>
                                                <?php }elseif($last_visit && $last_visit->is_med == 0){?>

                                                <?php } ?>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td style="height: 45px;">
                                              &nbsp;
                                          </td>
                                      </tr>
                                      <tr>
                                          <td style="height: 45px;">
                                              <?php if($last_visit){
                                                  $days = ($last_visit->is_med == 1)? $last_visit->med_days : $last_visit->no_med_days;
                                                  $diff = strtotime($this_visit->visit_date) - strtotime($last_visit->visit_date);
                                                  $diff = floor($diff/(60*60*24));
                                                  $diff = $diff - $days;
                                              ?>
                                              
                                              <table style="width: 185px;">
                                                  <tr>
                                                      <td style="padding: 6px;border: 1px solid black;">
                                                          <h4  style="font-size: 20px;text-align: left;padding: 0px;margin: 10px;overflow: auto;">Coming After: <?php echo $diff;?> days</h4>
                                                      </td>
                                                  </tr>
                                              </table>
                                                  
                                              
                                              <?php } ?>
                                          </td>
                                      </tr>
                                  </table>
                              </td>
                          </tr>
                      </table>
                  </td>
                  <td style="width: 15%;vertical-align: top;padding-left: 10px;border-right: 1px solid #ccc;">
                      <table class="align-left" style="font-size: 20px;">
                          <tr class="">
                              <td class="bold" style="height: 45px;">Ht</td>
                                <?php $height = $patient->height;
                                    $feet = floor($height/12);$inches = ($height%12);
                                ?>
                              <td style="height: 45px;"><?php echo $feet."'";?> <?php if($inches > 0) echo $inches.'"';?></td>
                          </tr>
                          <tr class="">
                              <td class="bold" style="height: 45px;">Age</td>
                              <?php $age = ($patient->dob)? date_diff(date_create($patient->dob), date_create('now'))->y:0; ?>
                              <td style="height: 45px;"><?php echo $age;?></td>
                          </tr>
                          <tr class="">
                              <td class="bold" style="height: 45px;"><?php echo $patient->gender==1? 'M':'F';?></td>
                              <td style="height: 45px;"></td>
                          </tr>
                      </table>
                  </td>
                  <td style="width: 20%;vertical-align: top;padding-left: 10px;">
                      <table class="align-left" style="font-size: 20px;">
                          <tr class="">
                              <td class="bold" style="height: 45px;">Initial Wt</td>
                              <td style="height: 45px;"><?php echo (isset($first_visit))?$first_visit->weight:"-";?></td>
                          </tr>
                          <tr class="">
                              <td class="bold" style="height: 45px;">GOAL Wt</td>
                              <td style="height: 45px;"><?php echo $patient->goal_weight;?></td>
                          </tr>
                          <tr class="">
                              <td class="bold" style="height: 45px;">Last 2 Visit</td>
                              <td style="height: 45px;"><?php echo ($last_visit)? ($this_visit->weight - $last_visit->weight): "-";  ?></td>
                          </tr>
                          <tr class="">
                              <td class="bold" style="height: 45px;">Today's Date</td>
                              <td style="height: 45px;"><?php echo date('m/d/Y');?></td>
                          </tr>
                      </table>
                  </td>
                  <td style="width: 20%;vertical-align: top;">
                      <table  class="align-left fiftyh" style="font-size: 20px;">
                          <tr class="fortyfive">
                              <td class="bold aright" style="text-align: right;padding-right: 10px;width: 50%">Today's Wt</td>
                              <td class="aleft" style="padding-left: 5px;width: 50%;border: 1px solid black;"><?php echo $this_visit->weight;?></td>
                          </tr>
                          <tr class="fortyfive">
                              <td class="bold"  style="text-align: right;padding-right: 10px;">Today's BMI%</td>
                              <td class="aleft" style="padding-left: 5px;border: 1px solid black;"><?php echo $this_visit->bmi;?></td>
                          </tr>
                          <tr class="fortyfive">
                              <td class="bold"  style="text-align: right;padding-right: 10px;">Today's BFI%</td>
                              <td class="aleft" style="padding-left: 5px;border: 1px solid black;"><?php echo $this_visit->bfi;?></td>
                          </tr>
                          <tr class="fortyfive">
                              <td class="bold"  style="text-align: right;padding-right: 10px;">Today's BP</td>
                              <td class="aleft" style="padding-left: 5px;border: 1px solid black;"><?php echo $this_visit->bp;?></td>
                          </tr>
                      </table>
                  </td>
              </tr>
          </table>
          <table style="width: 100%;margin-top: 20px;">
              <tr>
                  <td style="border: 1px solid #666;width: 50%;padding: 10px;text-align: left;vertical-align: top;">
                      <h4 style="font-weight: bold;margin-top: 0px;">Allergies:</h4>
                    <p style="margin-bottom: 0px;"><?php echo $patient->allergies;?></p>
                  </td>
                   <td style="border: 1px solid #666;width: 50%;padding: 10px;text-align: left;vertical-align: top;">
                      <h4 style="font-weight: bold;margin-top: 0px;">Current Medications:</h4>
                    <p style="margin-bottom: 0px;"><?php echo $patient->previous_medication;?></p>
                  </td>
              </tr>
          </table>
          <table style="width: 100%;margin-top: 20px;" class="recommendations">
              <tr>
                  <td style="border: 1px solid #666;width: 50%;text-align: left;">
                      <table class="last_visit" style="width: 100%;">
                          <?php if($last_visit){?>
                          <tr><td colspan="2" style="text-align: center;font-weight: bold;border-bottom: 1px solid #666;">LAST VISIT</td></tr>
                          <tr><td colspan="2" style="text-align: center;font-weight: bold;text-align: left;">Recommendations:</td></tr>
                          <tr><td style="width: 10%;"><?php if($last_visit->change_meds){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">Change Meds:&nbsp;&nbsp;<span style="font-weight: bold;"><?php echo ($last_visit->change_meds)?str_replace(",",", ",$last_visit->change_meds):"";?></span></td></tr>
                          <tr><td style="width: 10%;"><?php if($last_visit->change_meds_status){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">Change Meds Status:&nbsp;&nbsp;<span style="font-weight: bold;"><?php echo ($last_visit->change_meds_status == 1)?"YES":"NO";?></span></td></tr>
                          <tr><td style="width: 10%;"><?php if($last_visit->wld > 0){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">Weight Loss Duration:&nbsp;&nbsp;<span style="font-weight: bold;"><?php echo ($last_visit->wld > 0)? $last_visit->wld." Months":"";?></span></td></tr>
                          <tr><td style="width: 10%;"><?php if($last_visit->bp_not_controlled == 1){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">BP not controlled</td></tr>
                          <tr><td style="width: 10%;"><?php if($last_visit->give_blood_pressure_log == 1){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">Give Blood Pressure Log</td></tr>
                          <tr><td style="width: 10%;"><?php if($last_visit->new_ekg == 1){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">New EKG</td></tr>
                          <tr><td style="width: 10%;"><?php if($last_visit->new_blood_work == 1){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">New Blood Work</td></tr>
                          <tr><td style="width: 10%;"><?php if($last_visit->see_your_primary_care_physician == 1){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">See your Primary Care Physician</td></tr>
                          <tr><td style="width: 10%;"><?php if($last_visit->abnormal_lab_work == 1){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">Abnormal Lab Work</td></tr>
                          <tr><td style="width: 10%;"><?php if($last_visit->schedule_to_see_the_dr == 1){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">Schedule to see the DR</td></tr>
                          <tr><td style="width: 10%;"><?php if($last_visit->clearance_letter == 1){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">Clearence Letter</td></tr>
                          <tr><td style="width: 10%;"><?php if($last_visit->reduce_dosage_of_current_medication == 1){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">Reduce dosage of current medication</td></tr>
                          <?php }else{?>
                          <tr><td colspan="2" style="text-align: center;font-weight: bold;">NO LAST VISIT</td></tr>
                          <?php } ?>
                      </table>
                  </td>
                   <td style="border: 1px solid #666;width: 50%;text-align: left;">
                       <table class="this_visit" style="width: 100%;">
                          <tr><td colspan="2" style="text-align: center;font-weight: bold;border-bottom: 1px solid #666;">THIS VISIT</td></tr>
                          <tr><td colspan="2" style="text-align: center;font-weight: bold;text-align: left;">Recommendations:</td></tr>
                          <tr><td style="width: 10%;"><?php if($this_visit->change_meds){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">Change Meds:&nbsp;&nbsp;<span style="font-weight: bold;"><?php echo ($this_visit->change_meds)?str_replace(",",", ",$this_visit->change_meds):"";?></span></td></tr>
                          <tr><td style="width: 10%;"><?php if($this_visit->change_meds_status){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">Change Meds Status:&nbsp;&nbsp;<span style="font-weight: bold;"><?php echo ($this_visit->change_meds_status == 1)?"YES":"NO";?></span></td></tr>
                          <tr><td style="width: 10%;"><?php if($this_visit->wld > 0){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">Weight Loss Duration:&nbsp;&nbsp;<span style="font-weight: bold;"><?php echo ($this_visit->wld > 0)? $this_visit->wld." Months":"";?></span></td></tr>
                          <tr><td style="width: 10%;"><?php if($this_visit->bp_not_controlled == 1){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">BP not controlled</td></tr>
                          <tr><td style="width: 10%;"><?php if($this_visit->give_blood_pressure_log == 1){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">Give Blood Pressure Log</td></tr>
                          <tr><td style="width: 10%;"><?php if($this_visit->new_ekg == 1){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">New EKG</td></tr>
                          <tr><td style="width: 10%;"><?php if($this_visit->new_blood_work == 1){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">New Blood Work</td></tr>
                          <tr><td style="width: 10%;"><?php if($this_visit->see_your_primary_care_physician == 1){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">See your Primary Care Physician</td></tr>
                          <tr><td style="width: 10%;"><?php if($this_visit->abnormal_lab_work == 1){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">Abnormal Lab Work</td></tr>
                          <tr><td style="width: 10%;"><?php if($this_visit->schedule_to_see_the_dr == 1){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">Schedule to see the DR</td></tr>
                          <tr><td style="width: 10%;"><?php if($this_visit->clearance_letter == 1){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">Clearence Letter</td></tr>
                          <tr><td style="width: 10%;"><?php if($this_visit->reduce_dosage_of_current_medication == 1){?><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/check_mark.png&amp;w=28&amp;h=28&amp;zc=1&amp;f=png" /><?php } ?></td><td class="rec_field">Reduce dosage of current medication</td></tr>
                      </table>
                  </td>
              </tr>
          </table>
          
          <table style="width: 100%;margin-top: 20px;" class="dr_note_tbl">
              <tr>
                  <td style="border: 1px solid #666;width: 50%;text-align: left;vertical-align: top;">
                      <?php if($last_visit){?>
                      <table style="width: 100%;">
                          <tr><td>
                              <h4 style="font-weight: bold;margin-top: 0px;">Assessment & Plan:</h4>
                            <p style="margin-bottom: 0px;"><?php echo $last_visit->assessment_and_plan;?></p>
                            </td></tr>
                          <tr><td>
                                  <h4 style="font-weight: bold;margin-top: 0px;">Meds OK to Continue:&nbsp;&nbsp;<?php echo ($last_visit->meds_ok_to_continue==1)?"YES":"NO";?></h4>
                            </td></tr>
                          <tr><td>
                              <h4 style="font-weight: bold;margin-top: 0px;">Alerts:</h4>
                            <p style="margin-bottom: 0px;"><?php echo $last_visit->alerts;?></p>
                            </td></tr>
                          <tr><td>
                              <h4 style="font-weight: bold;margin-top: 0px;">Other Instructions:</h4>
                            <p style="margin-bottom: 0px;"><?php echo $last_visit->other_instructions;?></p>
                            </td></tr>
                      </table>
                      <?php } ?>
                  </td>
                  <td style="border: 1px solid #666;width: 50%;text-align: left;vertical-align: top;">
                      <table style="width: 100%;">
                          <tr><td>
                              <h4 style="font-weight: bold;margin-top: 0px;">Assessment & Plan:</h4>
                            <p style="margin-bottom: 0px;"><?php echo $this_visit->assessment_and_plan;?></p>
                            </td></tr>
                          <tr><td>
                                  <h4 style="font-weight: bold;margin-top: 0px;">Meds OK to Continue:&nbsp;&nbsp;<?php echo ($this_visit->meds_ok_to_continue==1)?"YES":"NO";?></h4>
                            </td></tr>
                          <tr><td>
                              <h4 style="font-weight: bold;margin-top: 0px;">Alerts:</h4>
                            <p style="margin-bottom: 0px;"><?php echo $this_visit->alerts;?></p>
                            </td></tr>
                          <tr><td>
                              <h4 style="font-weight: bold;margin-top: 0px;">Other Instructions:</h4>
                            <p style="margin-bottom: 0px;"><?php echo $this_visit->other_instructions;?></p>
                            </td></tr>
                      </table>
                  </td>
              </tr>
          </table>
          <table style="width: 100%;margin-top: 30px;" >
              <tr>
                  <td style="width: 40%;">&nbsp;</td>
                  <td style="width: 40%;text-align: left;">Medical Director Signature</td>
                  <td style="width: 20%;text-align: left;">Date:</td>
              </tr>
          </table>
      </div>
</body>
</html>
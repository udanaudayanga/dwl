<!DOCTYPE html>
<html lang="en-US">
    <head>    
  <link rel="stylesheet" type="text/css" href="/assets/css/ticket.css">
  </head>
    <body>
      <div id="container" style="margin: 0px;padding-top: 40px;">
          <table style="width: 100%;">
              <tr>
                  <td style="width: 45%;padding-right: 10px;border-right: 1px solid #ccc;vertical-align: top;">
                      <table>
                          <tr>
                              <td style="width: 40%;text-align: left;">     
                                  <?php if(file_exists("./assets/upload/patients/$patient->photo")){?>
                                  <img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/upload/patients/<?php echo $patient->photo;?>&amp;w=64&amp;h=54&amp;zc=1&amp;f=png" />
                                  <?php }else{ 
                                      $gender_img = $patient->gender == 1 ? "male.png":"female.png";
                                  ?>                                  
                                  <img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/<?php echo $gender_img;?>&amp;w=190&amp;h=190&amp;zc=1" />
                                  <?php } ?>
                              </td>
                              <td style="width: 60%;vertical-align: top;padding-top: 0px;padding-left: 10px;">
                                  <table class="align-left" style="font-size: 20px;">
                                      <tr>
                                          <td style="height: 35px;">
                                              <h4 style="font-size: 24px;text-align: left;padding: 0px;margin: 0px;"><?php echo $patient->lname." ".$patient->fname;?></h4>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td style="height: 35px;">
                                              <h4  style="font-size: 20px;text-align: left;padding: 0px;margin: 10px;overflow: auto;font-weight: normal;">Location: <?php echo $order_location->abbr;?></h4>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td style="height: 35px;">
                                               <?php 
                                                    $noOfVisits = count($visits);
                                                    $last_visit = isset($visits[$noOfVisits - 1])?$visits[$noOfVisits - 1]:false;
                                                ?>
                                                <?php if($last_visit && $last_visit->is_med == 1){?>
                                                <h4 style="font-size: 20px;text-align: left;padding: 0px;margin: 0px;font-weight: normal;">MEDS: &nbsp;  <?php echo ($last_visit->med3)? getProduct($last_visit->med3)->name : ($last_visit->med1?getProduct($last_visit->med1)->name:"-")." / ".($last_visit->med2?getProduct($last_visit->med2)->name:"-");?></h4>
                                                <?php }elseif($last_visit && $last_visit->is_med == 0){?>
                                                <h4 style="font-size: 20px;text-align: left;padding: 0px;margin: 0px;font-weight: normal;">MEDS: &nbsp;  No Meds</h4>
                                                <?php } ?>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td style="height: 35px;">
                                              <?php 
                                              $phase_txt = '-';
                                              $turns_txt = '';
                                              $phase = null;
                                              if($last_visit)$phase = getPhaseObjByVisit(date('Y-m-d',strtotime($last_visit->visit_date)), $patient->id);
                                              if($phase)
                                              {
                                                  $phase_txt = getPhaseByVisit(date('Y-m-d',strtotime($last_visit->visit_date)), $patient->id);
                                                  $turns_txt = getNoOfTurns($phase);
                                              }
                                              ?>
                                             <h4 style="font-size: 20px;text-align: left;padding: 0px;margin: 0px;font-weight: normal;">Turn Finished#: <?php echo $turns_txt;?> &nbsp;  Phase: <?php echo $phase_txt;?></h4>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td style="height: 35px;">
                                              <?php if($last_visit){
                                                  $days = ($last_visit->is_med == 1)? $last_visit->med_days : $last_visit->no_med_days;
                                                  $diff = time() - strtotime($last_visit->visit_date);
                                                  $diff = floor($diff/(60*60*24));
                                                  $diff = $diff - $days;
                                                  $diff = $diff < 0 ? 0 : $diff;
                                              ?>
                                              
                                              <table style="width: 270px;">
                                                  <tr>
                                                      <td style="padding: 6px;border: 1px solid black;">
                                                          <h4  style="font-size: 20px;text-align: left;padding: 0px;margin: 10px;overflow: auto;">Days after Scheduled Visit: <?php echo $diff;?> days</h4>
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
                              <td class="bold" style="height: 45px;">Goal Wt</td>
                              <td style="height: 45px;"><?php echo $patient->goal_weight;?></td>
                          </tr>
                          <tr class="">
                              <td class="bold" style="height: 45px;">Last 2 Visit</td>
                              <?php 
                              $visit_count = count($visits);
                              $last_2_diff = '';
                              if($visit_count > 1)
                              {
                                  $last_2_diff = $visits[$visit_count - 1]->weight - $visits[$visit_count - 2]->weight;
                              }
                              ?>
                              <td style="height: 45px;"><?php echo $last_2_diff;?></td>
                          </tr>
                          <tr class="">
                              <td class="bold" style="height: 45px;">Today's Date</td>
                              <td style="height: 45px;"><?php echo ($thisvisit)?date('m/d/Y',strtotime($thisvisit->visit_date)):date('m/d/Y',strtotime($order->created));?></td>
                          </tr>
                      </table>
                  </td>
                  <td style="width: 20%;vertical-align: top;">
                      <table  class="align-left fiftyh" style="font-size: 20px;">
                          <tr class="fortyfive">
                              <td class="bold aright" style="text-align: right;padding-right: 10px;width: 50%">Today's Wt</td>
                              <td class="aleft" style="padding-left: 5px;width: 50%;border: 1px solid black;"></td>
                          </tr>
                          <tr class="fortyfive">
                              <td class="bold"  style="text-align: right;padding-right: 10px;">Today's BFI%</td>
                              <td class="aleft" style="padding-left: 5px;border: 1px solid black;"></td>
                          </tr>
                          <tr class="fortyfive">
                              <td class="bold"  style="text-align: right;padding-right: 10px;">Today's BMI%</td>
                              <td class="aleft" style="padding-left: 5px;border: 1px solid black;"></td>
                          </tr>
                          <tr class="fortyfive">
                              <td class="bold"  style="text-align: right;padding-right: 10px;">Today's BP</td>
                              <td class="aleft" style="padding-left: 5px;border: 1px solid black;"></td>
                          </tr>
                      </table>
                  </td>
              </tr>
          </table>
          
          <table style="margin-top: 20px;">
              <tr>
                  <td style="width: 70%;border: 2px solid black;">
                      <table >
                          <tr class="fiftyh">
                            <td colspan="8" style="text-align: left;padding-left: 10px;border-bottom: 1px solid #666;font-size: 20px;" class="bold">Past 6 Visit Info</td>                  
                        </tr>
                        <tr class="fortyh LightBorderBottom last_visits">
                            <td class="LightBorderRight">Visit #</td>
                            <td class="LightBorderRight">Phase</td>
                            <td class="LightBorderRight">Visit Date</td>
                            <td>Weight</td>                            
                            <td>BFI</td>
                            <td>BMI</td>
                            <td>BP</td>
                            <td style="width: 15%;">Last 2 Visit -/+</td>
                            <td style="width: 15%;">Total Wt -/+</td>
                        </tr>
                        <?php
                        $last_weight = 0;
                        $total_weight_diff = 0;
                        for ($i = 0;$i<6;$i++){?>
                        <?php if(isset($visits[$i])){
                            $v = $visits[$i];
                            ?>
                        <tr class="fortyh last_visits">
                            <td class="LightBorderRight"><?php echo $v->visit;?></td>
                            <td class="LightBorderRight"><?php echo getPhaseByVisit(date('Y-m-d',strtotime($v->visit_date)), $patient->id);?></td>
                            <td class="LightBorderRight"><?php echo date('m/d/Y',strtotime($v->visit_date));?></td>
                            <td><?php echo $v->weight;?></td>                            
                            <td><?php echo $v->bfi;?></td>
                            <td><?php echo $v->bmi;?></td>
                            <td><?php echo $v->bp;?></td>
                            <?php $diff = floatval($v->weight - $last_weight); ?>
                            <td><?php echo $i==0? 0: $diff?></td>
                            <td><?php echo $i==0? 0: $total_weight_diff = $total_weight_diff + $diff ;?></td>
                        </tr>
                        <?php $last_weight = $v->weight;?>
                        <?php }else{?>
                        <tr class="fortyh last_visits">
                            <td class="LightBorderRight"></td>
                            <td class="LightBorderRight"></td>
                            <td class="LightBorderRight"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php 
                            }
                        } ?>                       
                        
                    </table>
                  </td>
                  <td style="width: 30%;border: 2px solid black;">
                      <table>
                        <tr class="fiftyh last_visits">
                            <td class="bold" style="border-bottom: 1px solid #666;border-right: 1px solid #666;">Products bought today</td>
                            <td class="bold" style="border-bottom: 1px solid #666;width: 25%;">Qty / Days</td>
                        </tr>
                        <?php for ($i = 0;$i<7;$i++){?>
                        <?php if(isset($order_items[$i])){
                            $product = $order_items[$i];
                            ?>
                        <tr class="fortyh last_visits" style="border: 1px solid #666;">
                            <td class="LightBorderRight"><?php echo $product->name;?></td>
                            <td><?php echo $product->quantity;?></td>
                        </tr>
                        <?php }else{?>
                        <tr class="fortyh last_visits" style="border: 1px solid #666;">
                            <td class="LightBorderRight">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <?php 
                        }
                        } ?>   
                    </table>
                  </td>
              </tr>
          </table>
          <table style="width: 100%;margin-top: 25px;">
              <tr>
                  <td style="width: 50%;padding-right:12px;vertical-align: top;">
                      <table  class="last_visit">
                          <tr class="fortyh">
                              <td class="bold" style="font-size: 20px;">
                                  LAST VISIT
                              </td>
                          </tr>
                          <tr>
                              <td style="height: 60px;" class="med_fields">
                                  <p style="font-weight: bold;">Allergies:</p>
                                  <?php if($patient) echo $patient->allergies;?> 
                              </td>
                          </tr>
                          <tr>
                              <td style="height: 95px;" class="med_fields">
                                  <p style="font-weight: bold;">Current Medications:</p>
                                  <?php if($patient) echo $patient->previous_medication;?> 
                              </td>
                          </tr>
                          <tr>
                              <td style="height: 60px;" class="med_fields">
                                  <p style="font-weight: bold;">Alerts:</p>
                                  <?php if($last_visit) echo $last_visit->alerts;?> 
                              </td>
                          </tr>
                          <tr>
                              <td style="height: 155px;border-bottom: none;" class="med_fields">
                                  <p style="font-weight: bold;">Assessment & Plan:</p>
                                  <?php if($last_visit) echo $last_visit->assessment_and_plan;?> 
                              </td>
                          </tr>
                          <tr>
                              <td style="height: 110px;border-top: none;" class="med_fields">
                                  <p style="">Other Instructions:</p>
                                  <?php if($last_visit) echo $last_visit->other_instructions;?> 
                              </td>
                          </tr>
                      </table>
                  </td>
                  <td style="width: 50%;padding-left:12px;vertical-align: top;">
                      <table  class="this_visit">
                          <tr class="fortyh">
                              <td class="bold" style="font-size: 20px;">
                                  THIS VISIT
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  <table class="border_none">
                                      <tr class="fortyh">
                                          <td class="med_fields">
                                              If Hungry? [YES]/[NO] : [AM] [PM]
                                          </td>
                                          <td class="med_fields">
                                              [See the Doctor]
                                          </td>
                                      </tr>
                                      <tr class="fortyh">
                                          <td class="med_fields">
                                              [Exercise]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[4-6 Small Meals]
                                          </td>
                                          <td class="med_fields">
                                              [Question on Food]
                                          </td>
                                      </tr>
                                      <tr class="fortyh">
                                          <td class="med_fields">
                                              <strong>[Constipated]</strong>&nbsp;&nbsp; Moderate | Severe
                                          </td>
                                          <td class="med_fields">
                                              [Enough H2O]
                                          </td>
                                      </tr>
                                      <tr class="fortyh">
                                          <td class="med_fields">
                                              Pills taken at: [_____ AM][_____ PM]
                                          </td>
                                          <td class="med_fields">
                                              
                                          </td>
                                      </tr>
                                  </table>
                              </td>
                          </tr>
                          <tr>
                              <td style="height: 105px;" class="med_fields">
                                  <strong>Other MA Remarks:</strong>
                              </td>
                              
                          </tr>
                          <tr>
                              <td style="height: 82px;" class="med_fields">
                                  Change of Meds Requested by Patient: <span style="font-style: italic;">[Reason]</span>
                              </td>
                          </tr>
                      </table>
                      <table class="inj_tbl" style="margin-top: 10px;">
                          <?php for ($i = 0;$i<4;$i++){?>
                          <?php if(isset($injections[$i])){
                              $inj = $injections[$i];
                              ?>
                                <tr>
                                  <td style="width: 50%;">
                                      <?php echo $inj['name'];?>
                                  </td>
                                  <td style="width: 25%;">
                                      <?php echo $inj['lot'];?>
                                  </td>
                                  <td style="width: 25%;">
                                      <?php echo date('m/d/Y',strtotime($inj['exp']));?>
                                  </td>
                                </tr>
                          <?php }else{?>
                          <tr>
                              <td style="width: 50%;">
                                  &nbsp;
                              </td>
                              <td style="width: 25%;">
                                  &nbsp;
                              </td>
                              <td style="width: 25%;">
                                  &nbsp;
                              </td>
                          </tr>
                          <?php }
                          }?>
                      </table>
                  </td>
              </tr>
          </table>
          <table style="margin-top: 25px;">
              <tr>
                  <td style="border: 1px solid black;width: 100%;">
                    <table class="medication_tbl">
                        <tr class="header">
                            <td style="width: 20%;">MEDICATIONS</td>
                            <td style="width: 10%;">Made By</td>
                            <td style="width: 10%;">Checked</td>
                            <td class="gray_bg" style="width: 10%;">Pat Sign</td>
                            <td style="width: 10%;text-align: center;">Days</td>
                            <td style="width: 12%;border: 1px solid black;border-top: none;"></td>
                            <td style="width: 12%;text-align: center;">Total:</td>
                            <td style="width: 16%;border: 1px solid black;border-top: none;border-right: none;"></td>
                        </tr>
                        <tr class="med_fields">
                            <td><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/blank_squire.png&amp;w=18&amp;h=18&amp;zc=1&amp;f=png" /> Phentermine 37.5 mg TAB</td>
                            <td></td>
                            <td></td>
                            <td class="gray_bg"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="med_fields">
                            <td><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/blank_squire.png&amp;w=18&amp;h=18&amp;zc=1&amp;f=png" /> Phentermine 30 mg CAP</td>
                            <td></td>
                            <td></td>
                            <td class="gray_bg"></td>
                            <td colspan="2"><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/blank_squire.png&amp;w=18&amp;h=18&amp;zc=1&amp;f=png" /> Counselled</td>                            
                            <td colspan="2"><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/blank_squire.png&amp;w=18&amp;h=18&amp;zc=1&amp;f=png" /> Option to fill in pharmacy given</td>
                            
                        </tr>
                        <tr class="med_fields">
                            <td><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/blank_squire.png&amp;w=18&amp;h=18&amp;zc=1&amp;f=png" /> Phentermine 15 mg CAP</td>
                            <td></td>
                            <td></td>
                            <td class="gray_bg"></td>
                            <td colspan="4" style="padding: 0px;">
                                <table class="border_none">
                                    <tr>
                                        <td><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/blank_squire.png&amp;w=18&amp;h=18&amp;zc=1&amp;f=png" /> Amino Acids</td>
                                        <td><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/blank_squire.png&amp;w=18&amp;h=18&amp;zc=1&amp;f=png" /> Calcium</td>
                                        <td><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/blank_squire.png&amp;w=18&amp;h=18&amp;zc=1&amp;f=png" /> Multi-Vitamins</td>
                                    </tr>
                                </table>
                            </td>
                            
                        </tr>
                        <tr class="med_fields">
                            <td><img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/blank_squire.png&amp;w=18&amp;h=18&amp;zc=1&amp;f=png" /> Diethylpropion 25 mg TAB</td>
                            <td></td>
                            <td></td>
                            <td class="gray_bg"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        
                    </table>
                  </td>
              </tr>
          </table>
          <table style="margin-top: 10px;">
              <tr class="fiftyh">
                <td style="width: 40%;"></td>
                <td class="bold" style="width: 30%;border: 1px solid #666;text-align: left;padding-left: 5px;font-size: 20px;">Meds for Day/s: <?php echo isset($days)?$days:'-';?></td>
                <td class="bold" style="width: 30%;border: 1px solid #666;text-align: left;padding-left: 5px;font-size: 20px;">Next Visit Dt:  <?php if(isset($next_visit)) echo $next_visit;?></td>
              </tr>
          </table>

            <table class="bottom_tbl" style='margin-top: 10px;'>
                <tr>
                    <td style="width: 40%;text-align: left;padding-left: 5px;height: 60px;">
                        Return:  Week/s for follow up this visit
                        
                    </td>
                    <td colspan="2" style="width: 60%;text-align: left;padding-left: 5px;height: 60px;">
                        Medical Director Signature
                    </td>
                </tr>
                <tr>
                    <td style="width: 40%;text-align: left;padding-left: 5px;height: 80px;vertical-align: top;">
                        I have received a copy of my EKG / Lab report. I have been informed both verbally and in writing the follow up instructions. 
                    </td>
                    <td style="text-align: left;padding-left: 5px;height: 80px;vertical-align: top;">
                        PATIENT'S Sign:
                    </td>
                    <td style="text-align: left;padding-left: 5px;height: 80px;vertical-align: top;">
                        PA or Nurse Practitioner Signature
                    </td>
                </tr>
            </table>
      </div>
</body>
</html>
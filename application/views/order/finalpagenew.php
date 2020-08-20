<!DOCTYPE html>
<html lang="en-US">
    <head>    
        <link rel="stylesheet" type="text/css" href="/assets/css/finalpage.css">
  
  </head>
  <body>
      <div id="container" style="margin: 0px;padding: 40px 40px 10px 40px;">
        <table style="width: 100%;">
              <tr>
<td style="width: 45%;border-right: 1px solid #999;vertical-align: top;">
                      <table>
                          <tr>
                              <td style="width: 50%;text-align: left;vertical-align: top;"> 
                                <table class="align-left" style="font-size: 20px;">
                                    <tr class="">
                                        <td colspan="2" class="bold" style="height: 105px;vertical-align: top;text-align: left;">
                                            <?php if(file_exists("./assets/upload/patients/$patient->photo")){?>
                                            <img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/upload/patients/<?php echo $patient->photo;?>&amp;h=153&amp;f=png" />
                                            <?php }else{ 
                                                $gender_img = $patient->gender == 1 ? "male.png":"female.png";
                                            ?>                                  
                                            <img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/<?php echo $gender_img;?>&amp;h=153&amp;f=png" />
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    
                                    <tr class="">
                                        <td class="" style="height: 30px;">Age</td>
                                        <?php $age = ($patient->dob)? date_diff(date_create($patient->dob), date_create('now'))->y:0; ?>
                                        <td style="height: 30px;"><?php echo $age;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $patient->gender==1? 'M':'F';?></strong></td>
                                    </tr>
                                    
                                    <tr class="">
                                        <td class="" style="height: 30px;padding-top: 10px;">DOB</td>
                                        <td style="height: 30px;"><?php echo date('m/d/Y',strtotime($patient->dob));?></td>
                                    </tr>
                                </table>
                              </td>
                              <td style="width: 50%;vertical-align: top;padding-top: 0px;padding-left: 10px;">
                                  <table class="align-left" style="font-size: 20px;">
                                      <tr>
                                          <td style="height: 30px;">
                                              <h4 style="font-size: 24px;text-align: left;padding: 0px;margin: 0px;"><?php echo $patient->lname." ".$patient->fname;?></h4>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td style="padding-top: 9px;">
                                              <h4  style="font-size: 20px;text-align: left;padding: 0px;margin: 0px;overflow: auto;font-weight: normal;">Location: <?php echo $order_location->abbr;?></h4>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td style="height: 35px;padding-top: 9px;">
                                               <?php 
                                                    $noOfVisits = count($visits);
                                                    $last_visit = isset($visits[$noOfVisits - 1])?$visits[$noOfVisits - 1]:false;
                                                    if($last_visit && date('Y-m-d',strtotime($last_visit->visit_date))==date('Y-m-d'))
                                                    {
                                                        $last_visit = isset($visits[$noOfVisits - 2])?$visits[$noOfVisits - 2]:false;
                                                    }
                                                ?>
                                                <?php if($last_visit && $last_visit->is_med == 1){?>
                                                <h4 style="font-size: 20px;text-align: left;padding: 0px;margin: 0px;font-weight: normal;">MEDS: &nbsp;  <?php echo ($last_visit->med3)? getProduct($last_visit->med3)->name : ($last_visit->med1?getProduct($last_visit->med1)->name:"-")." / ".($last_visit->med2?getProduct($last_visit->med2)->name:"-");?></h4>
                                                <?php }elseif($last_visit && $last_visit->is_med == 0){?>
                                                <h4 style="font-size: 20px;text-align: left;padding: 0px;margin: 0px;font-weight: normal;">MEDS: &nbsp;  No Meds</h4>
                                                <?php } ?>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td style="height: 35px;padding-top: 9px;">
                                              <?php 
                                              $phase_txt = '-';
                                              $turns_txt = '';
                                              $phase = null;
                                              if($last_visit)$phase = getPhaseObjByVisit(date('Y-m-d',strtotime($thisvisit->visit_date)), $patient->id);
                                              if($phase)
                                              {
                                                  $phase_txt = "12.".$phase->phase;
                                                  $turns_txt = getNoOfTurns($phase);
                                              }
                                              ?>
                                              <h4 style="font-size: 20px;text-align: left;padding: 0px;margin: 0px;font-weight: normal;">Turn Finished#: &nbsp;&nbsp;<?php echo $turns_txt;?>
                                                  
                                                  <br>  Phase:&nbsp;&nbsp; <?php echo $phase_txt;?>
                                              </h4>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td style="height: 35px;padding-top: 9px;">
                                              <?php if($last_visit){
                                                  $days = ($last_visit->is_med == 1)? $last_visit->med_days : $last_visit->no_med_days;
                                                  $diff = time() - strtotime($last_visit->visit_date);
                                                  $diff = floor($diff/(60*60*24));
                                                  $diff = $diff - $days;
//                                                  $diff = $diff < 0 ? 0 : $diff;
                                              ?>
                                              
                                              <table style="width: 270px;">
                                                  <tr>
                                                      <td style="padding: 6px 6px 3px 0px;">
                                                          <h4  style="font-size: 20px;text-align: left;padding: 0px;margin: 0px;overflow: auto;">No Meds For: <?php echo $diff;?> days</h4>
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
                  
                  <td style="width: 25%;vertical-align: top;padding-left: 10px;border-right: 1px solid #999;">
                      <table class="align-left" style="font-size: 20px;">
                          <tr class="">
                            <td class="" style="height: 35px;padding-top: 5px;">Height</td>
                              <?php $height = $patient->height;
                                  $feet = floor($height/12);$inches = ($height%12);
                              ?>
                            <td style="height: 35px;text-align: left;padding-top: 5px;"><?php echo $feet."'";?> <?php if($inches > 0) echo $inches.'"';?></td>
                        </tr>
                          <tr class="">
                              <td class="" style="height: 38px;">Initial Weight:</td>
                              <td style="height: 38px;text-align: left;"><?php echo (isset($first_visit))?$first_visit->weight:"-";?></td>
                          </tr>
                          <tr class="">
                              <td class="" style="height: 38px;">Goal Weight:</td>
                              <td style="height: 38px;"><?php echo $patient->goal_weight;?></td>
                          </tr>
                          
                          <tr class="">
                              <td class=""  style="height: 38px;">Today's Date:</td>
                              <td style="height: 38px;text-align: left;"><span class="bold" style="font-size:26px;"><?php echo date('m/d/Y',strtotime($order->created));?></span></td>
                          </tr>
                          <tr class="">
                              <td class="" style="height: 38px;">Time Stamp:</td>
                              <td style="height: 38px;"><?php echo date('h:ia',strtotime($order->created));?></td>
                          </tr>
                          <tr class="">
                              <td class="" style="height: 38px;">Order by:</td>
                              <td style="height: 38px;"><strong><?php echo $staff;?></strong></td>
                          </tr>
                      </table>
                  </td>
                  <td style="width: 30%;vertical-align: top;padding: 0px 0px 0px 10px;">
                      <table class="table_spacing" class="align-left fiftyh" style="font-size: 18px;width: 100%;">
                          
                          <tr class="fortyh">
                              <td class="bold aright" style="text-align: right;padding:0px 5px 5px 5px;width: 62%;">
                                  <table><tr><td style="border: 1px solid black;height: 45px;font-size: 26px;font-weight: bold;text-align: center;"><?php if($thisvisit) echo $thisvisit->weight;?></td></tr></table>
                              </td>
                              <td class="aleft" style="padding-left: 5px;width: 38%;height: 45px;">TODAY'S WT</td>
                          </tr>
                          <tr class="fortyh">
                              <td class="bold aright" style="text-align: right;padding:0px 5px 0px 5px;">
                                  <table><tr>
                                          <td style="border: 1px solid black;font-size: 12px;font-weight: bold;text-align: left;border-right: none;vertical-align: top;width: 24%;padding: 3px 0 0 3px;">
                                              BFI %
                                          </td>  
                                      
                                          <td style="border: 1px solid black;font-size: 26px;font-weight: bold;text-align: left;height: 40px;border-left: none;padding-left: 10px;">                                              
                                      <?php if($thisvisit) echo $thisvisit->bfi;?>
                                          </td></tr></table>
                              </td>
                              <td rowspan="2" class="aleft" style="padding:0px 0px 0px 10px;font-weight: bold;font-size: 60px;color: #888;line-height: 0.9;">
                                  <img src="/assets/img/final_page.png" /> 
                              </td>
                          </tr>
                           <tr class="fortyh">
                              
                              <td class="aleft" style="padding:0px 5px 5px 5px;">
                                  <table><tr>
                                          <td style="border: 1px solid black;font-size: 12px;font-weight: bold;text-align: left;border-right: none;vertical-align: top;width: 24%;padding: 3px 0 0 3px;">
                                              BMI %
                                          </td>   
                                          
                                          <td style="border: 1px solid black;font-size: 26px;font-weight: bold;text-align: left;height: 40px;border-left: none;padding-left: 10px;">
                                      <?php if($thisvisit) echo $thisvisit->bmi;?></td>
                                      </tr></table>
                              </td>
                          </tr>
                          <tr class="fortyh">
                              <td class="bold aright" style="text-align: right;padding:0px 5px 0px 5px;">
                                  <table><tr><td style="border: 1px solid black;text-align: center;font-size: 26px;height: 45px;font-weight: bold;"><?php if($thisvisit) echo $thisvisit->bp;?></td></tr></table>
                              </td>
                              <td class="aleft" style="padding:0px 5px 0px 5px;height: 45px;">TODAY'S BP</td>
                          </tr>
                      </table>
                      

                  </td>
              </tr>
              <tr><td colspan="3" style="border-bottom: 1px solid #999;padding: 0px;margin: 0px;height: 8px;"></td></tr>
        </table>
          
        <table style="width: 100%;margin-top: 25px;">
            <tr>
                <td style="width: 25%;vertical-align: top;">
                    <table class="inj_tbl">
                        <?php foreach($injections as $inj){?>
                        <tr>
                            <td style="height: 40px;vertical-align: top;font-size:15px;line-height:1.1;">
                                <strong><?php echo $inj['name'];?>: </strong><br>
                                <?php echo $inj['lot'];?>  &nbsp;&nbsp;&nbsp;&nbsp;Exp Dt: <?php echo date('m/Y',strtotime($inj['exp']));?> &nbsp;&nbsp;&nbsp;&nbsp;Qty: <?php echo $inj['qty'];?>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </td>
                <td  style="width: 75%;">
                    <table style="width:100%;">
                        <tr class="fiftyh">
                            <td colspan="12" style="text-align: left;padding-left: 10px;border: 1px solid #666;font-size: 19px;height: 35px;" class="bold">PAST 6 VISITS INFO</td>                  
                        </tr>
                        <tr class="fortyh LightBorderBottom last_visits last_visits_hd">
                            <td style="width: 6%;" class="LightBorderRight">Visit #</td>
                            <td class="LightBorderRight">Phase</td>
                            <td class="LightBorderRight" style="width: 7%;"># of Wks</td>
                            <td class="LightBorderRight">Visit Date</td>
                            <td class="LightBorderRight">Loc</td>
                            <td>Weight</td>                            
                            <td>BFI</td>
                            <td>BMI</td>
                            <td style="width: 10%;">BP</td>
                            <td style="width: 10%;">Last 2 Visit</td>
                            <td style="width: 10%;">Total Wt</td>
                            <td style="width: 10%;">Days Over Schld Visit</td>
                        </tr>
                        <?php
                        $last_weight = 0;
                        $tr = 0;
                        $total_weight_diff = 0;
                        $pvdate = FALSE;
                        $vn = 0;
                        for ($i = 0;$i<6;$i++){?>
                        <?php if(isset($visits[$i])){
                            $v = $visits[$i];
                            $med_days = $v->is_med==1 ? $v->med_days : $v->no_med_days;
                            $coming_after = "-";
                            if($pvdate)
                            {
                                $d1 = new DateTime(date('Y-m-d',strtotime($pvdate->visit_date)));
                                $d2 = new DateTime(date('Y-m-d',strtotime($v->visit_date)));
                                $gap = $d1->diff($d2)->format("%a");
                                $prev_med_days = $pvdate->is_med==1 ? $pvdate->med_days : $pvdate->no_med_days;
                                $gap = $gap - $prev_med_days;
                                $coming_after = $gap." days";
                            }
                            $pvdate = $v;
                            $vt = getTurnsForDays($med_days);
//                            if($i==0) $tr = getNoOfTurnsForPeriod($patient->id,$phase->start,date('Y-m-d',strtotime($v->visit_date)));
//                            $tr += $vt;
                            if($i == 0)
                            {
                                $vn = ($lastRestart)? $lastRestart->count : $v->visit;
                            }
                            ?>
                        <tr class="fortyh last_visits">
                            <td class="LightBorderRight"><?php echo $vn;?></td>
                            <td class="LightBorderRight"><?php echo getPhaseByVisit(date('Y-m-d',strtotime($v->visit_date)), $patient->id);?></td>
                            <td class="LightBorderRight"><?php echo $vt;?></td>
                            <td class="LightBorderRight"><?php echo date('m/d/Y',strtotime($v->visit_date));?></td>
                            <td class="LightBorderRight"><?php echo $v->abbr;?></td>
                            <td><?php echo $v->weight;?></td>                            
                            <td><?php echo $v->bfi;?></td>
                            <td><?php echo $v->bmi;?></td>
                            <td><?php echo $v->bp;?></td>
                            <?php $diff = floatval($v->weight - $last_weight); 
                                $diff = round($diff,1);
                            ?>
                            <td><?php echo $i==0? 0: $diff?></td>
                            <?php
                            
                            if($i == 0)
                            {
                                if($v->visit > 1 && isset($first_visit))
                                {
                                    $total_weight_diff = floatval($v->weight - $first_visit->weight);
                                }
                                else
                                {
                                    $total_weight_diff = 0;
                                }
                            }
                            else
                            {
                                $total_weight_diff = $total_weight_diff + $diff ;
                            }
                            ?>
                            <td><?php echo round($total_weight_diff,1);?></td>
                            <td><?php echo $coming_after;?></td>
                        </tr>
                        <?php $last_weight = $v->weight;?>
                        <?php $vn++;?>
                        <?php }else{?>
                        <tr class="fortyh last_visits">
                            <td class="LightBorderRight"></td>
                            <td class="LightBorderRight"></td>
                            <td class="LightBorderRight"></td>
                            <td class="LightBorderRight"></td>
                            <td class="LightBorderRight"></td>
                            <td></td>
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
            </tr>
            
        </table>
          <table style="width: 100%;margin-top: 25px;">
              <tr>
                  <td style="width:30%;border:1px solid #666;padding:5px;border-right: none;">
                      <table id="tbl_allergies">
                          <tr><td style="height: 60px;">Allergies:  &nbsp;<span style="font-style: italic;"><?php echo $patient->allergies;?></span></td></tr>
                          <tr><td style="height: 150px;">Current Medications:   &nbsp;<span style="font-style: italic;"><?php echo $patient->previous_medication;?></span></td></tr>
                          <tr><td style="height: 150px;">Dr’s Alerts:</td></tr>   
                      </table>
                  </td>
                  <td style="width: 70%;border:1px solid #666;border-left: none;vertical-align: top;padding:5px;">
                      <table>
                          <tr>
                              <td style="vertical-align: top;height: 100px;border-bottom: 1px solid #666;">
                                  <table class="border_none">
                                      <tr class="fortyh">
                                          <td style="width: 50%;" class="med_fields">
                                              If Hungry?&nbsp;&nbsp; YES&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NO&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;:&nbsp; AM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PM
                                          </td>
                                          <td style="width: 30%;" class="med_fields">
                                              See the Doctor
                                          </td>
                                          <td style="width: 20%;padding-left: 50px;" class="med_fields">
                                              Exercise
                                          </td>
                                      </tr>
                                      <tr class="fortyh">
                                          <td class="med_fields">
                                              [3-4 Small Meals]
                                          </td>
                                          <td class="med_fields">
                                              Question on Food
                                          </td>
                                         
                                          <td class="med_fields" style="padding-left: 50px;">
                                              Enough H2O
                                          </td>
                                      </tr>
                                      
                                      <tr class="fortyh">
                                           <td class="med_fields">
                                              Constipated&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Moderate &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Severe
                                          </td>
                                          <td class="med_fields">
                                              Meds taken at: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PM
                                          </td>
                                          <td class="med_fields">
                                              
                                          </td>
                                      </tr>
                                  </table>
                              </td>
                          </tr>
                          <tr>
                              <td style="height: 35px;vertical-align: bottom;text-align: left;font-size: 18px;">
                                  Notes
                              </td>
                          </tr>
                          <tr>
                              <td style="padding: 0px 15px;">
                                  <table id="blank_tbl">
                                      <tr><td>&nbsp;</td></tr>
                                      <tr><td>&nbsp;</td></tr>
                                      <tr><td>&nbsp;</td></tr>
                                  </table>
                              </td>
                          </tr>
                          <tr>
                              <td style="height: 35px;vertical-align: bottom;text-align: left;font-size: 18px;">
                              Assessment & Plan:
                              </td>
                          </tr>
                          <tr>
                              <td style="padding: 0px 15px;">
                                  <table id="blank_tbl">
                                      <tr><td>&nbsp;</td></tr>
                                      <tr><td>&nbsp;</td></tr>
                                      <tr><td>&nbsp;</td></tr>
                                  </table>
                              </td>
                          </tr>
                         
                      </table>
                  </td>
              </tr>
          </table>
          <table style="width: 100%;margin-top: 15px;">
              <tr>
                  <td style="width: 35%;vertical-align: top;">
                      <table class="counsel_tbl">
                          <tr>
                              <td style="height:10px;">Counselled</td>
                              <td style="height:10px;">Option to fill in pharmacy given</td>
                          </tr>                         
                          <tr>
                              <td colspan="2" style="padding-bottom:15px;">Amino Acids &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Calcium &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Multi-vitamins</td>
                          </tr>
                          <tr  class="twentyh">
                              <td colspan="2" style="padding: 0px;margin: 0px;">
                                  <table class="compact">
                                      <tr class="twentyh">
                                          <td>Largo</td>
                                          <td>Palm</td>
                                          <td>St Pete</td>
                                      </tr>
                                  </table>
                              </td>
                          </tr>                        
                          <tr class="twentyh">
                              <td colspan="2" style="vertical-align: top;line-height:1;padding: 0px;margin: 0px;">
                                  <table class="compact">
                                      <tr class="twentyh">
                                          <td>MON</td>
                                          <td>TUE</td>
                                          <td>WED</td>
                                          <td>THU</td>
                                          <td>FRI</td>
                                          <td>SAT</td>
                                      </tr>
                                  </table>
                              </td>
                          </tr>  
                          <tr class="twentyh">
                              <td colspan="2" style="vertical-align: top;line-height:1;padding: 0px;margin: 0px;">Time: . . . . . . . . . . . . . . . . . . . . . . . &nbsp; &nbsp;Appt Made </td>
                          </tr>                     
                          <tr>
                              <td colspan="2">MA: . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . </td>
                          </tr>
                      </table>
                  </td>              
                  <td style="width: 65%;">
                      <table class="medication_tbl">
                          <tr class="hdr">
                              <td style="width: 30%;">Medication</td>
                              <td>Made By</td>
                              <td>Pat Sign</td>
                              <td>Suppl Bag</td>                              
                              <td>Days</td>
                              <td>Total Qty</td>
                          </tr>
                          <tr>
                              <td>Phentermine 37.5mg TAB</td>
                              <?php $m37 = getMedAmount($thisvisit,37);?>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td><?php echo $m37;?></td>
                              <td><?php if(!empty($m37)) echo $m37 * $thisvisit->meds_per_day; ?></td>
                          </tr>
                          <tr>
                              <td>Phentermine 30mg CAP</td>
                              <?php $m30 = getMedAmount($thisvisit,30);?>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td><?php echo $m30;?></td>
                              <td><?php if(!empty($m30)) echo $m30 * $thisvisit->meds_per_day; ?></td>
                          </tr>
                          <tr>
                              <td>Phentermine 15mg CAP</td>
                              <?php $m15 = getMedAmount($thisvisit,15);?>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td><?php echo $m15;?></td>
                              <td><?php if(!empty($m15)) echo $m15 * $thisvisit->meds_per_day; ?></td>
                          </tr>
                          <tr>
                              <td>Diethylproprion 25mg TAB</td>
                              <?php $mdi = getMedAmount($thisvisit,'DI');?>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td><?php echo $mdi;?></td>
                              <td><?php if(!empty($mdi)) echo $mdi * $thisvisit->meds_per_day; ?></td>
                          </tr>
                      </table>
                  </td>
                  <tr><td colspan="2" style="border-bottom: 1px solid #666;padding: 0px;margin: 0px;height: 8px;"></td></tr>
              </tr>
          </table>
          <table class="bottom_tbl" style='margin-top: 10px;'>
                <tr>
                    <td style="width: 35%;text-align: left;padding-left: 5px;height: 60px;border-right: 1px solid #999;border-bottom: 1px solid #999;vertical-align:top;padding-bottom:5px;padding-top:0;margin:0;">
                                        
                        <p style="font-size: 26px;padding:0;margin:0;line-height:1;">Next Visit Cal: <?php echo $next_cal;?></p>
                        <p style="font-size: 26px;padding:0;margin:0;line-height:1;">Next Visit Auto: <?php echo $next_auto;?></p>
                    </td>
                    <td colspan="2" style="width: 65%;text-align: left;padding-left: 5px;height: 60px;border-bottom: 1px solid #999;">
                        Patient's Sign:
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;padding-left: 5px;height: 80px;vertical-align: top;border-right: 1px solid #999;font-size: 17px;">
                        <?php if($phase && $phase->phase == 2){?> 
                        <?php if($turns_txt && $turns_txt >= 11){?>
                            <p style="margin: 0px;padding: 0px;font-size: 36px;font-weight: bold;">Re-Eval coming up</p>
                                <?php if($turns_txt < 12){?>
                                <p style="margin: 0px;padding: 0px;font-size: 20px;font-weight: bold;">1 Turn Left for 12.2</p>
                                <?php } ?>
                            <?php } ?>
                        <?php }else{ ?>
                            I have received a copy of my EKG / Lab report. I have been informed both verbally and in writing the follow up instructions.
                        <?php } ?>
                    </td>
                    <td style="text-align: left;padding-left: 5px;height: 80px;vertical-align: top;border-right: 1px solid #999;">
                        Medical Director Signature
                    </td>
                    <td style="text-align: left;padding-left: 5px;height: 80px;vertical-align: top;width:25%">
                        PA / Nurse Practitioner Signature
                    </td>
                </tr>
            </table>
      </div>
  </body>
</html>
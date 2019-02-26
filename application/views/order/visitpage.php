<!DOCTYPE html>
<html lang="en-US">
    <head>    
        <link rel="stylesheet" type="text/css" href="/assets/css/finalpage.css">
        <style>
            table.avbl_meds tr td{text-align: left;font-size: 16px;height: 18px;}
            table.bottom_tbl tr td{font-size: 19px;}
        </style>
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
                                        <td class="" style="height: 30px;padding-top: 10px;">Age</td>
                                        <?php $age = ($patient->dob)? date_diff(date_create($patient->dob), date_create('now'))->y:0; ?>
                                        <td style="height: 30px;"><?php echo $age;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $patient->gender==1? 'M':'F';?></strong></td>
                                    </tr>
                                    
                                    <tr class="">
                                        <td class="" style="height: 30px;">DOB</td>
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
                                              if($last_visit)$phase = getPhaseObjByVisit(date('Y-m-d',strtotime($last_visit->visit_date)), $patient->id);
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
                              <td class="" style="height: 38px;width: 40%;">Initial Weight:</td>
                              <td style="height: 38px;text-align: left;width: 60%;"><?php echo (isset($first_visit))?$first_visit->weight:"-";?></td>
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
                                  <table><tr><td style="border: 1px solid black;height: 45px;"></td></tr></table>
                              </td>
                              <td class="aleft" style="padding-left: 5px;width: 38%;height: 45px;">TODAY'S WT</td>
                          </tr>
                          <tr class="fortyh">
                              <td class="bold aright" style="text-align: right;padding:0px 5px 0px 5px;">
                                  <table><tr><td style="border: 1px solid black;height: 45px;text-align: left;vertical-align: top;font-size: 15px;padding: 5px;">BFI %</td></tr></table>
                              </td>
                              <td rowspan="2" class="aleft" style="padding:0px 0px 0px 10px;font-weight: bold;font-size: 60px;color: #888;line-height: 0.9;">
                                  <img src="/assets/img/visit_info.png" /> 
                              </td>
                          </tr>
                           <tr class="fortyh">
                              
                              <td class="aleft" style="padding:0px 5px 5px 5px;">
                                  <table><tr><td style="border: 1px solid black;height: 45px;text-align: left;vertical-align: top;font-size: 15px;padding: 5px;">BMI %</td></tr></table>
                              </td>
                          </tr>
                          <tr class="fortyh">
                              <td class="bold aright" style="text-align: right;padding:0px 5px 0px 5px;">
                                  <table><tr><td style="border: 1px solid black;text-align: center;font-size: 24px;height: 45px;">/</td></tr></table>
                              </td>
                              <td class="aleft" style="padding:0px 5px 0px 5px;height: 45px;">TODAY'S BP</td>
                          </tr>
                      </table>
                  </td>
              </tr>
              
        </table>
          
        <table style="width: 100%;margin-top: 40px;">
            <tr>

                <td  style="width: 75%;">
                    <table style="width:100%;">
                        <tr class="fiftyh">
                            <td colspan="12" style="text-align: left;padding-left: 10px;border: 1px solid #666;font-size: 19px;height: 30px;" class="bold">PAST 6 VISITS INFO</td>                  
                        </tr>
                        <tr class="fortyh LightBorderBottom last_visits last_visits_hd">
                            <td class="LightBorderRight" style="width: 6%;">Visit #</td>
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
                            if($i == 0)
                            {
                                $vn = ($lastRestart)? $lastRestart->count : $v->visit;
                            }
                            
                            ?>
                        <tr class="fortyh last_visits">
                            <td class="LightBorderRight"><?php echo $vn;?></td>
                            <td class="LightBorderRight"><?php echo getPhaseByVisit(date('Y-m-d',strtotime($v->visit_date)), $patient->id);?></td>
                            <td class="LightBorderRight"><?php echo getTurnsForDays($med_days);?></td>
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
                <td style="width: 25%;vertical-align: top;padding-left: 20px;">
                    <table class="inj_tbl">
                        <?php foreach($injections as $inj){?>
                        <tr>
                            <td style="height: 40px;vertical-align: top;font-size:15px;line-height:1.1;">
                                <strong><?php echo $inj['name'];?>: </strong><br>
                                <?php echo $inj['lot'];?>  &nbsp;&nbsp;&nbsp;&nbsp;Exp Dt: <?php echo date('m/d',strtotime($inj['exp']));?> &nbsp;&nbsp;&nbsp;&nbsp;Qty: <?php echo $inj['qty'];?>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </td>
            </tr>            
        </table>
          <table style="margin-top: 40px;width: 100%;">
              <tr>
                  <td style="width: 20%;text-align: left;font-size: 20px;font-weight: bold;padding-left: 5px;border-right: 1px solid #999;height: 30px;vertical-align: top;">Previous (Available TODAY)</td>
                  <td style="width: 20%;text-align: left;font-size: 20px;font-weight: bold;padding-left: 5px;border-right: 1px solid #999;height: 30px;vertical-align: top;">Existing (Redeemed TODAY)</td>
                  <td style="background-color: #efefef;width: 20%;text-align: left;font-size: 20px;font-weight: bold;padding-left: 5px;height: 30px;border-right: 1px solid #999;vertical-align: top;">Bought (Today's Order)</td>
                  <td style="width: 20%;text-align: left;font-size: 20px;font-weight: bold;padding-left: 5px;height: 30px;border-right: 1px solid #999;vertical-align: top;">Redeemed (Today's Order)</td>
                  <td style="width: 20%;text-align: left;font-size: 20px;font-weight: bold;padding-left: 5px;height: 30px;vertical-align: top;">Available after TODAY</td>
              </tr>
              
              <tr>
                  <td style="border-right: 1px solid #999;padding:0px 5px;vertical-align: top;padding-top: 30px;">
                      <table class="avbl_meds">
                          <?php foreach($avbl_prior as $prior){?>
                          <tr>
                              <td><strong>Order: </strong><?php echo $prior['order'];?><?php if(!empty($prior['date'])) echo " (".$prior['date'].")";?></td>
                          </tr>
                          <tr>
                              <td><strong>Item: </strong><?php echo $prior['name'];?></td>
                          </tr>
                          <tr>
                              <td style="padding-bottom: 8px;"><strong>Qty Avl: </strong><?php echo $prior['available'];?>
                              <?php if($prior['measure_in']=='Days'){
                                    $wks = getWkForDays($prior['available']);
                                    if($wks > 0)
                                    {
                                        echo "&nbsp;(".$wks." Wk)"; 
                                    }
                              } ?>
                              </td>
                          </tr>
                          <?php } ?>
                      </table>                      
                  </td>
                  <td style="border-right: 1px solid #999;padding:0px 5px;vertical-align: top;padding-top: 30px;">
                      <table class="avbl_meds">
                          <?php foreach($redeem_exis as $prior){?>
                          <tr>
                              <td><strong>Order: </strong><?php echo $prior['order'];?><?php if(!empty($prior['date'])) echo " (".$prior['date'].")";?></td>
                          </tr>
                          <tr>
                              <td><strong>Item: </strong><?php echo $prior['name'];?></td>
                          </tr>
                          <tr>
                              <td style="padding-bottom: 6px;"><strong>Qty: </strong><?php echo $prior['available'];?>
                              <?php if($prior['measure_in']=='Days'){
                                    $wks = getWkForDays($prior['available']);
                                    if($wks > 0)
                                    {
                                        echo "&nbsp;(".$wks." Wk)"; 
                                    }
                              } ?>
                              </td>
                          </tr>
                          <?php } ?>
                          
                      </table>  
                  </td>
                  <td style="border-right: 1px solid #999;padding:0px 5px;vertical-align: top;padding-top: 30px;">
                      <table class="avbl_meds">
                          <?php foreach($got_today as $prior){?>                          
                          <tr>
                              <td><strong>Item: </strong><?php echo $prior['name'];?></td>
                          </tr>
                          <tr>
                              <td style="padding-bottom: 6px;"><strong>Qty: </strong><?php echo $prior['available'];?><?php if($prior['subtotal']>0){?>&nbsp;&nbsp;&nbsp;<strong>$: </strong><?php echo $prior['subtotal'];?><?php } ?></td>
                          </tr>
                          
                          <?php } ?>
                      </table>  
                  </td>
                  <td style="border-right: 1px solid #999;padding:0px 5px;vertical-align: top;padding-top: 30px;">
                      <table class="avbl_meds">
                          <?php foreach($redeem_new as $prior){?>
                          <tr>
                              <td><strong>Item: </strong><?php echo $prior['name'];?></td>
                          </tr>
                          <tr>
                              <td style="padding-bottom: 6px;"><strong>Qty: </strong><?php echo $prior['available'];?>
                              <?php if($prior['measure_in']=='Days'){
                                    $wks = getWkForDays($prior['available']);
                                    if($wks > 0)
                                    {
                                        echo "&nbsp;(".$wks." Wk)"; 
                                    }
                              } ?>
                              </td>
                          </tr>
                          <?php } ?>
                      </table>  
                  </td>
                  <td style="padding:0px 5px;vertical-align: top;padding-top: 30px;">
                      <table class="avbl_meds">
                          <?php foreach($pp_remaining as $prior){?>
                          <tr>
                              <td><strong><?php echo $prior['order_text'];?>: </strong><?php echo $prior['order'];?><?php if(!empty($prior['date'])) echo " (".$prior['date'].")";?></td>
                          </tr>
                          <tr>
                              <td><strong>Item: </strong><?php echo $prior['name'];?></td>
                          </tr>
                          <tr>
                              <td style="padding-bottom: 6px;"><strong>Qty: </strong><?php echo $prior['available'];?>
                              <?php if($prior['measure_in']=='Days'){
                                    $wks = getWkForDays($prior['available']);
                                    if($wks > 0)
                                    {
                                        echo "&nbsp;(".$wks." Wk)"; 
                                    }
                              } ?>
                              </td>
                          </tr>
                          <?php } ?>
                      </table>  
                  </td>
              </tr>
          </table>
          <table style="width: 100%;margin-top: 20px;">
              <tr>
                  <td style="width:35%;border:1px solid #666;padding:5px;border-right: none;">
                      <table id="tbl_allergies">
                          <tr><td style="height: 60px;">Allergies: &nbsp;<span style="font-style: italic;"><?php echo $patient->allergies;?></span></td></tr>
                          <tr><td style="height: 100px;">Current Medications:  &nbsp;<span style="font-style: italic;"><?php echo $patient->previous_medication;?></span></td></tr>
                          <tr><td style="height: 160px;">Dr’s Alerts:
                              <?php foreach($dralerts as $alert){?> 
                                  <p style="font-weight: bold;font-size: 15px;"><?php echo date('m/d',strtotime($alert->created));?>:&nbsp;<?php echo nl2br_except_pre($alert->msg);?></p>
                              <?php } ?>
                            </td></tr>
                          <tr><td style="height: 80px;">Assessment & Plan:</td></tr>
                          
                      </table>
                  </td>
                  <td style="width: 65%;border:1px solid #666;border-left: none;vertical-align: top;padding:5px;">
                      <table>
                          <tr>
                              <td style="vertical-align: top;height: 100px;border-bottom: 1px solid #666;">
                                  <table class="border_none">
                                      <tr class="thirtyh">
                                          <td class="med_fields">
                                              If Hungry?&nbsp;&nbsp; YES&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NO&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;:&nbsp; AM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PM
                                          </td>
                                          <td class="med_fields">
                                              See the Doctor
                                          </td>
                                          <td class="med_fields">
                                              Exercise
                                          </td>
                                      </tr>
                                      <tr class="thirtyh">
                                          <td class="med_fields">
                                              [3-4 Small Meals]
                                          </td>
                                          <td class="med_fields">
                                              Question on Food
                                          </td>
                                         
                                          <td class="med_fields">
                                              Enough H2O
                                          </td>
                                      </tr>
                                      
                                      <tr class="thirtyh">
                                           <td class="med_fields">
                                              Constipated&nbsp;&nbsp; Moderate &nbsp; Severe
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
                              <td style="height: 30px;vertical-align: bottom;text-align: left;font-size: 18px;">
                                  Other Remarks (MA/CNA): During Visit
                              </td>
                          </tr>
                          <tr>
                              <td style="padding: 0px 15px;">
                                  <table id="blank_tbl">
                                      <tr><td>&nbsp;</td></tr>
                                      <tr><td>&nbsp;</td></tr>
                                      <tr><td>&nbsp;</td></tr>
                                      <tr><td>&nbsp;</td></tr>
                                      <tr><td>&nbsp;</td></tr>
                                      <tr><td>&nbsp;</td></tr>
                                  </table>
                              </td>
                          </tr>
                          <tr>
                              <td style="height: 30px;vertical-align: bottom;text-align: left;font-size: 18px;">
                                  How Resolved
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

          <table class="bottom_tbl" style="margin: 10px 0 0 0;padding: 0px;">
              <tr>
                  <td colspan="9"></td>                  
                  <td style="font-weight: bold;">Medication</td>
                  <td style="font-weight: bold;">Week / s</td>
              </tr>
              <tr>
                  <td style="text-align: left;width: 250px;padding-top: 5px;">MA Name: ........................................</td>
                  <td style="font-weight: bold;padding: 5px 0px;border: 1px solid #999;">MEDS</td>
                  <td style="width: 10px;"></td>
                  <td style="font-weight: bold;padding: 5px 0px;border: 1px solid #999;">NO MEDS</td>
                  <td style="width: 10px;"></td>
                  <td style="font-weight: bold;padding: 5px 0px;border: 1px solid #999;">SHOTS ONLY</td>
                  <td style="width: 10px;"></td>
                  <td style="font-weight: bold;padding: 5px 0px;border: 1px solid #999;">NO Inject / Supp</td>
                  <td style="width: 25px;"></td>
                  <td style="border: 1px solid #999;padding: 5px 0px;">37.5mg / 30mg / 15mg / DI 25mg</td>
                  <td style="border: 1px solid #999;width: 100px;"></td>
              </tr>
          </table>
      </div>
  </body>
</html>
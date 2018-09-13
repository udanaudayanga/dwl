<!DOCTYPE html>
<html lang="en-US">
    <head>    
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="/assets/css/finalpage.css">
        <style>
            table.avbl_meds tr td{text-align: left;font-size: 16px;height: 18px;}
            table.bottom_tbl tr td{font-size: 19px;}
        </style>
  </head>
  <body>
      <div id="container" style="margin: 0px;padding: 44px 44px 10px 44px;">
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
                                        <td class="" style="height: 25px;">Age</td>
                                        <?php $age = ($patient->dob)? date_diff(date_create($patient->dob), date_create('now'))->y:0; ?>
                                        <td style="height: 25px;"><?php echo $age;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $patient->gender==1? 'M':'F';?></strong></td>
                                    </tr>
                                    
                                    <tr class="">
                                        <td class="" style="height: 25px;">DOB</td>
                                        <td style="height: 25px;"><?php echo date('m/d/Y',strtotime($patient->dob));?></td>
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
                                          <td style="height: 35px;padding-top: 15px;">
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
                                          <td style="height: 50px;padding-top: 25px;font-size: 21px;font-weight: bold;">Currently In:</td>
                                      </tr>
                                      <tr>
                                          <td style="height: 35px;padding-top:10px;">
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
                              <td class="" style="height: 44px;width: 40%;">Initial Weight:</td>
                              <td style="height: 44px;text-align: left;width: 60%;"><?php echo (isset($first_visit))?$first_visit->weight:"-";?></td>
                          </tr>
                          <tr class="">
                              <td class="" style="height: 44px;">Goal Weight:</td>
                              <td style="height: 44px;"><?php echo $patient->goal_weight;?></td>
                          </tr>
                          
                          <tr class="">
                              <td class=""  style="height: 44px;">Today's Date:</td>
                              <td style="height: 44px;text-align: left;"><span class="bold" style="font-size:26px;"><?php echo date('m/d/Y',strtotime($last_visit->visit_date));?></span></td>
                          </tr>
                          <tr class="">
                              <td class="" style="height: 44px;">Time Stamp:</td>
                              <td style="height: 44px;"><?php echo date('h:ia',strtotime($last_visit->visit_date));?></td>
                          </tr>
                         
                      </table>
                  </td>
                  <td style="width: 30%;vertical-align: top;padding: 0px 0px 0px 10px;">
                      <table class="table_spacing" class="align-left " style="font-size: 18px;width: 100%;">
                          <tr class="">
                              <td class="" style="">
                                  <img src="/assets/img/re_eval.png" />
                              </td>                              
                          </tr>
                           <tr class="">
                              <td class="" style="padding-top: 10px;">                                  
                                  <p style="color: #A7A9AC;font-size: 50px;font-weight: bold;">12.<?php echo $eval->phase;?></p>                                  
                              </td>                              
                          </tr>
                      </table>
                  </td>
              </tr>
              
        </table>
          
        <table style="width: 100%;margin-top: 44px;">
            <tr>

                <td  style="width: 68%;vertical-align: top;">
                    <table style="width:100%;">
                        
                        <tr class="fortyh LightBorderBottom last_visits last_visits_hd">
                            <td class="LightBorderRight" style="width: 6%;">Visit #</td>
                            <td class="LightBorderRight">Phase</td>
                            <td class="LightBorderRight" style="width: 7%;"># of Wks</td>
                            <td class="LightBorderRight">Visit Date</td>
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
                        if(isset($visits[0]))
                        {
                            $pfv = $visits[0];
                            if($pfv->visit > 1)
                            {
                                $lplv = $this->patient->getPatientVisit($patient->id,($pfv->visit - 1));
                                $last_weight = $lplv->weight;
                            }                            
                        }
                        $total_weight_diff = 0;
                        $pvdate = FALSE;
                        for ($i = 0;$i<12;$i++){?>
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
                            ?>
                        <tr class="fortyh last_visits">
                            <td class="LightBorderRight"><?php echo $v->visit;?></td>
                            <td class="LightBorderRight"><?php echo getPhaseByVisit(date('Y-m-d',strtotime($v->visit_date)), $patient->id);?></td>
                            <td class="LightBorderRight"><?php echo getTurnsForDays($med_days);?></td>
                            <td class="LightBorderRight"><?php echo date('m/d/Y',strtotime($v->visit_date));?></td>
                            <td><?php echo $v->weight;?></td>                            
                            <td><?php echo $v->bfi;?></td>
                            <td><?php echo $v->bmi;?></td>
                            <td><?php echo $v->bp;?></td>
                            <?php $diff = floatval($v->weight - $last_weight); 
                                $diff = round($diff,1);
                            ?>
                            <td><?php echo $last_weight==0? 0: $diff?></td>
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
                        <?php }else{?>
                        <tr class="fortyh last_visits">
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
                <td style="width: 2%;border: none;">
                    &nbsp;
                </td>
                <td style="width: 30%;vertical-align: top;padding:10px 5px 10px 5px;border: 1px solid #666;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 50%;text-align: left;height: 40px;border-right: 1px solid #666;padding: 10px;">
                                <span style="font-size:16px;">Start Date 12.<?php echo $eval->phase;?></span><br>&nbsp;&nbsp;&nbsp;&nbsp;<strong style="font-size: 27px;"><?php echo date('m-d-y',strtotime($eval->start));?></strong>
                            </td>
                            <td style="width: 50%;text-align: left;padding: 10px;">
                                <?php $startWeight = isset($visits[0])?$visits[0]->weight: "";?>
                                <span style="font-size:16px;">Start Date Weight 12.<?php echo $eval->phase;?></span><br> &nbsp;&nbsp;&nbsp;&nbsp;<strong style="font-size: 27px;"><?php echo $startWeight;?></strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%;text-align: left;height: 40px;border-right: 1px solid #666;padding: 10px;border-bottom: 1px solid #666;">
                                <span style="font-size:16px;">GOAL Weight for 12.<?php echo $eval->phase;?></span><br>&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $patient->goal_weight;?></strong>
                            </td>
                            <td style="width: 50%;text-align: left;padding: 10px;border-bottom: 1px solid #666;">
                                <?php 
                                    $d1 = new DateTime($eval->start);
                                    $d2 = new DateTime($eval->end);
                                    $gap = $d1->diff($d2)->format("%a");
                                ?>
                                <span style="font-size:16px;">Total Time taken for 12.<?php echo $eval->phase;?></span><br>&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $gap;?> days</strong>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border-bottom: 1px solid #666;">
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="text-align: left;padding: 10px;width: 65%;font-size: 22px;height: 55px;">Total Weight Loss/Gained in 12.<?php echo $eval->phase;?></td>
                                        <?php 
                                        $vsc = count($visits);
                                        $endWeight = $visits[$vsc - 1]->weight;
                                        $tdiff = floatval($endWeight - $startWeight); 
                                        $tdiff = round($tdiff,1);
                                        ?>
                                        <td style="text-align: left;font-weight: bold;font-size: 27px;"><?php echo $tdiff;?></td>
                                        <td style="text-align: left;">
                                            <?php if($tdiff < 0){?>
                                            <!--<img src="/assets/img/greenThumbs.gif" style="width: 40px;"/>-->
                                            <?php }else{?>
                                            <!--<img src="/assets/img/redThums.gif" style="width: 40px;"/>-->
                                            <?php } ?>
                                            
                                        </td>
                                    </tr>
                                </table>
                            </td>                            
                        </tr>
                        

                    </table>
                    
                    <img style="padding: 3px 0px 0px 0px;margin: 0px;overflow: visible;" src="/assets/upload/eval/<?php echo $eval->id;?>.png" />
                    <p style="margin: 0px;padding: 0px;text-align: center;font-size: 12px;">Visits</p>
                </td>
            </tr>            
        </table>
          
          <table style="width: 100%;margin-top: 44px;">
              <tr>
                  <td style="border: 1px solid #666;padding: 10px;">
                      <table style="width: 100%;">
                          <tr>
                              <td style="text-align: left;">
                                  <strong>Current Medications: </strong><?php echo $patient->previous_medication;?>
                              </td>
                          </tr>
                          <tr>
                              <td style="text-align: left;">
                                  <strong>Physician's Notes </strong>(Nutritional Strategies, Exercise, Behavior Modification, Insulin-resistant Plan, Optimizing health and Improving overall quality of life, 
                                  Decreasing other risks, Current medication suggestive change, Visit your PCP, Order new Lab Work)
                              </td>
                          </tr>
                          <tr>
                              <td style="text-align: left;">
                                  <table style="width: 100%;">
                                      <tr><td style="border-bottom: 1px solid #999;height: 50px;">&nbsp;</td></tr>
                                      <tr><td style="border-bottom: 1px solid #999;height: 50px;">&nbsp;</td></tr>
                                      <tr><td style="border-bottom: 1px solid #999;height: 50px;">&nbsp;</td></tr>
                                      <tr><td style="border-bottom: 1px solid #999;height: 50px;">&nbsp;</td></tr>
                                      <tr><td style="border-bottom: 1px solid #999;height: 50px;">&nbsp;</td></tr>
                                      <tr><td style="border-bottom: 1px solid #999;height: 50px;">&nbsp;</td></tr>
                                      <tr><td style="border-bottom: 1px solid #999;height: 50px;">&nbsp;</td></tr>
                                      <tr><td style="border-bottom: 1px solid #999;height: 50px;">&nbsp;</td></tr>
                                      <tr><td style="border-bottom: 1px solid #999;height: 50px;">&nbsp;</td></tr>
                                      
                                      
                                  </table>
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>
              <tr>
                   <?php if($eval->phase == 1){?> 
                  <td style="padding-top: 20px;">
                      <table style="width: 100%;">
                          <tr>
                              <td style="text-align: left;width: 35%;">
                                  MA .............................................................
                              </td>
                              <td style="text-align: left;width: 40%;">
                                  Physician's Signature ..........................................................
                              </td>
                              <td style="text-align: left;width: 25%;">
                                  OK to continue to Phase 12.2.1
                              </td>
                          </tr>
                      </table>
                  </td>
                   <?php }elseif($eval->phase == 2){?> 
                    <td style="padding-top: 20px;">
                      <table style="width: 100%;">
                          <tr>
                              <td style="text-align: left;width: 35%;">
                                  MA .............................................................
                              </td>
                              <td style="text-align: left;width: 65%;">
                                  Physician's Signature ..........................................................
                              </td>                              
                          </tr>
<!--                          <tr>
                              <td style="text-align: left;width: 35%;">
                                  &nbsp;
                              </td>
                              <td style="text-align: left;width: 65%;padding-top: 10px;">
                                  <table style="width: 100%;">
                                      <tr>
                                          <td style="text-align: left;">
                                              <img src="/assets/img/blank_squire.png" style="width:20px;" /> Take a "30 day break"
                                          </td>
                                          <td style="text-align: left;">
                                              <img src="/assets/img/blank_squire.png" style="width: 20px;" /> Continue with 12.2.2 again
                                          </td>
                                      </tr>
                                  </table>
                              </td>                              
                          </tr>-->
                      </table>
                  </td> 
                   <?php } ?>
              </tr>
          </table>
          
      </div>
  </body>
</html>
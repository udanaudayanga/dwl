<!DOCTYPE html>
<html lang="en-US">
    <head>    
        <link rel="stylesheet" type="text/css" href="/assets/css/print_prescription.css">
  </head>
    <body>
      <div id="container" style="margin: 0px;">
         
          <table class="main_table" style="width: 100%;">
              <?php
              $i = 0;
              $c = count($logs);
              foreach($logs as $log){?>
              <?php if($i%2 == 0){?><tr><?php } ?>
                  <td class="child_cell" style="width: 50%;padding-top: 45px;padding-left: 45px;padding-right: 45px;">
                      <table>
                          <tr>
                              <td>
                                  <table>
                                      <tr>
                                          <td>&nbsp;</td>
                                          <td style="width: 70%;">
                                              <img src="<?php echo base_url();?>assets/img/dwlclogo.png" style="width: 340px;"/>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td>
                                              <h5>M NANDA, M.D.</h5>
                                              <h5>&nbsp;</h5>
                                          </td>
                                      
                                          <td style="padding-left: 45px;">                                              
                                              <p style="font-size: 18px;"><?php echo $log->caddress;?><br>TEL: 727-412-8208  FAX: 727-287-5101</p>
                                          </td>
                                      </tr>
                                  </table>
                              </td>
                          </tr>
                           <tr>
                               <td style="padding-top: 20px;">
                                   <table class="loc_table" style="width: 100%;">
                                       <tr>
                                           <td>DEA# <?php echo $log->dea;?></td>
                                           <td style="width: 14%;"></td>
                                           <td colspan="2" style="text-align: center;border: 1px solid #999;font-weight: bold;border-bottom: none;width: 53%;font-size: 23px;"><?php echo $location->name;?></td>
                                       </tr>
                                       <tr>
                                           <td></td>
                                           <td>Trans ID:</td>
                                           <td style="border: 1px solid #999;padding-left: 5px;border-bottom: none;border-right: none;"><?php echo $log->prescription_no;?></td>
                                           <td style="border: 1px solid #999;padding-left: 5px;<?php if(!$log->refill){?>border-left: none;<?php } ?>">
                                           <?php if($log->refill){?>
                                            Refill:  <?php echo $log->refill;?>
                                            <?php } ?>
                                           </td>
                                       </tr>
                                       <tr>
                                           <td rowspan="2" style="font-size: 19px;">Expiry Date:<br><?php echo date('m/d/Y', strtotime($log->pres_date.' +6 months'));?></td>
                                           <td>Date:</td>
                                           <td colspan="2" style="border: 1px solid #999;padding-left: 5px;"><?php echo date('l, F d, Y',strtotime($log->visit_date));?></td>
                                       </tr>
                                   </table>
                               </td>
                           </tr>
                           <tr>
                               <td style="padding-top: 20px;">
                                   <table class="name_tbl">
                                       <tr><td style="border-bottom: none;"><h4><?php echo $log->lname." ".$log->fname;?></h4></td></tr>
                                       <tr><td><?php echo $log->address.", ".$log->city.", ".$log->abbr." ".$log->zip;?></td></tr>
                                   </table>   
                               </td>
                           </tr>
                           
                                   <?php if(isset($log->med3) && $log->med3 > 0){?>
                           <tr>
                               <td style="padding-top: 30px;">
                                   <table class="med_tbl">
                                       <tr>
                                           <td style="width: 10%;text-align: center;">Rx</td>
                                           <td><?php echo $log->med3_med;?></td>
                                       </tr>
                                       <tr>
                                           <td></td>
                                           <td>
                                               <table>
                                                   <tr>
                                                       <td style="width: 10%;">Days</td>
                                                       <td style="width: 10%;border: 1px solid #666;text-align: center;"><?php echo $log->days;?></td>
                                                       <td style="text-align: right;padding-right: 10px;">Total</td>
                                                       <td style="width: 10%;border: 1px solid #666;text-align: center;"><?php echo $log->qty;?></td>
                                                       <td style="padding-left: 5px;text-transform: capitalize;"><?php echo convert_number_to_words($log->qty);?></td>
                                                   </tr>
                                               </table>
                                           </td>
                                       </tr>
                                       <tr>
                                           <td></td>
                                           <td style="font-size: 16px;padding-top: 5px;"><?php echo $log->med3_msg;?></td>
                                       </tr>
                                   </table>
                                   </td>
                           </tr>
                                   <?php } ?>
                                   <?php if(isset($log->med1) && $log->med1 > 0){?>
                                   <tr>
                               <td style="padding-top: 30px;">
                                   <table class="med_tbl">
                                       <tr>
                                           <td style="width: 10%;text-align: center;">Rx</td>
                                           <td><?php echo $log->med1_med;?></td>
                                       </tr>
                                       <tr>
                                           <td></td>
                                           <td>
                                               <table>
                                                   <tr>
                                                       <td style="width: 10%;">Days</td>
                                                       <td style="width: 10%;border: 1px solid #666;text-align: center;"><?php echo $log->days;?></td>
                                                       <td style="text-align: right;padding-right: 10px;">Total</td>
                                                       <td style="width: 10%;border: 1px solid #666;text-align: center;"><?php echo $log->qty;?></td>
                                                       <td style="padding-left: 5px;text-transform: capitalize;"><?php echo convert_number_to_words($log->qty);?></td>
                                                   </tr>
                                               </table>
                                           </td>
                                       </tr>
                                       <tr>
                                           <td></td>
                                           <td style="font-size: 16px;padding-top: 5px;"><?php echo $log->med1_msg;?></td>
                                       </tr>
                                   </table>
                                   </td>
                           </tr>
                                   <?php } ?>
                                   <?php if(isset($log->med2) && $log->med2 > 0){?>
                                   <tr>
                               <td style="padding-top: 30px;">
                                   <table class="med_tbl" style="<?php if(isset($log->med1) && $log->med1 > 0){?>padding-top: 20px;<?php } ?>">
                                       <tr>
                                           <td style="width: 10%;text-align: center;">Rx</td>
                                           <td><?php echo $log->med2_med;?></td>
                                       </tr>
                                       <tr>
                                           <td></td>
                                           <td>
                                               <table>
                                                   <tr>
                                                       <td style="width: 10%;">Days</td>
                                                       <td style="width: 10%;border: 1px solid #666;text-align: center;"><?php echo $log->days;?></td>
                                                       <td style="text-align: right;padding-right: 10px;">Total</td>
                                                       <td style="width: 10%;border: 1px solid #666;text-align: center;"><?php echo $log->qty;?></td>
                                                       <td style="padding-left: 5px;text-transform: capitalize;"><?php echo convert_number_to_words($log->qty);?></td>
                                                   </tr>
                                               </table>
                                           </td>
                                       </tr>
                                       <tr>
                                           <td></td>
                                           <td style="font-size: 16px;padding-top: 5px;"><?php echo $log->med2_msg;?></td>
                                       </tr>
                                   </table>
                                   </td>
                           </tr>
                                   <?php } ?>
                           <tr>
                               <td style="font-size: 19px;padding-top: <?php echo (isset($log->med3) || !(isset($log->med1) && isset($log->med2)))? '50px;':'40px;';?>">
                                    <?php if($log->refill){?>                                        
                                        <?php echo (5 - $log->refill);?> Refills before <?php echo date('m/d/Y', strtotime($log->pres_date.' +6 months'));?>                                        
                                    <?php }else{ ?>
                                        No Refills
                                    <?php } ?>
                               </td>
                           </tr>
                           <tr>
                               <td style="padding-top:<?php echo (isset($log->med3) || !(isset($log->med1) && isset($log->med2)))? '150px;':'40px;';?>text-align:right;">         
                               <table>
                                    <tr>
                                        <td style="font-size:15px;width:30%;text-align:left;">
                                            ELECTRONICALLY SIGNED BY<br>
                                            NANDA MANU MD<br>
                                            <?php echo date('m/d/Y',strtotime($log->visit_date));?>
                                        </td>
                                        <td style="width:40%;">
                                        <img src="<?php echo base_url();?>assets/img/sign2.jpg" style="width: 200px;"/>
                                        </td>
                                        <td style="text-align:right;font-size:20px;vertical-align: bottom;">NANDA M, MD</td>
                                    </tr>
                               </table>                          
                                   
                               </td>
                           </tr>
                      </table>
                  </td>
                  <?php if($c == $i+1 && $c%2 == 1){?> <td>&nbsp;</td><?php } ?>
                  
              <?php if($i%2 == 1 || $c== $i+1){?></tr><?php } ?>
              <?php              
              $i++;
              } ?>
          </table>
      </div>
</body>
</html>
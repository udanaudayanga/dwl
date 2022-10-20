<!DOCTYPE html>
<html lang="en-US">
    <head>    
  <link rel="stylesheet" type="text/css" href="/assets/css/label.css">
  </head>
    <body>
        <?php
        $i = 1;
        foreach($meds as $med){
            
          $tqty = $med['qty'];
        
          if($med['med_name'] !== '37 Extd')
          {
            $nw = floor($tqty/7);
            $tw = floor($nw/2);
            $ow = $nw%2;
        ?>
               <?php for($j=0;$j<$tw;$j++){ ?>
               
                    <div id="container" style="margin: 0px;<?php if(count($meds)>1 && $i==1){?>page-break-after:always;<?php } ?>">
                            <table  style="">
                                <tr>
                                    <td colspan="3" style="font-size: 12px;padding-bottom: 3px;letter-spacing: 0.5px;">
                                        Federal Law prohibits the transfer of this to any other than the patient for whom it was prescribed.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 10px;">
                                        <table class="tbl_border align_left">
                                            <tr>
                                                <td style=""><strong>RX</strong>:&nbsp; <?php echo $med['rx'];?></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="padding-right: 10px;">
                                        <table class="tbl_border align_left" >
                                            <tr>
                                                <td style=""><strong>Qty</strong>:&nbsp; 14</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <table  class="tbl_border align_left" >
                                            <tr>
                                                <td style=""><strong>Date</strong>:&nbsp; <?php echo $med['date'];?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="font-size: 17px;font-weight: normal;padding: 5px 0px 0px 0px;margin: 0px;font-weight: bold;letter-spacing: 0.6px;">
                                        <?php echo $med['name'];?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="font-size: 12px;border-bottom: 1px solid #666;font-weight: normal;padding: 0px;margin: 0px;letter-spacing: 0.2px;">
                                        <?php echo $med['addr'];?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="font-size: 12px;font-style: italic;padding-top: 3px;letter-spacing: 0.5px;">
                                        <?php echo $med['msg'];?>
                                    </td>
                                </tr>
                            </table>
                        </div>

               <?php } ?>

               <?php for($j=0;$j<$ow;$j++){ ?>
               
               <div id="container" style="margin: 0px;<?php if(count($meds)>1 && $i==1){?>page-break-after:always;<?php } ?>">
                       <table  style="">
                           <tr>
                               <td colspan="3" style="font-size: 12px;padding-bottom: 3px;letter-spacing: 0.5px;">
                                   Federal Law prohibits the transfer of this to any other than the patient for whom it was prescribed.
                               </td>
                           </tr>
                           <tr>
                               <td style="padding-right: 10px;">
                                   <table class="tbl_border align_left">
                                       <tr>
                                           <td style=""><strong>RX</strong>:&nbsp; <?php echo $med['rx'];?></td>
                                       </tr>
                                   </table>
                               </td>
                               <td style="padding-right: 10px;">
                                   <table class="tbl_border align_left" >
                                       <tr>
                                           <td style=""><strong>Qty</strong>:&nbsp; 7</td>
                                       </tr>
                                   </table>
                               </td>
                               <td>
                                   <table  class="tbl_border align_left" >
                                       <tr>
                                           <td style=""><strong>Date</strong>:&nbsp; <?php echo $med['date'];?></td>
                                       </tr>
                                   </table>
                               </td>
                           </tr>
                           <tr>
                               <td colspan="3" style="font-size: 17px;font-weight: normal;padding: 5px 0px 0px 0px;margin: 0px;font-weight: bold;letter-spacing: 0.6px;">
                                   <?php echo $med['name'];?>
                               </td>
                           </tr>
                           <tr>
                               <td colspan="3" style="font-size: 12px;border-bottom: 1px solid #666;font-weight: normal;padding: 0px;margin: 0px;letter-spacing: 0.2px;">
                                   <?php echo $med['addr'];?>
                               </td>
                           </tr>
                           <tr>
                               <td colspan="3" style="font-size: 12px;font-style: italic;padding-top: 3px;letter-spacing: 0.5px;">
                                   <?php echo $med['msg'];?>
                               </td>
                           </tr>
                       </table>
                   </div>

          <?php } ?>
        
        <?php
          }
          else
          {
                $lb_arr = [
                            11 => [11],
                            21 => [21],
                            32 => [11,21],
                            42 => [21,21]
                          ];

                $lbls = $lb_arr[$tqty];

                foreach($lbls as $lbl)
                {

        ?>
                    <div id="container" style="margin: 0px;<?php if(count($lbls)>1 && $i==1){?>page-break-after:always;<?php } ?>">
                       <table  style="">
                           <tr>
                               <td colspan="3" style="font-size: 12px;padding-bottom: 3px;letter-spacing: 0.5px;">
                                   Federal Law prohibits the transfer of this to any other than the patient for whom it was prescribed.
                               </td>
                           </tr>
                           <tr>
                               <td style="padding-right: 10px;">
                                   <table class="tbl_border align_left">
                                       <tr>
                                           <td style=""><strong>RX</strong>:&nbsp; <?php echo $med['rx'];?></td>
                                       </tr>
                                   </table>
                               </td>
                               <td style="padding-right: 10px;">
                                   <table class="tbl_border align_left" >
                                       <tr>
                                           <td style=""><strong>Qty</strong>:&nbsp; <?php echo $lbl;?></td>
                                       </tr>
                                   </table>
                               </td>
                               <td>
                                   <table  class="tbl_border align_left" >
                                       <tr>
                                           <td style=""><strong>Date</strong>:&nbsp; <?php echo $med['date'];?></td>
                                       </tr>
                                   </table>
                               </td>
                           </tr>
                           <tr>
                               <td colspan="3" style="font-size: 17px;font-weight: normal;padding: 5px 0px 0px 0px;margin: 0px;font-weight: bold;letter-spacing: 0.6px;">
                                   <?php echo $med['name'];?>
                               </td>
                           </tr>
                           <tr>
                               <td colspan="3" style="font-size: 12px;border-bottom: 1px solid #666;font-weight: normal;padding: 0px;margin: 0px;letter-spacing: 0.2px;">
                                   <?php echo $med['addr'];?>
                               </td>
                           </tr>
                           <tr>
                               <td colspan="3" style="font-size: 12px;font-style: italic;padding-top: 3px;letter-spacing: 0.5px;">
                                   <?php echo $med['msg'];?>
                               </td>
                           </tr>
                       </table>
                   </div>
        <?php 
                 $i++;

                }
          }

        $i++;
        } ?>
       
<!--    <div id="container" style="margin: 0px;">
          <table style="">
              <tr>
                  <td colspan="2" style="font-size: 11px;">
                      Federal Law prohibits the transfer of this to any other than the patient for whom it was prescribed.
                  </td>
              </tr>
              <tr>
                  <td style="padding-right: 10px;">
                      <table class="tbl_border align_center">
                          <tr>
                              <td>RX</td>
                              <td>654321</td>
                              <td>Qty</td>
                              <td>7</td>
                          </tr>
                      </table>
                  </td>
                  <td>
                      <table  class="tbl_border align_center">
                          <tr>
                              <td>Date</td>
                              <td>12/22/16</td>
                          </tr>
                      </table>
                  </td>
              </tr>
              <tr>
                  <td colspan="2" style="font-size: 16px;border-bottom: 1px solid #666;">
                      UDANA UDAYANGA, 16 Main St, Largo, FL 33767
                  </td>
              </tr>
              <tr>
                  <td colspan="2" style="font-size: 16px;">
                      TAKE ONE CAP (15 MG) A DAY AROUND 9-10 AM -- ABOUT 10-15 MINS AFTER CONSUMING FOOD
                  </td>
              </tr>
          </table>
      </div>-->
</body>
</html>
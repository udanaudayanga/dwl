<!DOCTYPE html>
<html lang="en-US">
    <head>    
  <link rel="stylesheet" type="text/css" href="/assets/css/ticket.css">
  </head>
    <body>
      <div id="container" style="margin: 0px;">
          <table style="width: 100%;">
              <tr>
                  <td style="width: 35%;padding-right: 10px;border-right: 1px solid #ccc;">
                      <table>
                          <tr>
                              <td style="width: 50%;">                                  
                                  <img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/upload/patients/<?php echo $patient->photo;?>&amp;w=64&amp;h=54&amp;zc=1&amp;f=png" />
                              </td>
                              <td style="width: 50%;vertical-align: top;padding-top: 10px;">
                                  <h4 style="font-size: 23px;">BLACK BABBARA</h4>
                              </td>
                          </tr>
                      </table>
                  </td>
                  <td style="width: 15%;vertical-align: top;padding-left: 10px;border-right: 1px solid #ccc;">
                      <table class="align-left" style="font-size: 20px;">
                          <tr class="">
                              <td class="bold" style="height: 50px;">Ht</td>
                              <td style="height: 50px;">51</td>
                          </tr>
                          <tr class="">
                              <td class="bold" style="height: 50px;">Age</td>
                              <td style="height: 50px;">45</td>
                          </tr>
                          <tr class="">
                              <td class="bold" style="height: 50px;">F</td>
                              <td style="height: 50px;"></td>
                          </tr>
                      </table>
                  </td>
                  <td style="width: 15%;vertical-align: top;padding-left: 10px;">
                      <table class="align-left" style="font-size: 20px;">
                          <tr class="">
                              <td class="bold" style="height: 50px;">Initial Wt</td>
                              <td style="height: 50px;">150.5</td>
                          </tr>
                          <tr class="">
                              <td class="bold" style="height: 50px;">GOAL Wt</td>
                              <td style="height: 50px;">135.5</td>
                          </tr>
                          <tr class="">
                              <td class="bold" style="height: 50px;">Last 2 Visit</td>
                              <td style="height: 50px;">5.6</td>
                          </tr>
                      </table>
                  </td>
                  <td style="width: 35%;vertical-align: top;">
                      <table  class="align-left fiftyh" style="font-size: 20px;">
                          <tr class="fortyh">
                              <td class="bold aright" style="text-align: right;padding-right: 10px;width: 40%">Today's Wt</td>
                              <td class="aleft" style="padding-left: 5px;width: 60%;border: 1px solid black;"></td>
                          </tr>
                          <tr class="fortyh">
                              <td class="bold"  style="text-align: right;padding-right: 10px;">Today's BMI%</td>
                              <td class="aleft" style="padding-left: 5px;border: 1px solid black;"></td>
                          </tr>
                          <tr class="fortyh">
                              <td class="bold"  style="text-align: right;padding-right: 10px;">Today's BFI%</td>
                              <td class="aleft" style="padding-left: 5px;border: 1px solid black;"></td>
                          </tr>
                          <tr class="fortyh">
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
                            <td colspan="8" style="text-align: left;padding-left: 10px;border-bottom: 1px solid #666;" class="bold">Past 6 Visit Info</td>                  
                        </tr>
                        <tr class="fortyh LightBorderBottom last_visits">
                            <td class="LightBorderRight">Visit #</td>
                            <td class="LightBorderRight">Visit Date</td>
                            <td>Weight</td>
                            <td>BMI</td>
                            <td>BFI</td>
                            <td>BP</td>
                            <td style="width: 15%;">Last 2 Visit -/+</td>
                            <td style="width: 15%;">Total Wt -/+</td>
                        </tr>
                        <?php for ($i = 0;$i<6;$i++){?>
                        <tr class="fortyh LightBorderBottom last_visits">
                            <td class="LightBorderRight"></td>
                            <td class="LightBorderRight"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php } ?>                       
                        
                    </table>
                  </td>
                  <td style="width: 30%;border: 2px solid black;">
                      <table>
                        <tr class="fiftyh last_visits">
                            <td class="bold" style="border-bottom: 1px solid #666;border-right: 1px solid #666;">Products bought today</td>
                            <td class="bold" style="border-bottom: 1px solid #666;width: 25%;">Qty</td>
                        </tr>
                        <?php for ($i = 0;$i<7;$i++){?>
                        <tr class="fortyh last_visits" style="border: 1px solid #666;">
                            <td class="LightBorderRight">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <?php } ?>   
                    </table>
                  </td>
              </tr>
          </table>
          <table style="width: 100%;margin-top: 20px;">
              <tr>
                  <td style="width: 50%;padding-right:5px;vertical-align: top;">
                      <table  class="last_visit">
                          <tr class="fortyh">
                              <td class="bold">
                                  LAST VISIT
                              </td>
                          </tr>
                          <tr>
                              <td style="height: 60px;" class="med_fields">
                                  <p style="font-weight: bold;">Allergies:</p>
                              </td>
                          </tr>
                          <tr>
                              <td style="height: 80px;" class="med_fields">
                                  <p style="font-weight: bold;">Current Medications:</p>
                              </td>
                          </tr>
                          <tr>
                              <td style="height: 60px;" class="med_fields">
                                  <p style="font-weight: bold;">Alerts:</p>
                              </td>
                          </tr>
                          <tr>
                              <td style="height: 170px;border-bottom: none;" class="med_fields">
                                  <p style="font-weight: bold;">Assessment & Plan:</p>
                              </td>
                          </tr>
                          <tr>
                              <td style="height: 80px;border-top: none;" class="med_fields">
                                  <p style="">Other Instructions:</p>
                              </td>
                          </tr>
                      </table>
                  </td>
                  <td style="width: 50%;padding-left:5px;vertical-align: top;">
                      <table  class="this_visit">
                          <tr class="fortyh">
                              <td class="bold">
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
                              <td style="height: 75px;" class="med_fields">
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
                          <tr>
                              <td style="width: 50%;">
                                  B-12 (Inj USP 0.5cc- 1M
                              </td>
                              <td style="width: 25%;">
                                  Lot #
                              </td>
                              <td style="width: 25%;">
                                  Exp Dt
                              </td>
                          </tr>
                          <tr>
                              <td style="width: 50%;">
                                  B-12 (Inj USP 0.5cc- 1M
                              </td>
                              <td style="width: 25%;">
                                  Lot #
                              </td>
                              <td style="width: 25%;">
                                  Exp Dt
                              </td>
                          </tr>
                          <tr>
                              <td style="width: 50%;">
                                  B-12 (Inj USP 0.5cc- 1M
                              </td>
                              <td style="width: 25%;">
                                  Lot #
                              </td>
                              <td style="width: 25%;">
                                  Exp Dt
                              </td>
                          </tr>
                          <tr>
                              <td style="width: 50%;">
                                  B-12 (Inj USP 0.5cc- 1M
                              </td>
                              <td style="width: 25%;">
                                  Lot #
                              </td>
                              <td style="width: 25%;">
                                  Exp Dt
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>
          </table>
          <table style="margin-top: 10px;">
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
                            <td>&#8864; Phentermine 37.5 mg TAB</td>
                            <td></td>
                            <td></td>
                            <td class="gray_bg"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="med_fields">
                            <td>&#8864; Phentermine 30 mg CAP</td>
                            <td></td>
                            <td></td>
                            <td class="gray_bg"></td>
                            <td colspan="2">&#8864; Counselled</td>                            
                            <td colspan="2">&#8864; Option to fill in pharmacy given</td>
                            
                        </tr>
                        <tr class="med_fields">
                            <td>&#8864; Phentermine 15 mg CAP</td>
                            <td></td>
                            <td></td>
                            <td class="gray_bg"></td>
                            <td colspan="4" style="padding: 0px;">
                                <table class="border_none">
                                    <tr>
                                        <td>&#8864; Amino Acids</td>
                                        <td>&#8864; Calcium</td>
                                        <td>&#8864; Multi-Vitamins</td>
                                    </tr>
                                </table>
                            </td>
                            
                        </tr>
                        <tr class="med_fields">
                            <td>&#8864; Diethylpropion 25 mg TAB</td>
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
                <td class="bold" style="width: 30%;border: 1px solid #666;text-align: left;padding-left: 5px;">Meds for Week/s</td>
                <td class="bold" style="width: 30%;border: 1px solid #666;text-align: left;padding-left: 5px;">Next Visit Dt</td>
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
                    <td style="width: 40%;text-align: left;padding-left: 5px;height: 60px;">
                        I have received a copy of my EKG / Lab report. I have been informed both verbally and in writing the follow up instructions. 
                    </td>
                    <td style="text-align: left;padding-left: 5px;height: 60px;">
                        PATIENT'S Sign:
                    </td>
                    <td style="text-align: left;padding-left: 5px;height: 60px;">
                        PA or Nurse Practitioner Signature
                    </td>
                </tr>
            </table>
      </div>
</body>
</html>
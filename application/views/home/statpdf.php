<!DOCTYPE html>
<html lang="en-US">
    <head>    
  <link rel="stylesheet" type="text/css" href="/assets/css/logs.css">
  <style>
      
      table.table-striped tbody tr:nth-child(odd) {
            background-color: #e8e8e8;
        }
      table.stats_tbl tr td{height: 44px;border: 1px solid #666;}
      table.medstbl tr td{height: 35px;border: 1px solid #666;}
      table.inj_tbl tr td,table.inj_tbl tr th{height: 30px;font-size: 18px;padding: 2px 0px;}
      tr.border_top td{border-top: 3px solid black !important;}
  </style>
  </head>
    <body>
      <div id="container" style="margin: 0px;">
          
          <table style="margin-top: 20px;" class="patients_tbl table-striped">
              <thead>
              <tr>
                  <td>DATE</td>
                  <td>TIME</td>
                  <td>STATUS</td>
                  <td>NAME</td>
                  <td style="width: 4%;">B12 HALF</td>
                  <td style="width: 4%;">B12 FULL</td>
                  <td style="width: 4%;">LIPO</td>
                  <td style="width: 4%;">ULTRA</td>
                  <td style="width: 5%;">GLUTATHIONE</td>
                  <td style="width: 4%;">AMINO</td>
                  <td style="width: 5%;">STRESS BUSTER</td>
                  <td style="width: 4%;">VIT D3</td>
                  <td style="width: 4%;">BIOTIN</td>
                  <td>CREDIT</td>
                  <td>CASH</td>
                  <td>MEDS</td>
                  <td>QTY</td>
                  <td>DAYS</td>
              </tr>
              </thead>
              <tbody>
              <?php 
                $pending = array();
                foreach($logs as $log){
                  if($log->status == 4){
                      array_push($pending, $log);
                      continue;
                  }
                ?>
              <tr>
                  <td><?php echo date('m/d/Y',strtotime($log->created));?></td>
                  <td><?php echo date('h:i a',strtotime($log->created));?></td>
                  <td><?php echo ($log->visit==1 && empty($log->last_status_date))?"New":"Existing";?></td>
                  <td><?php echo $log->lname." ".$log->fname;?></td>
                  <?php $injecs = $log->injs;?>
                  <td><?php echo isset($injecs['B-12'])?$injecs['B-12']:'';?></td>
                  <td><?php echo isset($injecs['b121cc'])?$injecs['b121cc']:'';?></td>
                  <td><?php echo isset($injecs['Lipogen'])?$injecs['Lipogen']:'';?></td>
                  <td><?php echo isset($injecs['Ultraburn'])?$injecs['Ultraburn']:'';?></td>
                  <td><?php echo isset($injecs['Glutathione'])?$injecs['Glutathione']:'';?></td>
                  <td><?php echo isset($injecs['AminoBlend'])?$injecs['AminoBlend']:'';?></td>
                  <td><?php echo isset($injecs['StressBuster'])?$injecs['StressBuster']:'';?></td>
                  <td><?php echo isset($injecs['VitD3'])?$injecs['VitD3']:'';?></td>
                  <td><?php echo isset($injecs['Biotin'])?$injecs['Biotin']:'';?></td>
                  <?php
                  $cr = $cs = '';
                  
                  if(!$log->visit_date || date('Y-m-d',strtotime($log->created)) == date('Y-m-d',strtotime($log->visit_date)))
                  {
                        switch ($log->payment_type) {
                            case 'credit':
                                $cr = $log->net_total;
                                break;
                            case 'cash':
                                $cs = $log->net_total;
                                break;
                            case 'mix':
                                $cr = $log->credit_amount;
                                $cs = $log->net_total - $log->credit_amount;
                                break;

                            default:
                                break;
                        }
                  }
                  else 
                  {
                      switch ($log->payment_type) {
                            case 'credit':
                                $cr = 'PAID';
                                break;
                            case 'cash':
                                $cs = 'PAID';
                                break;
                            case 'mix':
                                $cr = 'PAID';
                                $cs = 'PAID';
                                break;

                            default:
                                break;
                        }
                  }
                  ?>
                  <td><?php echo $cr;?></td>
                  <td><?php echo $cs;?></td>
                  <td><?php 
                    if($log->status == 3)
                    {
                        echo "SO/PrO";
                    }                    
                    elseif($log->is_med==1){
                        if($log->id > $med_change_last_orderid)
                        {
                            if($log->med1>0 && isset($medinfo[$log->med1]))
                            {
                                echo $medinfo[$log->med1];
                            }
                        }
                        else
                        {
                            if($log->med3>0)
                            {
                                echo $medinfo[$log->med3];
                            }
                            else
                            {
                                if($log->med1>0 && isset($medinfo[$log->med1]))
                                {
                                    echo $medinfo[$log->med1];
                                }
                                else 
                                {
                                    echo "-";
                                }
                                echo " / ";
                                if($log->med2>0 && isset($medinfo[$log->med2]))
                                {
                                    echo $medinfo[$log->med2];
                                }
                                else 
                                {
                                    echo "-";
                                }
                            }
                        }

                        
                    }
                    else
                    {
                        echo "No Med";
                    }
                  ?>
                  
                  </td>
                  <td><?php if(empty($log->is_med)){echo "&nbsp;";}else{ echo $log->is_med==1?ceil($log->med_days * $log->meds_per_day):"&nbsp;";}?></td>
                  <td><?php if(empty($log->is_med)){echo "&nbsp;";}else{echo $log->is_med==1?$log->med_days:$log->no_med_days;}?></td>
              </tr>
              <?php } ?>
              </tbody>
          </table>
          <?php $location = getLocation($location_id);?>
          <table class="" style="width: 100%;margin-top: 40px;">
              <tr>
                  <td style="width: 28%;vertical-align: top;padding: 0px 15px 0px 0px;">
                      
                    <table class="table-striped stats_tbl" >
                        <thead>
                            <tr>
                                <td style="height: 25px;"></td>
                                <td>#</td>
                                <td>Credit</td>
                                <td>Cash</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>                            
                                <td>New</td>
                                <td><?php echo $location->new_count = isset($stat['new']['count'])?$stat['new']['count']:0;?></td>
                                <td><?php echo $location->new_crd = isset($stat['new']['crd'])?$stat['new']['crd']:0;?></td>
                                <td><?php echo $location->new_cash = isset($stat['new']['cash'])?$stat['new']['cash']:0;?></td>
                            </tr>
                            <tr>
                                <td>Existing</td>
                                <td><?php echo $location->ex_count = isset($stat['ex']['count'])?$stat['ex']['count']:0;?></td>
                                <td><?php echo $location->ex_crd = isset($stat['ex']['crd'])?$stat['ex']['crd']:0;?></td>
                                <td><?php echo $location->ex_cash = isset($stat['ex']['cash'])?$stat['ex']['cash']:0;?></td>
                            </tr>
                            <tr>
                                <td>All</td>
                                <td><?php echo $location->new_count + $location->ex_count;?></td>
                                <td><?php echo $location->new_crd + $location->ex_crd;?></td>
                                <td><?php echo $location->new_cash + $location->ex_cash;?></td>
                            </tr>
                        </tbody>
                    </table>
                            
                  </td>
                  <td style="width: 18%;vertical-align: top;padding: 0px 15px 0px 0px;">
                      
                        <table class="table-striped stats_tbl" style="margin: 0px;">
                            <thead>
                                <tr>
                                    <td style="width: 34%;"></td>
                                    <td style="width: 34%;">#</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>                            
                                    <td>Meds</td>
                                    <td><?php echo isset($stat['med'])?$stat['med']:0;?></td>
                                </tr>
                                <tr>
                                    <td>No Meds</td>
                                    <td><?php echo isset($stat['nomed'])?$stat['nomed']:0;?></td>
                                </tr>
                                <tr>
                                    <td>SO/PrO</td>
                                    <td><?php echo isset($stat['so'])?$stat['so']:0;?></td>
                                </tr>                                            
                            </tbody>
                        </table>
                                
                  </td>
                  <td style="width: 18%;vertical-align: top;padding: 0px 15px 0px 0px;">
                      
                        <table class="stats_tbl inj_tbl table-striped" style="margin: 0px;">
                            <thead>
                                <tr>
                                    <td></td>
                                    <td>Used</td>
                                    <?php if($sg && isset($mig[$location_id]['Lipogen'][$wd])){?>
                                    <td><strong>Left</strong></td>
                                    <td>Total</td>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="">                            
                                    <td>B-12 &nbsp;0.4</td>
                                    <td><?php echo $b12 = isset($injs['B-12'])?$injs['B-12']:0;?></td>                                    
                                </tr>
                                <tr class="">                            
                                    <td>B-12 &nbsp;1cc</td>
                                    <td><?php echo $b12 = isset($injs['b121cc'])?$injs['b121cc']:0;?></td>                                    
                                </tr>
                                <tr class="border_top">
                                    <td style="border-top: 2px solid black;">Lipogen</td>
                                    <td style="border-top: 2px solid black;"><?php echo $lipogen = isset($injs['Lipogen'])?$injs['Lipogen']:0;?></td>                                    
                                </tr>
                                <tr>
                                    <td>Ultraburn</td>
                                    <td><?php echo $ub = isset($injs['Ultraburn'])?$injs['Ultraburn']:0;?></td>                                    
                                </tr>  
                                <tr>
                                    <td>Glutathione 200mg/ml</td>
                                    <td><?php echo isset($injs['Glutathione'])?$injs['Glutathione']:0;?></td>                                    
                                </tr> 
                            </tbody>
                        </table>
                                
                  </td>
                  <td style="width: 18%;vertical-align: top;padding: 0px 15px 0px 0px;">
                      
                        <table class="stats_tbl inj_tbl table-striped" style="margin: 0px;">
                            <thead>
                                <tr>
                                    <td></td>
                                    <td>Used</td>
                                    <?php if($sg && isset($mig[$location_id]['Lipogen'][$wd])){?>
                                    <td><strong>Left</strong></td>
                                    <td>Total</td>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="">                            
                                    <td>Amino Blend</td>
                                    <td><?php echo isset($injs['AminoBlend'])?$injs['AminoBlend']:0;?></td>                                    
                                </tr>
                                <tr class="">                            
                                    <td>Stress Buster</td>
                                    <td><?php echo isset($injs['StressBuster'])?$injs['StressBuster']:0;?></td>                                    
                                </tr>
                                <tr>
                                    <td>Vit D3</td>
                                    <td><?php echo $lipogen = isset($injs['VitD3'])?$injs['VitD3']:0;?></td>                                    
                                </tr>
                                <tr>
                                    <td>Biotin</td>
                                    <td><?php echo $ub = isset($injs['Biotin'])?$injs['Biotin']:0;?></td>                                    
                                </tr>  
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>                                    
                                </tr> 
                            </tbody>
                        </table>
                                
                  </td>
                  <td style="width: 18%;vertical-align: top;padding: 0px 0px 0px 0px;">
                      
                        <table class="table-striped medstbl" style="margin: 0px;">
                            <thead>
                                <tr>
                                    <td></td>
                                    <td>Used</td>
                                    <?php if($sg && isset($mig[$location_id]['37'][$wd])){?>
                                    <td><strong>Left</strong>&nbsp;/&nbsp;Total</td>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>                            
                                    <td>37.5</td>
                                    <td><?php echo $ts = isset($stat['37'])?round($stat['37']/7):0;?></td>
                                    <?php if($sg && isset($mig[$location_id]['37'][$wd])){?>
                                    <td><strong><?php echo ($mig[$location_id]['37'][$wd]) - $ts; ?></strong>&nbsp;&nbsp;(<?php echo $mig[$location_id]['37'][$wd];?>)</td>
                                    <?php } ?>
                                </tr>
                                <tr>
                                    <td>15</td>
                                    <td><?php echo $ft = isset($stat['15'])?round($stat['15']/7):0;?></td>
                                    <?php if($sg && isset($mig[$location_id]['15'][$wd])){?>
                                    <td><strong><?php echo ($mig[$location_id]['15'][$wd]) - $ft; ?></strong>&nbsp;&nbsp;(<?php echo $mig[$location_id]['15'][$wd];?>)</td>
                                    <?php } ?>
                                </tr>
                                <tr>
                                    <td>30</td>
                                    <td><?php echo $tf = isset($stat['30'])?round($stat['30']/7):0;?></td>
                                    <?php if($sg && isset($mig[$location_id]['30'][$wd])){?>
                                    <td><strong><?php echo ($mig[$location_id]['30'][$wd]) - $tf; ?></strong>&nbsp;&nbsp;(<?php echo $mig[$location_id]['30'][$wd];?>)</td>
                                    <?php } ?>
                                </tr>
                                <tr>
                                    <td>DI</td>
                                    <td><?php echo $di = isset($stat['DI'])?round($stat['DI']/7):0;?></td>
                                    <?php if($sg && isset($mig[$location_id]['di'][$wd])){?>
                                    <td><strong><?php echo ($mig[$location_id]['di'][$wd]) - $di; ?></strong>&nbsp;&nbsp;(<?php echo $mig[$location_id]['di'][$wd];?>)</td>
                                    <?php } ?>
                                </tr>
                            </tbody>
                        </table>
                                
                  </td>
              </tr>
          </table>
          <table style="margin-top: 20px;" class="patients_tbl table-striped">
              <thead>
                  <tr>
                      <td colspan="7" style="font-weight: bold;padding-left: 20px;text-align: left;">PENDING</td>
                  </tr>
              <tr>
                  <td>DATE</td>
                  <td>TIME</td>
                  <td>STATUS</td>
                  <td>NAME</td>
                  <td>CREDIT</td>
                  <td>CASH</td>
                  <td>MEDS</td>
              </tr>
              </thead>
              <tbody>
              <?php 
                foreach($pending as $log){
                ?>
              <tr>
                  <td><?php echo date('m/d/Y',strtotime($log->created));?></td>
                  <td><?php echo date('h:i a',strtotime($log->created));?></td>
                  <td><?php echo ($log->visit==1 && empty($log->last_status_date))?"New":"Existing";?></td>
                  <td><?php echo $log->lname." ".$log->fname;?></td>
                  <?php
                  $cr = $cs = '';
                  switch ($log->payment_type) {
                      case 'credit':
                          $cr = $log->net_total;
                          break;
                      case 'cash':
                          $cs = $log->net_total;
                          break;
                      case 'mix':
                          $cr = $log->credit_amount;
                          $cs = $log->net_total - $log->credit_amount;
                          break;

                      default:
                          break;
                  }
                  ?>
                  <td><?php echo $cr;?></td>
                  <td><?php echo $cs;?></td>
                  <td><?php 
                    if($log->status == 4)
                    {
                        echo "PNDG";
                    }                    
                    elseif($log->is_med==1){
                        if($log->med3>0)
                        {
                            echo $medinfo[$log->med3];
                        }
                        else
                        {
                            if($log->med1>0 && isset($medinfo[$log->med1]))
                            {
                                echo $medinfo[$log->med1];
                            }
                            else 
                            {
                                echo "-";
                            }
                            echo " / ";
                            if($log->med2>0 && isset($medinfo[$log->med2]))
                            {
                                echo $medinfo[$log->med2];
                            }
                            else 
                            {
                                echo "-";
                            }
                        }
                    }
                    else
                    {
                        echo "No Med";
                    }
                  ?>
                  
                  </td>
              </tr>
              <?php } ?>
              </tbody>
          </table>
      </div>
</body>
</html>
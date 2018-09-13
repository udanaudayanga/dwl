<!DOCTYPE html>
<html lang="en-US">
    <head>    
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style>
            table tr td,table tr th{border: 1px solid #999;padding:5px;font-size: 24px;}
            table.line_tbl tr td{font-size: 18px;}
            table.header_tbl tr td{border: none;}
        </style>
  </head>
  <body>
      <div id="container" style="margin: 0px;padding: 10px;">
          <table class="header_tbl" style="width: 100%;">
              <tr>
                  <td style="text-transform: uppercase; width: 40%;text-align: left;">
                      <strong>Employee Schedule:</strong>
                  </td>
                  <td style=" width: 30%;text-align: left;">
                      <strong>Location: </strong> <?php echo $daily_loc=='all'?'ALL':  getLocation($daily_loc)->name;?>
                  </td>
                  <td style=" width: 30%;text-align: left;">
                      <strong>Date: </strong> <?php echo date('D M d',strtotime($date));?>
                  </td>
              </tr>
              
          </table>
          <table class=""  border="0" cellspacing="0" cellpadding="0" width="100%" style="margin-top: 20px;">
                        <thead>
                            <tr>
                                <th style="width: 15%;">Employee [<?php echo count($emp_shifts);?>]</th>
                                <th>Shifts</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach($emp_shifts as $key=>$emp){?>
                            <tr>
                                <td><?php echo $emp['name'];?> 
                                    <br><span style="font-size: 18px;"><?php echo isset($emp['s1'])?'['.gmdate('H:i',$emp['s1']).']':'[0]';?>&nbsp;<?php echo isset($emp['s2'])?'['.gmdate('H:i',$emp['s2']).']':'[0]';?>&nbsp;<?php echo isset($emp['s3'])?'['.gmdate('H:i',$emp['s3']).']':'[0]';?></span></td>

                                <?php
                                $shift = $emp['shifts'];
                                $colors = $emp['color'];
                                ?>
                                
                                        <td>
                                            <table class="line_tbl" style="width: 100%;">
                                            <?php 
                                                $start = strtotime('06:00:00');
                                                $fss = strtotime($shift[1]['start']);
                                                $fmlv = ceil($fss - $start)/60;
                                                $fmlv = $fmlv/30;
                                                
                                                $fwv = (strtotime($shift[1]['end']) - strtotime($shift[1]['start']))/60;
                                                $fw = $fwv/30;
                                                
                                                $first = $second = $third = TRUE;
                                                
//                                                echo $fmlv." ".$fw;
//                                                die();
                                                
                                                if(isset($shift[2])){                                                    
                                                    $sss = strtotime($shift[2]['start']);
                                                    $smlv = ceil($sss - $start)/60;
                                                    $smlv = $smlv/30;
                                                    $swv = (strtotime($shift[2]['end']) - strtotime($shift[2]['start']))/60;
                                                    $sw = $swv/30;
                                                }
                                                if(isset($shift[3])){
                                                    $tss = strtotime($shift[3]['start']);
                                                    $tmlv = ceil($tss - $start)/60;
                                                    $tmlv = $tmlv/30;
                                                    
                                                    $twv = (strtotime($shift[3]['end']) - strtotime($shift[3]['start']))/60;
                                                    $tw = $twv/30;
                                                }
                                            ?>
                                            <tr>
                                                <?php for($i=0;$i<14;$i++){?> 
                                                <td colspan="2" style="width: <?php echo (100/14);?>%;height:20px;border: 1px solid #ccc;float: left;padding-left:2px;text-align: left;padding-bottom: 2px;line-height: 20px;font-size:18px;">
                                                        <?php echo substr(date('ga',strtotime("+$i hours", $start)),0,-1);?>
                                                    </td>
                                                <?php } ?> 
                                            </tr>
                                            <tr>
                                            <?php for($i=1;$i<=28;$i++){?> 
                                                    
                                                    
                                                <?php if($i>$fmlv && ($fw+$fmlv)>=$i){?>
                                                    <?php if($first){?>
                                                    <td colspan="<?php echo $fw;?>" style="height:20px;border: 1px solid #ccc;float: left;padding-left:2px;text-align: left;padding-bottom: 2px;line-height: 20px;font-size:18px;display: block;background-color: <?php echo $colors[0];?>;color: #FFFFFF;text-align: center;">
                                                    <?php echo dateFormat($shift[1]['start'])." - ".dateFormat($shift[1]['end']);?>
                                                    </td>
                                                    <?php $first = FALSE; } ?>
                                                <?php }else if(isset($shift[2]) && $i>$smlv && ($fw+$smlv)>=$i){?> 
                                                    <?php if($second){?>
                                                    <td colspan="<?php echo $sw;?>" style="height:20px;border: 1px solid #ccc;float: left;padding-left:2px;text-align: left;padding-bottom: 2px;line-height: 20px;font-size:18px;display: block;background-color: <?php echo $colors[1];?>;color: #FFFFFF;text-align: center;">
                                                    <?php echo dateFormat($shift[2]['start'])." - ".dateFormat($shift[2]['end']);?>
                                                    </td>
                                                    <?php $second = FALSE; } ?>
                                                <?php }else if(isset($shift[3]) && $i>$tmlv && ($tw+$tmlv)>=$i){?> 
                                                    <?php if($third){?>
                                                    <td colspan="<?php echo $tw;?>" style="height:20px;border: 1px solid #ccc;float: left;padding-left:2px;text-align: left;padding-bottom: 2px;line-height: 20px;font-size:18px;display: block;background-color: <?php echo $colors[2];?>;color: #FFFFFF;text-align: center;">
                                                    <?php echo dateFormat($shift[3]['start'])." - ".dateFormat($shift[3]['end']);?>
                                                    </td>
                                                    <?php $third = FALSE; } ?>
                                                <?php }else{?> 
                                                <td style="width: <?php echo (100/28);?>%;height:20px;border: none;float: left;padding-left:2px;text-align: left;padding-bottom: 2px;line-height: 20px;font-size:14px;display: block;">
                                                    &nbsp;
                                                </td>
                                                <?php } ?>
                                                    
                                                    
                                            <?php } ?>
                                            </tr>
                                            
                                            </table>
                                        </td>
                            </tr>
                            <?php } ?>
                            
                        </tbody>
                    </table>
      </div>
  </body>
</html>
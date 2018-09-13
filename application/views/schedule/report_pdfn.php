<!DOCTYPE html>
<html lang="en-US">
    <head>    
  <link rel="stylesheet" type="text/css" href="/assets/css/logs.css">
  <style>
    @page {
        margin: 130px 50px 10px 50px;
    }
    .label {
        border-radius: 0.25em;
        color: #000;
        display: inline;
        font-size: 100%;
        font-weight: 700;
        line-height: 1;
        padding: 0.2em 0.8em 0.3em;
        text-align: center;
        vertical-align: baseline;
        white-space: nowrap;
    }
    table.patients_tbl tr td{border: 1px solid #999;padding: 0px 2px;height: 35px;}
    table tr.zebra td{
            background-color: #e8e8e8;
        }
  </style>
  </head>
    <body>
      <div id="container" style="margin: 0px;padding: 0px 0px 0px 0px;">
          <h4>FROM: <?php echo date('m/d/Y',strtotime($from));?>   &nbsp; - &nbsp;   TO: <?php echo date('m/d/Y',strtotime($to));?></h4>
          
          <table style="margin-top: 20px;" class="patients_tbl table-striped">
              <thead>
                <tr>
                    <td style="font-weight: bold;">Employee</td>
                    <td style="font-weight: bold;width: 80px;">Location</td>
                    <td style="font-weight: bold;width: 120px;">Week</td>
                    <?php for($i=0;$i<6;$i++){
                        $day = date('D',strtotime("+".$i." days", strtotime($week['wk1']['start'])));
                        ?> 
                    <td style="font-weight: bold;"><?php echo $day;?></td>
                    <?php } ?> 
                    
                    <td style="font-weight: bold;width: 100px;">Total Hrs</td>
                </tr>
              </thead>
              <tbody>
                    <?php
                    $z = 0;
                    foreach($shifts as $user_id=>$user){
                        $locs = $user['locs'];
                        $name_row = true;
                        foreach($locs as $loc_id=>$weeks){
                            $wk1 = isset($weeks['wk1'])?$weeks['wk1']:array();
                            $wk2 = isset($weeks['wk2'])?$weeks['wk2']:array();
                    ?>
                  <tr class="<?php if($z%2 == 0) echo 'zebra';?>">
                            <?php if($name_row){?>
                            <td rowspan="<?php echo count($locs) * 2;?>" style="text-align: center;font-size: 20px;">
                                <?php echo $user['name'];?>
                                <br> 
                                [<?php echo secToHM($user['work']);?>]
                            </td>
                            <?php } ?>
                            <td rowspan="2" style="text-align: center;"><?php echo $weeks['abbr']?></td>
                            <td style="text-align: center;"><?php echo date('m/d',strtotime($week['wk1']['start']))." - ".date('m/d',strtotime('-1 day',strtotime($week['wk1']['end'])));?></td>
                            <?php for($i=0;$i<6;$i++){
                                $day = date('Y-m-d',strtotime("+".$i." days", strtotime($week['wk1']['start'])));
                                ?> 
                            <td style="font-weight: bold;">
                                <?php if(isset($wk1[$day])){
                                    $ds =  $wk1[$day];
                                ?>
                                <table> 
                                    <tr class="<?php if($z%2 == 0) echo 'zebra';?>">
                                        <td style="border: none;padding: 0px;">
                                            <span class="label" style="">
                                                &nbsp;<?php echo dateFormat($ds[1]['start'])." - ".dateFormat($ds[1]['end']);?>&nbsp;
                                            </span>
                                        </td>
                                    <?php if(isset($ds[2])){?>
                                        <td style="border: none;padding: 0px;border-left: 1px solid #666;">
                                        <span class="label" style="margin-left: 20px;">
                                            &nbsp;<?php echo dateFormat($ds[2]['start'])." - ".dateFormat($ds[2]['end']);?>&nbsp;
                                        </span>
                                        </td>
                                    <?php } ?>
                                    </tr>
                                </table>
                                <?php } ?>
                            </td>
                            <?php } ?> 
                            <td style="text-align: center;"><?php echo secToHM(isset($weeks['wk1c'])?$weeks['wk1c']:0);?></td>

                        </tr>
                        
                        <tr class="<?php if($z%2 == 0) echo 'zebra';?>">
                            
                           <td style="text-align: center;"><?php echo date('m/d',strtotime($week['wk2']['start']))." - ".date('m/d',strtotime('-1 day',strtotime($week['wk2']['end'])));?></td>
                            
                            <?php for($i=0;$i<6;$i++){
                                $day = date('Y-m-d',strtotime("+".$i." days", strtotime($week['wk2']['start'])));
                                ?> 
                            <td style="font-weight: bold;">
                                <?php if(isset($wk2[$day])){
                                    $ds =  $wk2[$day];
                                ?>
                                <table> 
                                    <tr  class="<?php if($z%2 == 0) echo 'zebra';?>">
                                        <td style="border: none;padding: 0px;">
                                            <span class="label" style="">
                                                &nbsp;<?php echo dateFormat($ds[1]['start'])." - ".dateFormat($ds[1]['end']);?>&nbsp;
                                            </span>
                                        </td>
                                    <?php if(isset($ds[2])){?>
                                        <td style="border: none;padding: 0px;border-left: 1px solid #666;">
                                        <span class="label" style="margin-left: 20px;">
                                            &nbsp;<?php echo dateFormat($ds[2]['start'])." - ".dateFormat($ds[2]['end']);?>&nbsp;
                                        </span>
                                        </td>
                                    <?php } ?>
                                    </tr>
                                </table>
                                <?php } ?>
                            </td>
                            <?php } ?> 
                            <td style="text-align: center;"><?php echo secToHM(isset($weeks['wk2c'])?$weeks['wk2c']:0);?></td>

                        </tr>
                        <?php    
                        $name_row = FALSE;
                        }
                        $z++;
                    }?>
              </tbody>
          </table>
      </div>
</body>
</html>
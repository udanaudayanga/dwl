<!DOCTYPE html>
<html lang="en-US">
    <head>    
  <link rel="stylesheet" type="text/css" href="/assets/css/logs.css">
  <style>
      table thead tr td{background-color: #efefef;font-weight: bold;padding: 5px 0px;}
  </style>
  </head>
    <body>
      <div id="container" style="margin: 0px;">
          <table class="" style="width: 100%;">
              <tr>
                  <td style="text-transform: uppercase; width: 35%;text-align: left;">
                      <strong>WEEKLY USAGE:</strong>
                  </td>
                  
                  <td style=" width: 35%;text-align: left;">
                      <strong>Location: </strong> <?php echo getLocation($loc_id)->name;?>
                  </td>
                  <td style=" width: 30%;text-align: left;">
                      <strong>Week: </strong> <?php echo date('W',strtotime($weekdays[1]));?>
                  </td>
              </tr>
              
          </table>
         <?php foreach($meds as $key => $med){?>
          <?php                         
                $tbl = $mis[$key];                            
            ?>
          <table style="margin-top: 20px;page-break-inside: avoid;" class="patients_tbl ">
              <thead>
                  <tr><td colspan="6" style="font-size: 20px;"><?php echo $med;?></td></tr>
                    <tr>
                        <td>Date</td>
                        <td>Start of Day/Given</td>
                        <td class="strip" style="width: 20%;">End of Today Count<br>(left in the bags)</td>
                        <td>Utilized Today</td>
                        <td style="width: 20%;">Auto Utilized<br>(From Today’s Orders)</td>
                        <td style="width: 20%;">Staff</td>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php foreach($tbl as $ky=>$val){?> 
                        
                    <tr>
                        <td><?php echo date('D M d',strtotime($ky));?></td>
                        <td class="font_nmbr"><?php $given = isset($val['given'])?$val['given']:0; echo $given;?></td>
                        <td class="strip font_nmbr">
                            <?php $used = isset($val['used'])?$val['used']:0; echo $used;?>
                        </td>
                        <td class="font_nmbr" style=""><?php echo ($given - $used)>0?($given - $used):0;?></td>
                        <td class="stripblue font_nmbr" style="">
                            <?php echo $val['ou'];?>
                        </td>
                        <td>                            
                                <?php if($val && isset($val['staff'])){echo $val['staff'];}?>
                        </td>
                    </tr> 
                    
                    <?php } ?>
                </tbody>
          </table>
         <?php } ?>
      </div>
</body>
</html>
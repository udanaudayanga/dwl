<!DOCTYPE html>
<html lang="en-US">
    <head>    
  <link rel="stylesheet" type="text/css" href="/assets/css/logs.css">
  </head>
    <body>
      <div id="container" style="margin: 0px;padding: 80px 40px 0px 40px;">
          <h4>FROM: <?php echo date('m/d/Y',strtotime($from));?>   &nbsp; - &nbsp;   TO: <?php echo date('m/d/Y',strtotime($to));?></h4>
          
          <table style="margin-top: 20px;" class="patients_tbl">
              <thead>
              <tr>
                    <td style="font-weight: bold;">Employee Name</td>
                    <td style="font-weight: bold;">Total Hrs</td>
                    <td style="font-weight: bold;">Date</td>
                    <td style="font-weight: bold;">Location</td>
                </tr>
              </thead>
              <tbody>
              <?php foreach($shifts as $date=>$users){
                        foreach($users as $user=>$locs){
                            foreach($locs as $loc_id=>$data){
                                $shift = $data['shift'];
                  ?>
                <tr>
                    
                    <td style="text-align: center;"><?php echo $shift->lname." ".$shift->fname;?></td>
                    <td style="text-align: center;"><?php echo gmdate ('H:i', $data['work']);?></td>
                    <td style="text-align: center;"><?php echo $shift->date;?></td>
                    <td style="text-align: center;"><?php echo $shift->abbr;?></td>
                    
                </tr>
                        <?php }                        
                        }
                    }?>
              </tbody>
          </table>
      </div>
</body>
</html>
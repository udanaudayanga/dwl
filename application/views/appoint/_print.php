<!DOCTYPE html>
<html lang="en-US">
    <head>    
  <link rel="stylesheet" type="text/css" href="/assets/css/logs.css">
  </head>
    <body>
      <div id="container" style="margin: 0px;">
          <table class="" style="width: 100%;">
              <tr>
                  <td style="text-transform: uppercase; width: 40%;text-align: left;">
                      <strong>APPOINTMENTS:</strong>
                  </td>
                  <td style=" width: 30%;text-align: left;">
                      <strong>Location: </strong> <?php echo getLocation($loc_id)->name;?>
                  </td>
                  <td style=" width: 30%;text-align: left;">
                      <strong>Date: </strong> <?php echo date('m/d/Y',strtotime($date));?>
                  </td>
              </tr>
              
          </table>
          <?php 
                $appType = array();
                $appType[1] = "New";
                $appType[2] = "Weekly";
                $appType[3] = "Dr.Consult";
                $appType[4] = "Shots Only";
            ?>
          <table style="margin-top: 20px;" class="patients_tbl">
              <thead>
              <tr>   
                  <td style="font-weight: bold;">TIME</td>
                  <td style="font-weight: bold;">NAME</td>
                  <td style="font-weight: bold;">TYPE</td>
                  <td style="font-weight: bold;">PHONE</td>
                  <td style="width: 25%;font-weight: bold;">NOTE</td>
              </tr>
              </thead>
              <tbody>
              <?php foreach($appoints as $app){?>
              <tr>        
                  <td><?php echo date("h:i a",strtotime($app->time));?></td>
                  <td style="text-transform: capitalize;"><?php echo $app->patient_id? $app->lname." ".$app->fname:$app->last_name." ".$app->first_name;?></td>
                  <td><?php echo $appType[$app->type];?></td>
                  <td><?php echo $app->patient_id?getPhnFormat($app->phone):getPhnFormat($app->phn);?></td>
                  <td><?php echo $app->note;?></td>
              </tr>
              <?php } ?>
              </tbody>
          </table>
      </div>
</body>
</html>
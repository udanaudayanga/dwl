<!DOCTYPE html>
<html lang="en-US">
    <head>    
  <link rel="stylesheet" type="text/css" href="/assets/css/logs.css">
  <style>
      @page {
            margin: 130px 200px 20px 200px;
          }
        table.patients_tbl thead tr td{font-size: 27px;}
        table.patients_tbl tbody tr td{font-size: 27px;}
  </style>
  </head>
    <body>
      <div id="container" style="margin: 0px;padding: 0px;">
          
          <table style="margin-top: 20px;" class="patients_tbl">
              <thead>
              <tr>
                    <td style="font-weight: bold;padding: 5px 0px;">Products</td>
                    <td style="text-align: center;font-weight: bold;">MAIN</td>
                    <?php foreach($locations as $loc){?>
                    <td style="text-align: center;font-weight: bold;"><?php echo !empty($loc->abbr)?$loc->abbr:$loc->name;?></td>
                    <?php } ?>
                </tr>
              </thead>
              <tbody>
              <?php foreach($products as $pro){?>
                <tr>
                    <td style="padding: 5px 0px 5px 20px;text-align: left;"><?php echo $pro->name;?></td>
                    <td style="text-align: center;padding: 5px 0px;"><?php echo empty($pro->stock)? 0 : $pro->stock;?></td>
                     <?php foreach($locations as $loc){
                         $ls = $pro->ls;
                    ?>
                    <td style="text-align: center;padding: 5px 0px;"><?php echo isset($ls[$loc->id])?$ls[$loc->id]['qty']:0;?>&nbsp;(<?php echo isset($ls[$loc->id])?$ls[$loc->id]['msl']:0;?>)</td>
                    <?php } ?>
                </tr>
                <?php } ?>
              </tbody>
          </table>
      </div>
</body>
</html>
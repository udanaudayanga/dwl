<!DOCTYPE html>
<html lang="en-US">
    <head>    
  <link rel="stylesheet" type="text/css" href="/assets/css/logs.css">
  <style>
      table.table-striped tbody tr:nth-child(odd) {
            background-color: #e8e8e8;
        }
  </style>
  </head>
    <body>
      <div id="container" style="margin: 0px;padding: 0px 40px 0px 40px;">
          <h4><?php echo $title;?></h4>
          
          <table style="margin-top: 20px;" class="patients_tbl table-striped">
              <thead>
                <tr>
                    <th style="width: 20%;">Patient</th>
                    <th style="width: 20%;">Last Visit</th>
                    <th style="width: 15%;">Med Days</th>
                    <th style="width: 20%;">No Show Date</th>
                    <th style="width: 25%;">Days over No Show date</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($pds as $pd){?>
                <tr>
                    <td><?php echo $pd->lname." ".$pd->fname;?></td>
                    <td><?php echo $pd->visit_date;?></td>
                    <td><?php echo $pd->med_days;?></td>
                    <?php $no_show_date =  date('Y-m-d',strtotime("+$pd->med_days days", strtotime($pd->visit_date))); ?>
                    <td><?php echo date("m/d/Y",strtotime($no_show_date));?></td>
                    <?php 
                    $date1 = new DateTime($no_show_date);
                    $date2 = new DateTime(date("Y-m-d"));
                    $diff = $date2->diff($date1)->format("%a");
                    ?>
                    <td><?php echo $diff;?></td>
                </tr>
            <?php } ?>
            </tbody>
          </table>
      </div>
</body>
</html>
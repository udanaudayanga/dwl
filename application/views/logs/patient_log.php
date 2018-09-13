<!DOCTYPE html>
<html lang="en-US">
    <head>    
  <link rel="stylesheet" type="text/css" href="/assets/css/logs.css">
  </head>
    <body>
      <div id="container" style="margin: 0px;">
          <table class="" style="width: 100%;">
              <tr>
                  <td style="text-transform: uppercase; width: 15%">
                      <?php if(file_exists("./assets/upload/patients/$patient->photo")){?>
                        <img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/upload/patients/<?php echo $patient->photo;?>&amp;h=125&amp;f=png" />
                        <?php }else{ 
                            $gender_img = $patient->gender == 1 ? "male.png":"female.png";
                        ?>                                  
                        <img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/<?php echo $gender_img;?>&amp;h=125&amp;f=png" />
                        <?php } ?>
                  </td>
                  <td style="text-transform: uppercase; width: 25%;text-align: left;vertical-align: top;">
                      <p style="margin-top: 0px;font-size: 22px;font-weight: bold;"><?php echo $patient->lname." ".$patient->fname;?></p>
                      <p style="font-size: 19px;padding-top: 20px;">DOB:  <?php echo date('m/d/Y',strtotime($patient->dob));?></p>
                      <p style="font-size: 19px;padding-top: 20px;">PHN:  <?php echo "(".substr($patient->phone, 0, 3).") ".substr($patient->phone, 3, 3)."-".substr($patient->phone,6);?></p>
                  </td>
                  <td style="width: 60%;text-align: left;vertical-align: top;font-size: 19px;">
                        <?php echo $patient->address;?><br>
                        <?php echo $patient->city.", ".getStateAbbr($patient->state)." ".$patient->zip;?><br>
                  </td>
                 
              </tr>
              
          </table>
          <table style="margin-top: 20px;" class="patients_tbl">
              <thead>
              <tr>   
                  <td>Location</td>
                  <td style="font-weight: bold;">Pres#</td>
                  <td style="font-weight: bold;">Pres Date</td>
                  <td>Expiry Date</td>
                  <td>Refill#</td>
                  <td>Refill Date</td>                  
                  <td>Medication</td>
                  <td>Qty</td>
                  <td>Days</td>                  
              </tr>
              </thead>
              <tbody>
              <?php foreach($logs as $log){?>
              <tr>        
                  <td><?php echo $log->loc;?></td>          
                  <td style="font-weight: bold;"><?php echo $log->prescription_no;?></td>
                  <td style="font-weight: bold;"><?php echo $log->pres_date;?></td>
                  <td><?php echo date('m/d/Y', strtotime($log->pres_date.' +6 months'));?></td>
                  <td><?php echo $log->refill? $log->refill:'';?></td>
                  <td><?php echo $log->refill? date('m/d/Y',strtotime($log->visit_date)):'';?></td>                  
                  <td><?php echo $log->medi;?></td>
                  <td><?php echo $log->qty;?></td>
                  <td><?php echo $log->days;?></td>                  
              </tr>
              <?php } ?>
              </tbody>
          </table>
      </div>
</body>
</html>
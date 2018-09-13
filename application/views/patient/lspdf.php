<!DOCTYPE html>
<html lang="en-US">
    <head>    
        <link rel="stylesheet" type="text/css" href="/assets/css/finalpage.css">
        <style>
            table#last_status_tbl tr td{border: 1px solid #666;text-align: left;width: 20%;padding: 5px;}
            table#meds_tbl tr td{text-align: left;width: 50%;vertical-align: top;}
        </style>
  </head>
  <body>
      <div id="container" style="margin: 0px;padding: 40px 40px 10px 40px;">
          <table style="width: 100%;">
              <tr>
                  <td style="vertical-align: top;">
                      <?php if(file_exists("./assets/upload/patients/$patient->photo")){?>
                    <img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/upload/patients/<?php echo $patient->photo;?>&amp;w=64&amp;h=54&amp;zc=1&amp;f=png" />
                    <?php }else{ 
                        $gender_img = $patient->gender == 1 ? "male.png":"female.png";
                    ?>                                  
                    <img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/<?php echo $gender_img;?>&amp;w=64&amp;h=54&amp;zc=1&amp;f=png" />
                    <?php } ?>
                  </td>
                  <td style="vertical-align: top;">
                      <table>
                          <tr>
                            <td style="height: 30px;text-align: left;">
                                <h4 style="font-size: 24px;text-align: left;padding: 0px;margin: 0px;"><?php echo $patient->lname." ".$patient->fname;?></h4>
                            </td>
                        </tr>
                        <tr class="">
                                <td class="" style="text-align: left;"><?php echo $patient->gender==1? 'M':'F';?></td>
                            </tr>
                      </table>
                  </td>
                  <td style="vertical-align: top;">
                      <table style="width: 70%;">
                            <tr class="">
                                <td class="" style="height: 25px;vertical-align: top;text-align: left;">Height:</td>
                                  <?php $height = $patient->height;
                                      $feet = floor($height/12);$inches = ($height%12);
                                  ?>
                                <td style="height: 25px;text-align: left;vertical-align: top;text-align: left;"><?php echo $feet."'";?> <?php if($inches > 0) echo $inches.'"';?></td>
                            </tr>
                            <tr class="">
                                <td class="" style="height: 25px;text-align: left;">Age:</td>
                                <?php $age = ($patient->dob)? date_diff(date_create($patient->dob), date_create('now'))->y:0; ?>
                                <td style="height: 25px;text-align: left;"><?php echo $age;?></td>
                            </tr>
                            
                      </table>
                  </td>
              </tr>              
          </table>
          
          <table id="last_status_tbl" style="margin-top: 20px;">
              <tr>
                  <td style="font-weight: bold;">Location</td>
                  <td style="font-weight: bold;">Visit Date</td>    
                  <td style="font-weight: bold;">Weight</td>
                  <td style="font-weight: bold;">BFI</td>
                  <td style="font-weight: bold;">BMI</td>
                  <td style="font-weight: bold;">BP</td>
              </tr>
              <tr>
                  <td><?php echo getLocation($pls->location_id)->abbr;?></td>
                  <td><?php echo ($patient->last_status_date)?date('m/d/Y',strtotime($patient->last_status_date)):'';?></td>
                  <td><?php echo $pls->weight;?></td>
                  <td><?php echo $pls->bfi;?></td>
                  <td><?php echo $pls->bmi;?></td>
                  <td><?php echo $pls->bp;?></td>
              </tr>
          </table>
          <table id="meds_tbl" style="margin-top: 20px;">
              <tr>
                  <td style="font-weight: bold;padding: 5px;border-right: 1px solid #666;border-bottom: 1px solid #666;">Previous (Available Today)</td>
                  <td style="font-weight: bold;padding: 5px;border-bottom: 1px solid #666;">Dr's Note</td>
              </tr>
              <tr>
                  <td style="border-right: 1px solid #666;padding: 5px;"><?php echo $pls->avbl_today;?></td>
                  <td style="padding: 5px;"><?php echo $pls->dr_note;?></td>
              </tr>
          </table>
      </div>
  </body>
</html>
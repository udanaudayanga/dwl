<!DOCTYPE html>
<html lang="en-US">
    <head>    
  <link rel="stylesheet" type="text/css" href="/assets/css/logs.css">
  </head>
    <body>
      <div id="container" style="margin: 0px;padding: 0px 15px;">
          <table class="header_table" style="width: 100%;">
              <tr>
                  <td style="text-transform: uppercase; width: 12%">
                      <?php echo $loc->name;?>
                  </td>
                  <td style="width: 18%">
                      <?php echo date('l, F d, Y',strtotime($start));?><br> - <br><?php echo date('l, F d, Y',strtotime($end));?>
                  </td>
                  <td style="width: 16%;vertical-align: bottom;border-right-width: 3px;border-left: none;">
                      MANU NANDA M.D.
                  </td>
                  <td style="width: 27%;padding: 0px;border-right-width: 3px;">
                      <table class="medications" style="width: 100%;">
                          <tr class="fortyh"><td style="width: 80%;border-top: none;border-left: none;">Phentermine Hydrochloride 37.5mg TAB</td><td style="width: 20%;border-top: none;border-left: none;border-right: none;"><?php if(isset($counts[37])) echo $counts[37]; ?></td></tr>
                          <tr class="fortyh"><td style="width: 80%;border-top: none;border-left: none;border-top: none;border-bottom: none;">Phentermine Hydrochloride 37.5mg Extd TAB</td><td style="width: 20%;border: none;"><?php if(isset($counts['37 Extd'])) echo $counts['37 Extd']; ?></td></tr>
                      </table>
                  </td>
                  <td style="width: 27%;padding: 0px;">
                      <table class="medications" style="width: 100%;">
                          <tr class="fortyh"><td style="width: 80%;border-top: none;border-left: none;">Phentermine Hydrochloride 15 mg CAP</td><td style="width: 20%;border-top: none;border-left: none;border-right: none;"><?php if(isset($counts[15])) echo $counts[15]; ?></td></tr>
                          <tr class="fortyh"><td style="width: 80%;border-left: none;border-top: none;border-bottom: none;">Diethylproprion Hydrochloride 25 mg TAB</td><td style="width: 20%;border: none;"><?php if(isset($counts['DI'])) echo $counts['DI']; ?></td></tr>
                      </table>
                  </td>
              </tr>
              
          </table>
          <table style="margin-top: 20px;" class="patients_tbl">
              <thead>
              <tr>
                  <td>Phone</td>
                  <td>Name</td>
                  <td>DOB</td>
                  <td>Address</td>
                  <td style="font-weight: bold;border-left-width: 3px;">Pres#</td>
                  <td style="font-weight: bold;">Pres Date</td>                  
                  <td>Refill#</td>
                  <td style="border-right-width: 3px;">Refill Date</td>
                  <td>Pres ExpDt</td>
                  <td>NDC#</td>
                  <td style="width: 9%;">Medication</td>
                  <td>Qty</td>
                  <td>Days</td>
                  <td>Mfg</td>
              </tr>
              </thead>
              <tbody>
              <?php foreach($logs as $log){?>
              <tr>
                  <td><?php echo empty($log->phone)?'-':substr($log->phone, 0, 3)."-".substr($log->phone, 3, 3)."-".substr($log->phone,6);?></td>
                  <td><?php echo $log->lname." ".$log->fname;?></td>
                  <td><?php echo date('m/d/Y',strtotime($log->dob));?></td>
                  <td><?php echo $log->address.", ".$log->city.", ".$log->abbr." ".$log->zip;?></td>
                  <td style="font-weight: bold;border-left-width: 3px;"><?php echo $log->prescription_no;?></td>
                  <td style="font-weight: bold;"><?php echo $log->pres_date;?></td>                  
                  <td><?php echo $log->refill? $log->refill:'';?></td>
                  <td style="border-right-width: 3px;"><?php echo $log->refill? date('m/d/Y',strtotime($log->visit_date)):'';?></td>
                  <td><?php echo date('m/d/Y', strtotime($log->pres_date.' +6 months'));?></td>
                  <td><?php echo $log->ndc;?></td>
                  <td><?php echo $log->medi;?></td>
                  <td><?php echo $log->qty;?></td>
                  <td><?php echo $log->days;?></td>
                  <td><?php echo $log->mfg;?></td>
              </tr>
              <?php } ?>
              </tbody>
          </table>
      </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en-US">
    <head>    
        <style>
            @page {
                /*size: letter portrait;*/
                margin: 50px 70px 20px;
              }

              body { margin: 0px;padding: 0px;font-family:'ptsans', sans-serif;}
              html{margin: 0px;}
              p{margin: 0px;padding: 0px;}
              table { border-spacing: 0; border-collapse: collapse;width: 100%;}
              tr th{border: 1px solid #ccc;background-color: #f5f5f5;padding: 5px 0px;}
              table.order_table tr td{border: 1px solid #ccc;}
        </style> 
    </head>
  <body>
    <div id="container" style="margin: 0px;padding: 00px 50px 10px 50px;">
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center;">
                    <img src="<?php echo base_url();?>assets/img/receipt_header.png" style="height: 300px;"/>
                </td>                
            </tr>
            <tr>
                <td style="font-size: 40px;padding-top: 20px;font-weight: bold;font-family:'courier' ;">Sales Activity Report</td>
            </tr>
            
            <tr>
                <td style="padding-top: 20px;">
                    <?php echo $patient->lname." ".$patient->fname;?>&nbsp;
                     <?php echo $patient->address;?>&nbsp;
                     <?php echo $patient->city.", ".getStateAbbr($patient->state)." ".$patient->zip;?>
                </td>
            </tr>
            <tr>
                <td style="padding-top: 30px;padding-bottom: 15px;">To Whomsoever it may concern</td>
            </tr>
        </table>
        
            <table class="order_table" style="width: 100%;page-break-inside: auto;">
                <thead>
                    <tr>
                        <th style="width: 12%;">Order No.</th>
                        <th style="width: 15%;">Date</th>
                        <th>Description</th>
                        <th style="width: 12%;">Amount $</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orders as $order){?> 
                    <tr>
                        <td style="padding-right: 40px;text-align: right;"><?php echo $order->id;?></td>
                        <td style="text-align: center;"><?php echo date("m/d/Y",strtotime($order->created));?></td>
                        <td style="padding: 0px 5px;">
                            <?php
                            $itm_str = "";
                            foreach($order->items as $item){
                                $itm_str .= empty($item->friendly_name)? $item->name: $item->friendly_name;
                                $itm_str .= " ($item->quantity) ,  ";
                            } ?>
                            <?php echo substr($itm_str, 0, -3);?>
                        </td>
                        <td style="padding-right: 5px;text-align: right;">$<?php echo $order->net_total;?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <table>
            <tr>
                <td style="padding-top: 20px;">
                    Thank you for your business
                </td>
            </tr>
            <tr>
                <td style="padding-top: 20px;">
                    For<br>
                    Doctors´ Weight Loss Center
                </td>
            </tr>
        </table>        
    </div>
  </body>
</html>

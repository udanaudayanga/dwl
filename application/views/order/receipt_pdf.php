<!DOCTYPE html>
<html lang="en-US">
    <head>    
        <style>
            @page {
                /*size: letter portrait;*/
                margin: 100px 150px 20px;
              }

              body { margin: 0px;padding: 0px;font-family:'ptsans', sans-serif;}
              html{margin: 0px;}
              p{margin: 0px;padding: 0px;}
              table { border-spacing: 0; border-collapse: collapse;width: 100%;}
        </style> 
    </head>
  <body>
    <div id="container" style="margin: 0px;padding: 20px 50px 10px 50px;">
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center;">
                    <img src="<?php echo base_url();?>assets/img/receipt_header.png" style="height: 300px;"/>
                </td>                
            </tr>
            <tr>
                <td style="font-size: 40px;padding-top: 20px;font-weight: bold;font-family:'courier' ;">sales receipt</td>
            </tr>
            <tr>
                <td style="padding-top: 20px;">Date: &nbsp;&nbsp;<?php echo date('m/d/Y',strtotime($order->created));?></td>
            </tr>
            <tr>
                <td style="padding-top: 20px;">
                    To:<br>
                    <?php echo $patient->lname." ".$patient->fname;?><br>
                     <?php echo $patient->address;?><br>
                     <?php echo $patient->city.", ".getStateAbbr($patient->state)." ".$patient->zip;?>
                </td>
            </tr>
            <tr>
                <td style="padding-top: 30px;padding-bottom: 15px;">To Whomsoever it may concern</td>
            </tr>
            <tr>
                <td style="border: 1px solid #ababab;padding: 5px 20px;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 12%;">Order No.</td>
                            <td style="width: 68%;">Description</td>
                            <td style="width: 10%;">Qty</td>
                            <td style="width: 10%;text-align: right;">Amount $</td>
                        </tr>  
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 20px;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 13%;font-size: 30px;vertical-align: top;"><?php echo $order->id;?></td>
                            <td style="width: 87%;">
                                <table style="width: 100%;">
                                    <?php foreach($ois as $oi){?> 
                                    <tr>
                                        <td style="width: 78%;padding: 5px 0px;"><?php echo empty($oi->friendly_name)? $oi->name: $oi->friendly_name;?></td>
                                        <td style="width: 11%;padding: 5px 0px;"><?php echo $oi->quantity;?></td>
                                        <td style="width: 11%;text-align: right;padding:5px 10px 5px 0px;"><?php echo $oi->price * $oi->quantity;?></td>
                                    </tr>
                                    <?php } ?>
                                </table>
                            </td>                            
                        </tr>  
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table style="width: 100%;">
                        <?php if($order->discount > 0){?>
                        <tr>
                            <td style="width: 82%;text-align: right;">DISCOUNT</td>
                            <td style="text-align: right;font-weight: bold;padding-right: 30px;"><?php echo "-".$order->discount;?></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td style="width: 82%;text-align: right;">TOTAL</td>
                            <td style="text-align: right;font-weight: bold;padding-right: 30px;"><?php echo $order->net_total;?></td>
                        </tr>
                    </table>
                    
                </td>
            </tr>
            <tr>
                <td style="padding-top: 100px;">
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

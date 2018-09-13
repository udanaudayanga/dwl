<!DOCTYPE html>
<html lang="en-US">
    <head>    
        <style>
            @page {
                /*size: letter portrait;*/
                margin: 100px 75px 20px;
              }

              body { margin: 0px;padding: 0px;font-family:'ptsans', sans-serif;}
              html{margin: 0px;}
              p{margin: 0px;padding: 0px;}
              table { border-spacing: 0; border-collapse: collapse;width: 100%;}
              table.recived_tbl tr td{border: 1px solid #ababab;padding-top:15px;padding-bottom:15px;}
        </style> 
    </head>
  <body>
    <div id="container" style="margin: 0px;padding: 20px 50px 10px 50px;">
        <table style="width: 100%;">
            
            
            <tr>
                <td style="padding-top: 0px;padding-bottom: 30px;">
                    <table style="width: 100%;">
                        <tr>
                            <td><strong><?php echo $patient->lname." ".$patient->fname;?></strong>
                            <?php echo $patient->address;?>
                            <?php echo $patient->city.", ".getStateAbbr($patient->state)." ".$patient->zip;?>
                            </td>
                            <td style="text-align: right;">
                                <strong><?php echo date('m/d/Y',strtotime($order->created))." : ".date("l",strtotime($order->created))." : ".strtoupper($this->data['location']->name);?></strong>
                            </td>
                        </tr>  
                    </table>
                </td>
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
                        <?php if($order->payment_type == 'mix'){?>
                        <tr>
                            <td colspan="2" style="text-align: right;font-size:14px;">
                                (&nbsp;CASH:<?php echo number_format($order->net_total - $order->credit_amount,2);?>&nbsp;&nbsp;|&nbsp;&nbsp;CREDIT:<?php echo number_format($order->credit_amount,2);?>&nbsp;)
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                    
                </td>
            </tr>
            <tr>
                <td  style="padding-top: 100px;">
                    <table class="recived_tbl" style="width: 100%;">
                        <tr>
                            <td style="text-align:left;padding-left: 2%;">Received</td>
                            <td style="text-align:center;width: 12%;">$1</td>
                            <td style="text-align:center;width: 12%;">$2</td>
                            <td style="text-align:center;width: 12%;">$5</td>
                            <td style="text-align:center;width: 12%;">$10</td>
                            <td style="text-align:center;width: 12%;">$20</td>
                            <td style="text-align:center;width: 12%;">$50</td>
                            <td style="text-align:center;width: 12%;">$100</td>
                        </tr>
                        <tr>
                            <td style="text-align:left;padding-left: 2%;">Number</td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                        </tr>
                        <tr>
                            <td style="text-align:left;padding-left: 2%;">TOTAL</td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                        </tr>
                        <tr>
                            <td style="text-align:left;border:none;" colspan="7"></td>
                            <td style="text-align:center;width: 12%;border:1px dashed #ababab;">&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding-top: 150px;">
                    <table class="recived_tbl" style="width: 100%;">
                        <tr>
                            <td style="text-align:left;padding-left: 2%;">Cash Tendered</td>
                            <td style="text-align:center;width: 12%;">$1</td>
                            <td style="text-align:center;width: 12%;">$2</td>
                            <td style="text-align:center;width: 12%;">$5</td>
                            <td style="text-align:center;width: 12%;">$10</td>
                            <td style="text-align:center;width: 12%;">$20</td>
                            <td style="text-align:center;width: 12%;">$50</td>
                            <td style="text-align:center;width: 12%;">$100</td>
                        </tr>
                        <tr>
                            <td style="text-align:left;padding-left: 2%;">Number</td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                        </tr>
                        <tr>
                            <td style="text-align:left;padding-left: 2%;">TOTAL</td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                            <td style="text-align:center;width: 12%;"></td>
                        </tr>
                        <tr>
                            <td style="text-align:left;border:none;" colspan="7"></td>
                            <td style="text-align:center;width: 12%;border:1px dashed #ababab;">&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding-top: 100px;">
                    <table style="width: 100%;">
                        <tr>
                            <td>Cash Received By: ..............................................................................</td>
                            <td>Cash Tendered By: ..............................................................................</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>        
    </div>
  </body>
</html>

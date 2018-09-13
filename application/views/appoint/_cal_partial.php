<div class="loader-container text-center color-white">
            <div><i class="fa fa-spinner fa-pulse fa-4x"></i></div>
            <div>Loading...</div>
        </div>
<div class="col-xs-12">
    <div class="col-lg-4 col-md-3 col-sm-3" style="text-align: right;font-weight: bold;font-size: 50px;"><a class="nav_date" data-date="<?php echo date("m/d/Y",strtotime($appdate. "-1 day"));?>" title="Prev Day: <?php echo date("l, F j, Y",strtotime($appdate. "-1 day"));?>" href=""><</a></div>
    <div class="col-lg-4 col-md-6 col-sm-6" style="text-align: center;">
        <p style="text-align: center;font-size: 30px;margin-bottom: 0px;"><?php echo date("l, F j, Y",strtotime($appdate));?></p>
        <p style="font-size: 20px;color: gray;"><?php echo date("Y-m-d")==$appdate?"Today":(date("Y-m-d")>$appdate?"This is a past day.":"This is a future day.");?>&nbsp;&nbsp;&nbsp;<span style="font-size: 16px;color: #007fff;">(<?php echo $appcount;?> appointments)</span></p>
        
    </div>
    <div class="col-lg-4 col-md-3 col-sm-3" style="text-align: left;font-weight: bold;font-size: 50px;"><a class="nav_date"  data-date="<?php echo date("m/d/Y",strtotime($appdate. "+1 day"));?>" title="Next Day: <?php echo date("l, F j, Y",strtotime($appdate. "+1 day"));?>" href="">></a></div>
</div>
<div class="col-xs-12"  style="overflow-y: scroll;height: 400px;">
    <?php 
        $appType = array();
        $appType[1] = "New";
        $appType[2] = "Weekly";
        $appType[3] = "Dr.Consult";
        $appType[4] = "Shots Only";
    ?>
    <table class="table">
        <tr>
            <td style="width: 10%;border-right:1px solid red;"></td>
            <?php if($apploc == 0){?>
                <?php
                $width = 90/count($locations);
                foreach($locations as $loc){?> 
            <td style="width: <?php echo $width;?>%;text-align: center;font-weight: bold;border-right:1px solid red;font-size:18px;"><?php echo $loc->name;?></td>
                <?php } ?>
            <?php }else{ ?>
            <td style="text-align: center;font-weight: bold;border-right:1px solid red;font-size:18px;"><?php echo getLocation($apploc)->name;?></td>
            <?php } ?>
        </tr>
        <?php 
            for ($i = 7; $i <= 18; $i++){
                for ($j = 0; $j < 60; $j+=30){
        ?>
            <tr>
                <td style="border-right:1px solid red;text-align: center;padding: 2px;<?php if($j==0) {?>font-weight: bold;border-top: 1px solid #999;<?php }else{ ?>color:#aaa;<?php } ?>"><?php echo date("h:i a",strtotime("$i:$j:00"));?></td>
            <?php if($apploc == 0){?>
                <?php foreach($locations as $loc){?> 
                <?php if(isset($appoints[$loc->id][$i][$j])){
                    $appnts = $appoints[$loc->id][$i][$j];
                ?> 
                <td style="border-right:1px solid red;padding: 1px;<?php if($j==0) {?>border-top: 1px solid #999;<?php } ?>">
                    <?php
                    $z = 1;
                    foreach($appnts as $appoint){?>
                        <div class="col-xs-12" style="text-transform: capitalize;padding: 0px 2px;<?php if($z != 1){?>border-top: 1px solid #eaeaea;<?php } ?><?php if($appoint->no_show){?>background-color: #FDCCCC;<?php }elseif($appoint->type == 1){ ?>background-color: #67f792;<?php }else{ ?>background-color: #bce8f1;<?php } ?>">
                            <a class="tt" data-title='<strong>P: </strong><?php echo $appoint->patient_id?getPhnFormat($appoint->phone):getPhnFormat($appoint->phn);?><br>
                               <strong>E: </strong><?php echo $appoint->email;?><?php if(!empty($appoint->note)){?><br><strong>Note: </strong><?php echo $appoint->note;?><?php } ?>' onclick="return false;" href=""><?php echo $appoint->patient_id? $appoint->lname." ".$appoint->fname:$appoint->last_name." ".$appoint->first_name;?></a>

                            - <span style="font-size: 12px;"><?php echo $appType[$appoint->type];?></span>

                            <a title="Remove Appointment" class="rem_app pull-right" data-id="<?php echo $appoint->id;?>" href=""><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color: red;font-size: 12px;"></span></a>
                            <a title="Edit Appointment" class="edit_app pull-right" data-ns="<?php echo $appoint->no_show;?>" data-date="<?php echo date("m/d/Y",strtotime($appoint->date));?>" data-staff="<?php echo $appoint->staff_id;?>"  data-type="<?php echo $appoint->type;?>" data-location="<?php echo $appoint->location_id;?>" data-note="<?php echo $appoint->note;?>" data-time="<?php echo date('h:i A',strtotime($appoint->time));?>" data-id="<?php echo $appoint->id;?>" style="margin-right: 5px;" href=""><span class="glyphicon glyphicon-edit" aria-hidden="true" style="color: blue;font-size: 12px;"></span></a>
                            <a title="Appointment History" class="pull-right show_history" style="padding-right: 5px;" data-id="<?php echo $appoint->patient_id;?>" href=""><span class="glyphicon glyphicon-time" aria-hidden="true" style="color: green;font-size: 12px;"></span></a>
                        </div>
                    <?php
                    $z++;
                    } ?>
                </td>
                <?php }elseif(isset($blocks[$loc->id][$i][$j])){
                        $blk = $blocks[$loc->id][$i][$j];
                    ?>
                    <?php if($blk->first){?> 
                    <td style="border-right:1px solid red;padding: 2px;text-align: right;" class="<?php echo $blk->type ==1?'striped_red':'striped';?>">
                        <?php if($blk->reason){?><a class="info_block" data-reason="<?php  echo $blk->reason;?>" href=""><span class="glyphicon glyphicon-info-sign" aria-hidden="true" style="color: blue;"></span></a><?php } ?>
                        <a class="rem_block" data-id="<?php  echo $blk->id;?>" href=""><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color: red;"></span></a>
                    </td>
                    <?php }else{?>
                        <td style="padding: 2px;border-right:1px solid red;" class="<?php echo $blk->type ==1?'striped_red':'striped';?>">&nbsp;</td>
                    <?php } ?>
                <?php }else{?> 
                    <td style="border-right:1px solid red;padding: 2px;<?php if($j==0) {?>border-top: 1px solid #999;<?php } ?>">&nbsp;</td>
                <?php } ?>
                    
                <?php } ?>
            <?php }else{ ?>
                <?php if(isset($appoints[$apploc][$i][$j])){
                    $appnts = $appoints[$apploc][$i][$j];
                ?> 
                <td style="border-right:1px solid red;padding: 1px;<?php if($j==0) {?>border-top: 1px solid #999;<?php } ?>">
                    <?php
                    
                    foreach($appnts as $appoint){?>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="padding: 0px;padding-top: 2px;">
                        <div style="text-transform: capitalize;width: 96%;padding: 0px 5px;margin: 0px 10px 0px 0px;<?php if($appoint->no_show){?>background-color: #FDCCCC;<?php }elseif($appoint->type == 1){ ?>background-color: #67f792;<?php }else{ ?>background-color: #bce8f1;<?php } ?>">
                            <a class="tt" data-title='<strong>P: </strong><?php echo $appoint->patient_id?getPhnFormat($appoint->phone):getPhnFormat($appoint->phn);?><br>
                               <strong>E: </strong><?php echo $appoint->email;?><?php if(!empty($appoint->note)){?><br><strong>Note: </strong><?php echo $appoint->note;?><?php } ?>' onclick="return false;" href=""><?php echo $appoint->patient_id? $appoint->lname." ".$appoint->fname:$appoint->last_name." ".$appoint->first_name;?></a>

                            - <span style="font-size: 12px;"><?php echo $appType[$appoint->type];?></span>

                            <a class="rem_app pull-right" data-id="<?php echo $appoint->id;?>" href=""><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color: red;font-size: 12px;"></span></a>
                            <a class="edit_app pull-right" data-ns="<?php echo $appoint->no_show;?>" data-date="<?php echo date("m/d/Y",strtotime($appoint->date));?>" data-type="<?php echo $appoint->type;?>" data-location="<?php echo $appoint->location_id;?>" data-note="<?php echo $appoint->note;?>" data-time="<?php echo date('h:i A',strtotime($appoint->time));?>" data-id="<?php echo $appoint->id;?>" style="margin-right: 5px;" href=""><span class="glyphicon glyphicon-edit" aria-hidden="true" style="color: blue;font-size: 12px;"></span></a>
                            <a title="Appointment History" class="pull-right show_history" style="padding-right: 5px;" data-id="<?php echo $appoint->patient_id;?>" href=""><span class="glyphicon glyphicon-time" aria-hidden="true" style="color: green;font-size: 12px;"></span></a>
                        </div>
                    </div>
                    <?php
                    } ?>
                </td>
                <?php }elseif(isset($blocks[$apploc][$i][$j])){
                        $blk = $blocks[$apploc][$i][$j];
                    ?>
                    <?php if($blk->first){?> 
                <td style="border-right:1px solid red;padding: 2px;text-align: right;<?php if($j==0) {?>border-top: 1px solid #999;<?php } ?>" class="striped"><a class="rem_block" data-id="<?php  echo $blk->id;?>" href=""><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color: red;"></span></a></td>
                    <?php }else{?>
                        <td style="border-right:1px solid red;padding: 2px;<?php if($j==0) {?>border-top: 1px solid #999;<?php } ?>" class="striped">&nbsp;</td>
                    <?php } ?>
                <?php }else{?> 
                    <td style="border-right:1px solid red;padding: 2px;<?php if($j==0) {?>border-top: 1px solid #999;<?php } ?>">&nbsp;</td>
                <?php } ?>
            <?php } ?>
        </tr>
        <?php                  
                }
            }
        ?>
        
    </table>
</div>
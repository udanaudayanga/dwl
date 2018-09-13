<form id="im_usage_form">
    <input type="hidden" name="im_id" value="<?php echo $locweek->id;?>" />
    <input type="hidden" name="med" value="<?php echo $med;?>" />
    <table class="table table-bordered" style="margin-bottom: 10px;">
    <thead>
        <tr>
            <th>Date</th>
            <th>Start of Day/Given</th>
            <th class="strip" style="width: 20%;">End of Today Count<br>(left in the bags)</th>
            <th>Utilized Today</th>
            <th class="stripblue">Auto Utilized<br>(From Today’s Orders)</th>
            <th style="width: 20%;">Staff</th>
        </tr>
    </thead>
    <tbody>
        <?php $given = $locweek->{$med};?>
        <?php foreach($weekdays as $day){
            $balance = isset($imu[$day])? $imu[$day]->{$med}:0;
            $stf = isset($imu[$day])? $imu[$day]->{$med}:NULL;
            if($day < date('Y-m-d'))$balance = ($balance == 0 && !$stf)? $given:$balance;
            $usage = $given - $balance;
            
            $proUsage = array();
            if(substr($med,0,3) == 'med') $proUsage = getMedUsage($locweek->location_id, $day);
            if(substr($med,0,3) == 'inj') $proUsage = getInjUsage($locweek->location_id, $day);
            
        ?>
        <tr>
            <td><?php echo date('D M d',strtotime($day));?></td>
            <td class="font_nmbr"><?php echo $given;?></td>
            <td class="strip font_nmbr">
                <?php if($day == date('Y-m-d')){?>
                <input type="number" class="form-control" name="usage[<?php echo $day;?>][balance]" value="<?php echo $balance;?>"/>
                <?php }else{echo $balance;} ?> 
            </td>
            <td class="font_nmbr" style="color: #999;"><?php echo $usage;?></td>
            <td class="stripblue font_nmbr" style="color: #999;"><?php
                $match = 0;
                if(substr($med,0,3) == 'med')
                {
                    $match = isset($proUsage[$med])? round($proUsage[$med]/7):0;
                }elseif(substr($med,0,3) == 'inj')
                {
                    $match = isset($proUsage[$med])? round($proUsage[$med]):0;
                }
                echo $match;
            ?></td>
            <td>
                <?php if($day == date('Y-m-d')){?>
                <select class="form-control" name="usage[<?php echo $day;?>][staff_id]">
                    <?php foreach($staff as $s){?>
                    <option <?php if(isset($imu[$day]) && $imu[$day]->staff_id == $s->id) echo 'selected="selected"';?>  value="<?php echo $s->id;?>"><?php echo $s->lname." ".$s->fname;?></option>
                    <?php } ?>
                </select>
                <?php }else{?>
                    <?php if(isset($imu[$day]) && $imu[$day]->staff_id) echo $imu[$day]->lname." ".$imu[$day]->fname;?>
                <?php } ?>
            </td>
        </tr> 
        <?php $given = $given - $usage;?>
        <?php } ?>
    </tbody>
</table>
<!--<div class="col-xs-12">-->
<p style="text-align: right;margin-bottom: 0px;">    
    <button id="update_mi" class="btn btn-success">Update</button>
</p>
<!--</div>-->
</form>
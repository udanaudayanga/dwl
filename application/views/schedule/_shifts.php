<div class="loader-container text-center color-white">
            <div><i class="fa fa-spinner fa-pulse fa-4x"></i></div>
            <div>Loading</div>
        </div>
<div role="alert" class="alert fresh-color alert-success" id="shift_success" style="display: none;">
                      
</div>
        <div class="card">
            <div class="card-header">

                <div class="card-title">
                <div class="title">Weekly Schedule: <?php echo date('D M d',strtotime($week_days[1]))." - ".date('D M d',strtotime($week_days[6]));?></div>
                </div>
                
            </div>
            <div class="card-body" style="padding: 15px;">
                <?php if($this->session->flashdata('message')){?>
                <div role="alert" class="alert fresh-color alert-success">
                      <strong><?php echo $this->session->flashdata('message');?></strong>
                </div>
                <?php } ?>
                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                    <select class="form-control" name="shift_location" id="shift_location" tabindex="-1" aria-hidden="true" style="width: 100%;">                            
                       <?php foreach($locations as $loc){?>
                        <option <?php echo set_select('shift_location', $loc->id,$loc->id == $slocation); ?> value="<?php echo $loc->id;?>"><?php echo $loc->name;?></option>
                        <?php } ?>
                        <option <?php echo set_select('shift_location', 'all','all' == $slocation); ?> value="all">ALL</option>
                    </select>                    
                </div>
                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12" style="font-size: 20px;">
                    Total Hours: <strong><?php echo secToHM($total);?></strong>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 pull-right" style="padding-bottom: 10px;">
                    <a href="" data-val="next" style="float: right;font-weight: bold;font-size: 150%;" class="btn btn-success nav_week">></a>
                    <a href="" data-val="prev" style="float: right;margin-right: 10px;font-weight: bold;font-size: 150%;" class="btn btn-success nav_week"><</a>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 pull-right" style="padding-bottom: 10px;">
                    <a title="Copy all locations to next week" id="copy_next" href="" class="btn btn-success" style="font-weight: bold;font-size: 110%;float: right;">Copy To Next Week</a>
                </div>
                    <table class="table-bordered table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="max-width: 16%;padding: 8px 3px;">Employee</th>
                                <?php foreach($week_days as $day){?>
                                <th style="text-transform: uppercase;text-align: left;max-width: 12%;padding: 8px 3px;">
                                    <?php echo date('D m/d',  strtotime($day));?>
                                    <a href="<?php echo site_url("schedule/daily/$day");?>" title="View Daily Schedule"><span style="float: right;font-size: 12px;" aria-hidden="true" class="glyphicon glyphicon-export"></span></a>
                                </th>
                                <?php } ?>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach($emp_shifts as $key=>$emp){?>
                            <tr>
                                <td style="padding: 8px 3px;"><?php echo $emp['name'];?>  [<?php echo secToHM($emp['work']);?>]</td>
                                <?php
                                $shifts = $emp['shifts'];
                                $colors = $emp['color'];
                                foreach($week_days as $day){?>
                                    <?php if(isset($shifts[$day])){?>
                                        <?php $day_shift = $shifts[$day]?> 
                                        <td style="text-align: left;padding: 8px 3px;"><span style="font-size: 90%;padding-left: 3px;padding-right: 3px;background-color: <?php echo $colors[0];?>;" class="label label-success">
                                                <a data-title="<?php echo $day_shift[1]['note'];?>" data-toggle="tooltip" <?php if(!empty($day_shift[1]['note'])){?> title="Update: <?php echo $day_shift[1]['note'];?>"<?php } ?>  href="" class="edit_shift" data-id="<?php echo $day_shift[1]['id'];?>" data-start="<?php echo date('H:i', strtotime($day_shift[1]['start']));?>" data-end="<?php echo date('H:i', strtotime($day_shift[1]['end']));?>" data-date="<?php echo $day_shift[1]['date'];?>" data-shift="<?php echo $day_shift[1]['shift'];?>"  style="color: #FFFFFF;display: inline-block;min-width: 90px;">
                                                    <?php echo dateFormat($day_shift[1]['start'])." - ".dateFormat($day_shift[1]['end']);?>
                                                    <?php if($slocation == 'all'){?> | <?php echo $day_shift[1]['abbr'];?><?php } ?>
                                                </a>
                                            <a href="" title="Remove Shift" data-id="<?php echo $day_shift[1]['id'];?>" class="rem_shift" style="margin-left: 3px;"><img src="/assets/img/delete.png" /></a></span>

                                            <?php if(isset($day_shift[2])){?>
                                            <hr style="margin: 5px 0px;">
                                            <span style="font-size: 90%;padding-left: 3px;padding-right: 3px;background-color: <?php echo $colors[1];?>;" class="label label-primary">
                                                <a data-title="<?php echo $day_shift[2]['note'];?>" data-toggle="tooltip" <?php if(!empty($day_shift[2]['note'])){?> title="Update: <?php echo $day_shift[2]['note'];?>"<?php } ?> href="" class="edit_shift" data-id="<?php echo $day_shift[2]['id'];?>" data-start="<?php echo date('H:i', strtotime($day_shift[2]['start']));?>" data-end="<?php echo date('H:i', strtotime($day_shift[2]['end']));?>" data-date="<?php echo $day_shift[2]['date'];?>" data-shift="<?php echo $day_shift[2]['shift'];?>"  style="color: #FFFFFF;display: inline-block;min-width: 90px;">
                                                    <?php echo dateFormat($day_shift[2]['start'])." - ".dateFormat($day_shift[2]['end']);?>
                                                    <?php if($slocation == 'all'){?> | <?php echo $day_shift[2]['abbr'];?><?php } ?>
                                                </a>
                                                <a href="" title="Remove Shift"  data-id="<?php echo $day_shift[2]['id'];?>" class="rem_shift" style="margin-left: 3px;"><img src="/assets/img/delete.png" /></a></span>
                                            <?php } ?>
                                            <?php if(isset($day_shift[3])){?>
                                            <hr style="margin: 5px 0px;">
                                            <span style="font-size: 90%;padding-left: 3px;padding-right: 3px;background-color: <?php echo $colors[2];?>;" class="label label-primary">
                                                <a data-title="<?php echo $day_shift[3]['note'];?>" data-toggle="tooltip" <?php if(!empty($day_shift[3]['note'])){?> title="Update: <?php echo $day_shift[3]['note'];?>"<?php } ?> href="" class="edit_shift" data-id="<?php echo $day_shift[3]['id'];?>" data-start="<?php echo date('H:i', strtotime($day_shift[3]['start']));?>" data-end="<?php echo date('H:i', strtotime($day_shift[3]['end']));?>" data-date="<?php echo $day_shift[3]['date'];?>" data-shift="<?php echo $day_shift[3]['shift'];?>"  style="color: #FFFFFF;display: inline-block;min-width: 90px;">
                                                    <?php echo dateFormat($day_shift[3]['start'])." - ".dateFormat($day_shift[3]['end']);?>
                                                    <?php if($slocation == 'all'){?> | <?php echo $day_shift[3]['abbr'];?><?php } ?>
                                                </a>
                                                <a href="" title="Remove Shift"  data-id="<?php echo $day_shift[3]['id'];?>" class="rem_shift" style="margin-left: 3px;"><img src="/assets/img/delete.png" /></a></span>
                                            <?php } ?>
                                        </td>
                                    <?php }else{?> 
                                        <td></td>
                                    <?php } ?>
                                <?php } ?>
                            </tr>
                            <?php } ?>
                            <?php if(empty($emp_shifts)){?> 
                            <tr><td></td></tr>
                            <?php } ?> 
                        </tbody>
                    </table>
        </div>
</div>
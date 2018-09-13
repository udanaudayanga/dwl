<div class="modal fade modal-info"  id="visit_info_<?php echo $visit->id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Visit Info - <?php echo date('M d, Y', strtotime($visit->visit_date));?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="">
                        <label class="col-sm-4 col-xs-6">Weight: </label><?php echo $visit->weight;?>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                        <label class="col-sm-4 col-xs-6">BFI: </label><?php echo $visit->bfi;?>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                        <label class="col-sm-4 col-xs-6">BMI: </label><?php echo $visit->bmi;?>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                        <label class="col-sm-4 col-xs-6">BP: </label><?php echo $visit->bp;?>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                        <label class="col-sm-4 col-xs-6">Med: </label>
                            <?php 
                            if($visit->is_med == 1)
                            {
                                echo $visit->med1? getMedName($visit->med1):'-';
                                echo ' / ';
                                echo $visit->med2? getMedName($visit->med2):'-';
                                echo "&nbsp;&nbsp;&nbsp;";
                                echo $visit->med3? getMedName($visit->med3):'-';
                            }
                            else
                            {
                                echo 'No Med';
                            }
                            ?>
                    </div>
                     <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                        <label class="col-sm-4 col-xs-6">No of days: </label><?php echo $visit->is_med==1? $visit->med_days:$visit->no_med_days;?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
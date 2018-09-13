<div class="modal fade modal-info"  id="check_prepaid_<?php echo $patient_id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">PRE-PAID - <?php echo $patient_name;?></h4>
            </div>
            <div class="modal-body" id="pp_history_body_<?php echo $patient_id;?>">
                <div  style="padding:5px;font-weight: bold" role="alert" class="alert fresh-color alert-danger pp_error_div"></div>
                <div style="padding:5px;font-weight: bold" role="alert" class="alert fresh-color alert-success pp_success_div"></div>
                <div class="loader-container text-center color-white">
                    <div><i class="fa fa-spinner fa-pulse fa-4x"></i></div>
                    <div>Loading...</div>
                </div>
                <div class="ppredeemtbl">
                    <?php $this->load->view('patient/_pp_redeem_tbl');?>
                </div>
                <div class="today_redeem" style="margin-top: 20px;border-top: 1px solid #999;overflow: auto;">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>
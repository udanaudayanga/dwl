<div class="modal fade modal-info"  id="bw_history_<?php echo $patient_id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">BW History - <?php echo $patient->lname." ".$patient->fname;?></h4>
            </div>
            <div class="modal-body">
                <table class="datatable table table-bordered">
                    <thead>
                       
                        <tr>
                            <th style="text-align: center;">ID</th>                            
                            <th style="text-align: center;">Date</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $max = count($bws);
                        foreach($bws as $bw){?>
                            <tr>
                                
                                <td style="text-align: center;"><?php echo $max;?></td>
                                <td style="text-align: center;"><?php echo date('m/d/Y',strtotime($bw->created));?></td>                               
                                <td style="text-align: center;">
                                    <a class="hover" target="_blank"  style="font-weight: 500;color: #31708f;" href="<?php echo site_url("assets/upload/bw/$bw->file")?>"><span aria-hidden="true" style="font-size: 1.2em;" class="glyphicon glyphicon-new-window"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <a data-id="<?php echo $bw->id;?>" class="hover" style="font-weight: 500;color: red;"  onclick="return confirm('Are you sure?');" href="<?php echo site_url("patient/del_bw/$bw->id")?>"><span style="font-size: 1.2em;" aria-hidden="true" class="glyphicon glyphicon-trash"></span></a>
                                </td>
                            </tr>
                        <?php 
                        $max--;
                        } ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>
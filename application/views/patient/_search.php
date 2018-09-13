<table class="datatable table table-striped" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Name</th>
            <th>Telephone</th>
            <th>DOB</th>
            <th style="text-align: center;">Status</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach($patients as $patient){?>
        <tr class="black">
            <td><?php echo $patient->lname.' '.$patient->fname;?></td>
            <td><?php echo "(".substr($patient->phone, 0, 3).") ".substr($patient->phone, 3, 3)."-".substr($patient->phone,6);?></td>
            <td><?php if($patient->dob) echo date('m/d/Y',strtotime($patient->dob));?></td>
            <td style="text-align: center;"><?php echo getPatientStatus($patient->status);?></td>
            <td>
                <a  data-status="weekly" class="add_visit" href="<?php echo site_url("patient/addVisit/$patient->id");?>" style="color: red;" >1 - Add Visit</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                <?php if($user->type == '1'){?><a onclick="return confirm('Are you sure?')" href="<?php echo site_url("patient/remove/$patient->id");?>" style="color: #31b0d5;">Del</a> &nbsp;&nbsp;|&nbsp;&nbsp;<?php } ?>
                <a href="<?php echo site_url("patient/view/$patient->id");?>" style="color: #31b0d5;">View</a> 
                <!--&nbsp;&nbsp;|&nbsp;&nbsp;<a href="" data-id="<?php echo $patient->id;?>" data-name="<?php echo $patient->lname.' '.$patient->fname;?>" class="add_photo" style="color: #31b0d5;">Add Photo</a>-->          
                <?php if($user->type == '1' && FALSE){?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" data-name="<?php echo $patient->lname.' '.$patient->fname;?>" data-patientid="<?php echo $patient->id;?>" class="add_pp" style="color: #31b0d5;">Add PP</a><?php } ?>
                <?php if($patient->vcount > 0){?>
                &nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo site_url("evaluate/view/$patient->id");?>"  style="color: #31b0d5;">Re-eval</a>                                
                <?php }else if(!empty ($patient->pls)){?>
                &nbsp;&nbsp;|&nbsp;&nbsp;<a target="_blank" href="<?php echo site_url("patient/lspdf/$patient->id");?>"  style="color: #31b0d5;">Last Status</a>
                <?php } ?>
                &nbsp;&nbsp;|&nbsp;&nbsp;<a title="Create an Appointment" data-lsd="<?php echo $patient->last_status_date?date('m/d/Y',strtotime($patient->last_status_date)):'';?>" data-status="<?php echo ($patient->status == 1)? '6':(($patient->status == 2)? '>6':'>12');?>" data-id="<?php echo $patient->id;?>" data-name="<?php echo $patient->lname.' '.$patient->fname;?>" class="appt" href=""  style="color: #31b0d5;">Appt</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
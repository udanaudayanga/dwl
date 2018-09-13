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
            <td><?php echo date('m/d/Y',strtotime($patient->dob));?></td>
            <td style="text-align: center;"><?php echo ($patient->status == 1)? '6':(($patient->status == 2)? '>6':'>12');?></td>
            <td>
                <a  data-status="weekly" class="add_alert" data-id="<?php echo $patient->id;?>" data-name="<?php echo $patient->lname.' '.$patient->fname;?>" href="" style="color: blue;" >Add Alert</a>
                
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
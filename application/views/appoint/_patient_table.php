<div class="card-header">
    <div class="card-title" style="padding: 10px;">
        <div class="title"><?php echo $patient->lname." ".$patient->fname." Appointments";?></div>        
    </div>
    <a href="" style="margin: 10px;" id="add_appt" data-lsd="<?php echo $patient->last_status_date?date('m/d/Y',strtotime($patient->last_status_date)):'';?>" data-status="<?php echo ($patient->status == 1)? '6':(($patient->status == 2)? '>6':'>12');?>" data-id="<?php echo $patient->id;?>" data-name="<?php echo $patient->lname." ".$patient->fname;?>" class="btn btn-success pull-right">Add Appt</a>
</div>
<div class="card-body"  style="padding: 10px;overflow: hidden;">
    <table class="table-bordered table table-striped datatable" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Date</th>
                <th style="width:20%;">Time</th>
                <th>Location</th>
                <th>Type</th>
                <th style="width:20%;">Note</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($appoints as $appoint){?>
        <tr>
            <td><?php echo date("Y M d",strtotime($appoint->date));?></td>
            <td><?php echo date("g:ia",strtotime($appoint->time));?>  <?php if($appoint->no_show){?>
        <span class="label label-danger" style="margin-left: 20px;">NO SHOW</span>
        <?php } ?></td>
            <td><?php echo $appoint->name;?></td>
            <td><?php echo $types[$appoint->type];?></td>
            <td><?php echo $appoint->note;?></td>
            <td>
                <?php if($appoint->date >= date('Y-m-d')){?>
                <a data-id="<?php echo $appoint->id;?>" data-pid="<?php echo $patient->id;?>"  class="del_appoint" style="color: red;margin-left: 20px;" href="" title="Remove"><span style="font-size: 1.1em;" class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
        
        </tbody>
    </table>
</div>
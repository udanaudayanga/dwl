<div class="loader-container text-center color-white">
            <div><i class="fa fa-spinner fa-pulse fa-4x"></i></div>
            <div>Loading...</div>
        </div>
<table class="table-bordered table table-striped datatable" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Name</th>
            <th>Telephone</th>
            <th>DOB</th>
            <th style="text-align: center;">Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <?php foreach($patients as $patient){?>
    <tr>
        <td><?php echo $patient->lname." ".$patient->fname;?></td>
        <td><?php echo "(".substr($patient->phone, 0, 3).") ".substr($patient->phone, 3, 3)."-".substr($patient->phone,6);?></td>
        <td><?php echo date('m/d/Y',strtotime($patient->dob));?></td>
        <td style="text-align: center;"><?php echo getPatientStatus($patient->status);?></td>
        <td>
            <a data-id="<?php echo $patient->id;?>"  class="del_freeze" style="color: red;margin-left: 20px;" href="" title="Remove"><span style="font-size: 1.1em;" class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
        </td>
    </tr>
    <?php } ?>
    <tbody>
    </tbody>
</table>
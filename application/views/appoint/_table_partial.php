<div class="loader-container text-center color-white">
            <div><i class="fa fa-spinner fa-pulse fa-4x"></i></div>
            <div>Loading...</div>
        </div>
<table class="table-bordered table table-striped datatable" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Patient</th>
            <th>Location</th>
            <th>Date</th>
            <th>Time</th>
            <th>Action</th>
        </tr>
    </thead>
    <?php foreach($appoints as $appoint){?>
    <tr>
        <td><?php echo $appoint->lname." ".$appoint->fname;?></td>
        <td><?php echo $appoint->abbr;?></td>
        <td><?php echo date('m/d/Y',strtotime($appoint->date));?></td>
        <td><?php echo $appoint->time;?></td>
        <td>
            <a data-id="<?php echo $appoint->id;?>"  class="del_appoint" style="color: red;margin-left: 20px;" href="" title="Remove"><span style="font-size: 1.1em;" class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
        </td>
    </tr>
    <?php } ?>
    <tbody>
    </tbody>
</table>

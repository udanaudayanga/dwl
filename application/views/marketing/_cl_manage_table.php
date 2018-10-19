<table class="datatable table table-striped" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach($members as $m){?>
        <tr class="black">            
            <td><?php echo $m->lname." ".$m->fname;?></td>
            <td><?php echo $m->email;?></td>
            <td><?php echo "(".substr($m->phone, 0, 3).") ".substr($m->phone, 3, 3)."-".substr($m->phone,6);?></td>
            <td>
                <a class="remove_del" data-list="<?php echo $m->cl_id;?>" data-id="<?php echo $m->id;?>" title="Remove Member" href=""><span style="color:red;font-size: 17px;" aria-hidden="true" class="glyphicon glyphicon-trash"></span></a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<div class="col-xs-12" style="margin-top: 20px;">
    
    <a data-id="<?php echo $id;?>" class="btn btn-info pull-right"  id="send_email" href="">Post List to Mailchimp</a>
    <a data-id="<?php echo $id;?>" class="btn btn-info pull-right" id="send_sms" style="margin-right: 20px;" href="">Send Instant SMS to List</a>
</div>
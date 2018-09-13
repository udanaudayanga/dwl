
<table class="datatable table table-striped" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Start</th>
            <th>End</th>
            <th>Type</th>
            <th>Message</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($qms as $m) { ?>
            <tr class="">
                <td><?php echo date('m/d/Y',strtotime($m->start)); ?></td>
                <td><?php echo date('m/d/Y',strtotime($m->end)); ?></td>
                <td><?php echo $m->type==1?'Default':'2nd Visit'; ?></td>
                <td><?php echo $m->msg; ?></td>
                <td style="padding: 0px 10px;">
                    <?php if($m->photo){?>
                    <img title="test" data-photo="<?php echo $m->photo;?>" src="/phpThumb/phpThumb.php?src=/assets/upload/queue_alerts/<?php echo $m->photo;?>&amp;h=48&amp;zc=1&amp;f=png" class="thumbnail qm_photo"  style="margin-bottom: 0px;max-width: 100%;float: left;"/>
                    <?php } ?>
                </td>
                <td>                                
                    <a title="Edit Message" class="edit_msg" data-id="<?php echo $m->id; ?>" data-start="<?php echo $m->start;?>" data-end="<?php echo $m->end;?>" data-msg="<?php echo $m->msg;?>" data-photo="<?php echo $m->photo;?>" data-type="<?php echo $m->type;?>" href="" style="color: #31b0d5;"><span aria-hidden="true"  class="glyphicon glyphicon-edit"></span></a> 
                    <a title="Remove"   href="" style="color: red;margin-left: 20px;" data-id="<?php echo $m->id; ?>" class="rem_msg" onclick=""><span aria-hidden="true" class="glyphicon glyphicon-trash"></span></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
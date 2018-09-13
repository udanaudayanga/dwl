<h2 style="margin: 0px 0px 15px 0px;"><?php echo $title;?></h2>
<table class="datatable table " cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Patient</th>
            <th>Last Visit</th>
            <th>Med Days</th>
            <th>No Show Date</th>
            <th>Days over No Show date</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($pds as $pd){?>
        <tr>
            <td><a style="color:#006dcc;" href="<?php echo site_url("patient/view/$pd->patient_id");?>" target="_blank"><?php echo $pd->lname." ".$pd->fname;?></a></td>
            <td><?php echo date("m/d/Y",strtotime($pd->visit_date));?></td>
            <td><?php echo $pd->med_days;?></td>
            <?php $no_show_date =  date('Y-m-d',strtotime("+$pd->med_days days", strtotime($pd->visit_date))); ?>
            <td><?php echo date("m/d/Y",strtotime($no_show_date));?></td>
            <?php 
            $date1 = new DateTime($no_show_date);
            $date2 = new DateTime(date("Y-m-d"));
            $diff = $date2->diff($date1)->format("%a");
            ?>
            <td><?php echo $diff;?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>

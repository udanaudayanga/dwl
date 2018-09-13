<table class="table table-bordered" style="margin-bottom: 10px;">
    <thead>
        <tr>
            <th>Date</th>
            <th>Start of Day/Given</th>
            <th class="strip" style="width: 20%;">End of Today Count<br>(left in the bags)</th>
            <th>Utilized Today</th>
            <th class="stripblue">Auto Utilized<br>(From Today’s Orders)</th>
            <th style="width: 20%;">Staff</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($weekdays as $day){?>
       <td><?php echo date('D M d',strtotime($day));?></td> 
        <?php } ?>
    </tbody>
</table>
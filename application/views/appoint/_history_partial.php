<?php foreach($history as $h){?>
<div class="col-xs-12" style="border-bottom: 1px solid #ccc;padding: 5px 0px;"> 
    <div class="col-xs-4" style="font-size: 16px;font-weight: bold;">
        <?php echo date("l",strtotime($h->date));?>,<br>
        <span style="font-size: 22px;"><?php echo date("M d",strtotime($h->date));?>,</span><br>
        <?php echo date("Y",strtotime($h->date));?>
    </div>
    <div class="col-xs-3"  style="font-size: 16px;padding-top: 20px;">
        <?php echo date("g:ia",strtotime($h->time));?>
    </div>
    <div class="col-xs-2"  style="font-size: 16px;text-transform: uppercase;padding-top: 20px;">
        <?php echo $h->name;?>
    </div>
    <div class="col-xs-3" style="text-align: center;padding-top: 20px;">
        <?php if($h->no_show){?>
        <span class="label label-danger">NO SHOW</span>
        <?php } ?>
    </div>
    <?php if(!empty($h->note) || !empty($h->sname)){?>
    <div class="col-xs-12">
        <p style="margin: 5px 0px 0px 0px;font-weight: bold;"><?php echo $h->sname;?></p>
        <?php echo $h->note;?>
    </div>
    <?php } ?>
</div>
<?php } ?>


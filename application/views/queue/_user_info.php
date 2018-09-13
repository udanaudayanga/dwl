<div class="col-xs-12">
    <div class="col-xs-4" style="padding-left: 0px;">
        <?php if(file_exists("./assets/upload/patients/$patient->photo")){?>
        <img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/upload/patients/<?php echo $patient->photo;?>&amp;h=120&amp;f=png" />
        <?php }else{ 
            $gender_img = $patient->gender == 1 ? "male.png":"female.png";
        ?>                                  
        <img src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/<?php echo $gender_img;?>&amp;h=153&amp;f=png" />
        <?php } ?>
    </div>
    <div class="col-xs-8" style="padding-right: 0px;">
        <h1><?php echo $patient->lname." ".$patient->fname;?></h1>
        <h4><?php echo $patient->address.", ".$patient->city.", ".getStateAbbr($patient->state)." ".$patient->zip;?></h4>
    </div>
</div>
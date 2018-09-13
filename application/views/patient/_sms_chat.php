<div class="col-xs-12" style="padding: 0px;margin-bottom: 0px;">
    <div class="panel panel-primary" style="border: none;">

        <div class="panel-body">
            <ul class="chat">
                
                <?php foreach($logs as $log){?>
                    <?php if($log->status == 1){?> 
                        <li class="left clearfix"><span class="chat-img pull-left">
                                <img src="/assets/img/DW_chat.png" alt="User Avatar" class="img-circle" />
                            </span>
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <strong class="primary-font">DWLC</strong> <small  style="color:#999;" class="pull-right text-muted">
                                        <span class="glyphicon glyphicon-time"></span><?php echo facebook_time_ago($log->created);?></small>
                                </div>
                                <p style="color:#444;">
                                    <?php echo nl2br($log->msg);?>
                                </p>
                            </div>
                        </li>
                    <?php }else{?> 
                            <li class="right clearfix"><span class="chat-img pull-right">
                                    <?php if($log->photo && file_exists("./assets/upload/patients/$log->photo")){?>
                                    <img class="img-circle" src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/upload/patients/<?php echo $log->photo;?>&amp;h=50&amp;w=50&amp;zc=1&amp;f=png" />
                                    <?php }else{ 
                                        $gender_img = $log->gender == 1 ? "male.png":"female.png";
                                    ?>                                  
                                    <img class="img-circle" src="/phpThumb/phpThumb.php?src=<?php echo base_url();?>assets/img/<?php echo $gender_img;?>&amp;h=50&amp;w=50&amp;zc=1&amp;f=png" />
                                    <?php } ?>
                                    
                                </span>
                                <div class="chat-body clearfix">
                                    <div class="header">
                                        <small class=" text-muted"  style="color:#999;"><span class="glyphicon glyphicon-time"></span><?php echo facebook_time_ago($log->created);?></small>
                                        <strong class="pull-right primary-font"><?php echo $log->lname." ".$log->fname;?></strong>
                                    </div>
                                    <p style="color:#444;">
                                        <?php echo nl2br($log->msg);?>
                                    </p>
                                </div>
                            </li>
                    <?php } ?> 
                <?php } ?>
                
                
                
            </ul>
        </div>
        
    </div>
</div>

<?php $this->load->view('template/header');?>
<div class="row">
    
        <div class="card">
            <form method="POST">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title">Add HTML content</div>
                    </div>
                    <a href="<?php echo site_url('logs/preview');?>" target="_blank" style="margin: 15px;" class="btn btn-success btn-lg pull-right"> Preview </a>
                </div>
                <div class="card-body" style="padding: 15px;">
                    <div class="col-xs-12">
                        <?php echo validation_errors('<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;"><div role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>'); ?>
                    </div>

<!--                    <div class="sub-title">Previous Medication</div>
                    <div>
                        <textarea rows="2" name="med" required="required" class="form-control"><?php echo set_value('med');?></textarea>
                    </div>-->
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="">
                        <label for="lname"> </label>
                        <textarea class="form-control" rows="10" name="text" placeholder="HTML TEXT"><?php echo $test->text;?></textarea>
                    </div>
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
                </div>
            </form>
        </div>
    
</div>
<?php $this->load->view('template/footer');?>
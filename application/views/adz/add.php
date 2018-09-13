<?php $this->load->view('template/header');?>
<div class="row">
    
        <div class="card">
            <form   method="POST" enctype="multipart/form-data">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title">Add Mail Adz</div>
                    </div>
                </div>
                <div class="card-body" style="padding: 15px;">
                    <div class="col-xs-12">
                        <?php echo $errors; ?>
                    </div>

<!--                    <div class="sub-title">Previous Medication</div>
                    <div>
                        <textarea rows="2" name="med" required="required" class="form-control"><?php echo set_value('med');?></textarea>
                    </div>-->
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="">
                        <label for="lname">Text</label>
                        <textarea  name="text" placeholder="Advertiesment Text" class="form-control"><?php echo set_value("text");?></textarea>
                    </div>
                    <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12" style="">				
                        <label for="fname">Image </label>
                        <input type="file" required="required" name="photo" value="" id="fname" class="">
                    </div>
                    
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="">
                        <button class="btn btn-success pull-right" type="submit">Add</button>
                    </div>
                </div>
            </form>
        </div>
    
</div>
<?php $this->load->view('template/footer');?>
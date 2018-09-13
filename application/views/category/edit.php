<?php $this->load->view('template/header');?>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <form method="POST">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title">Update Category</div>
                    </div>
                </div>
                <div class="card-body" style="padding: 15px;">
                    <div class="col-xs-12">
                        <?php echo validation_errors('<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;"><div role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>'); ?>
                    </div>

                    <div class="sub-title">Category Name</div>
                    <div>
                        <input type="text" name="name" required="required" class="form-control" value="<?php echo set_value('name',$category->name);?>" />
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="">
                        <button class="btn btn-success pull-right" type="submit">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view('template/footer');?>
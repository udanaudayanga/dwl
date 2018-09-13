<?php $this->load->view('template/header');?>
<div class="row">
    
        <div class="card">
            <form   method="POST">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title">Update Visit Page</div>
                    </div>
                </div>
                <div class="card-body" style="padding: 15px;">
                    
                    <div class="sub-title">Visit Page</div>
                    <div>
                        <textarea rows="20" name="html" required="required" class="form-control"><?php echo $html;?></textarea>
                    </div>
                    
                    
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="">
                        <button class="btn btn-success pull-right" type="submit">Update</button>
                    </div>
                </div>
            </form>
        </div>
    
</div>
<?php $this->load->view('template/footer');?>
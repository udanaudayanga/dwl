<?php $this->load->view('template/header');?>

<style>
    
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">

                <div class="card-title">
                <div class="title">Enter new patient's email below</div>
                </div>
            </div>
            <div class="card-body patients_list" style="padding: 15px;">
                
                <?php if($this->session->flashdata('message')){?>
                <div role="alert" class="alert fresh-color alert-success">
                      <strong><?php echo $this->session->flashdata('message');?></strong>
                </div>
                <?php } ?>
                <?php if(validation_errors()){?>
                <div role="alert" class="alert fresh-color alert-danger">
                    <strong><?php echo validation_errors();?></strong>
                </div>
                <?php } ?>
                <form method="POST">
                    <div class="col-lg-4 col-md-5 col-sm-8 col-xs-12 form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="inputEmail3">Email Address:</label>
                        <div class="col-sm-8">
                            <input type="text" id="" name="email" class="form-control"/>
                        </div>
                    </div>
                </div>
                    <button class="btn btn-success" type="submit"  style="margin: 0px;">SEND</button>
                </form>
                
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(function(){
        
    });
</script>
<?php $this->load->view('template/footer');?>

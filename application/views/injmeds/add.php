<?php $this->load->view('template/header');?>
<div class="row">
    <div class="col-xs-12">
        <div class="card">   
            <div class="card-header">
                <div class="card-title">
                    <div class="title">Add/Update Weekly Given <div class="" style="font-size: 18px;padding-bottom: 5px;"><strong>Week:</strong>  <?php echo date('D M d',strtotime($weekdays[1]))." - ". date('D M d',strtotime($weekdays[6]));?> &nbsp;&nbsp;&nbsp;&nbsp;<strong>Location:</strong> <?php echo getLocation($loc_id)->name;?></div></div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-xs-12" style="font-weight: bold;font-size: 20px;padding-left: 30px;padding-bottom: 30px;"># of Vials</div>
                <div class="col-xs-12"><?php echo validation_errors('<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;"><div role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');?></div>
                <form id="" method="POST" >
                    <div class="col-lg-8 col-md-10 col-sm-12 col-xs-12">
                        <div class="form-group col-lg-2 col-md-3 col-sm-3 col-xs-12">
                            <label for="37">37mg</label>
                            <input type="number" name="med1" class="form-control" id="37" value="<?php echo set_value('med1',($given)?$given->med1:0);?>">
                        </div>
                        <div class="form-group col-lg-2 col-md-3 col-sm-3 col-xs-12">
                            <label for="30">30mg</label>
                            <input type="number" name="med2" class="form-control" id="30" value="<?php echo set_value('med2',($given)?$given->med2:0);?>">
                        </div>
                        <div class="form-group col-lg-2 col-md-3 col-sm-3 col-xs-12">
                            <label for="15">15mg</label>
                            <input type="number" name="med3" class="form-control" id="15" value="<?php echo set_value('med3',($given)?$given->med3:0);?>">
                        </div>
                        <div class="form-group col-lg-2 col-md-3 col-sm-3 col-xs-12">
                            <label for="di">Diethyl</label>
                            <input type="number" name="med4" class="form-control" id="di" value="<?php echo set_value('med4',($given)?$given->med4:0);?>">
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-10 col-sm-12 col-xs-12">
                         <div class="form-group col-lg-2 col-md-3 col-sm-3 col-xs-12">
                            <label for="0.4">B-12 0.4cc</label>
                            <input type="number" name="inj1" class="form-control" id="0.4" value="<?php echo set_value('inj1',($given)?$given->inj1:0);?>">
                          </div>
                          <div class="form-group col-lg-2 col-md-3 col-sm-3 col-xs-12">
                            <label for="1cc">B-12 1cc</label>
                            <input type="number" name="inj2" class="form-control" id="1cc" value="<?php echo set_value('inj2',($given)?$given->inj2:0);?>">
                          </div>
                        <div class="form-group col-lg-2 col-md-3 col-sm-3 col-xs-12">
                            <label for="lipo">Lipogen</label>
                            <input type="number" name="inj3" class="form-control" id="lipo" value="<?php echo set_value('inj3',($given)?$given->inj3:0);?>">
                        </div>
                        <div class="form-group col-lg-2 col-md-3 col-sm-3 col-xs-12">
                            <label for="ultra">Ultraburn</label>
                            <input type="number" name="inj4" class="form-control" id="ultra" value="<?php echo set_value('inj4',($given)?$given->inj4:0);?>">
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-10 col-sm-12 col-xs-12" style="padding-left: 30px;">
                        <button class="btn btn-success pull-left">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('template/footer');?>
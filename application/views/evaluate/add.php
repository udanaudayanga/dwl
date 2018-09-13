<?php $this->load->view('template/header');?>
<div class="row">
    <div class="col-xs-12">
        <div class="card">                                
            <div class="card-body" style="padding:15px 0px;">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="">
                    <div class="thumbnail" style="border: none;margin-bottom: 0px;">
                        <img  src="/phpThumb/phpThumb.php?src=/assets/upload/patients/<?php echo $patient->photo;?>&amp;w=64&amp;h=54&amp;zc=1&amp;f=png" />
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="">
                    <h3 style="margin-top: 0px;"><?php echo $patient->lname." ".$patient->fname;?></h3>
                    <h5><abbr title="Date of birth">DOB:</abbr>  <?php echo date('m/d/Y',strtotime($patient->dob));?></h5><br />

                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="">
                    
                    <address style="margin-top: 0px;">
                        <?php echo $patient->address;?><br>
                        <?php echo $patient->city.", ".getStateAbbr($patient->state)." ".$patient->zip;?>
                    </address>					
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="">
                    <address style="margin-top: 0px;">
                        <?php echo "(".substr($patient->phone, 0, 3).") ".substr($patient->phone, 3, 3)."-".substr($patient->phone,6);?><br>
                        <a href="mailto:<?php echo $patient->email;?>"><?php echo $patient->email;?></a>
                    </address>		
                    
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
        <div class="col-xs-12" style="margin-bottom: 0px;">
        <?php echo validation_errors('<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');?>
    </div>
    <div class="col-xs-12">
        <div class="card">   
            <div class="card-header">
                <div class="card-title" style="width: 100%;">
                    <div class="title" style="width: 100%;">Add 12 week phase   <span style="font-weight: bold;float: right;">Phase : <?php echo $pn;?></span></div>
                </div>
                
            </div>
            <div class="card-body">
                <form id="add_pro_cart" method="POST">
                    <div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
                        <label for="qty">Start Weight</label>
                        <input type="text" class="form-control" name="start_weight" value="" id="qty" >
                    </div>
                    <div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
                        <label for="qty">Start BMI</label>
                        <input type="text" class="form-control" name="bmi" value="" id="qty" >
                    </div>
                    <div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
                        <label for="qty">Start BFI</label>
                        <input type="text" class="form-control" name="bfi" value="" id="qty" >
                    </div>
                    <div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
                        <label for="qty">Start Date</label>
                        <input type="text" class="form-control" name="start" readonly="readonly" value="<?php echo $start_date;?>" id="qty" >
                    </div>
                    <div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
                        <label for="qty">Target Weight</label>
                        <input type="text" class="form-control" name="target_weight" value="" id="qty" >
                    </div>
                    <div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
                        <label for="qty">Target Date</label>
                        <input type="text" class="form-control" name="end" readonly="readonly" value="<?php echo $end_date;?>" id="qty" >
                    </div>

                    <div style="" class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
                        <label for="day">&nbsp;</label>
                        <button style="margin-top: 0px;"  type="submit" class="btn btn-success form-control">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('template/footer');?>
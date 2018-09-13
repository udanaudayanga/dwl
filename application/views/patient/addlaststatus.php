<?php $this->load->view('template/header');?>
<div class="row">
    <div class="col-xs-12">
        <div class="card">                                
            <div class="card-body" style="padding:15px 0px;">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="thumbnail" style="border: none;margin-bottom: 0px;">
                        <img  src="/phpThumb/phpThumb.php?src=/assets/upload/patients/<?php echo $patient->photo;?>&amp;w=64&amp;h=54&amp;zc=1&amp;f=png" />
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="">
                    <h3 style="margin-top: 0px;"><?php echo $patient->lname." ".$patient->fname;?></h3>
                    <h5><abbr title="Date of birth">DOB:</abbr>  <?php echo date('m/d/Y',strtotime($patient->dob));?></h5>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="">
                    
                    <address style="margin-top: 0px;margin-bottom: 0px;">
                        <?php echo $patient->address;?><br>
                        <?php echo $patient->city.", ".getStateAbbr($patient->state)." ".$patient->zip;?><br>
                        <?php echo "(".substr($patient->phone, 0, 3).") ".substr($patient->phone, 3, 3)."-".substr($patient->phone,6);?>
                    </address>					
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="">
                    <h4 style="margin-top: 0px;"><?php echo $location->name;?></h4>
                    <h4 style="margin-top: 10px;font-size: 16px;">Patient Category: &nbsp;&nbsp;<span style="text-transform: uppercase;"><?php echo $patient->patient_category;?></span></h4>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="card">   
            <div class="card-header">
                <div class="card-title" style="padding: 0.3em 15px;">
                    <div class="title">Add Last Status of the patient</div>
                </div>
            </div>
            <div class="card-body" style="padding: 10px 0;">
                <?php if(isset($success)){?>
                <div class="alert fresh-color alert-success" role="alert">Submitted Successfully.</div>
                <?php } ?>
                <form id="" method="POST">

                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label for="lsd">Last Status Date</label>
                        <input type="text" data-date-orientation="bottom auto" data-provide="datepicker" data-date-autoclose="true" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY" name="last_status_date" value="<?php echo set_value("last_status_date",($patient->last_status_date) ? date('m/d/Y',strtotime($patient->last_status_date)):NULL);?>" id="lsd" class="form-control">
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label for="weight">Location</label>
                        <select name="location_id" class="form-control" style="width: 100%;">
                            <?php foreach($locations as $loc){?>
                            <option <?php if($pls && $pls->location_id == $loc->id){?> selected="selected" <?php } ?> value="<?php echo $loc->id;?>"><?php echo $loc->name;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label for="weight">Weight</label>
                        <input type="text" class="form-control" name="weight" value="<?php echo ($pls)?$pls->weight:'';?>" id="weight" placeholder="Weight">
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label for="bfi">BFI</label>
                        <input type="text" class="form-control" name="bfi" value="<?php echo ($pls)?$pls->bfi:'';?>" id="bfi" placeholder="BFI">
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label for="bmi">BMI</label>
                        <input type="text" class="form-control" name="bmi" value="<?php echo ($pls)?$pls->bmi:'';?>" id="bmi" placeholder="BMI">
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label for="bp">BP</label>
                        <input type="text" class="form-control" name="bp" value="<?php echo ($pls)?$pls->bp:'';?>" id="bp" placeholder="BP">
                    </div>
                    <br>
                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label for="avbl_today">Previous (Available TODAY)</label>
                        <textarea class="form-control" id="avbl_today" name="avbl_today"><?php echo ($pls)?$pls->avbl_today:'';?></textarea>
                    </div>                    
                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label for="dr_note">Doctor's Note</label>
                        <textarea class="form-control" id="dr_note" name="dr_note"><?php echo ($pls)?$pls->dr_note:'';?></textarea>
                    </div>
                    <div class="col-xs-12" style="text-align: right;">
                        <input type="submit" class="btn btn-success" value="SUBMIT"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer');?>
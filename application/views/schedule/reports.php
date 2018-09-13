<?php $this->load->view('template/header');?>

<div class="row">
    <div class="col-xs-12">
        <div class="card">   
            <div class="card-header">
                <div class="card-title">
                    <div class="title">Generate Employee Shift Reports</div>
                </div>
            </div>
            <div class="card-body">
                <form id="" method="GET" action="<?php echo site_url('schedule/genReport');?>" target="_blank">
                    <div class="col-xs-12" id="add_shift_errors"></div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label for="from">From</label>
                        <input type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" placeholder="YYYY-MM-DD" class="form-control dp" name="from" value="" id="from" placeholder="From">
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label for="to">To</label>
                        <input type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" placeholder="YYYY-MM-DD" class="form-control dp" name="to" value="" id="to" placeholder="To">
                    </div>
                    <div class="form-group  col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label for="cat">Location</label>
                        <select class="form-control" name="location_id" id="" tabindex="-1" aria-hidden="true" style="width: 100%;">    
                            <option  value="all">ALL</option>
                           <?php foreach($locations as $loc){?>
			    <option <?php echo set_select('location_id', $loc->id); ?> value="<?php echo $loc->id;?>"><?php echo $loc->name;?></option>
			    <?php } ?>
                        </select>
                    </div>
                    
                    
                    
<!--                    <div class="form-group col-lg-2 col-md-6 col-xs-12">
                        <label for="day">Days</label>
                        <input type="text" class="form-control" name="days" id="day" placeholder="Days">
                    </div>-->

                    <div style="" class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label for="day">&nbsp;</label>
                        <button style="margin-top: 0px;" id="add_shift_btn" type="submit" class="btn btn-success form-control">Generate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('.dp').datepicker({
                daysOfWeekDisabled: [0,2,3,4,5,6]
        });
    });
</script>
<?php $this->load->view('template/footer');?>
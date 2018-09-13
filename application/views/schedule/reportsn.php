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
                <form id="" method="GET" action="<?php echo site_url('schedule/genReportn');?>" target="_blank">
                    <div class="col-xs-12" id="add_shift_errors"></div>
                    <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label for="from">Select a Monday to generate for next two weeks</label>
                        <input readonly="readonly" type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" placeholder="YYYY-MM-DD" class="form-control dp" name="from" value="" id="from" placeholder="From">
                    </div>
                    <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
                        <label for="se">Select Employee</label>
                        <select id="se" class="form-control" name="se">
                            <option value="all">ALL</option>
                            <?php foreach($employees as $emp){?>
                            <option value="<?php echo $emp->id;?>"><?php echo $emp->lname." ".$emp->fname;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    

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
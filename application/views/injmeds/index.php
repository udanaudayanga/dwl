<?php $this->load->view('template/header');?>
<style>
    table tr td,table tr th{text-align: center;}
    table tr th.strip,table tr td.strip{background: repeating-linear-gradient(
  90deg,
  #eee,
  #eee 5px,
  #fff 5px,
  #fff 10px
);}
    
        table tr th.stripblue,table tr td.stripblue{background: repeating-linear-gradient(
  90deg,
  #e0ffff,
  #e0ffff 5px,
  #fff 5px,
  #fff 10px
);}
        td.mi_name{font-size: 22px;}
        td.action_td a img{width: 40%;}
        table.table tr td.action_td{padding: 2px;width: 30%;}
        td.font_nmbr{font-size: 20px;}
</style>

<div class="row">
    <div class="col-xs-12">
        <div class="card">   
            <div class="card-header">
                <div class="card-title">
                    <div class="title">View Weekly Summary</div>
                </div>
            </div>
            <div class="card-body" style="padding: 5px 0px 0px 0px;">
                <div class="col-xs-12">
                    <?php if($this->session->flashdata('message')){?>
                    <div role="alert" class="alert fresh-color alert-success">
                          <strong><?php echo $this->session->flashdata('message');?></strong>
                    </div>
                    <?php } ?>
                    <?php if($this->session->flashdata('error')){?>
                    <div role="alert" class="alert fresh-color alert-danger">
                          <strong><?php echo $this->session->flashdata('error');?></strong>
                    </div>
                    <?php } ?>
                </div>
                <?php echo validation_errors();?>
                <form id="" method="POST" action="<?php echo site_url('injmeds');?>">
                    <div class="col-xs-12" id="add_shift_errors"></div>
                    <?php if($user->type == 1){?>
                    <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label for="from">Select a Monday to view</label>
                        <input readonly="readonly" type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" placeholder="YYYY-MM-DD" class="form-control dp" name="week" value="<?php if(isset($week)) echo $week;?>" id="from" placeholder="From">
                    </div>
                    <?php } ?>
                    <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
                        <label for="se">Select Location</label>
                        <select id="se" class="form-control" name="location">
                            <?php foreach($locations as $loc){?>
                            <option <?php if(isset($loc_id) && $loc_id == $loc->id) echo 'selected="selected"';?> value="<?php echo $loc->id;?>"><?php echo $loc->abbr;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    

                    <div style="" class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label for="day">&nbsp;</label>
                        <button style="margin-top: 0px;" id="" type="submit" class="btn btn-success form-control">SELECT</button>
                    </div>
                    <?php if(isset($loc_week) && $user->type == 1){?> 
                    <div style="" class="form-group col-lg-1 col-md-2 col-sm-6 col-xs-12">
                        <label for="day">&nbsp;</label>
                        <a class="btn btn-warning form-control" style="margin-top: 0px;"  href="<?php echo site_url("injmeds/add/$loc_id/$week");?>">EDIT</a>
                    </div>
                    <div style="" class="form-group col-lg-1 col-md-2 col-sm-6 col-xs-12">
                        <label for="day">&nbsp;</label>
                        <a class="btn btn-info form-control" style="margin-top: 0px;"  href="<?php echo site_url("injmeds/copy/$loc_week->id");?>">Copy To Next Week</a>
                    </div>
                    <div style="" class="form-group col-lg-1 col-md-2 col-sm-6 col-xs-12">
                        <label for="day">&nbsp;</label>
                        <a class="btn btn-primary form-control" style="margin-top: 0px;" target="_blank"  href="<?php echo site_url("injmeds/printWeek/$loc_week->id");?>">PRINT</a>
                    </div>
                    <?php } ?>
                    
                    
                </form>
            </div>
        </div>
    </div>
</div>
<?php if($add){?>
    <div class="panel panel-default">
        <div class="panel-body" style="text-align: center;">
            <?php if($user->type == 1){?>
       <a class="btn btn-success btn-lg" href="<?php echo site_url("injmeds/add/$loc_id/$week");?>">Add Given Values For This Week</a>
            <?php }else{?>
       <div class="alert alert-danger" role="alert">No Given values added for the week.</div>
            <?php } ?>
      </div>
    </div>
<?php } ?>
<?php if(isset($loc_week)){?> 
<div class="panel panel-default">
    <div class="panel-body" style="">
        <div class="col-xs-12" style="font-size: 18px;padding-bottom: 5px;">
            <strong>Week:&nbsp;&nbsp;</strong>  <?php echo date('D M d',strtotime($weekdays[1]))."&nbsp;&nbsp;  -  &nbsp;&nbsp;". date('D M d',strtotime($weekdays[6]));?>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Location:</strong> <?php echo getLocation($loc_week->location_id)->name;?>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>WEEK:</strong> <?php echo date('W',strtotime($weekdays[1]));?>
        </div>
        <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Med/Inj</th>
                        <th>Given</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="mi_name"><?php echo $imarr['med1'];?></td>
                        <td><?php echo $loc_week->med1;?></td>
                        <td class="action_td"><a class="update_injmeds" data-id="<?php echo $loc_week->id;?>" data-med="med1" data-mn="<?php echo $imarr['med1'];?>" href=""><img src="/assets/img/update_btn.png" /></a></td>
                    </tr>
                    <tr>
                        <td class="mi_name"><?php echo $imarr['med2'];?></td>
                        <td><?php echo $loc_week->med2;?></td>
                        <td class="action_td"><a class="update_injmeds"  data-id="<?php echo $loc_week->id;?>" data-med="med2" data-mn="<?php echo $imarr['med2'];?>" href=""><img src="/assets/img/update_btn.png"/></a></td>
                    </tr>
                    <tr>
                        <td class="mi_name"><?php echo $imarr['med3'];?></td>
                        <td><?php echo $loc_week->med3;?></td>
                        <td class="action_td"><a class="update_injmeds"  data-id="<?php echo $loc_week->id;?>" data-med="med3" data-mn="<?php echo $imarr['med3'];?>" href=""><img src="/assets/img/update_btn.png"/></a></td>
                    </tr>
                    <tr>
                        <td class="mi_name"><?php echo $imarr['med4'];?></td>
                        <td><?php echo $loc_week->med4;?></td>
                        <td class="action_td"><a class="update_injmeds"  data-id="<?php echo $loc_week->id;?>" data-med="med4" data-mn="<?php echo $imarr['med4'];?>" href=""><img src="/assets/img/update_btn.png"/></a></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1 hidden-xs"></div>
        <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Med/Inj</th>
                        <th>Given</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="mi_name"><?php echo $imarr['inj1'];?></td>
                        <td><?php echo $loc_week->inj1;?></td>
                        <td class="action_td"><a class="update_injmeds" data-id="<?php echo $loc_week->id;?>" data-med="inj1" data-mn="<?php echo $imarr['inj1'];?>" href=""><img src="/assets/img/update_btn.png"/></a></td>
                    </tr>
                    <tr>
                        <td class="mi_name"><?php echo $imarr['inj2'];?></td>
                        <td><?php echo $loc_week->inj2;?></td>
                        <td class="action_td"><a class="update_injmeds" data-id="<?php echo $loc_week->id;?>" data-med="inj2" data-mn="<?php echo $imarr['inj2'];?>" href=""><img src="/assets/img/update_btn.png"/></a></td>
                    </tr>
                    <tr>
                        <td class="mi_name"><?php echo $imarr['inj3'];?></td>
                        <td><?php echo $loc_week->inj3;?></td>
                        <td class="action_td"><a class="update_injmeds" data-id="<?php echo $loc_week->id;?>" data-med="inj3" data-mn="<?php echo $imarr['inj3'];?>" href=""><img src="/assets/img/update_btn.png"/></a></td>
                    </tr>
                    <tr>
                        <td class="mi_name"><?php echo $imarr['inj4'];?></td>
                        <td><?php echo $loc_week->inj4;?></td>
                        <td class="action_td"><a class="update_injmeds" data-id="<?php echo $loc_week->id;?>" data-med="inj4" data-mn="<?php echo $imarr['inj4'];?>" href=""><img src="/assets/img/update_btn.png"/></a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade modal-info" id="view_injmeds" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">
                    <div class="col-sm-4 col-xs-12">Weekly Usage <span style="font-size: 14px;font-style: italic;">(Update end of day)</span></div>
                    <div class="col-sm-3 col-xs-12"><span style="">Location: <?php echo getLocation($loc_id)->name;?></span></div>
                    <div class="col-sm-2 col-xs-12"><span id="med_name" style=""></span></div>
                    <div class="col-sm-2 col-xs-12"><span style="">WEEK: <?php echo date('W',strtotime($weekdays[1]));?></span></div>
                
                </h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<script type="text/javascript">
    $(function(){
        $('.dp').datepicker({
                daysOfWeekDisabled: [0,2,3,4,5,6]
        });
        
        $(".update_injmeds").on('click',function(e)
        {
            e.preventDefault();
            _target = $(this);
            _modal = $('#view_injmeds');
            _id = _target.data('id');
            _med = _target.data('med');
            _modal.find('#med_name').html('Med: '+_target.data('mn'));
            
            $.get(BASE_URL+'injmeds/getViewUsage/'+_id+'/'+_med,function doload(data){
                _modal.find('.modal-body').html(data);
                
                _modal.find('#update_mi').unbind('click').bind('click', function(e) {
                    e.preventDefault();
                    $.post(BASE_URL+'injmeds/updateUsage',$('#im_usage_form').serialize(),function(data){
//                        _modal.find('.modal-body').html(data);
                        bootbox.alert('Usage Updated.');
                          doload(data);
                    });
                });
            });
            
            _modal.on('hidden', function() {
            	_modal.find('.modal-body').html('');
            });

            _modal.modal();
        });
    });
</script>
<?php $this->load->view('template/footer');?>
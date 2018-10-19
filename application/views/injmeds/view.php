<?php $this->load->view('template/header');?>
<style>
    td.lb,th.lb{background-color: #CAF0FE;}
    table.util_tbl tr td input
    {
        font-size: 19px;
    }
    
    ul.nav-tabs li.active{font-weight: bold;}
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
                <form id="" method="POST" action="">
                    <div class="col-xs-12" id="add_shift_errors"></div>
                   
                    <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label for="from">Select a Monday to view</label>
                        <input readonly="readonly" type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" placeholder="YYYY-MM-DD" class="form-control dp" name="week" value="<?php if(isset($week)) echo $week;?>" id="from" placeholder="From">
                    </div>
                    
                    <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
                        <label for="se">Select Location</label>
                        <select id="se" class="form-control" name="loc_id">
                            <?php foreach($locations as $loc){?>
                            <option <?php if(isset($loc_id) && $loc_id == $loc->id) echo 'selected="selected"';?> value="<?php echo $loc->id;?>"><?php echo $loc->abbr;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    

                    <div style="" class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        <label for="day">&nbsp;</label>
                        <button style="margin-top: 0px;" id="" type="submit" class="btn btn-success form-control">SELECT</button>
                    </div>
                    <?php if(isset($mis)){?> 
                    
                    <div style="" class="form-group col-lg-1 col-md-2 col-sm-6 col-xs-12">
                        <label for="day">&nbsp;</label>
                        <a class="btn btn-primary form-control" style="margin-top: 0px;" target="_blank"  href="<?php echo site_url("injmeds/printWeek/$week/$loc_id");?>">PRINT</a>
                    </div>
                    <div style="" class="form-group col-lg-1 col-md-2 col-sm-6 col-xs-12">
                        <label for="day">&nbsp;</label>
                        <a class="btn btn-info form-control " id="copynext" style="margin-top: 0px;" data-loc_id="<?php echo $loc_id;?>" data-start="<?php echo $week;?>"  href="">Copy To Next Week</a>
                    </div>
                    <?php } ?>
                    
                    
                </form>
            </div>
        </div>
    </div>
</div>
<?php if(isset($mis)){?>
<div class="row">
    <div class="col-xs-12">
        <div class="card">  
            <div class="card-body" style="padding: 5px 0px 0px 0px;">
                <div>

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <?php
                        $first = TRUE;
                        foreach($imarr as $key => $val){?>
                        <li role="presentation" class="<?php if($first){$first = FALSE;echo 'active';};?>"><a style="font-size: 19px;" href="#<?php echo $key;?>" aria-controls="home" role="tab" data-toggle="tab"><?php echo $val;?></a></li>                      
                        <?php } ?>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <?php
                        $first = TRUE;
                        foreach($imarr as $key => $val){?>
                        <?php                         
                            $tbl = $mis[$key];                            
                        ?>
                            <div role="tabpanel" class="tab-pane <?php if($first){$first = FALSE;echo 'active';};?>" id="<?php echo $key;?>">
                                
                                <table class="table table-bordered util_tbl" style="margin-bottom: 10px;">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%;">Date</th>
                                            <th class="lb">Start of Day/Given</th>
                                            <th class="strip" style="width: 20%;">End of Today Count<br>(left in the bags)</th>
                                            <th>Utilized Today<br>(From Manual Count)</th>
                                            <th class="stripblue">Auto Utilized<br>(From Today’s Orders)</th>
                                            <th style="width: 15%;">Staff</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $tu = 0; ?>
                                        <?php foreach($tbl as $ky=>$val){?> 
                                        <?php 
                                            $date = date('j',strtotime($ky)); 
                                            $id = $key."_".$date;
                                        ?>
                                        <tr>
                                            <td><?php echo date('D M d',strtotime($ky));?></td>
                                            <td class="lb"><input <?php if($ky != date('Y-m-d') && $user->type != 1) echo 'readonly="readonly"'?> id="<?php echo $id.'_g';?>" style="text-align: center;" type="text" class="form-control" value="<?php $given = isset($val['given'])?$val['given']:0; echo $given;?>"/></td>
                                            <td><input <?php if($ky != date('Y-m-d') && $user->type != 1) echo 'readonly="readonly"'?> id="<?php echo $id.'_u';?>" style="text-align: center;" type="text" class="form-control" value="<?php $used = isset($val['used'])?$val['used']:0; echo $used;?>"/></td>
                                            <td style="text-align: center;font-size: 19px;" class="<?php echo $key.'_u';?>" id="<?php echo $id.'_b';?>"><?php $tempu = ($given - $used);  if($tempu>0){echo $tempu;$tu += $tempu;}?></td>
                                            <td style="text-align: center;font-size: 19px;" ><?php echo $val['ou'];?></td>
                                            <td>
                                                <?php if($ky == date('Y-m-d') || $user->type == 1){?>
                                                <select id="<?php echo $id.'_s';?>" style="width: 100%;" class="form-control" name="">
                                                    <option value="0">Select</option>
                                                    <?php foreach($staff as $s){?>
                                                    <option <?php if($val && isset($val['staff_id']) && $val['staff_id'] == $s->id) echo 'selected="selected"';?>  value="<?php echo $s->id;?>"><?php echo $s->lname." ".$s->fname;?></option>
                                                    <?php } ?>
                                                </select>
                                                <?php }else{?> 
                                                <?php if($val && isset($val['staff'])){echo $val['staff'];}?>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                 <?php if($ky == date('Y-m-d') || $user->type == 1){?>
                                                <a href="" style="margin: 0px;" data-loc="<?php echo $loc_id;?>" data-date="<?php echo $ky;?>" data-im="<?php echo $key;?>" data-id="<?php echo $id;?>" class="btn btn-success btn-sm update_im">Update</a>
                                                 <?php } ?>
                                            </td>
                                        </tr>
                                        
                                        <?php } ?>
                                        <tr>
                                            <td style="font-weight: bold;">Total:</td>
                                            <td class="lb">&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td id="<?php echo $key.'_t';?>" style="font-weight: bold;text-align: center;"><?php echo $tu;?></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>                    
                        <?php } ?>                      
                    </div>

                  </div>
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
        
        $('#copynext').on('click',function(e){
           e.preventDefault(); 
           
           _target = $(this);
           
           _start = _target.data('start');
           _loc_id = _target.data('loc_id');
           
           $.post(BASE_URL+'injmeds/copy',{start:_start,loc_id:_loc_id},function(data){
               if(data == 'success')
                   bootbox.alert('Copied to next week successfully');
           });
        });
        
        $('.update_im').on('click',function(e){
            e.preventDefault();
            _target = $(this);
            _id = _target.data('id');
            _g = $('#'+_id+'_g').val();
            _u = $('#'+_id+'_u').val();
            _s = $('#'+_id+'_s').val();
            _date = _target.data('date');
            _im = _target.data('im');
            _loc = _target.data('loc');
            _bal = _g - _u;
            
            
            
            var _post = {
                            date:_date,
                            given:_g,
                            used:_u,
                            staff:_s,
                            im:_im,
                            loc:_loc
                        };
            
            $.post(BASE_URL+'injmeds/updateIM',_post,function(data){
                if(data == 'success')
                {
                    $('#'+_id+'_b').html(_bal);
//                    _total = $('#'+_im+'_t').html();
//                    _total = parseInt(_total + _bal);
//                    $('#'+_im+'_t').html(_total);
                    bootbox.alert('Report added successfully');
                }
            });
            
        });
    });
</script>
<?php $this->load->view('template/footer');?>
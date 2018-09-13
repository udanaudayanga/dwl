<?php $this->load->view('template/header');?>
<div class="row">
    <div class="col-xs-12">
        <div class="card">                                
            <div class="card-body" style="padding:7px 0px;">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="">
                    <div class="thumbnail" style="border: none;margin-bottom: 0px;">
                        <img  src="/phpThumb/phpThumb.php?src=/assets/upload/patients/<?php echo $patient->photo;?>&amp;h=100&amp;zc=1&amp;f=png" />
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
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">

                <div class="card-title">
                <div class="title">Phases</div>
                </div>                
                <a class="btn btn-danger pull-right" style="font-weight: bold;margin: 15px;" onclick="return confirm('Are you sure?');" href="<?php echo site_url("evaluate/remAll/$patient->id");?>">Remove All Phases</a>
                <a class="btn btn-success pull-right" id="add_phase" style="font-weight: bold;margin: 15px;"  href="#">Add Phase</a>
            </div>
            <div class="card-body" id="eval_div" style="padding: 15px;">
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
                <table class="datatable table table-striped" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>12 wk Phase</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $last_phase = null;
                        foreach($phases as $phase){?>
                        <tr class="black">
                            <td style="font-weight: bold;padding-left: 25px;"><?php echo $phase->phase;?></td>
                            <td><?php echo date('m/d/Y',strtotime($phase->start));?></td>
                            <td><?php echo ($phase->end)?date('m/d/Y',strtotime($phase->end)):'-';?></td>
                            <td style="text-transform: capitalize;"><?php echo ($phase->end)?'Complete':'inprogress';?></td>
                            <td>
                                <a title="Summery PDF" target="_blank"  class="" href="<?php echo site_url("evaluate/report/$phase->id");?>" style="color: #04c;" ><span style="font-size: 1.2em;" aria-hidden="true" class="glyphicon glyphicon-file"></span></a>&nbsp;&nbsp;
                                <a title="Edit" data-id="<?php echo $phase->id;?>" data-start="<?php echo $phase->start;?>" data-end="<?php echo $phase->end;?>" data-phase="<?php echo $phase->phase;?>" class="edit_phase" href="" style="color: #31b0d5;"><span aria-hidden="true" style="font-size: 1.2em;" class="glyphicon glyphicon-edit"></span></a> &nbsp;&nbsp;
                                <a title="Delete" href="<?php echo site_url("evaluate/remove/$patient->id/$phase->id");?>" style="color: red;" data-toggle="modal" onclick="return confirm('Are you sure?');"><span style="font-size: 1.2em;" aria-hidden="true" class="glyphicon glyphicon-trash"></span></a>
                                
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-info" id="add_phase_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 550px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Add Phase</h4>
            </div>
            <div class="modal-body" style="overflow: auto;">
                <div id="as_errors"></div>
                <form id="add_phase_frm" >
                    <input type="hidden" id="shift_id" name="patient_id" value="<?php echo $patient->id;?>" />
                    
                    <div class="col-xs-12" style="padding: 0px;">
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label for="qty">Start Date</label>
                            <input type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" placeholder="YYYY-MM-DD" class="form-control" name="start" value="<?php echo date('Y-m-d');?>" >
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label for="qty">End Date</label>
                            <input type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" placeholder="YYYY-MM-DD" class="form-control" name="end" value="<?php echo date('Y-m-d',strtotime("+12 weeks"));?>" >
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label for="qty">Phase</label>
                            <select name="phase" class="form-control not_select2">
                                <?php for($i=1;$i<10;$i++){?> 
                                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        
                        <div class="col-xs-12" style="">
                            <a href="" class="btn btn-success pull-right" id="add_phase_btn">Add</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-info" id="edit_phase_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 550px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Edit Phase</h4>
            </div>
            <div class="modal-body" style="overflow: auto;">
                <div id="as_errors"></div>
                <form id="edit_phase_frm" >
                    <input type="hidden" id="phase_id" name="id" value="" />
                    
                    <div class="col-xs-12" style="padding: 0px;">
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label for="qty">Start Date</label>
                            <input type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" placeholder="YYYY-MM-DD" class="form-control" name="start" value="" id="ep_start_date">
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label for="qty">End Date</label>
                            <input type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" placeholder="YYYY-MM-DD" class="form-control" name="end" value="" id="ep_end_date">
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label for="qty">Phase</label>
                            <select id="ep_phase" name="phase" class="form-control not_select2">
                                <?php for($i=1;$i<10;$i++){?> 
                                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        
                        <div class="col-xs-12" style="">
                            <a href="" class="btn btn-success pull-right" id="update_phase_btn">Update</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript"> 
    $(function(){
       $('#add_phase').on('click',function(e){
           e.preventDefault();
           _target = $(this);
            _modal = $('#add_phase_modal');
            
            _modal.find('#add_phase_btn').unbind('click').bind('click', function(ex) {
                ex.preventDefault();
                _modal.find('#as_errors').html('');
                $.post(BASE_URL+'evaluate/add',$('#add_phase_frm').serialize(),function(data){
                    data = JSON.parse(data);
                    if(data.status == 'error')
                    {
                        _modal.find('#as_errors').html(data.errors);
                    }
                    else if(data.status == 'success')
                    {
                        _modal.find('#as_errors').html(data.msg);
                        setTimeout(function(){
                            _modal.find('#as_errors').html('');
                            _modal.modal('hide');
                        },1500);
                        location.reload();
                    }
                });
            });
            
            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
            _modal.modal();
       }); 
       
        $('#eval_div').on('click','.edit_phase',function(e){
           e.preventDefault();
           _target = $(this);
            _modal = $('#edit_phase_modal');
            
            _modal.find('#ep_start_date').val(_target.data('start'));
            _modal.find('#ep_end_date').val(_target.data('end'));
            _modal.find('#ep_phase').val(_target.data('phase'));
            _modal.find('#phase_id').val(_target.data('id'));
            
            _modal.find('#update_phase_btn').unbind('click').bind('click', function(ex) {
                ex.preventDefault();
                _modal.find('#as_errors').html('');
                $.post(BASE_URL+'evaluate/update',$('#edit_phase_frm').serialize(),function(data){
                    data = JSON.parse(data);
                    if(data.status == 'error')
                    {
                        _modal.find('#as_errors').html(data.errors);
                    }
                    else if(data.status == 'success')
                    {
                        _modal.find('#as_errors').html(data.msg);                        
                        _modal.find('#as_errors').html('');
                        _modal.modal('hide');                        
                        location.reload();
                    }
                });
            });
            
            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
            _modal.modal();
       }); 
       
 
    });
</script>
<?php $this->load->view('template/footer');?>
<?php $this->load->view('template/header');?>
<style>
    tr.restart td{background-color: #99ff99 !Important;}
.chat
{
    list-style: none;
    margin: 0;
    padding: 0;
}

.chat li
{
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 1px dotted #B3A9A9;
}

.chat li.left .chat-body
{
    margin-left: 60px;
}

.chat li.right .chat-body
{
    margin-right: 60px;
}


.chat li .chat-body p
{
    margin: 0;
    color: #777777;
}

.panel .slidedown .glyphicon, .chat .glyphicon
{
    margin-right: 5px;
}

.panel-body
{
    
}

::-webkit-scrollbar-track
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    background-color: #F5F5F5;
}

::-webkit-scrollbar
{
    width: 12px;
    background-color: #F5F5F5;
}

::-webkit-scrollbar-thumb
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #555;
}

</style>
    <div class="row">

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12" style="">
                <img src="/phpThumb/phpThumb.php?src=/assets/upload/patients/<?php echo $patient->photo;?>&amp;h=180&amp;zc=1&amp;f=png" class="thumbnail"  style="margin-bottom: 0px;max-width: 100%;float: left;"/>
                <a style="float: right;" href="<?php echo site_url("patient/edit/$patient->id");?>" class="btn btn-default " title="edit"><span class="glyphicon glyphicon-edit"></a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="">
                <h3 style="margin-top: 0px;"><?php echo $patient->lname." ".$patient->fname;?></h3>
                <?php $age = ($patient->dob)? date_diff(date_create($patient->dob), date_create('now'))->y:0; ?>
                <h4 style="width: 20%;float: left;margin-top: 0px;"><span class="label label-success"  ><?php echo getPatientStatus($patient->status);?></span> </h4><?php if($age > 1){?><h4 style="float: left;width: 80%;margin-top: 0px;"> <?php echo $age;?> Years</h4><?php } ?>
                
                <?php $height = $patient->height;
                $feet = floor($height/12);$inches = ($height%12);
                ?>
                <br>
                <h4  style="width: 20%;float: left;"><?php echo $feet."'";?> <?php if($inches > 0) echo $inches.'"';?></h4>  <h4 style="float: left;width: 80%;"><span class="badge" style="font-size: 15px;"><?php echo $patient->gender==1? 'M':'F';?></span></h4>
                <br>
                <address>
                    <span style="font-size: 14px;"> <?php echo $patient->address.", ".$patient->city.", ".getStateAbbr($patient->state)." ".$patient->zip;?></span><br>
                    <?php echo "(".substr($patient->phone, 0, 3).") ".substr($patient->phone, 3, 3)."-".substr($patient->phone,6);?> <?php if($patient->sms == 0){?>&nbsp;<span style="color:red;font-size: 16px;cursor: pointer;" title="SMS has stopped" class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><?php } ?><br>
                    <a href="mailto:<?php echo $patient->email;?>"><?php echo $patient->email;?><?php if($patient->email_validated == 1){?><span style="color:#5cb85c;margin-left: 10px;font-size: 18px;" aria-hidden="true" class="glyphicon glyphicon-ok"></span><?php }else{?><span style="color:#fabe28;margin-left: 10px;font-size: 18px;" aria-hidden="true" class="glyphicon glyphicon-warning-sign"></span><?php } ?></a>
                </address>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="">
                <p style="overflow: auto;"><label class="col-lg-2 col-md-4 col-sm-5 col-xs-12" style="margin-bottom: 0px;padding-left: 0px;">Date Of Birth</label> <?php if($patient->dob)echo date('m/d/Y',strtotime($patient->dob));?></p>
                <p style="overflow: auto;"><label class="col-lg-2 col-md-4 col-sm-5 col-xs-12" style="margin-bottom: 0px;padding-left: 0px;">Goal Weight</label> <?php echo $patient->goal_weight;?></p>
                <?php if($patient->allergies){?><p style="font-size: 14px;"><label class="col-lg-2 col-md-4 col-sm-5 col-xs-12" style="margin-bottom: 0px;padding-left: 0px;">Allergies</label> <?php echo $patient->allergies;?></p><?php } ?>
                <?php if($patient->previous_medication){?><p style="font-size: 14px;"><label class="col-lg-2 col-md-4 col-sm-5 col-xs-12" style="margin-bottom: 0px;padding-left: 0px;">Prev. Meds</label> <?php echo $patient->previous_medication;?></p><?php } ?>
                <?php if($patient->staff && ($usr = getUser($patient->staff))){?><p style="font-size: 14px;"><label class="col-lg-2 col-md-4 col-sm-5 col-xs-12" style="margin-bottom: 0px;padding-left: 0px;">Added By</label> <?php echo $usr->lname." ".$usr->fname; ?></p><?php } ?>
                <?php if($fv){?><p style="font-size: 14px;"><label class="col-lg-2 col-md-4 col-sm-5 col-xs-12" style="margin-bottom: 0px;padding-left: 0px;">Initial Weight</label><span id="ini_weight_val"><?php echo $fv->weight;?></span> <a data-weight="<?php echo $fv->weight;?>" data-id="<?php echo $fv->id;?>" href="" id="edit_iw"><span style="color:#31b0d5;margin-left: 10px;font-size: 16px;" aria-hidden="true" class="glyphicon glyphicon-edit"></span></a></p><?php } ?>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12" style="">
                <!--<a class="btn btn-success col-xs-10" style="margin-bottom: 0px;padding: 3px 6px;"  href="<?php echo site_url("patient/addLastStatus/$patient->id");?>">Add Last Status</a>-->
                <a class="btn btn-info col-xs-10" style="margin-bottom: 0px;padding: 3px 6px;"  id="add_alert_btn" data-id="<?php echo $patient->id;?>" href="">Add Alert</a>
                <a class="btn btn-info col-xs-10" style="margin-bottom: 0px;padding: 3px 6px;"  id="view_alert_btn" data-id="<?php echo $patient->id;?>" href="">View Alerts</a>
                <?php if($patient->phone){?>
                <a class="btn btn-primary col-xs-10" data-phone="<?php echo $patient->phone;?>" style="margin-bottom: 0px;padding: 3px 6px;"  id="send_sms_alert_btn" data-id="<?php echo $patient->id;?>" href="">Send SMS</a>
                <?php } ?>
                <?php if($patient->patient_refferral_id){?>
                <a class="btn btn-warning col-xs-10" style="margin-bottom: 0px;padding: 3px 6px;"  id="ref_info_alert_btn" data-id="<?php echo $patient->id;?>" href="">Ref. Info</a>
                <?php } ?>
                <?php if(!empty($restarts)){?> 
                <a class="btn  col-xs-10" style="margin-bottom: 0px;padding: 3px 6px;background-color: #b2c326;color: #fff;"  id="restart_info_alert_btn" data-id="<?php echo $patient->id;?>"  data-toggle="modal" data-target="#view_restarts" href="#view_restarts">Restarts</a>
                <?php } ?>
            </div>

            <!--<a href="<?php echo site_url("patient/edit/$patient->id");?>" class="btn btn-default " title="edit"><span class="glyphicon glyphicon-edit"></a>-->
        </div>
    </div>

</div>
<?php if($this->session->flashdata('message')){?>
    <div role="alert" class="alert fresh-color alert-success">
          <strong><?php echo $this->session->flashdata('message');?></strong>
    </div>
<?php } ?>
<?php if($this->session->flashdata('error')){?>
    <div role="alert" class="alert fresh-color alert-danger">
                    <?php echo $this->session->flashdata('error');?>
    </div>
<?php } ?>
<div class="card">
    <div class="card-body" style="padding: 15px 0;">
        
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="padding: 15px 0;">
                <div class="col-xs-2">
                    <label style="font-size: 18px;">ECG:</label>
                </div>
                <?php if($ecgs){?>
                <div class="col-xs-5">
                    <?php
                    $latest = array_values($ecgs)[0];
                    ?>
                    <a target="_blank" href="<?php echo site_url("assets/upload/ecg/$latest->file")?>"  class="btn btn-info btn-sm" style="font-size: 15px;padding: 3px 6px;">Latest ECG</a>
                    &nbsp;&nbsp;<a href="" data-toggle="modal" data-target="#ecg_history" class="btn btn-warning btn-sm" style="font-size: 15px;padding: 3px 6px;">ECG History</a>
                </div>
                <?php } ?>
                <div class="col-xs-5">
                    <form id="ecg_form" method="POST" enctype="multipart/form-data" action="<?php echo site_url('patient/ecg');?>">
                        <input type="hidden" name="patient_id" value="<?php echo $patient->id;?>"/>
                        <input class="" type="file" name="userfile" id="ecg_file"/>
                    </form>
                </div>
            </div>
        <div class="col-lg-2 col-md-2 hidden-sm hidden-xs" style="padding: 15px 0;"></div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="padding: 15px 0;">
                <div class="col-xs-2">
                    <label style="font-size: 18px;">BW:</label>
                </div>
                 <?php if($bws){?>
                <div class="col-xs-5">
                     <?php
                    $latest = array_values($bws)[0];
                    ?>
                    <a target="_blank" href="<?php echo site_url("assets/upload/bw/$latest->file")?>"  class="btn btn-info btn-sm" style="font-size: 15px;padding: 3px 6px;">Latest BW</a>
                    &nbsp;&nbsp;<a href="" data-toggle="modal" data-target="#bw_history" class="btn btn-warning btn-sm" style="font-size: 15px;padding: 3px 6px;">BW History</a>
                </div>
                <?php } ?>
                <div class="col-xs-5">
                    <form id="bw_form"  method="POST" enctype="multipart/form-data" action="<?php echo site_url('patient/bw');?>">
                        <input type="hidden" name="patient_id" value="<?php echo $patient->id;?>"/>
                    <input type="file"  name="userfile" id="bw_file"/>
                    </form>
                </div>
            </div>
    </div>
</div>
    

<div class="card" style="margin-top: 10px;">

    <div class="card-body no-padding">
        <div role="tabpanel">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Visits</a></li>
                <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Orders</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="home">
                        <table class="datatable table table-bordered">
                            <thead>
                                <tr>
                                    <th>Visit</th>
                                    <th>RS Visit</th>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Weight</th>
                                    <th>Phase</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $rsv = 1;?>
                                <?php foreach($visits as $visit){?>
                                <?php if(isset($rss[$visit->order_id])) $rsv = 1; ?>
                                <tr class="<?php if(isset($rss[$visit->order_id])){?>restart<?php } ?>">
                                    <td scope="row"><?php echo $visit->visit;?></td>
                                    <td><?php echo $rsv;?></td>
                                    <td><a title="View Order" target="_blank"  href="<?php echo site_url("order/view/$visit->order_id");?>" style="color: #31b0d5;"><?php echo str_pad($visit->order_id, 5, '0', STR_PAD_LEFT);?></a></td>
                                    <td><?php echo date('m/d/Y',  strtotime($visit->visit_date));?></td>
                                    <td><?php echo $visit->weight;?></td>
                                    <td><?php echo getPhaseByVisit(date('Y-m-d',  strtotime($visit->visit_date)), $patient->id);?></td>
                                    <td>
                                        <a title="Visit Info" href="" data-toggle="modal" data-target="#visit_info_<?php echo $visit->id;?>"  style="color: #31b0d5;"><span aria-hidden="true" class="glyphicon glyphicon-info-sign" style="font-size: 1.2em;"></span></a>
                                        <a title="Final Page" target="_blank" href="<?php echo site_url("order/finalpage/$visit->order_id");?>"   style="color: #5cb85c;margin-left: 15px;"><span aria-hidden="true" class="glyphicon glyphicon-print" style="font-size: 1.2em;"></span></a>
                                        
                                    </td>
                                    <?php $this->load->view('patient/_visit_info_modal',array('visit'=>$visit));?>
                                </tr>
                                <?php $rsv++;?>
                                <?php } ?>
                            </tbody>
                        </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="profile">
                    <table class="datatable table table-bordered">
                            <thead>
                                <tr>
                                    <th>Order Id</th>
                                    <th>Date</th>
                                    <th>Location</th>
                                    <th>Total</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($orders as $order){?>
                                <tr>
                                    <td scope="row"><?php echo str_pad($order->id, 5, '0', STR_PAD_LEFT);?></td>
                                    <td><?php echo date('m/d/Y',strtotime($order->created));?></td>
                                    <td><?php echo $order->location;?></td>
                                    <td>&#36; <?php echo $order->net_total;?></td>
                                    <td>
                                        <a title="Edit" href="<?php echo site_url("order/view/$order->id");?>" style="color: #31b0d5;"><span aria-hidden="true" style="font-size: 1.3em;" class="glyphicon glyphicon-new-window"></span></a>
                                        <?php if(!$order->vid && date('Y-m-d',strtotime($order->created)) == date('Y-m-d') && $user->type == '1'){?>
                                        <a onclick="return confirm('Are you sure?');" class="" style="color: red;margin-left: 20px;" href="<?php echo site_url("order/viewRemoveOrder/$order->id/$patient->id");?>" title="Remove"><span style="font-size: 1.3em;" class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                                        <?php } ?>
                                        <?php if(date('Y-m-d',strtotime($order->created)) == date('Y-m-d')){?>
                                        <a title="Visit Page" target="_blank" href="<?php echo site_url("order/visitpage/$order->id");?>"   style="color: #fabe28;margin-left: 15px;"><span aria-hidden="true" class="glyphicon glyphicon-print" style="font-size: 1.2em;"></span></a>
                                        <?php }elseif(!empty($order->vp)){?>
                                        <a title="Visit Page" target="_blank" href="<?php echo site_url("order/oldvp/$order->id");?>"   style="color: #fabe28;margin-left: 15px;"><span aria-hidden="true" class="glyphicon glyphicon-print" style="font-size: 1.2em;"></span></a>
                                        <?php } ?>
                                        <?php if($order->net_total > 0){?>
                                        <a title="Sales Receipt" target="_blank" href="<?php echo site_url("order/receipt/$order->id");?>"   style="color: #666;margin-left: 15px;"><img src="/assets/img/receipt_icon.png" style="height: 20px;vertical-align: top;" ></a>
                                        <?php } ?>
                                        <?php if($lo && $lo->id == $order->id && $user->type == '1'){?>
                                        <a title="Prepaid" href="" data-date="<?php echo date('Y-m-d',strtotime($order->created));?>" data-id="<?php echo $patient->id;?>" class="check_prepaid"  style="color: #F4A81C;margin-left: 15px;"><span aria-hidden="true" class="glyphicon glyphicon-adjust" style="font-size: 1.2em;"></span></a>
                                        <?php } ?>
                                        
                                        <?php if($lo && $lo->id == $order->id && $user->type == '1'){?>
                                        <a title="Prepaid" href="" data-pid="<?php echo $patient->id;?>" data-id="<?php echo $order->id;?>" class="alter_order"  style="color: #00CC00;margin-left: 15px;"><span aria-hidden="true" class="glyphicon glyphicon-edit" style="font-size: 1.2em;"></span></a>
                                        <?php } ?>
                                        
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-info"  id="ecg_history" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">ECG History</h4>
            </div>
            <div class="modal-body">
                <table class="datatable table table-bordered">
                    <thead>
                       
                        <tr>
                            <th style="text-align: center;">ID</th>                            
                            <th style="text-align: center;">Date</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $max = count($ecgs);
                        foreach($ecgs as $ecg){?>
                            <tr>
                                
                                <td style="text-align: center;"><?php echo $max;?></td>
                                <td style="text-align: center;"><?php echo date('m/d/Y',strtotime($ecg->created));?></td>                               
                                <td style="text-align: center;">
                                    <a class="hover" target="_blank"  style="font-weight: 500;color: #31708f;" href="<?php echo site_url("assets/upload/ecg/$ecg->file")?>"><span aria-hidden="true" style="font-size: 1.2em;" class="glyphicon glyphicon-new-window"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <a data-id="<?php echo $ecg->id;?>" class="hover" style="font-weight: 500;color: red;"  onclick="return confirm('Are you sure?');" href="<?php echo site_url("patient/del_ecg/$ecg->id")?>"><span style="font-size: 1.2em;" aria-hidden="true" class="glyphicon glyphicon-trash"></span></a>
                                </td>
                            </tr>
                        <?php 
                        $max--;
                        } ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-info"  id="bw_history" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">BW History</h4>
            </div>
            <div class="modal-body">
                <table class="datatable table table-bordered">
                    <thead>
                       
                        <tr>
                            <th style="text-align: center;">ID</th>                            
                            <th style="text-align: center;">Date</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $max = count($bws);
                        foreach($bws as $bw){?>
                            <tr>
                                
                                <td style="text-align: center;"><?php echo $max;?></td>
                                <td style="text-align: center;"><?php echo date('m/d/Y',strtotime($bw->created));?></td>                               
                                <td style="text-align: center;">
                                    <a class="hover" target="_blank"  style="font-weight: 500;color: #31708f;" href="<?php echo site_url("assets/upload/bw/$bw->file")?>"><span aria-hidden="true" style="font-size: 1.2em;" class="glyphicon glyphicon-new-window"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <a data-id="<?php echo $bw->id;?>" class="hover" style="font-weight: 500;color: red;"  onclick="return confirm('Are you sure?');" href="<?php echo site_url("patient/del_bw/$bw->id")?>"><span style="font-size: 1.2em;" aria-hidden="true" class="glyphicon glyphicon-trash"></span></a>
                                </td>
                            </tr>
                        <?php 
                        $max--;
                        } ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-info" id="add_alert"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Add Alert: </h4>
            </div>
            <div class="modal-body">
                <form id="add_alert_form" class="form-horizontal">
                    <input type="hidden" name="patient_id" id="patient_id" />
                    <div class="col-xs-12 no-padding" id="as_errors"></div>

                    <div class="row">
                        <div class="col-md-12 no-padding" style="margin-bottom: 10px;">
                            <div class="col-md-3">

                                <div class="form-group" style="margin: 0px;">
                                    <label for="lsd">Expire On</label>
                                    <input type="text" data-date-orientation="bottom auto" data-provide="datepicker" data-date-autoclose="true" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY" name="expire" value="" id="lsd" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-9">

                                <div class="form-group" style="margin: 0px;">
                                    <label for="msg">Message</label>
                                    <input type="text" name="msg" placeholder="" id="msg" class="form-control">
                                </div>
                            </div>
                        </div>
                      </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="add_alert_btn_pop" class="btn btn-info">ADD</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-info" id="view_alerts"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">View Alerts: </h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>                        
                        <tr>
                            <th style="text-align: center;width: 15%;">Expire</th>
                            <th style="text-align: center;">Message</th>
                            <th style="text-align: center;width: 10%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($alerts as $alert){?>
                        <tr>
                            <td><?php echo date('m/d/Y',strtotime($alert->expire));?></td>
                            <td><?php echo $alert->msg;?></td>
                            <td><a class="hover" style="font-weight: 500;color: red;"  onclick="return confirm('Are you sure?');" href="<?php echo site_url("patient/del_alert/$alert->id/$alert->patient_id")?>"><span style="font-size: 1em;" aria-hidden="true" class="glyphicon glyphicon-trash"></span></a></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-info" id="view_restarts"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Restarts </h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>                        
                        <tr>
                            <th style="text-align: center;">Date</th>
                            <th style="text-align: center;">Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($restarts as $res){?>
                        <tr>
                            <td style="text-align: center;"><?php echo date('m/d/Y',strtotime($res->date));?></td>
                            <td style="text-align: center;"><?php echo $res->type==1?'Restart':'New Start';?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-success" id="send_sms_modal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">SMS chat with <?php echo $patient->lname." ".$patient->fname;?> <i id="chat_refresh" class="fa fa-refresh fa-pulse pull-right hide" style="margin-right: 30px;"></i></h4>
                
            </div>
            <div class="modal-body">
                <div class="row" id="sms_chat_body" style="overflow-y: scroll;height: 250px;">

                </div>
                <div class="loader-container text-center color-white">
                    <div><i class="fa fa-spinner fa-pulse fa-4x"></i></div>
                    <div>Sending ...</div>
                </div>
                <textarea class="form-control" id="sms_ta" rows="2" placeholder="SMS Text"></textarea>
            </div>
            <div class="modal-footer">
                <label id="char_count" class="pull-left">0</label>
                <label id="chat_error" style="margin-right: 50px;" class=""></label>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="send_sms_btn" class="btn btn-success">SEND</button>
            </div>
        </div>
    </div>
</div>
<?php if($patient->patient_refferral_id){?>
<div class="modal fade modal-success" id="ref_info_modal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Referral Info : <?php echo $patient->lname." ".$patient->fname;?></h4>
            </div>
            <div class="modal-body" style="overflow: auto;">
                <?php $ref_patient = $this->patient->getPatient($patient->patient_refferral_id);?>
                <div class="col-xs-5" style="font-weight: bold;">Referred by</div><div class="col-xs-5"><?php echo $ref_patient->lname." ".$ref_patient->fname;?></div>
                <div class="col-xs-5" style="font-weight: bold;">New Patient</div><div class="col-xs-5"><?php echo $patient->new_patient;?></div>
                <div class="col-xs-5" style="font-weight: bold;">Old Patient</div><div class="col-xs-5"><?php echo $patient->old_patient;?></div>
                <div class="col-xs-5" style="font-weight: bold;">Referral Meds Given</div><div class="col-xs-5"><?php echo $patient->referral_given==0?'NOT YET':'GIVEN';?></div>
                <?php if($patient->referral_given == 1){
                    $created = $this->patient->getRefGivenDate($patient);
                    if($created)
                    {
                ?>
                    <div class="col-xs-5" style="font-weight: bold;">Referral Given On</div><div class="col-xs-5"><?php echo $created;?></div>
                <?php }
                    }?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<div class="modal fade modal-info"  id="check_prepaid_modal" tabindex="-1"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">PRE-PAID - <?php echo $patient->lname." ".$patient->fname;?></h4>
            </div>
            <div class="modal-body" id="pp_history_body">
                <div  style="padding:5px;font-weight: bold" role="alert" class="alert fresh-color alert-danger pp_error_div"></div>
                <div style="padding:5px;font-weight: bold" role="alert" class="alert fresh-color alert-success pp_success_div"></div>
                <div class="loader-container text-center color-white">
                    <div><i class="fa fa-spinner fa-pulse fa-4x"></i></div>
                    <div>Loading...</div>
                </div>
                <div class="ppredeemtbl">
                   <?php $this->load->view('patient/_pp_redeem_tbl',array('patient_id'=>$patient->id));?>
                </div>
                <div class="today_redeem" style="margin-top: 20px;border-top: 1px solid #999;overflow: auto;">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>
<?php if($lo && $user->type == '1'){?>
<div class="modal fade modal-info"  id="alter_order_modal" tabindex="-1"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Alter Order Prepaid</h4>
            </div>
            <div class="modal-body" id="pp_history_body">
                <div  style="padding:5px;font-weight: bold" role="alert" class="alert fresh-color alert-danger pp_error_div"></div>
                <div style="padding:5px;font-weight: bold" role="alert" class="alert fresh-color alert-success pp_success_div"></div>
                <div class="loader-container text-center color-white">
                    <div><i class="fa fa-spinner fa-pulse fa-4x"></i></div>
                    <div>Loading...</div>
                </div>
                <div class="order_items">
                   <?php $this->load->view('patient/_pp_order_items');?>
                </div>
                <div class="" style="margin-top: 20px;border-top: 1px solid #999;overflow: auto;">
                    <h3>Add PP item to order</h3>
                    <form id="add_pp_item_form" class="form-horizontal">
                        <input type="hidden" value="<?php echo $lo->id;?>" name="order_id"/>
                        <input type="hidden" value="<?php echo date('Y-m-d',strtotime($lo->created));?>" name="order_date"/>
                        <input type="hidden" value="<?php echo $patient->id;?>" name="patient_id"/>
                        
                        <div class="col-xs-12 no-padding" id="as_errors"></div>

                        <div class="row" style="margin: 0px;">
                            <div class="col-md-12 no-padding" style="margin-bottom: 10px;">
                                <div class="col-xs-6">

                                    <div class="form-group" style="margin: 0px;">
                                        <label for="lsd">PP Product</label>
                                        <select name="pp_item" id="stock_item" class="form-control not_select2" style="width: 100%;">
                                            
                                            <?php foreach($ppPros as $cats){
                                                $pros = $cats['pros'];
                                                ?>
                                            <optgroup label="<?php echo $cats['name'];?>">
                                                <?php foreach($pros as $pro){?>
                                                <option  value="<?php echo $pro['id'];?>"><?php echo $pro['name'];?></option>
                                                <?php } ?>
                                            </optgroup>

                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group" style="margin: 0px;">
                                        <label for="msg">Qty</label>
                                        <input type="number" name="quantity" placeholder="" id="msg" value="1" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <label for="msg" style="width: 100%;">&nbsp;</label>
                                    <button id="add_item_btn" type="button" style="margin-top: 0px;" class="btn btn-success">ADD</button>
                                </div>
                            </div>
                          </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>
<?php } ?>
<script>
$(function(){
    
    $('#profile').on("click",".alter_order",function(e){
       e.preventDefault();
       _modal = $('#alter_order_modal');
       
       _pid = $(this).data('pid');
       _order_id = $(this).data('id');
       
       _modal.find('.pp_error_div').html('').hide();
       _modal.find('.pp_success_div').html('').hide();
       
       _modal.find('.remove_pp_btn').unbind('click').bind('click', function(ee) {
           ee.preventDefault();
           
           if(confirm('Are you sure?'))
           {
                _target = $(this);
                _pro_id = _target.data('proid');

                $.post(BASE_URL+'patient/removeOrderItem',{patient_id:_pid,pro_id:_pro_id,order_id:_order_id},function(data){
                    data = JSON.parse(data);
                    if(data.status == 'success')
                    {
                        _modal.find('.pp_success_div').html(data.msg).show().delay(5000).hide(0);
                        _modal.find('.order_items').html(data.oi_tbl);
                    }
                });
            }
           
       });
       
       _modal.find('#add_item_btn').unbind('click').bind('click', function(ee) {
           $.post(BASE_URL+'/patient/addOrderItem',$('#add_pp_item_form').serialize(),function(data){
               data = JSON.parse(data);
               if(data.status == 'success')
               {
                   _modal.find('.pp_success_div').html(data.msg).show().delay(5000).hide(0);
                   _modal.find('.order_items').html(data.oi_tbl);
               }
               
           });
       });
       
       _modal.modal();
    });
    
    $('#profile').on("click",'.check_prepaid',function(e){
        e.preventDefault();
        
        _pid = $(this).data('id');
        _date = $(this).data('date');
        _modal = $('#check_prepaid_modal');
          
        _modal.find('.pp_error_div').html('').hide();
        _modal.find('.pp_success_div').html('').hide();
        _modal.find(".today_redeem").html('');
        
        ppUpdate(_modal,_date);
        ppRemain(_modal);
        
        getTodayReedeemed(_pid,_date,_modal);
          
        _modal.modal();
    });
      
    function getTodayReedeemed(pid,date,_mod)
    {
        $.get(BASE_URL+"patient/lastRedeem/"+pid+"/"+date,{id:pid},function(data){
            
            _element = _mod.find(".today_redeem");
            _element.html(data);
            
            _element.find('.update_ppr').on('click', function(e) {
                e.preventDefault();
                _target = $(this);
                _id = _target.data('id');
                _ori = _target.data('ori');
                _val = _element.find('#pprd_'+_id).val();
                
                if(_val >= _ori)
                {
                    bootbox.alert('New value cannot be larger than redeemed amount');
                }
                else
                {
                    $.post(BASE_URL+'patient/adjustPP',{id:_id,ori:_ori,val:_val,date:date},function(res){
                        res = JSON.parse(res);
                        if(res.status == 'success')
                        {
                            _mod.find('.ppredeemtbl').html(res.pp_tbl);
                            ppUpdate(_mod,date);
                            ppRemain(_mod);
                            getTodayReedeemed(pid,date,_mod);                            
                        }
                    });
                }
                
            });
            
        });
    }
    
    function ppRemain(_modal)
    {
        _modal.find(".ppt").on('change keyup paste', function() {
            _pp_id = $(this).data('ppid');
            _today = parseInt($(this).val());
            _remaining = parseInt(_modal.find('#pp_remaining_'+_pp_id).val());
            _balance = 0;
            if(_today > _remaining)
            {
                $(this).val(_remaining);
            }
            else
            {
                _balance = _remaining - _today;
            }

            $('#pp_at_'+_pp_id).val(_balance);
        });
    }
    
    function ppUpdate(_modal,date)
    {
        _modal.find('.pp_update_new').on('click', function(ee) {
            ee.preventDefault();
            
            _row = $(this);
            _pro_id = _row.data('proid');
            _pp_id = _row.data('ppid');
            _patient = _row.data('patient');
            _quantity = parseInt($('#pp_today_'+_pp_id).val());
            _pro_id = _row.data('proid');
            _today = parseInt(_modal.find('#pp_today_'+_pp_id).val());
            _remaining = parseInt(_modal.find('#pp_remaining_'+_pp_id).val());

            console.log(_today + " - " + _remaining);
            if($.trim(_today) == '' || $.trim(_today) < 1)
            {            
                _modal.find('.pp_error_div').html('Today value cannot be empty!').show().delay(2000).hide(0);
            }
            else if(_today > _remaining)
            {
                _modal.find('.pp_error_div').html('Not enough Qty for that item to redeem today').show().delay(2000).hide(0);
                $('#pp_at_'+_pp_id).val('');
                $('#pp_today_'+_pp_id).val('')
            }
            else
            {
                _data = {pp_id:_pp_id,qnty:_quantity,date:date};
                _modal.find('.modal-body').addClass('loader');
                $.post(BASE_URL+'patient/update_prepaid',_data,function(data){
                    _modal.find('.modal-body').removeClass('loader');
                    data = JSON.parse(data);
                    if(data.status == 'success')
                    {
                        getTodayReedeemed(_pid,date,_modal);
                        _modal.find('.pp_success_div').html(data.msg).show().delay(5000).hide(0);
                        $('#pp_remaining_'+_pp_id).val($('#pp_at_'+_pp_id).val());
                        $('#pp_at_'+_pp_id).val('');
                        $('#pp_today_'+_pp_id).val('')
                    }
                    else if(data.status == 'error')
                    {
                        _modal.find('.pp_error_div').html(data.error).show().delay(2000).hide(0);
                        $('#pp_at_'+_pp_id).val('');
                        $('#pp_today_'+_pp_id).val('')
                    }
                });
            }
        });
    }
    
    $("#ecg_file").change(function(){
        $("#ecg_form").submit();
    });
    $("#bw_file").change(function(){
        $("#bw_form").submit();
    });
    
    $('#add_alert_btn').on('click',function(e){
        e.preventDefault();
        _modal = $('#add_alert');        
        _target = $(this);
        _id = _target.data('id');
        _modal.find('#patient_id').val(_id);
        
        _modal.find('#add_alert_btn_pop').unbind('click').bind('click', function(e) {
	    $.post(BASE_URL+'patient/addAlert',$('#add_alert_form').serialize(),function(data){
		document.location.reload(true);
	    });
	});
        
        _modal.modal('show');
    });
    
    var _sms_chat_load = null;
    $('#send_sms_alert_btn').on('click',function(e){
        e.preventDefault();
        _modal = $('#send_sms_modal');
        _modal.find('#sms_ta').val('');
        _phn = $(this).data('phone');
        _id = $(this).data('id');
        _sms_chat_body = _modal.find('#sms_chat_body');
        
        _modal.find('#send_sms_btn').unbind('click').bind('click', function(e) {
            _msg = _modal.find('#sms_ta').val();
            if(_msg)
            {
                _modal.find('.modal-body').addClass('loader');
                $.post(BASE_URL+'patient/sendSMS',{msg:_msg,phn:_phn,id:_id},function(data){
                    _modal.find('.modal-body').removeClass('loader');
                    if(data == 'success')
                    {
                        _modal.find('#sms_ta').val('');
                        _modal.find("#char_count").text(0);
                        fetch_sms_log(_sms_chat_body,_id);
                        _modal.find('#chat_error').text("Message Sent Successfully").addClass('text-success');
                        setTimeout(function(){_modal.find('#chat_error').text("").removeClass('text-success');},'2000');
                    }
                    else
                    {
                        _modal.find('#chat_error').text("Message Sending Failed").addClass('text-danger');
                        setTimeout(function(){_modal.find('#chat_error').text("").removeClass('text-danger');},'2000');
                    }
                });
            }
            else 
            {
                _modal.find('#chat_error').text("Message cannot be empty!").addClass('text-danger');
                setTimeout(function(){_modal.find('#chat_error').text("").removeClass('text-danger');},'2000');
            }
	});
        fetch_sms_log(_sms_chat_body,_id);
        _sms_chat_load = setInterval(function(){fetch_sms_log(_sms_chat_body,_id)},10000);
        
        _modal.on("hidden.bs.modal", function () {               
            _modal.find('#sms_ta').val('');
            _modal.find("#char_count").text(0);
            clearInterval(_sms_chat_load);
        });
        
        _modal.modal();
    });
    
    function fetch_sms_log(element,patient_id)
    {   $('#chat_refresh').removeClass('hide');     
        $.get(BASE_URL+'patient/getSmsLog/'+patient_id,function(data){
            element.html(data);
            element.animate({scrollTop: element.find('.panel').height()}, 0);
            $('#chat_refresh').addClass('hide'); 
        });
    }
    
    $('#ref_info_alert_btn').on('click',function(e){
        e.preventDefault();
        _modal = $('#ref_info_modal');
        _modal.modal();
    });
    
    $('#view_alert_btn').on('click',function(e){
        e.preventDefault();
        _modal = $('#view_alerts');     
        
        _modal.modal('show');
    });
    
    $('#edit_iw').on('click',function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var weight = $(this).data('weight');
        _target = $(this);
        
      bootbox.prompt({
           size:'small',
            title: 'Update Initial Weight',
            message: 'Set weight',
            inputType: 'number',
            value: weight,
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancel'
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> Update',
                    className: 'btn-success'
                }
            },
            callback: function (result) {
                if(result)
                {
                    if(result != weight){
                        $.post(BASE_URL+'patient/updateIniWght',{id:id,weight:result},function(res){
                            $('#ini_weight_val').html(result);
                            _target.data('weight',result);
                        });                   
                    }
                }
                else
                {
                    bootbox.alert('Weight cannot be empty');
                }
            }
            
        });

    });
    
    $("#sms_ta").keyup(function(){
        $("#char_count").text($(this).val().length);
      });
      
      
});
</script>
<?php $this->load->view('template/footer');?>
<?php $this->load->view('template/header');?>

<style>
        .checkbox3 label, .radio3 label{font-size: 16px;}
        .checkbox3 label::before, .radio3 label::before {
            top: 9px;
        }
        .checkbox3 input:checked + label::after {margin-top: 5px;}
        .radio3.radio-check label::after, .radio3.radio-check.radio-light label::after {
            height: 26px;
            line-height: 26px;
        }
        
        table.meds_tbl tr td,table.meds_tbl tr th{padding: 4px 4px;}
        table.inj_tbl tr td,table.inj_tbl tr th{padding: 2px 4px;font-size: 14px;}
        
        tr td.b12ushade{border: 3px solid #666 !important;background: repeating-linear-gradient(
  130deg,
  #eee,
  #eee 2px,
  #fff 0px,
  #fff 10px
);}
        
/*@media (min-width: 1200px) {
  table{width: 80%;}
}
@media (max-width: 767px) {
  table{width: 100%;}
}
@media (min-width: 768px) and (max-width: 991px) {
  table{width: 90%;}
}
@media (min-width: 992px) and (max-width: 1199px) {
  table{width: 80%;}
}*/
    </style>
<div class="row">
    
        <div class="card"> 
           
            <div class="card-body" style="padding: 15px 0px;">
                <form method="POST">
                    <div class="col-xs-12">
                    <?php echo validation_errors('<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');?>
                    </div>
                <div class="form-inline col-xs-12">
                    
                     <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 15px;">
                         <?php if($user->type == 1){?>
                         <label style="font-size: 18px;" class="col-lg-1 col-md-2 col-sm-3 col-xs-4" for="exampleInputName3">Date Range: </label>
                        <div class="input-daterange input-group col-lg-2 col-md-6 col-sm-6 col-xs-6" id="datepicker">
                            <input type="text" class="input-sm form-control" id="start" name="start" value="<?php echo set_value('start');?>"/>
                            <span class="input-group-addon">to</span>
                            <input type="text" class="input-sm form-control" id="end" name="end" value="<?php echo set_value('end');?>"/>
                        </div>
                         <?php }else{?>
                         <label style="font-size: 18px;" class="col-lg-1 col-md-2 col-sm-3 col-xs-4" for="exampleInputName3">Date: </label>
                         <div class="input-group col-lg-2 col-md-6 col-sm-6 col-xs-6" id="">
                             <input style="font-size: 15px;width: 150px;" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="today" placeholder="YYYY-MM-DD" type="text" class="datepicker input-sm form-control" id="date" name="date" value="<?php echo set_value('date');?>"/>
                            
                        </div>
                         <?php } ?>
                    </div>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="">
                        <label style="font-size: 18px;" class="col-lg-1 col-md-2  col-sm-3 col-xs-4" for="exampleInputName3">Location : </label>
                        
                        <?php foreach($locations as $location){?>
                        <div class="checkbox3 checkbox-inline checkbox-check checkbox-light" style="margin-left: 15px;">
                            <input type="checkbox" value="<?php echo $location->id;?>" name="location[]" <?php echo set_checkbox('location[]',$location->id);?> id="checkbox-fa-light-<?php echo $location->id;?>">
                            <label for="checkbox-fa-light-<?php echo $location->id;?>">
                              <?php echo isset($location->abbr)? $location->abbr : $location->name;?>
                            </label>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                
                    <div class="form-inline col-xs-12" style="padding-left: 40px;">                    
                    <button class="btn btn-success" id="show_btn" type="submit" style="font-size: 16px;">Show Stats</button>
                </div>
                </form>
            </div>
        </div>
    
</div>

<?php if(isset($stats)){?>
    <?php foreach($stats as $key=>$stat){?>
        <div class="row" style="margin-top:20px;">
            <div class="card"> 
                <div class="card-header">
                    <div class="card-title" style="padding: 10px;">
                        <?php $location = getLocation($key);?>
                    <div class="title"><?php echo $location->name;?></div>
                    </div>
                    <?php if($user->type == 1){?>
                    <a class="btn btn-success pull-right" target="_blank" style="margin: 10px 15px 0 0;" href="<?php echo site_url("home/statpdf/$key/$start/$end");?>">View PDF</a>
                    <?php }else{?>
                    <a class="btn btn-success pull-right" target="_blank" style="margin: 10px 15px 0 0;" href="<?php echo site_url("home/statpdf/$key/$date/NULL");?>">View PDF</a>
                    <?php } ?>
                </div>
                <div class="card-body" style="padding: 15px 0px;">
                    
                    <div class="col-sm-4 col-xs-12" style="padding: 0px 8px 0px 5px;">
                        <p style="margin-bottom: 0px;height: 30px;">&nbsp;</p>
                        <table style="width: 100%;">
                            <tr>
                                <td style="border: 1px solid black;">
                                    <table class="table table-bordered table-striped" style="margin: 0px;">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>#</th>
                                            <th>Credit</th>
                                            <th>Cash</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>                            
                                            <td>New</td>
                                            <td><?php echo $location->new_count = isset($stat['new']['count'])?$stat['new']['count']:0;?></td>
                                            <td><?php echo $location->new_crd = isset($stat['new']['crd'])?$stat['new']['crd']:0;?></td>
                                            <td><?php echo $location->new_cash = isset($stat['new']['cash'])?$stat['new']['cash']:0;?></td>
                                        </tr>
                                        <tr>
                                            <td>Existing</td>
                                            <td><?php echo $location->ex_count = isset($stat['ex']['count'])?$stat['ex']['count']:0;?></td>
                                            <td><?php echo $location->ex_crd = isset($stat['ex']['crd'])?$stat['ex']['crd']:0;?></td>
                                            <td><?php echo $location->ex_cash = isset($stat['ex']['cash'])?$stat['ex']['cash']:0;?></td>
                                        </tr>
                                        <tr>
                                            <td>All</td>
                                            <td><?php echo $location->new_count + $location->ex_count;?></td>
                                            <td><?php echo $location->new_crd + $location->ex_crd;?></td>
                                            <td><?php echo $location->new_cash + $location->ex_cash;?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                    </div>
                    <div class="col-sm-2 col-xs-12" style="padding: 0px 8px 0px 5px;">
                        <p style="margin-bottom: 0px;height: 30px;">&nbsp;</p>
                        <table style="width: 100%;">
                            <tr>
                                <td style="border: 1px solid black;";>
                                    <table class="table table-bordered table-striped" style="margin: 0px;">
                                        <thead>
                                            <tr>
                                                <th style="width: 34%;"></th>
                                                <th style="width: 34%;">#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>                            
                                                <td>Meds</td>
                                                <td><?php echo isset($stats[$key]['med'])?$stats[$key]['med']:0;?></td>
                                            </tr>
                                            <tr>
                                                <td>No Meds</td>
                                                <td><?php echo isset($stats[$key]['nomed'])?$stats[$key]['nomed']:0;?></td>
                                            </tr>
                                            <tr>
                                                <td>SO/PrO</td>
                                                <td><?php echo isset($stats[$key]['so'])?$stats[$key]['so']:0;?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-2 col-xs-12" style="padding: 0px 8px 0px 8px;">
                        <p style="margin-bottom: 2px;font-weight: bold;height: 30px;">
                            <?php if($sg){?>
                            1cc B12 USED <input type="number" class="form-control bui input-sm bu_<?php echo $key;?>" style="width: 100px;display: inline-block;"/>&nbsp;<a style="display: inline-block;margin: 0px;" href="" data-loc="<?php echo $key;?>" data-date="<?php echo $date;?>" class="btn btn-sm btn-success add_b12">ADD</a>
                            <?php }else{ ?>
                            &nbsp;
                            <?php } ?>
                        </p>
                        <table style="width: 100%;">
                            <tr>
                                <td style="border: 1px solid black;">
                                        <table class="table table-bordered inj_tbl table-striped" style="margin: 0px;">
                                        <thead>
                                            <tr>
                                                <th style="width: 70%;"></th>
                                                <th style="width: 30%;">Used</th>
                                                <?php if($sg && isset($mig[$key]['Lipogen'][$wd])){?>
                                                <th style="text-align: center;"><span style="color: red;">Left</span></th>
                                                    <th style="text-align: center;"><span style="color: blue;">Total</span></th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>                                            
                                                <tr>                            
                                                    <td>B-12 &nbsp;<span style="color: red;">0.4</span></td>

                                                    <td><?php echo $b12 = isset($injs[$key]['B-12'])?$injs[$key]['B-12']:0;?></td>
                                                    <?php if($sg && isset($mig[$key]['B-12'][$wd])){?>
                                                    <?php $b12left = ($mig[$key]['B-12'][$wd]) - $b12;?>
                                                    <td  style="text-align: center;"><span style="color: red;font-weight: bold;"><?php echo $b12left; ?></span></td>
                                                    <td style="text-align: center;"><span style="color: blue;">(<?php echo $mig[$key]['B-12'][$wd];?>)</span></td>
                                                    <?php } ?>
                                                </tr>

                                                
                                                <tr  class="">                            
                                                    <td>B-12 &nbsp;<span style="color: red;">1cc</span></td>                                                    
                                                    <td><?php echo isset($injs[$key]['b121cc'])?$injs[$key]['b121cc']:0;?></td>       
                                                    <?php if($sg && isset($mig[$key]['B-12'][$wd])){?>
                                                    <td colspan="2"  style="text-align: center;">-</td>  
                                                    <?php } ?>
                                                </tr>
                                                
                                        
                                            <tr>
                                                <td style="width: 50%;border-top: 2px solid black;">Lipogen</td>
                                                <td style="width: 20%;border-top: 2px solid black;"><?php echo $lipogen = isset($injs[$key]['Lipogen'])?$injs[$key]['Lipogen']:0;?></td>
                                                 <?php if($sg && isset($mig[$key]['Lipogen'][$wd])){?>
                                                <td style="text-align: center;border-top: 2px solid black;"><span style="color: red;font-weight: bold;"><?php echo ($mig[$key]['Lipogen'][$wd]) - $lipogen; ?></span></td>
                                                <td style="text-align: center;border-top: 2px solid black;"><span style="color: blue;">(<?php echo $mig[$key]['Lipogen'][$wd];?>)</span></td>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <td>Ultraburn</td>
                                                <td><?php echo $ub = isset($injs[$key]['Ultraburn'])?$injs[$key]['Ultraburn']:0;?></td>
                                                 <?php if($sg && isset($mig[$key]['Ultraburn'][$wd])){?>
                                                <td style="text-align: center;"><span style="color: red;font-weight: bold;"><?php echo ($mig[$key]['Ultraburn'][$wd]) - $ub; ?></span></td>
                                                <td style="text-align: center;"><span style="color: blue;">(<?php echo $mig[$key]['Ultraburn'][$wd];?>)</span></td>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <td>Glutathione 200mg/ml</td>
                                                <td><?php echo $ub = isset($injs[$key]['Glutathione'])?$injs[$key]['Glutathione']:0;?></td>
                                                 <?php if($sg && isset($mig[$key]['Glutathione'][$wd])){?>
                                                <td style="text-align: center;"><span style="color: red;font-weight: bold;"><?php echo ($mig[$key]['Glutathione'][$wd]) - $ub; ?></span></td>
                                                <td style="text-align: center;"><span style="color: blue;">(<?php echo $mig[$key]['Glutathione'][$wd];?>)</span></td>
                                                <?php } ?>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-2 col-xs-12" style="padding: 0px 8px 0px 8px;">
                        <p style="margin-bottom: 2px;font-weight: bold;height: 30px;">
                            <?php if($sg){?>
                            1cc B12 USED <input type="number" class="form-control bui input-sm bu_<?php echo $key;?>" style="width: 100px;display: inline-block;"/>&nbsp;<a style="display: inline-block;margin: 0px;" href="" data-loc="<?php echo $key;?>" data-date="<?php echo $date;?>" class="btn btn-sm btn-success add_b12">ADD</a>
                            <?php }else{ ?>
                            &nbsp;
                            <?php } ?>
                        </p>
                        <table style="width: 100%;">
                            <tr>
                                <td style="border: 1px solid black;">
                                        <table class="table table-bordered inj_tbl table-striped" style="margin: 0px;">
                                        <thead>
                                            <tr>
                                                <th style="width: 70%;"></th>
                                                <th style="width: 30%;">Used</th>
                                                <?php if($sg && isset($mig[$key]['Lipogen'][$wd])){?>
                                                <th style="text-align: center;"><span style="color: red;">Left</span></th>
                                                    <th style="text-align: center;"><span style="color: blue;">Total</span></th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>                                           

                                            <tr>
                                                <td>Amino Blend</td>
                                                <td><?php echo $ub = isset($injs[$key]['AminoBlend'])?$injs[$key]['AminoBlend']:0;?></td>
                                                 <?php if($sg && isset($mig[$key]['AminoBlend'][$wd])){?>
                                                <td style="text-align: center;"><span style="color: red;font-weight: bold;"><?php echo ($mig[$key]['AminoBlend'][$wd]) - $ub; ?></span></td>
                                                <td style="text-align: center;"><span style="color: blue;">(<?php echo $mig[$key]['AminoBlend'][$wd];?>)</span></td>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <td>Stress Buster</td>
                                                <td><?php echo $ub = isset($injs[$key]['StressBuster'])?$injs[$key]['StressBuster']:0;?></td>
                                                 <?php if($sg && isset($mig[$key]['StressBuster'][$wd])){?>
                                                <td style="text-align: center;"><span style="color: red;font-weight: bold;"><?php echo ($mig[$key]['StressBuster'][$wd]) - $ub; ?></span></td>
                                                <td style="text-align: center;"><span style="color: blue;">(<?php echo $mig[$key]['StressBuster'][$wd];?>)</span></td>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <td>Vit D3</td>
                                                <td><?php echo $ub = isset($injs[$key]['VitD3'])?$injs[$key]['VitD3']:0;?></td>
                                                 <?php if($sg && isset($mig[$key]['VitD3'][$wd])){?>
                                                <td style="text-align: center;"><span style="color: red;font-weight: bold;"><?php echo ($mig[$key]['VitD3'][$wd]) - $ub; ?></span></td>
                                                <td style="text-align: center;"><span style="color: blue;">(<?php echo $mig[$key]['VitD3'][$wd];?>)</span></td>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <td>Biotin</td>
                                                <td><?php echo $ub = isset($injs[$key]['Biotin'])?$injs[$key]['Biotin']:0;?></td>
                                                 <?php if($sg && isset($mig[$key]['Biotin'][$wd])){?>
                                                <td style="text-align: center;"><span style="color: red;font-weight: bold;"><?php echo ($mig[$key]['Biotin'][$wd]) - $ub; ?></span></td>
                                                <td style="text-align: center;"><span style="color: blue;">(<?php echo $mig[$key]['Biotin'][$wd];?>)</span></td>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-2 col-xs-12" style="padding: 0px 5px 0px 8px;">
                        <p style="margin-bottom: 0px;height: 30px;">&nbsp;</p>
                        <table style="width: 100%;">
                            <tr>
                                <td style="border: 1px solid black;";>
                                    <table class="table table-bordered table-striped meds_tbl" style="margin: 0px;">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Used</th>
                                                <?php if($sg && isset($mig[$key]['37'][$wd])){?>
                                                <th><span style="color: red;font-weight: bold;">Left</span>&nbsp;/&nbsp;<span style="color: blue;">Total</span></th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>                            
                                                <td>37.5</td>
                                                <td><?php echo $ts = isset($stats[$key]['37'])?round($stats[$key]['37']/7):0;?></td>
                                                <?php if($sg && isset($mig[$key]['37'][$wd])){?>
                                                <td><span style="color: red;font-weight: bold;"><?php echo ($mig[$key]['37'][$wd]) - $ts; ?></span>&nbsp;&nbsp;<span style="color: blue;">(<?php echo $mig[$key]['37'][$wd];?>)</span></td>
                                                <?php } ?>
                                            </tr>
                                            <tr>                            
                                                <td>37.5 Extd</td>
                                                <td><?php echo $ts = isset($stats[$key]['37 Extd'])?round($stats[$key]['37 Extd']/7):0;?></td>
                                                <?php if($sg && isset($mig[$key]['37 Extd'][$wd])){?>
                                                <td><span style="color: red;font-weight: bold;"><?php echo ($mig[$key]['37 Extd'][$wd]) - $ts; ?></span>&nbsp;&nbsp;<span style="color: blue;">(<?php echo $mig[$key]['37 Extd'][$wd];?>)</span></td>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <td>15</td>
                                                <td><?php echo $ft = isset($stats[$key]['15'])?round($stats[$key]['15']/7):0;?></td>
                                                <?php if($sg && isset($mig[$key]['15'][$wd])){?>
                                                <td><span style="color: red;font-weight: bold;"><?php echo ($mig[$key]['15'][$wd]) - $ft; ?></span>&nbsp;&nbsp;<span style="color: blue;">(<?php echo $mig[$key]['15'][$wd];?>)</span></td>
                                                <?php } ?>
                                            </tr>
                                            <!-- <tr>
                                                <td>30</td>
                                                <td><?php echo $tf = isset($stats[$key]['30'])?round($stats[$key]['30']/7):0;?></td>
                                                <?php if($sg && isset($mig[$key]['30'][$wd])){?>
                                                <td><span style="color: red;font-weight: bold;"><?php echo ($mig[$key]['30'][$wd]) - $tf; ?></span>&nbsp;&nbsp;<span style="color: blue;">(<?php echo $mig[$key]['30'][$wd];?>)</span></td>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <td>DI</td>
                                                <td><?php echo $di = isset($stats[$key]['DI'])?round($stats[$key]['DI']/7):0;?></td>
                                                <?php if($sg && isset($mig[$key]['di'][$wd])){?>
                                                <td><span style="color: red;font-weight: bold;"><?php echo ($mig[$key]['di'][$wd]) - $di; ?></span>&nbsp;&nbsp;<span style="color: blue;">(<?php echo $mig[$key]['di'][$wd];?>)</span></td>
                                                <?php } ?>
                                            </tr> -->
                                        </tbody>
                                    </table>
                                </td>
                        </tr>
                    </table>
                    </div>

                </div>
            </div> 
        </div>
    <?php } ?>
<?php } ?>
        
<script type="text/javascript">
    $(function(){
        $('.input-daterange').datepicker({
            maxViewMode: 0,
            autoclose: true,
            format: "yyyy-mm-dd"
        });
        
        $('.bui').keyup(function () { 
            this.value = this.value.replace(/[^0-9\.]/g,'');
        });
        
        $('.add_b12').on('click',function(e){
            e.preventDefault();
            _loc = $(this).data('loc');
            _used = $('.bu_'+_loc).val();
            _date = $(this).data('date');
            
            if(_used == '' || _used ==0)
            {
                alert('Value cannot be empty!');
            }
            else
            {
                $.post(BASE_URL+'home/saveB12',{loc_id:_loc,date:_date,value:_used},function(data){
                    $('#show_btn').trigger('click');
                });
            }
        });
         
    });

</script>
<?php $this->load->view('template/footer');?>
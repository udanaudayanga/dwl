<?php $this->load->view('template/header');?>
<div class="row">
    <div class="col-xs-12" style="margin-bottom: 0px;">
        <?php echo validation_errors('<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');?>
    </div>
    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
        <div class="card"> 
            <div class="card-header">
                <div class="card-title" style="padding: 10px;">
                <div class="title">Compare Locations</div>
                </div>
            </div>
            <div class="card-body" style="padding: 15px 0px;">
                <form method="POST">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="inputEmail3">Loc 1</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="loc_1" style="width: 100%;">
                                <?php foreach($locations as $location){?>
                                <option <?php echo set_select('loc_1', $location->id);?> value="<?php echo $location->id;?>"><?php echo $location->name;?></option>
                                <?php } ?>
                            </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="inputEmail3">Loc 2</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="loc_2" style="width: 100%;">
                                <?php foreach($locations as $location){?>
                                <option <?php echo set_select('loc_2', $location->id);?> value="<?php echo $location->id;?>"><?php echo $location->name;?></option>
                                <?php } ?>
                            </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-horizontal">
                        <div class="form-group">
                            
                            <label class="col-sm-3 control-label" for="inputEmail3">Date Range</label>
                            <div class="col-sm-9">
                                <div class="input-daterange input-group " id="datepicker">
                                <input type="text" class="input-sm form-control" id="start" name="start" value="<?php echo set_value('start');?>"/>
                                <span class="input-group-addon">to</span>
                                <input type="text" class="input-sm form-control" id="end" name="end" value="<?php echo set_value('end');?>"/>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            
                            <div class="col-sm-10">
                                <button name="com_loc" value="1" type="submit" class="btn btn-success">Compare Locations</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <?php if(!empty($locc)){?>
            <div class="card"> 
                
                <div class="card-body" style="padding: 15px 0px;">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <table style="width:100%;">
                            <tr>
                                <td style="border: 1px solid black;";>
                                    <table class="table table-bordered table-striped" style="margin: 0px;">
                                <thead>
                                    <tr>
                                        <th style="width: 50%;"></th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>                            
                                        <td><?php echo getLocation($loc_1)->name;?></td>
                                        <td><?php echo $all_new_count = isset($locc['loc_1']['count'])?$locc['loc_1']['count']:0;?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo getLocation($loc_2)->name;?></td>
                                        <td><?php echo $all_ex_count =  isset($locc['loc_2']['count'])?$locc['loc_2']['count']:0;?></td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                                </td>
                            </tr>
                        </table>

                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <table style="width:100%;">
                            <tr>
                                <td style="border: 1px solid black;";>
                                    <table class="table table-bordered table-striped" style="margin: 0px;">
                                        <thead>
                                            <tr>
                                                <th style="width: 34%;"></th>
                                                <th style="width: 34%;">Credit</th>
                                                <th style="width: 34%;">Cash</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>                            
                                                <td><?php echo getLocation($loc_1)->name;?></td>
                                                <td><?php echo $all_new_crd = isset($locc['loc_1']['crd'])?$locc['loc_1']['crd']:0;?></td>
                                                <td><?php echo $all_new_cash = isset($locc['loc_1']['cash'])?$locc['loc_1']['cash']:0;?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo getLocation($loc_2)->name;?></td>
                                                <td><?php echo $all_ex_crd = isset($locc['loc_2']['crd'])?$locc['loc_2']['crd']:0;?></td>
                                                <td><?php echo $all_ex_cash = isset($locc['loc_2']['cash'])?$locc['loc_2']['cash']:0;?></td>
                                            </tr>                                            
                                        </tbody>
                                    </table>
                                    </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
        <div class="card"> 
            <div class="card-header">
                <div class="card-title" style="padding: 10px;">
                <div class="title">Compare Dates</div>
                </div>
            </div>
            <div class="card-body" style="padding: 15px 0px;">
                <form method="POST">
                    
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="inputEmail3">Date 1</label>
                        <div class="col-sm-9">
                            <div class="input-daterange input-group " id="datepicker">
                            <input type="text" class="input-sm form-control" id="start" name="d1_start" value="<?php echo set_value('d1_start');?>"/>
                            <span class="input-group-addon">to</span>
                            <input type="text" class="input-sm form-control" id="end" name="d1_end" value="<?php echo set_value('d1_end');?>"/>
                        </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="inputEmail3">Date 2</label>
                        <div class="col-sm-9">
                            <div class="input-daterange input-group " id="datepicker">
                            <input type="text" class="input-sm form-control" id="start" name="d2_start" value="<?php echo set_value('d2_start');?>"/>
                            <span class="input-group-addon">to</span>
                            <input type="text" class="input-sm form-control" id="end" name="d2_end" value="<?php echo set_value('d2_end');?>"/>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="inputEmail3">Location</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="location" name="location" style="width: 100%;">
                            <?php foreach($locations as $location){?>
                            <option value="<?php echo $location->id;?>"><?php echo $location->name;?></option>
                            <?php } ?>
                        </select>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <div class="col-sm-10">
                            <button name="com_date" type="submit" value="1" class="btn btn-success">Compare Date Ranges</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
        
        <?php if(!empty($datec)){?>
            <div class="card"> 
                
                <div class="card-body" style="padding: 15px 0px;">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <table style="width:100%;">
                            <tr>
                                <td style="border: 1px solid black;";>
                                    <table class="table table-bordered table-striped" style="margin: 0px;">
                                <thead>
                                    <tr>
                                        <th style="width: 50%;"></th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>                            
                                        <td><?php echo $dr1;?></td>
                                        <td><?php echo $all_new_count = isset($datec['dr1']['count'])?$datec['dr1']['count']:0;?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $dr2;?></td>
                                        <td><?php echo $all_ex_count =  isset($datec['dr2']['count'])?$datec['dr2']['count']:0;?></td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                                </td>
                            </tr>
                        </table>

                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <table style="width:100%;">
                            <tr>
                                <td style="border: 1px solid black;";>
                                    <table class="table table-bordered table-striped" style="margin: 0px;">
                                        <thead>
                                            <tr>
                                                <th style="width: 34%;"></th>
                                                <th style="width: 34%;">Credit</th>
                                                <th style="width: 34%;">Cash</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>                            
                                                <td><?php echo $dr1;?></td>
                                                <td><?php echo $all_new_crd = isset($datec['dr1']['crd'])?$datec['dr1']['crd']:0;?></td>
                                                <td><?php echo $all_new_cash = isset($datec['dr1']['cash'])?$datec['dr1']['cash']:0;?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $dr2;?></td>
                                                <td><?php echo $all_ex_crd = isset($datec['dr2']['crd'])?$datec['dr2']['crd']:0;?></td>
                                                <td><?php echo $all_ex_cash = isset($datec['dr2']['cash'])?$datec['dr2']['cash']:0;?></td>
                                            </tr>                                            
                                        </tbody>
                                    </table>
                                    </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('.input-daterange').datepicker({
            maxViewMode: 0,
            autoclose: true,
            format: "yyyy-mm-dd"
        });
    });

</script>
<?php $this->load->view('template/footer');?>
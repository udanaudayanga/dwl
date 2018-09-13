<?php $this->load->view('template/header');?>
<div class="row">
    <div class="col-xs-12" id="table_con">
        <div class="loader-container text-center color-white">
            <div><i class="fa fa-spinner fa-pulse fa-3x"></i></div>
            <div>Loading</div>
        </div>
        <div class="card">
            <div class="card-header">

                <div class="card-title">
                    <div class="title"><span style="margin-right:30px;">Location: <?php echo $daily_loc=='all'?'ALL':  getLocation($daily_loc)->name;?></span>Daily Schedule: <?php echo date('D M d',strtotime($date));?></div>
                    
                </div>
                <a href="<?php echo site_url('schedule/printDay');?>" target="_blank" class="btn btn-primary pull-right" style="margin: 15px 15px 0 0;font-weight: bold;">PRINT</a>
                <a class="btn btn-success pull-right" style="margin: 15px 15px 0 0;font-weight: bold;" href="<?php echo site_url('schedule');?>">Weekly Schedule</a>
            </div>
            <div class="card-body" id="graph_div" style="padding: 15px;">
                <?php if($this->session->flashdata('message')){?>
                <div role="alert" class="alert fresh-color alert-success">
                      <strong><?php echo $this->session->flashdata('message');?></strong>
                </div>
                <?php } ?>
                
                    <table class="table-bordered table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="width: 16%;">Employee [<?php echo count($emp_shifts);?>]</th>
                                <th>Shifts</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach($emp_shifts as $key=>$emp){?>
                            <tr>
                                <td><?php echo $emp['name'];?> 
                                    <br><?php echo isset($emp['s1'])?'['.gmdate('H:i',$emp['s1']).']':'[0]';?>&nbsp;<?php echo isset($emp['s2'])?'['.gmdate('H:i',$emp['s2']).']':'[0]';?>&nbsp;<?php echo isset($emp['s3'])?'['.gmdate('H:i',$emp['s3']).']':'[0]';?></td>

                                <?php
                                $shift = $emp['shifts'];
                                $colors = $emp['color'];
                                ?>
                                
                                        <td>
                                            <?php 
                                                $start = strtotime('06:00:00');
                                                $fss = strtotime($shift[1]['start']);
                                                $fmlv = ceil($fss - $start)/60;
                                                $fml = ($fmlv/840)*100;
                                                $fwv = (strtotime($shift[1]['end']) - strtotime($shift[1]['start']))/60;
                                                $fw = ($fwv/840)*100;
                                                
                                                if(isset($shift[2])){
                                                    $fse = strtotime($shift[1]['end']);
                                                    $sss = strtotime($shift[2]['start']);
                                                    $smlv = ceil($sss - $fse)/60;
                                                    $sml = ($smlv/840)*100;
                                                    $swv = (strtotime($shift[2]['end']) - strtotime($shift[2]['start']))/60;
                                                    $sw = ($swv/840)*100;
                                                }
                                                if(isset($shift[3])){
                                                    $sse = strtotime($shift[2]['end']);
                                                    $tss = strtotime($shift[3]['start']);
                                                    $tmlv = ceil($tss - $sse)/60;
                                                    $tml = ($tmlv/840)*100;
                                                    $twv = (strtotime($shift[3]['end']) - strtotime($shift[3]['start']))/60;
                                                    $tw = ($twv/840)*100;
                                                }
                                            ?>
                                            <?php for($i=0;$i<14;$i++){?> 
                                            <div style="width: <?php echo (100/14);?>%;height:20px;border: 1px solid #ccc;float: left;padding-left:2px;text-align: left;padding-bottom: 2px;line-height: 20px;font-size:14px;">
                                                <?php echo substr(date('ga',strtotime("+$i hours", $start)),0,-1);?>
                                            </div>
                                            <?php } ?> 
                                            <div style="height: 20px;width: 100%;border: 1px solid #ccc;overflow: auto;border-top: none;">
                                                <div style="float: left;height: 100%;background-color: <?php echo $colors[0];?>;width: <?php echo $fw;?>%;margin-left:<?php echo $fml;?>%;text-align: center;color: white;font-size: 14px;">
                                                    <?php echo dateFormat($shift[1]['start'])." - ".dateFormat($shift[1]['end']);?>
                                                </div>
                                                
                                                <?php if(isset($shift[2])){?> 
                                                <div style="float: left;height: 100%;background-color: <?php echo $colors[1];?>;width: <?php echo $sw;?>%;margin-left:<?php echo $sml;?>%;text-align: center;color: white;font-size: 14px;">
                                                    <?php echo dateFormat($shift[2]['start'])." - ".dateFormat($shift[2]['end']);?>
                                                </div>
                                                <?php } ?>
                                                <?php if(isset($shift[3])){?> 
                                                <div style="float: left;height: 100%;background-color: <?php echo $colors[2];?>;width: <?php echo $tw;?>%;margin-left:<?php echo $tml;?>%;text-align: center;color: white;font-size: 14px;">
                                                    <?php echo dateFormat($shift[3]['start'])." - ".dateFormat($shift[3]['end']);?>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </td>
                            </tr>
                            <?php } ?>
                            
                        </tbody>
                    </table>
        </div>
</div>
            
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('.rem_shift').on('click',function(e){
            e.preventDefault();
            _id = $(this).data('id');
            if(confirm('Are you sure?'))
            {
                
                $.post(BASE_URL+'schedule/remShift',{id:_id},function(data){
                    document.location.reload(true);
                });
            }
        });
        
    });
</script>
<?php $this->load->view('template/footer');?>
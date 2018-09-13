<?php $this->load->view('template/header');?>
<div class="row">

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="">
                <img src="/phpThumb/phpThumb.php?src=/assets/upload/patients/<?php echo $patient->photo;?>&amp;w=64&amp;h=54&amp;zc=1&amp;f=png" class="thumbnail"  style="margin-bottom: 0px;max-width: 100%;float: left;"/>
                
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="">
                <h3 style="margin-top: 0px;"><?php echo $patient->lname." ".$patient->fname;?></h3>
                <?php $age = ($patient->dob)? date_diff(date_create($patient->dob), date_create('now'))->y:0; ?>
                <h4 style="width: 20%;float: left;margin-top: 0px;"><span class="label label-success"  >Weekly</span> </h4><?php if($age > 1){?><h4 style="float: left;width: 80%;margin-top: 0px;"> <?php echo $age;?> Years</h4><?php } ?>
                
                <?php $height = $patient->height;
                $feet = floor($height/12);$inches = ($height%12);
                ?>
                <br>
                <h4  style="width: 20%;float: left;"><?php echo $feet."'";?> <?php if($inches > 0) echo $inches.'"';?></h4>  <h4 style="float: left;width: 80%;"><span class="badge" style="font-size: 15px;"><?php echo $patient->gender==1? 'M':'F';?></span></h4>
               
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="">
                <p style="overflow: auto;"><label class="col-lg-2 col-sm-4 col-xs-12" style="margin-bottom: 0px;padding-left: 0px;">Initial Weight</label> <?php echo isset($first_visit)?$first_visit->weight:'-';?></p>
                <p style="overflow: auto;"><label class="col-lg-2 col-sm-4 col-xs-12" style="margin-bottom: 0px;padding-left: 0px;">Goal Weight</label> <?php echo $patient->goal_weight;?></p>
            </div>

            <!--<a href="<?php echo site_url("patient/edit/$patient->id");?>" class="btn btn-default " title="edit"><span class="glyphicon glyphicon-edit"></a>-->
        </div>
    </div>

</div>

<div class="row">
    
	<div class="card">
	    <div class="card-header">

		<div class="card-title">
		    <div class="title">Visit History</div>
		</div>
	    </div>
            <div class="card-body" style="padding: 15px;">
                <?php if($this->session->flashdata('message')){?>
                <div role="alert" class="alert fresh-color alert-success">
                      <strong><?php echo $this->session->flashdata('message');?></strong>
                </div>
                <?php } ?>
		<table id="dr_patient_visits" class=" table table-bordered">
                    <thead>
                        <tr>
                            <th>Visit #</th>
                            <th>Visit Date</th>
                            <th>Weight</th>
                            <th>BMI</th>
                            <th>BFI</th>
                            <th>BP</th>
                            <th>Meds</th>
                            <th>Last 2 Visit -/+</th>
                            <th>Total Wt -/+</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $last_weight = $i = 0;
                        $total_weight_diff = 0;
                        foreach($visits as $visit){?>
                        <tr class="<?php if($visit->alerts){?>success<?php } ?>">
                            <th scope="row"><?php echo $visit->visit;?></th>
                            <td><?php echo date('m/d/Y',  strtotime($visit->visit_date));?></td>
                            <td><?php echo $visit->weight;?></td>
                            <td><?php echo $visit->bmi;?></td>
                            <td><?php echo $visit->bfi;?></td>
                            <td><?php echo $visit->bp;?></td>
                            <td><?php 
                            if($visit->is_med == 1){
                                echo ($visit->med3)? "D: ".$visit->med3n : "P: ".$visit->med1n." / ".$visit->med2n;
                            }
                            else
                            {
                                echo 'NO MEDS';
                            }
                            ?></td>
                            <?php $diff = floatval($last_weight - $visit->weight); ?>
                            <td><?php echo $i==0? 0: $diff?></td>
                            <td><?php echo $i==0? 0: $total_weight_diff = $total_weight_diff + $diff ;?></td>
                            <td><a title="Visit Info" href="<?php echo site_url("doctor/note/$visit->id");?>"  style="color: #31b0d5;"><span aria-hidden="true" style="font-size: 1.2em;" class="glyphicon glyphicon-edit"></span></a>
                            <?php if($visit->alerts){?>&nbsp;&nbsp;&nbsp;<a title="Print DR Note" target="_blank" href="<?php echo site_url("doctor/note_pdf/$visit->id");?>"  style="color: #31b0d5;"><span aria-hidden="true" style="font-size: 1.2em;" class="glyphicon glyphicon-print"></span></a><?php } ?>
                            </td>
                            
                        </tr>
                        <?php $last_weight = $visit->weight; $i++;?>
                        <?php } ?>
                    </tbody>
                </table>
	    </div>
	</div>
    
</div>
<?php $this->load->view('template/footer');?>
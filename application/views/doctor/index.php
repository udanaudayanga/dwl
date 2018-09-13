<?php $this->load->view('template/header');?>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript" src="/assets/js/jquery.highchartTable-min.js"></script>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">

                <div class="card-title col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="title">Search Patients</div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <form method="post">
                        <div class="input-group" style="padding: 1.2em 15px;">
                            <input type="text" name="query" value="<?php echo set_value('query');?>" class="form-control input-lg" placeholder="Search by name">
                            <span class="input-group-btn">
                                <button style="margin: 0px;" class="btn btn-success btn-lg" type="submit">Search</button>
                            </span>
                        </div><!-- /input-group -->
                    </form>
                </div>
            </div>
            <div class="card-body" style="padding: 15px;">
                <?php if($this->session->flashdata()){?>
                <div role="alert" class="alert fresh-color alert-success">
                      <strong><?php echo $this->session->flashdata('message');?></strong>
                </div>
                <?php } ?>
                <table class="datatable table table-striped" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Last Visit Date</th>
                            <th>Location</th>
                            <th style="text-align: center;">Graph</th>
                            <th style="text-align: center;">ECG</th>
                            <th style="text-align: center;">Lab</th>
                            <th style="text-align: center;">Stats</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($patients as $patient){?>
                        <tr class="black">
                            <td><?php echo $patient->lname.' '.$patient->fname;?></td>
                            <td><?php echo isset($patient->last_visit->visit_date)?(date('m/d/Y',strtotime($patient->last_visit->visit_date))):'';?></td>
                            <td><?php echo isset($patient->last_visit->location)?($patient->last_visit->location):'';?></td>
                            <td style="text-align: center;"><a target="_blank"  href="<?php echo site_url("evaluate/view/$patient->id");?>"><span class="glyphicon glyphicon-signal" aria-hidden="true"></span></a>
                            
                            </td>
                            <td style="text-align: center;"><a  data-toggle="modal" data-target="#ecg_history_<?php echo $patient->id;?>"  href=""><span class="glyphicon glyphicon-picture" aria-hidden="true"></span></a>
                            <?php $this->load->view('doctor/_ecg_history',array('patient_id'=>$patient->id,'ecgs'=>$patient->ecgs,'patient'=>$patient));?>
                            </td>
                            <td style="text-align: center;"><a  data-toggle="modal" data-target="#bw_history_<?php echo $patient->id;?>" href=""><span class="glyphicon glyphicon-compressed" aria-hidden="true"></span></a>
                            <?php $this->load->view('doctor/_bw_history',array('patient_id'=>$patient->id,'bws'=>$patient->bws,'patient'=>$patient));?>
                            </td>
                            <td style="text-align: center;"><a href="<?php echo site_url("doctor/patient/$patient->id");?>" style="color: #31b0d5;">History</a></td>
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
//  $('table.highchart').highchartTable();
  
  $('.pchart').on('click',function(){
      _id  = $(this).data('patient');
      
      _modal = $('#chart_'+_id);
      _modal.find('table.highchart').highchartTable();
      _modal.modal('show');
  });
});
</script>

<?php $this->load->view('template/footer');?>

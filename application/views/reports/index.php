<?php $this->load->view('template/header');?>
<!--<link rel="stylesheet" type="text/css" href="/assets/css/chartist.css">
<script type="text/javascript" src="/assets/js/chartist.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>-->
<link rel="stylesheet" type="text/css" href="https://code.highcharts.com/css/highcharts.css">
<script src="https://code.highcharts.com/highcharts.js"></script>
<style>
    ul.ui-autocomplete{z-index: 9999;overflow: auto;}
</style>
<div class="row">    
        <div class="card">           
            <div class="card-body" style="padding: 15px 0px;">
                <div class="col-xs-12">
                    <?php if($user->type == '1'){?>
                    <a href="" style="margin: 3px;padding: 5px 10px;font-size: 16px;" data-div="pl_div" class="btn btn-primary btn-lg type_btn">No Patients VS Locations</a>
                    <?php } ?>
                    <a href="" style="margin: 3px;padding: 5px 10px;font-size: 16px;" data-div="ml_div" class="btn btn-primary btn-lg type_btn">Meds VS Locations</a>
                    <a href="" style="margin: 3px;padding: 5px 10px;font-size: 16px;" data-div="il_div" class="btn btn-primary btn-lg type_btn">Injections VS Locations</a>
                    <?php if($user->type == '1'){?>
                    <a href="" style="margin: 3px;padding: 5px 10px;font-size: 16px;" data-div="pd_div" class="btn btn-primary btn-lg type_btn">Past Due Patients</a>
                    <a href="" style="margin: 3px;padding: 5px 10px;font-size: 16px;" data-div="sa_div" class="btn btn-primary btn-lg type_btn">Sales/Activity Report</a>
                    <?php } ?>
                </div>
            </div> 
        </div> 
</div>
<div id="errors_div" class="row"></div>
<div class="row" id="ctrl_div" style="margin-top: 10px;z-index: 0;">    
        <div class="card  selection_div pl_div">   
            <div class="card-header">
                <div class="card-title" style="padding: 10px;">
                <div class="title">Number of Patients VS Locations</div>
                </div>
            </div>
            <div class="card-body" style="padding: 15px 0px;">
               
                <form method="post" id="pl_form">
                <div class="form-inline col-xs-12">
                    <div class="form-group col-lg-4 col-md-6 col-sm-10 col-xs-12" style="margin-bottom: 15px;">                         
                        <label style="font-size: 18px;" class="col-lg-3 col-md-4 col-sm-4 col-xs-4" for="exampleInputName3">Date Range: </label>
                        <div class="input-daterange input-group col-lg-9 col-md-8 col-sm-8 col-xs-6" id="datepicker">
                            <input type="text" class="input-sm form-control"  name="start" value="<?php echo set_value('start');?>"/>
                            <span class="input-group-addon">to</span>
                            <input type="text" class="input-sm form-control"  name="end" value="<?php echo set_value('end');?>"/>
                        </div>                         
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12" style="margin-bottom: 15px;">
                        <a href="" id="gen_pl_btn" class="btn btn-success" style="padding: 5px 12px;margin-top: 0px;">Generate</a>
                    </div>
                </div> 
                </form>
            </div> 
        </div>
        <div class="card  selection_div ml_div">   
            <div class="card-header">
                <div class="card-title" style="padding: 10px;">
                <div class="title">Meds VS Locations</div>
                </div>
            </div>
            <div class="card-body" style="padding: 15px 0px;">
               
                <form method="post" id="ml_form">
                <div class="form-inline col-xs-12">
                    <div class="form-group col-lg-4 col-md-6 col-sm-10 col-xs-12" style="margin-bottom: 15px;">                         
                        <label style="font-size: 18px;" class="col-lg-3 col-md-4 col-sm-4 col-xs-4" for="exampleInputName3">Date Range: </label>
                        <div class="input-daterange input-group col-lg-9 col-md-8 col-sm-6 col-xs-6" id="datepicker">
                            <input type="text" class="input-sm form-control"  name="start" value="<?php echo set_value('start');?>"/>
                            <span class="input-group-addon">to</span>
                            <input type="text" class="input-sm form-control"  name="end" value="<?php echo set_value('end');?>"/>
                        </div>                         
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12" style="margin-bottom: 15px;">
                        <a href="" id="gen_ml_btn" class="btn btn-success" style="padding: 5px 12px;margin-top: 0px;">Generate</a>
                    </div>
                </div> 
                </form>
            </div> 
        </div>
        <div class="card  selection_div il_div">   
            <div class="card-header">
                <div class="card-title" style="padding: 10px;">
                <div class="title">Injections VS Locations</div>
                </div>
            </div>
            <div class="card-body" style="padding: 15px 0px;">
               
                <form method="post" id="il_form">
                <div class="form-inline col-xs-12">
                    <div class="form-group col-lg-4 col-md-6 col-sm-10 col-xs-12" style="margin-bottom: 15px;">                         
                        <label style="font-size: 18px;" class="col-lg-3 col-md-4 col-sm-4 col-xs-4" for="exampleInputName3">Date Range: </label>
                        <div class="input-daterange input-group col-lg-9 col-md-8 col-sm-6 col-xs-6" id="datepicker">
                            <input type="text" class="input-sm form-control"  name="start" value="<?php echo set_value('start');?>"/>
                            <span class="input-group-addon">to</span>
                            <input type="text" class="input-sm form-control"  name="end" value="<?php echo set_value('end');?>"/>
                        </div>                         
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12" style="margin-bottom: 15px;">
                        <a href="" id="gen_il_btn" class="btn btn-success" style="padding: 5px 12px;margin-top: 0px;">Generate</a>
                    </div>
                </div> 
                </form>
            </div> 
        </div>
    
        <div class="card  selection_div pd_div">   
            <div class="card-header">
                <div class="card-title" style="padding: 10px;">
                <div class="title">Past Due Patients</div>
                </div>
            </div>
            <div class="card-body" style="padding: 15px 0px;">
               
                <form method="post" id="pd_form">
                <div class="form-inline col-xs-12" style="padding: 0px;">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group col-sm-3 col-xs-3" style="margin-bottom: 10px;padding-right: 0px;">                         
                            <label style="padding: 5px 0px 0px 0px;" class="" for="pdn"># of Days:</label>                                          
                        </div>
                        <div class="form-group col-sm-6 col-xs-6" style="margin-bottom: 10px;">                         
                            
                            <input type="number" style="" id="pd_nfd" name="no_of_days" class="form-control col-xs-9"/>                       
                        </div>
                        
                        <div class="form-group col-sm-3 col-xs-3" style="margin-bottom: 10px;padding-top: 0px;">
                            <a href="" id="gen_pd_btn"  class="btn btn-success" style="padding: 5px 12px;margin-top: 0px;width: 72px;">Generate</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group col-xs-4" style="margin-bottom: 10px;">
                            <a href="" id="pd_print_btn" class="btn btn-primary" style="padding: 5px 12px;margin-top: 0px;width: 72px;">Print</a>
                        </div>
                        <div class="form-group col-xs-4" style="margin-bottom: 10px;">
                            <a href="" id="" class="btn btn-info" style="padding: 5px 12px;margin-top: 0px;width: 72px;">For SMS</a>
                        </div>
                        <div class="form-group col-xs-4" style="margin-bottom: 10px;">
                            <a href="" id="pd_for_email" class="btn btn-info" style="padding: 5px 12px;margin-top: 0px;width: 72px;">Custom List</a>
                        </div>
                    </div>
                    
                </div> 
                </form>
            </div> 
        </div>
    
    <div class="card  selection_div sa_div" style="z-index: 0px;">   
            <div class="card-header">
                <div class="card-title" style="padding: 10px;">
                <div class="title">Generate Sales Activity report</div>
                </div>
            </div>
            <div class="card-body" style="padding: 15px 0px;">
               
                <form method="post" id="sa_form">
                    <div class="form-inline col-xs-12" style="padding: 0px;">
                    <div class="form-group col-lg-3 col-md-5 col-sm-5 col-xs-12" style="margin-bottom: 10px;padding-right: 0px;">                         
                            <label style="" class="col-lg-4 col-md-4 col-sm-5 col-xs-6" for="pdn">Patient Name:</label>   
                            <input type="text" class="form-control col-lg-8 col-md-8 col-sm-7 col-xs-6" style="" value="" id="sa_patient_name" placeholder="Patient Name">
                            <input type="hidden" name="patient_id" id="sa_patient_id"/>
                    </div>
                    <div class="form-group col-lg-4 col-md-5 col-sm-6 col-xs-12" style="margin-bottom: 15px;padding-right: 0px;">                         
                        <label style="font-size: 18px;" class="col-lg-3 col-md-4 col-sm-4 col-xs-4" for="exampleInputName3">Date Range: </label>
                        <div class="input-daterange input-group col-lg-9 col-md-8 col-sm-8 col-xs-6" id="datepicker">
                            <input type="text" class="input-sm form-control" id="sa_start"  name="start" />
                            <span class="input-group-addon">to</span>
                            <input type="text" class="input-sm form-control" id="sa_end" name="end" />
                        </div>                         
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-1 col-xs-12" style="margin-bottom: 15px;padding-right: 0px;">
                        <a href="" id="gen_sa_btn" class="btn btn-success" style="padding: 5px 12px;margin-top: 0px;">Generate</a>
                    </div>
                </div> 
                </form>
            </div> 
        </div>
</div>
<div class="col-xs-12" style="width: 100%;height: 500px;padding: 0px;margin-top: 10px;" id="graph_div"></div>
      
<div class="col-xs-12" id="table_div" style="padding: 15px;background-color: #FFFFFF;margin-top: 15px;"></div>

<div class="modal fade modal-info" id="for_email_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 550px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Create custom list</h4>
            </div>
            <div class="modal-body" style="overflow: auto;">
                <div class="loader-container text-center color-white">
                    <div><i class="fa fa-spinner fa-pulse fa-4x"></i></div>
                    <div>Creating custom list</div>
                </div>
                <div id="as_errors"></div>
                <form id="add_phase_frm" >
                    
                    <div class="col-xs-12" style="padding: 0px;">
                        <div class="form-group col-xs-12">
                            <label for="qty">List Name</label>
                            <input type="text" id="mc_list_name" class="form-control" name="name" >
                        </div>
                        
                        <div class="col-xs-12" style="">
                            <a href="" class="btn btn-success pull-right" id="add_mc_list">Create Custom List</a>
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
        $('.input-daterange').datepicker({
            maxViewMode: 0,
            autoclose: true,
            format: "yyyy-mm-dd"
        });
        
        $('.selection_div').hide();
        $('#table_div').hide();
        
        var cache = {};
        $("#sa_patient_name" ).autocomplete({
          minLength: 2,
          appendTo:'#ctrl_div',
          source: function( request, response ) {
            var term = request.term;
            if ( term in cache ) {
              response( cache[ term ] );
              return;
            }

            $.getJSON( BASE_URL+'patient/getReferalPatient', request, function( data, status, xhr ) {
              cache[ term ] = data;
              response( data );
            });
          },
          select: function( event, ui ) {
              $('#sa_patient_id').val(ui.item.id);
          }
        });
        
        $('.type_btn').on('click',function(e){
            e.preventDefault();
            
            $('.type_btn').removeClass('btn-success').addClass('btn-primary');
            $(this).addClass('btn-success');
            
            
            $('.selection_div').hide();
            _div = $(this).data('div');
            $('.'+_div).show();
            $('#graph_div').html('');     
            $('#errors_div').html('');
            $('.selection_div form').trigger('reset');
        });
        
        $('#pd_print_btn').on('click',function(e){
            e.preventDefault();
            _nfd = $('.pd_div').find('#pd_nfd').val();
            
            if(_nfd > 0)
            {
                var win = window.open(BASE_URL+'reports/pastDuePDF/'+_nfd, '_blank');
                if(win){
                    win.focus();
                }
            }
            else 
            {
                bootbox.alert("No of days value required.");
            }
        });
        
        $('#gen_pl_btn').on('click',function(e){
            e.preventDefault();   
            $('#errors_div').html('');
            $('#table_div').html('').hide();
            $('#graph_div').html('').show();
            $.post(BASE_URL+'reports/getPatientLocation',$('#pl_form').serialize(),function(data){
                data = JSON.parse(data);                
                if(data.status == 'error')
                {
                    $('#errors_div').html(data.errors);
                }
                else
                {
                    Highcharts.chart('graph_div', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Number of Patients vs Locations'
                        },
                        subtitle: {
                            text: data.subtitle
                        },
                        xAxis: {
                            categories: data.locs,
                            crosshair: true
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Number of Patients'
                            }
                        },
                        tooltip: {
                            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                '<td style="padding:0"><b>{point.y} </b></td></tr>',
                            footerFormat: '</table>',
                            shared: true,
                            useHTML: true,
                            followPointer:true,
                             followTouchMove:true
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0.2,
                                borderWidth: 0
                            }
                        },
                        series: data.graph
                    });
                }
            });
        });
        
        $('#gen_ml_btn').on('click',function(e){
            e.preventDefault();         
            $('#errors_div').html('');
            $('#table_div').html('').hide();
            $('#graph_div').html('').show();
            $.post(BASE_URL+'reports/getMedsLocation',$('#ml_form').serialize(),function(data){
                data = JSON.parse(data);                
                if(data.status == 'error')
                {
                    $('#errors_div').html(data.errors);
                }
                else
                {
                    Highcharts.chart('graph_div', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Number of Meds vs Locations'
                        },
                        subtitle: {
                            text: data.subtitle
                        },
                        xAxis: {
                            categories: data.locs,
                            crosshair: true
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Med Count'
                            }
                        },
                        tooltip: {
                            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                '<td style="padding:0"><b>{point.y} </b></td></tr>',
                            footerFormat: '</table>',
                            shared: true,
                            useHTML: true,
                            followPointer:true,
                             followTouchMove:true
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0.2,
                                borderWidth: 0
                            }
                        },
                        series: data.graph
                    });
                }
            });
        });
        
        $('#gen_il_btn').on('click',function(e){
            e.preventDefault();  
            $('#errors_div').html('');
            $('#table_div').html('').hide();
            $('#graph_div').html('').show();
            $.post(BASE_URL+'reports/getInjLocation',$('#il_form').serialize(),function(data){
                data = JSON.parse(data);                
                if(data.status == 'error')
                {
                    $('#errors_div').html(data.errors);
                }
                else
                {
                    Highcharts.chart('graph_div', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Number of Injection vs Locations'
                        },
                        subtitle: {
                            text: data.subtitle
                        },
                        xAxis: {
                            categories: data.locs,
                            crosshair: true
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Inj Count'
                            }
                        },
                        tooltip: {
                            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                '<td style="padding:0"><b>{point.y} </b></td></tr>',
                            footerFormat: '</table>',
                            shared: true,
                            useHTML: true,
                            followPointer:true,
                             followTouchMove:true
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0.2,
                                borderWidth: 0
                            }
                        },
                        series: data.graph
                    });
                }
            });
        });
        
        $('#gen_pd_btn').on('click',function(e){
            e.preventDefault();    
            $('#errors_div').html('');
            $('#table_div').html('').hide();
            $.post(BASE_URL+'reports/getPastDue',$('#pd_form').serialize(),function(data){
                data = JSON.parse(data);                
                if(data.status == 'error')
                {
                    $('#errors_div').html(data.errors);
                }
                else
                {
                    $('#table_div').html(data.html);
                    $('#table_div').find('.datatable').dataTable();
                    $('#table_div').show();
                    $('#graph_div').hide();
                }
            });
        });
        
        $('#pd_for_email').on('click',function(e){
            e.preventDefault();
            
            _target = $(this);
            _modal = $('#for_email_modal');
            
            _from = $('.pd_div').find('#pd_nfd').val();
            
            
            if(_from > 0)
            {
                _modal.find('#add_mc_list').unbind('click').bind('click', function(e) {
                    e.preventDefault();
                    _modal.find('.modal-body').addClass('loader');
                    _modal.find('#as_errors').html('');
                    _name  = _modal.find('#mc_list_name').val();
                    _from = $('.pd_div').find('#pd_nfd').val();
                    
                    $.post(BASE_URL+'reports/createCustomList',{name:_name,from:_from},function(data){
                        data = JSON.parse(data);
                        if(data.status == 'error')
                        {
                            _modal.find('#as_errors').html(data.errors);
                        }
                        else if(data.status == 'success')
                        {
                            _modal.find('#as_errors').html(data.msg);
                            
                            setTimeout(function(){_modal.modal('hide');},2000);
                        }
                        _modal.find('.modal-body').removeClass('loader');
                    });
                });         
                
                _modal.on('hidden', function() {                
                    _modal.find('#as_errors').html('');
                    _modal.find('#add_mc_list').unbind('click');
                });
                
                 _modal.modal();
            }
            else 
            {                
                bootbox.alert("From and To values are required.");
            } 
        });
        
        $('#gen_sa_btn').on('click',function(e){
            e.preventDefault();
            _from = $('.sa_div').find('#sa_start').val();
            _to = $('.sa_div').find('#sa_end').val();
            
            _pid = $('.sa_div').find('#sa_patient_id').val();
            

            if(!_pid)
            {
                bootbox.alert('Please select Patient from list.');
            }
            else if(!_from || !_to)
            {
                bootbox.alert('Please select date range');
            }
            else
            {
                var win = window.open(BASE_URL+'reports/salesActivity/'+_pid+'/'+_from+'/'+_to, '_blank');
                if(win){
                    win.focus();
                }
            }

        });
    });

</script>
<?php $this->load->view('template/footer');?>

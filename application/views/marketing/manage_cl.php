<?php $this->load->view('template/header');?>
<?php if($this->session->flashdata('message')){?>
    <div role="alert" class="alert fresh-color alert-success">
          <strong><?php echo $this->session->flashdata('message');?></strong>
    </div>
<?php } ?>
<div class="row">    
    <div class="card">   
        
        <div class="card-body" style="padding: 15px 0px;">
            <div class="col-xs-12">
                    <?php echo validation_errors('<div class="col-xs-12" style="padding:0 5px;"><div role="alert" style="padding:5px 10px;" class="alert fresh-color alert-danger">','</div></div>'); ?>
            </div>
            <form method="post" class="form-horizontal col-xs-12">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label for="inputEmail3" class="col-sm-2 control-label">Custom List Name:</label>
                  <div class="col-sm-8">
                      <input value="<?php if($id) echo $list->name;?>" type="text" name="name" class="form-control" id="inputEmail3" placeholder="Name">
                  </div>
                    <div class="col-sm-2"><button style="margin: 0px;" type="submit" class="btn btn-success">SUBMIT</button></div>
                </div>

            </form>
        </div> 
    </div> 
</div>
<?php if($id){?>
<div class="row" style="margin-top: 10px;">    
    <div class="card">           
        <div class="card-header">
            <div class="card-title" style="padding: 10px 15px;">
            <div class="title">List Members</div>
            </div>
            <a href="" id="add_cl_mem_btn" style="margin: 10px 15px;" class="btn btn-success pull-right">Add Member</a>
        </div>
        <div class="card-body" id="cl_mem_list_div" style="padding: 15px;">            
            <?php $this->load->view('marketing/_cl_manage_table');?>
        </div> 
    </div> 
</div>
<div class="modal fade modal-info" id="add_cl_member" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 400px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Add List Member</h4>
            </div>
            <div class="modal-body" style="overflow: auto;">
                <div class="loader-container text-center color-white">
                    <div><i class="fa fa-spinner fa-pulse fa-4x"></i></div>
                    <div>Loading...</div>
                </div>
                <div id="as_errors"></div>
                <form id="add_list_mem_frm" >
                    <input type="hidden" name="list_id" value="<?php echo $id;?>"/>
                    <div class="col-xs-12">
                        <div class="form-group col-xs-12" style="padding: 0px;">
                            <label for="patient_name">Patient Name</label>
                            <input type="text" class="form-control" value="" id="patient_name" placeholder="Patient Name">
                            <input type="hidden" name="patient_id" id="patient_id"/>
                        </div>
                        
                    </div>
                    <div class="col-xs-12">           
                            <a href="" class="btn btn-success pull-right" id="add_cl_mem_btn_modal">Add Member</a>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-info" id="for_email_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 550px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Create List in Mailchimp</h4>
            </div>
            <div class="modal-body" style="overflow: auto;">
                <div class="loader-container text-center color-white">
                    <div><i class="fa fa-spinner fa-pulse fa-4x"></i></div>
                    <div>Posting to Mailchimp...</div>
                </div>
                <div id="as_errors"></div>
                <form id="add_phase_frm" >
                    
                    <div class="col-xs-12" style="padding: 0px;">
                        <div class="form-group col-xs-12">
                            <label for="qty">List Name</label>
                            <input type="text" id="mc_list_name" class="form-control" name="name" >
                        </div>
                        
                        <div class="col-xs-12" style="">
                            <a href="" class="btn btn-success pull-right" id="add_mc_list">Create Mailchimp List</a>
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
<?php } ?>
<script type="text/javascript"> 
    $(function(){
        
        var cache = {};
        $("#patient_name" ).autocomplete({
          minLength: 2,
          appendTo:'#add_cl_member',
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
              $('#patient_id').val(ui.item.id);              
          }
        });
        
        $('#cl_mem_list_div').on('click','.remove_del',function(e){
            e.preventDefault();
            _id = $(this).data('id');
            _list = $(this).data('list');
            if(confirm('Are you sure?'))
            {
                $.post(BASE_URL+'marketing/removeCLMem',{id:_id,list:_list},function(data){
                    data = JSON.parse(data);
                    $('#cl_mem_list_div').html(data.html);
                    $('#cl_mem_list_div').find('.datatable').dataTable();
                });
            }
        });
        
        $('#add_cl_mem_btn').on('click',function(e){
            e.preventDefault();
            
            _modal = $('#add_cl_member');
            
            _modal.find('#add_cl_mem_btn_modal').unbind('click').bind('click', function(e) {
                e.preventDefault();
                
                _modal.find('#as_errors').html('');
                _modal.find('.modal-body').addClass('loader');
                $.post(BASE_URL+'marketing/addListMember',$('#add_list_mem_frm').serialize(),function(data){
                    data = JSON.parse(data);
                    _modal.find('.modal-body').removeClass('loader');
                    if(data.status == 'error')
                    {
                        _modal.find('#ea_errors').html(data.errors);
                    }
                    else if(data.status == 'success')
                    {
                        $('#cl_mem_list_div').html(data.html);
                         $('#cl_mem_list_div').find('.datatable').dataTable();
                         setSuccAlert("Member added successfully!");
                        _modal.find('#add_list_mem_frm').trigger("reset");
                        _modal.find('#aa_errors').html('');
                        _modal.modal('hide');

                    }
                });
            })
            
            _modal.modal();
        });
        
        
        $('#cl_mem_list_div').on('click','#send_email',function(e){
            e.preventDefault();
            
            _target = $(this);
            _modal = $('#for_email_modal');
            _id = _target.data('id');
            
            _modal.find('#add_mc_list').unbind('click').bind('click', function(e) {
                e.preventDefault();
                _modal.find('.modal-body').addClass('loader');
                _modal.find('#as_errors').html('');
                _name  = _modal.find('#mc_list_name').val();
                

                $.post(BASE_URL+'marketing/createMCList',{id:_id,name:_name},function(data){
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
            
        });
        
        function setSuccAlert(msg)
        {
            $("#appoint_success").show();
            $("#appoint_success").html("<strong>"+msg+"</strong>");
            setTimeout(function(){$("#appoint_success").hide();},3000);
        }
    });
</script>
<?php $this->load->view('template/footer');?>

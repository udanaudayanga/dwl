<?php $this->load->view('template/header'); ?>

<style>
    tr.done td{background-color: lightgreen;}
</style>
<div class="row">
    <div class="col-xs-12">
	<div class="card">
	    <div class="card-header">

		<div class="card-title">
		    <div class="title">Today Queue</div>
		</div>
	    </div>
	    <div class="card-body" style='padding: 15px;' id="queue_items_tbl">
		<?php if($this->session->flashdata()){?>
		<div role="alert" class="alert fresh-color alert-success">
                      <strong><?php echo $this->session->flashdata('message');?></strong>
		</div>
		<?php } ?>
		<table class="datatable table table-striped" cellspacing="0" width="100%">
		    <thead>
			<tr>
			    <th>Patient</th>
                <th>Type</th>
			    <th>Time</th>
			    <th>Actions</th>
			</tr>
		    </thead>
		    <tbody>
			<?php foreach($queue as $q){?>
                <tr class="<?php if($q->status==1) echo 'done';?>">
                    <td><?php echo $q->first." ".$q->last;?></td>
                    <td><?php echo $q->type==1? 'WeightLoss':'MedSpa'; ?></td>
                    <td><?php echo date("h:i a",strtotime($q->created));?></td>
                    <td>                                
                        <?php if($q->status == 0){?><a title="Mark as Done" href="" style="color: limegreen;margin-left: 20px;" onclick="return confirm('Are you sure?');"><span aria-hidden="true"  data-id="<?php echo $q->id;?>" data-done="<?php echo $q->status;?>" class="glyphicon glyphicon-ok mad"></span></a><?php } ?>
                    
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
        $('#queue_items_tbl').on('click','.ql',function(e){
            e.preventDefault();
            id = $(this).data('id');
            
            _modal = $('#que_items');
            
            $.get(BASE_URL+'queue/viewItems/'+id,function(data){
                _modal.find('.modal-body').html(data);
            });
            
             _modal.modal();
        });
        
        $('#queue_items_tbl').on('click','.del',function(e){
            e.preventDefault();
            _target = $(this);
            _id = _target.data('id');
            if(confirm('Are you sure'))
            {
                $.post(BASE_URL+'queue/removeQueue',{id:_id},function(data){
                    if(data == 'success')
                    {
                        _target.closest('tr').remove();
                    }
                });
            }
        });
        
        $('#queue_items_tbl').on('click','.mad',function(e){
            e.preventDefault();
            id = $(this).data('id');
            done = $(this).data('done');
            _target = $(this);
            
            if(done == 1) return;
            
            $.post(BASE_URL+'queue/markAsDone',{id:id,done:done},function(data){
                _target.closest('tr').addClass('done');
                _target.css('display','none');
            });
        });
    });
</script>
<?php $this->load->view('template/footer'); ?>
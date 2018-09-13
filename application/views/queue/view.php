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
			    <th>Status</th>
                            <th>Type</th>
			    <th>Time</th>
			    <th>Actions</th>
			</tr>
		    </thead>
		    <tbody>
			<?php foreach($queue as $q){?>
                        <tr class="<?php if($q->done==1) echo 'done';?>">
                            <td><?php echo $q->patient_id?($q->lname." ".$q->fname):($q->first." ".$q->last);?></td>
                            <td><?php echo $q->done==0?'Pending':'Done';?></td>
                            <td><?php echo $q->type==0?'New':($q->type==1?'Weekly':'Shot/Products Only');?></td>
                            <td><?php echo date("h:i a",strtotime($q->created));?></td>
                            <td>
                                <?php if($q->patient_id){?>
                                <a title="Add Visit" href="<?php echo site_url("patient/addVisit/$q->patient_id");?>" style="color: #31b0d5;"><span aria-hidden="true"  class="glyphicon glyphicon-link"></span></a>                                
                                <?php } ?>
                                <a title="Remove"   href="" style="color: red;margin-left: 20px;" data-id="<?php echo $q->id;?>" class="del" onclick=""><span aria-hidden="true" class="glyphicon glyphicon-trash"></span></a>
                                <?php if($q->done==0 && FALSE){?><a title="Mark as Done" href="" style="color: gray;margin-left: 20px;" onclick="return confirm('Are you sure?');"><span aria-hidden="true"  data-id="<?php echo $q->id;?>" data-done="<?php echo $q->done;?>" class="glyphicon glyphicon-ok mad"></span></a><?php } ?>
                            
                            </td>
			</tr>
			<?php } ?>
		    </tbody>
		</table>
	    </div>
	</div>
    </div>
</div>

<div class="modal fade modal-info" id="que_items" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h3 class="modal-title" id="myModalLabel">Items expect to buy</h3>
            </div>
            <div class="modal-body" style='overflow: auto;'>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-lg" style="background-color: #979797;"  data-dismiss="modal">CLOSE</button>
                
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
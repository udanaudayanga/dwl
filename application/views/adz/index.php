<?php $this->load->view('template/header');?>
<link rel="stylesheet" type="text/css" href="/assets/js/lightbox/css/lightbox.css">
<script type="text/javascript" src="/assets/js/lightbox/js/lightbox.js"></script>
<script>
    lightbox.option({
      'resizeDuration': 200,
      'wrapAround': true,
      'positionFromTop': 100
    })
</script>
<style>
	div.dataTables_wrapper div.dataTables_filter input{width: 400px;}
    .dataTables_length select{font-size: 16px;}
</style>
<div class="page-title text-right">
    <a href="<?php echo site_url('adz/add');?>" class="btn btn-success">Add Adz</a>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">

                <div class="card-title">
                <div class="title">Mail Advertisements</div>
                </div>
            </div>
            <div class="card-body" style="padding: 15px;">
                <?php if($this->session->flashdata('message')){?>
                <div role="alert" class="alert fresh-color alert-success">
                      <strong><?php echo $this->session->flashdata('message');?></strong>
                </div>
                <?php } ?>
                <table class="datatable table table-striped" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Text</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($adz as $ad){?>
                        <tr class="black">
                            <td><?php echo $ad->id;?></td>
                            <td><?php echo (!empty($ad->text))?word_limiter($ad->text, 4):'';?></td>
                            <td><?php if($ad->img){?><a href="/assets/upload/adz/<?php echo $ad->img;?>" data-lightbox="image-<?php echo $ad->id;?>" data-title=""><img src="/phpThumb/phpThumb.php?src=/assets/upload/adz/<?php echo $ad->img;?>&amp;h=50&amp;f=png" class="thumbnail"  style="margin-bottom: 0px;max-width: 100%;"/></a> <?php }?></td>
                            <td><?php echo $ad->status==1?'Active':'Inactive';?></td>
                            <td><a title="Edit" href="<?php echo site_url("adz/edit/$ad->id");?>" style="color: #31b0d5;"><span aria-hidden="true" class="glyphicon glyphicon-edit"></span></a>
                                <a title="Remove"   href="<?php echo site_url("adz/delete/$ad->id");?>" style="color: red;margin-left: 20px;" class="" onclick="return confirm('Are you sure?');"><span aria-hidden="true" class="glyphicon glyphicon-trash"></span></a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('template/footer');?>
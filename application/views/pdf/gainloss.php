<!DOCTYPE html>
<html>
<head>
    <title>Gain / Loss</title>
    <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/gainloss_pdf.css">
</head>
<body>
    <div style="width: 600px;height: 600px;border: #9a1313 10px solid;margin: 40px auto 0px">
	<p style="text-align: center;padding: 20px 0px 10px 0px;"><img width="240" src="<?php echo base_url();?>/assets/img/logo.png" ></p>
	<!--<p style="text-align: center;font-size: 50px;">VISIT <?php echo $vn;?></p>-->
	<p style="text-align: center;font-size: 30px;padding: 0px 0px 30px 0px;">Between <?php echo $d1;?> and <?php echo $d2;?></p>
	<table>
	    <tr>
		<td width="15%">&nbsp;</td>
		<?php if($loss){?>
		<td width="35%">
		    <img src="<?php echo assets('img'); ?>/scale.png" width="150"  alt='Logo' />
		</td>
		<td width="35%">
		    <img src="<?php echo assets('img'); ?>/happy.png" width="180"  alt='Logo' />
		</td>
		<?php }else{?>
		<td width="35%">
		    <img src="<?php echo assets('img'); ?>/scale.png" width="150"  alt='Logo' />
		</td>
		<td width="35%">
		    <img src="<?php echo assets('img'); ?>/sad.png" width="170"  alt='Logo' />
		</td>
		<?php } ?>
		<td width="15%">&nbsp;</td>
	    </tr>
	</table>
        <p style="text-align: center;font-size: 35px;padding: 10px 0px 0px 0px;">You <?php echo $loss?"lost":"gained";?> <?php echo $diff;?> pounds</p>
	<p style="text-align: center;font-size: 25px;">From <?php echo $d1w;?> to your current weight of <?php echo $d2w;?> pounds</p>
    </div>
</body>
</html>

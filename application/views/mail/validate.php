<!DOCTYPE html>
<html>
<head>
	<title>Email validate !</title>
</head>
<body>
    <p style="text-align: center;"><img src="/assets/img/logo.png" style="width: 250px;"></p>
    <?php if($result){?>
<h3 style="text-align: center;color:#181818;
		font-family:Helvetica, Arial, sans-serif;
		font-size:26px;">Thank you!</h3>
<p style="text-align: center;color:#181818;
		font-family:Helvetica, Arial, sans-serif;
		font-size:20px;">You have successfully validated the email.  </p>
    <?php }else{?>
<h3 style="text-align: center;color:#181818;
		font-family:Helvetica, Arial, sans-serif;
		font-size:26px;">Oops!</h3>
<p style="text-align: center;color:#181818;
		font-family:Helvetica, Arial, sans-serif;
		font-size:20px;">Something went wrong. Try Again.  </p>
    <?php } ?>
</body>
</html>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>[SUBJECT]</title>
	<style type="text/css">

	    #outlook a {padding:0;}
	    body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
	    .ExternalClass {width:100%;}
	    .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
	    #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
	    img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
	    a img {border:none;display:inline-block;}
	    .image_fix {display:block;}

	    h1, h2, h3, h4, h5, h6 {color: black !important;}

	    h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {color: blue !important;}

	    h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {
		color: red !important; 
	    }

	    h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
		color: purple !important; 
	    }

	    table td {border-collapse: collapse;}

	    table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }

	    a {color: #000;}

	    @media only screen and (max-device-width: 480px) {

		a[href^="tel"], a[href^="sms"] {
		    text-decoration: none;
		    color: black; /* or whatever your want */
		    pointer-events: none;
		    cursor: default;
		}

		.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
		    text-decoration: default;
		    color: orange !important; /* or whatever your want */
		    pointer-events: auto;
		    cursor: default;
		}
	    }


	    @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
		a[href^="tel"], a[href^="sms"] {
		    text-decoration: none;
		    color: blue; /* or whatever your want */
		    pointer-events: none;
		    cursor: default;
		}

		.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
		    text-decoration: default;
		    color: orange !important;
		    pointer-events: auto;
		    cursor: default;
		}
	    }

	    p {
		margin:0;
		color:#555;
		font-family:Helvetica, Arial, sans-serif;
		font-size:16px;
		line-height:160%;
	    }
	    a.link2{
		text-decoration:none;
		font-family:Helvetica, Arial, sans-serif;
		font-size:16px;
		color:#fff;
		border-radius:4px;
	    }
	    h2{
		color:#181818;
		font-family:Helvetica, Arial, sans-serif;
		font-size:22px;
		font-weight: normal;
	    }

	</style>

	<script type="colorScheme" class="swatch active">
	    {
	    "name":"Default",
	    "bgBody":"ffffff",
	    "link":"f2f2f2",
	    "color":"555555",
	    "bgItem":"F4A81C",
	    "title":"181818"
	    }
	</script>

    </head>
    <body>
	<!-- Wrapper/Container Table: Use a wrapper table to control the width and the background color consistently of your email. Use this approach instead of setting attributes on the body tag. -->
	<table cellpadding="0" width="100%" cellspacing="0" border="0" id="backgroundTable" class='bgBody'>
	    <tr>
		<td>

		    <!-- Tables are the most common way to format your email consistently. Set your table widths inside cells and in most cases reset cellpadding, cellspacing, and border to zero. Use nested tables as a way to space effectively in your message. -->

		    <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-collapse:collapse;">
			<tr>
			    <td class='movableContentContainer'>

				<div class='movableContent'>
				    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600">
					<tr height="40">
					    <td width="200">&nbsp;</td>
					    <td width="200">&nbsp;</td>
					    <td width="200">&nbsp;</td>
					</tr>
					<tr>
					    <td width="200" valign="top">&nbsp;</td>
					    <td width="200" valign="top" align="center">
						<div class="contentEditableContainer contentTextEditable">
						    <div class="contentEditable" >
							<img src="<?php echo assets('img'); ?>/logo.png" width="170"  alt='Logo'  data-default="placeholder" />
						    </div>
						</div>
					    </td>
					    <td width="200" valign="top">&nbsp;</td>
					</tr>

				    </table>
				</div>

				<div class='movableContent'>
				    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600">
					<tr>
					    <td width="100%" colspan="3" align="center" style="padding-bottom:10px;padding-top:25px;">
						<div class="contentEditableContainer contentTextEditable">
						    <div class="contentEditable" >
							<h2 >Wrong location IP</h2>
						    </div>
						</div>
					    </td>
					</tr>
					<tr>										
					    <td colspan="3" width="600" align="center" style="padding-bottom:5px;">
						<div class="contentEditableContainer contentTextEditable">
						    <div class="contentEditable" >
							<p style="text-align: left;padding-bottom: 20px;">Hi Admin,</p>
							<p style="text-align: justify;padding-bottom: 20px;">Employee (<?php echo $user->fname." ".$user->lname;?>) reported, IP address of <?php echo $location->name;?> has changed. Current assigned IP: <?php echo $location->ip;?>. Please assign current IP to location.</p>
						    </div>
						</div>
					    </td>										
					</tr>
				    </table>
				</div>
				<div class='movableContent'>
				    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600">	
					<tr>										
					    <td colspan="3" width="600" align="center" style="padding-bottom:5px;">
						<div class="contentEditableContainer contentTextEditable">
						    <div class="contentEditable" >
							
							<p style="text-align: left;">
							-Auto generated email
							</p>
						    </div>
						</div>
					    </td>										
					</tr>
				    </table>
				</div>

			    </td>
			</tr>
		    </table>
		    <!-- END BODY -->

		</td>
	    </tr>
	</table>
	<!-- End of wrapper table -->
    </body>
</html>

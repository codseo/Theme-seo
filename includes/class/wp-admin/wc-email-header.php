<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>{site_title}</title>
	<style type="text/css">

	@media screen and (max-width: 600px) {
		table[class="container"] {
			width: 95% !important;
		}
	}

	#outlook a {padding:0;}
		body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
		.ExternalClass {width:100%;}
		.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
		#backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
		img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
		a img {border:none;}
		.image_fix {display:block;}
		p {margin: 1em 0;}
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

		@media only screen and (-webkit-min-device-pixel-ratio: 2) {
			/* Put your iPhone 4g styles in here */
		}

		@media only screen and (-webkit-device-pixel-ratio:.75){
			/* Put CSS for low density (ldpi) Android layouts in here */
		}
		@media only screen and (-webkit-device-pixel-ratio:1){
			/* Put CSS for medium density (mdpi) Android layouts in here */
		}
		@media only screen and (-webkit-device-pixel-ratio:1.5){
			/* Put CSS for high density (hdpi) Android layouts in here */
		}
		/* end Android targeting */
		h2{
			color:#181818;
			font-family: IRANSans, tahoma, sans-serif;
			font-size:22px;
			line-height: 22px;
			font-weight: normal;
			text-align: center;
		}
		a.link1{

		}
		a.link2 {
			color: #fff;
			text-decoration: none;
			font-family: IRANSans, tahoma, sans-serif;
			font-size: 12px;
			color: #fff;
			border-radius: 4px;
			line-height: 16px;
			display: block;
			 padding: 8px 0;
		}
		p {
			color: #555;
			font-family: IRANSans, tahoma, sans-serif;
			font-size: 14px;
			line-height: 200%;
		}
	</style>

<script type="colorScheme" class="swatch active">
  {
    "name":"Default",
    "bgBody":"ffffff",
    "link":"fff",
    "color":"555555",
    "bgItem":"ffffff",
    "title":"181818"
  }
</script>

</head>
<body>
	<table cellpadding="0" width="100%" cellspacing="0" border="0" id="backgroundTable" class='bgBody' dir="rtl">
		<tr>
			<td>
				<table cellpadding="0" width="620" class="container" align="center" cellspacing="0" border="0">
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
								<tr>
									<td class='movableContentContainer bgItem'>

										<div class='movableContent'>
											<table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
												<tr height="40">
													<td width="200">&nbsp;</td>
													<td width="200">&nbsp;</td>
													<td width="200">&nbsp;</td>
												</tr>
												<tr>
													<td width="200" valign="top">&nbsp;</td>
													<td width="200" valign="top" align="center">
														<div class="contentEditableContainer contentImageEditable">
															<div class="contentEditable" align='center' >
																<img src="{site_logo}" width="auto" height="auto" alt='{site_title}'>
															</div>
														</div>
													</td>
													<td width="200" valign="top">&nbsp;</td>
												</tr>
												<tr height="25">
													<td width="200">&nbsp;</td>
													<td width="200">&nbsp;</td>
													<td width="200">&nbsp;</td>
												</tr>
											</table>
										</div>

										<div class='movableContent'>
											<table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
												<tr>
													<td width="100%" colspan="3" align="center" style="padding-bottom:10px;padding-top:25px;">
														<div class="contentEditableContainer contentTextEditable">
															<div class="contentEditable" align='center' >
																<h2>{mail_subject}</h2>
															</div>
														</div>
													</td>
												</tr>
												<tr>
													<td width="100">&nbsp;</td>
													<td width="400" align="center">
														<div class="contentEditableContainer contentTextEditable">
															<div class="contentEditable" align='justify'>

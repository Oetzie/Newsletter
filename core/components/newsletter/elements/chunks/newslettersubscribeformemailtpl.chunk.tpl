<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<base href="[[++site_url]]" />

		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,600,700" type="text/css" />
	</head>
	<body style="margin: 0; padding: 15px; background: #ffffff;">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: 'Open Sans', Arial, Verdana, sans-serif; font-size: 14px; line-height: 22px; font-weight: 400; color: #333333; background: #ffffff;">
			<tr>
				<td width="100%" align="left">
					<h2 style="font-size: 22px; font-weight: 600; line-height: 32px; margin: 0 0 10px;">[[%newsletter.email_subscribe_title? &topic=`site`&namespace=`newsletter`]]</h2>
					[[%newsletter.email_subscribe_content? &topic=`site`&namespace=`newsletter`]]
					<p>[[%site.form_email_regard? &topic=`site`&namespace=`site`]]</p>
					<p>[[++site_name]]</p>
				</td>
			</tr>
		</table>
	</body>
</html>
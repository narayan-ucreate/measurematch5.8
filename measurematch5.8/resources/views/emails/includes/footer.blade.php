<table width="100%" style="max-width:800px; background:#E5F1E8;" align="center">
	<tr>
	   <td align="center" style="border-top:#252161 solid 3px; margin-bottom:15px; padding-bottom:20px;">
	   		<p style="padding:0px 5px; margin-top: 15px; font-family:Arial, Helvetica, sans-serif; font-size:12px;">Need more help? Visit our <a href="{{ url('/faq',[],$ssl) }}" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; text-decoration:none; color:#252161" title="click here">FAQ</a>.</p>

	       <p style="padding:0px 5px; margin-top: 15px; font-family:Arial, Helvetica, sans-serif; font-size:12px;">Copyright © {{date('Y')}} MeasureMatch Ltd. All Rights Reserved.</p>
	       <p style="padding:0px 5px; margin: 10px 0; font-family:Arial, Helvetica, sans-serif; font-size:12px;">Company Number: 10199524 | VAT Number: 253943881 | Address: Unit 7, 270 Kingsland Road, London E8 4DG</p>

	        <p style="padding:0px 5px; margin-top: 15px; margin-bottom: 2px; font-family:Arial, Helvetica, sans-serif; font-size:12px;">To unsubscribe

	        <a href="{{ url('/unsubscribeEmail?user='.urlencode( base64_encode($userEmail) ),[],$ssl) }}" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; text-decoration:none; color:#252161" title="click here">click here</a>.</p>

	<a style="padding:0px 10px; font-family:Arial, Helvetica, sans-serif; font-size:12px; text-decoration:none; color:#252161; padding-right:5px;" href="{{ url('/pplink',[],$ssl) }}" title="Privacy Policy">Privacy Policy</a>|<a style="padding:0px 10px; font-family:Arial, Helvetica, sans-serif; font-size:12px; text-decoration:none; color:#252161;" href="{{ url('/tnc',[],$ssl) }}" title="Terms of Service">Terms of Service</a>
	   </td>
	</tr>
</table>

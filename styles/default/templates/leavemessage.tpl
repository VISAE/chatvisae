<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>${msg:leavemessage.title}</title>
	<link rel="shortcut icon" href="${mibewroot}/images/favicon.ico" type="image/x-icon"/>
	<link rel="stylesheet" type="text/css" href="${tplroot}/chat.css" />
	<style type="text/css">
		#header{
			height:50px;
			background:url(${tplroot}/images/bg_domain.gif) repeat-x top;
			background-color:#24689B;
			width:99.6%;
			margin:0px 0px 20px 0px;
		}
		#header .mmimg{
			background:url(${tplroot}/images/quadrat.gif) bottom left no-repeat;
		}
		.form td{
			background-color:#f4f4f4;
			color:#525252;
		}
		.but{
			font-family:Verdana !important;
			font-size:11px;
			background:url(${tplroot}/images/butbg.gif) no-repeat top left;
			display:block;
			text-align:center;
			padding-top:2px;
			color:white;
			width:80px;
			height:18px;
			text-decoration:none;
			position:relative;top:1px;
		}

		img.msg{
			width: 100%;
			max-width: 630px;
			height: auto;
		}

		/* Inicio estilo mensaje temporal */
		div.temporal_msg {
			position: absolute;
			left: 50%;
			top: 10%;
			transform: translate(-50%);
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
		}
		/* Fin estilo mensaje temporal */
	</style>
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#C28400" vlink="#C28400" alink="#C28400" style="margin:0px;">

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td valign="top">


			<form name="leaveMessageForm" method="post" action="${mibewroot}/leavemessage.php">
				<input type="hidden" name="style" value="${styleid}"/>
				<input type="hidden" name="info" value="${form:info}"/>
				<input type="hidden" name="referrer" value="${page:referrer}"/>
				${if:formgroupid}<input type="hidden" name="group" value="${form:groupid}"/>${endif:formgroupid}
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td valign="top" height="150" style="padding:5px">
							<table cellspacing="0" cellpadding="0" border="0" id="header" class="bg_domain">
								<tr>
									<td style="padding-left:20px;width:612px;color:white;" class="mmimg">
										${msg:leavemessage.descr}
									</td>
									<td align="right" style="padding-right:17px;">
										<table cellspacing="0" cellpadding="0" border="0">
											<tr>
												<td><a href="javascript:window.close();" title="${msg:leavemessage.close}"><img src="${tplroot}/images/buttons/back.gif" width="25" height="25" border="0" alt="" /></a></td>
												<td width="5"></td>
												<td class="button"><a href="javascript:window.close();" title="${msg:leavemessage.close}">${msg:leavemessage.close}</a></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
							<table width="100%" cellspacing="0" cellpadding="0" border="0">
								<tr>
									<td width="100%" height="100" style="padding-left:20px;">
									</td>
									<td nowrap="nowrap" style="padding-right:10px"><span style="font-size:16px;font-weight:bold;color:#525252">${if:formgroupname}${form:groupname}: ${endif:formgroupname}${msg:leavemessage.title}</span></td>
								</tr>
							</table>
						</td>
					</tr>
					<!-- Inicio tag temporal-->
					<!-- <tr>
						<div align="center" class="temporal_msg"><img class="msg" src="/images/unad/fuera_servicio_2018.png" alt="Temporalmente fuera de servicio" width="630" height="630"></div>
					</tr> -->
					<!-- Fin tag temporal-->
				</table>
			</form>

		</td>
	</tr>
</table>


<!--
<div width="100%" align="center">
	<iframe src="http://sivisae.unad.edu.co/sivisae/pages/sivisae_directorio_consejeria.php" width="1000" height="2600" frameborder="0" scrolling="no" allowtransparency="true"></iframe>
</div>
-->

</body>
</html>
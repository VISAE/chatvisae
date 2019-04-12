<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>${msg:presurvey.title}</title>
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
                font-weight: bold;
                background:url(${tplroot}/images/wmchat.png) top left no-repeat;
            background-position:0px -25px;
            display:block;
            text-align:center;
            padding-top:2px;
            color:white;
            width:186px;
            height:18px;
            text-decoration:none;
            }
        </style>

        <script>
            var reglaMail = /^[A-Za-z][A-Za-z0-9_.]*@[A-Za-z0-9_]+\.[A-Za-z0-9_.]+[A-za-z]$/;

            function validar_acceso(frm) {
                if (frm.name.value == "") {
                    alert("Debe ingresar un nombre de usuario");
                    return false;
                }
                else if (frm.email.value == "") {
                    alert("Debe ingresar un correo");
                    return false;
                }
                else if (!reglaMail.test(frm.email.value)) {
                    alert("Correo invalido");
                    return false;
                }
                else
                    frm.submit();

            }
        </script>
    </head>
    <body bgcolor="#FFFFFF" text="#000000" link="#C28400" vlink="#C28400" alink="#C28400" style="margin:0px;">
        <table cellspacing="0" cellpadding="0" border="0" id="header" class="bg_domain">
                                        <tr>
                                            <td style="padding-left:20px;width:612px;color:white;" class="mmimg">
                                                ${msg:presurvey.intro}
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
                                            <td width="100%">

                                            </td>
                                            <td nowrap="nowrap" style="padding-right:10px"><span style="font-size:16px;font-weight:bold;color:#525252">${msg:presurvey.title}</span></td>
                                        </tr>
                                    </table>
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td valign="top" width="40%">
                    <form name="surveyForm" method="post" action="${mibewroot}/client.php">
                        <input type="hidden" name="style" value="${styleid}"/>
                        <input type="hidden" name="info" value="${form:info}"/>
                        <input type="hidden" name="referrer" value="${page:referrer}"/>
                        <input type="hidden" name="survey" value="on"/>
                        ${ifnot:showemail}<input type="hidden" name="email" value="${form:email}"/>${endif:showemail}
                        ${ifnot:groups}${if:formgroupid}<input type="hidden" name="group" value="${form:groupid}"/>${endif:formgroupid}${endif:groups}
                        ${ifnot:showmessage}<input type="hidden" name="message" value="${form:message}"/>${endif:showmessage}
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td valign="top" height="150" style="padding:5px">
                                </td>
                            </tr>

                            <tr>
                                <td valign="top" style="padding:0px 24px;">
                                    ${if:errors}
                                    <table cellspacing="0" cellpadding="0" border="0">
                                        <tr>
                                            <td valign="top"><img src="${mibewroot}/images/icon_err.gif" width="40" height="40" border="0" alt="" /></td>
                                            <td width="10"></td>
                                            <td class="text">
                                                ${errors}
                                            </td>
                                        </tr>
                                    </table>
                                    ${endif:errors}
                                    <table cellspacing="1" cellpadding="5" border="0" class="form">
                                        ${if:groups}
                                        <tr>
                                            <td class="text">${msg:presurvey.department}</td>
                                            <td>
                                                <select name="group" style="min-width:200px;">${page:groups}</select>
                                            </td>
                                        </tr>
                                        ${endif:groups}

                                        <tr>
                                            <td class="text">${msg:presurvey.name}</td>
                                            <td><input type="text" name="name" size="50" value="${form:name}" class="username" ${ifnot:showname}disabled="disabled"${endif:showname}/></td>
                                        </tr>

                                        ${if:showemail}
                                        <tr>
                                            <td class="text">${msg:presurvey.mail}</td>
                                            <td><input type="text" name="email" size="50" value="${form:email}" class="username"/></td>
                                        </tr>
                                        ${endif:showemail}

                                        ${if:showmessage}			
                                        <tr>
                                            <td class="text">${msg:presurvey.question}</td>
                                            <td valign="top">
                                                <textarea name="message" tabindex="0" cols="45" rows="2" style="border:1px solid #878787; overflow:auto">${form:message}</textarea>
                                            </td>
                                        </tr>
                                        ${endif:showmessage}			
                                        ${if:showcaptcha}
                                        <tr>
                                            <td class="text"><img src="captcha.php"/></td>
                                            <td><input type="text" name="captcha" size="50" maxlength="15" value="" class="username"/></td>
                                        </tr>
                                        ${endif:showcaptcha}
                                        <tr>
                                            <td colspan="2" align="right">
                                                <table cellspacing="0" cellpadding="0" border="0">
                                                    <tr>
                                                        <td>
                                                            <!--<a href="javascript:document.surveyForm.submit();" class="but" id="sndmessagelnk">${msg:presurvey.submit}</a>-->
                                                            <a href="javascript:validar_acceso(document.surveyForm);" class="but" id="sndmessagelnk">${msg:presurvey.submit}</a></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>
                <td valign="top" width="60%">
                    <table border="0" width="100%">
                        <tr><td class="copyr">
                                <p align="justify" style="font-size:9px">
                                    <div width="100%" align="center">
                                        <iframe scrolling="yes" src="http://sivisae.unad.edu.co/sivisae/pages/sivisae_directorio_consejeria.php" width="1100" height="800" frameborder="0" scrolling="no" allowtransparency="true"></iframe>
                                    </div>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table cellpadding="7" cellspacing="5" border="0" width="100%">
            <tr><td class="copyr">
                    <p align="center" style="font-size:9px">A trav&eacute;s de este servicio en l&iacute;nea, estudiantes y el p&uacute;blico en general podr&aacute;n obtener, de forma inmediata, informaci&oacute;n  acerca los diferentes servicios de la UNAD. Si usted tiene alguna queja o reclamo, le invitamos a usar la aplicaci&oacute;n de radicaci&oacute;n de PQRS del <a href='http://sau.unad.edu.co/' target='_blank'>Sistema de Atenci&oacute;n al Usuario.</a></p>
                </td>
            </tr>
            <tr>
                <tr>
                    <td id="poweredByTD" align="center" class="copyr">
                        ${msg:chat.window.poweredby} <a id="poweredByLink" href="http://mibew.org" title="Mibew Community" target="_blank">mibew.org</a>
                    </td>
                </tr>
        </table>

    </body>
</html>

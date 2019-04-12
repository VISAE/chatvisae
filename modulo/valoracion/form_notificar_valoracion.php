<?php
require_once("../../libs/common.php");
require_once("../../libs/chat.php");
require_once('../../libs/notify.php');
require_once('../../libs/phpmailer/class.phpmailer.php');
require_once("../operacionesGlobales.php");
require_once("valoracion_lib.php");
?>
<?php verify_thread_token();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>
<link rel="shortcut icon" href="<?php echo $mibewroot;?>/images/favicon.ico" type="image/x-icon">
<style>
.combo_list{
	font-size:10px;
}
</style>


<script LANGUAGE="JavaScript">



function validar_envio(frm){
	if(frm.email.value!=""){
			return true;
	}
	return false;
}
</script>






</head>

<body style="font-size:12px;">
<div align="center"><a href="javascript:window.close();">(cerrar ventana)</a></div>

<?php
$valoracion_realizada = existe_valoracion_realizada($_GET['thread']);


if( isset($_POST['email']) && isset($_POST['enviar']) ) {
	$link = connect();
	notificar_valoracion($_POST['thread'],$_POST['token'],$_POST['email'],$link);
	mysql_close($link);
	echo "<br /><br />Se ha enviado la notificación de la valoración del servicio al usuario.<br />";
	echo "<script>setTimeout (\"window.close()\", 2000); </script>";
}
else{

?>




<div align="center" style="width:100%">
<div style="min-width:200px; max-width:800px;">



<br />


<form name="frm_asignarTema" method="post" action="?thread=<?php echo $_GET['thread'];?>&token=<?php echo $_GET['token'];?>" onsubmit="return validar_envio(this);" >

  <input type="hidden" name="thread" value="<?php echo $_GET['thread'];?>" />
	<input type="hidden" name="token" value="<?php echo $_GET['token'];?>" />    
    
    <table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #7595B9;">
      <tr bgcolor="#FBEDD9">
        <th height="14" colspan="2" bgcolor="#003366" style="color:#FFF;">NOTIFICAR VALORACI&Oacute;N DEL SERVICIO</th>
      </tr>
      <tr bgcolor="#D3E7FE">
        <th width="196" height="33">Correo electr&oacute;nico</th>
        <td width="562"><input type="text" name="email" value="<?php echo $_GET["email"];?>" /></td>
      </tr>
      <tr>
        <td colspan="2" align="right"><input type="submit" name="enviar" id="enviar" value="enviar &gt;&gt;" /></td>
      </tr>
    </table>

</form>




</div>
</div>

<?php } ?>
<br />
<br />



</body>
</html>
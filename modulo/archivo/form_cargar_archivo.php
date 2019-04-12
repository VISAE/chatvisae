<?php
require_once("../../libs/common.php");
require_once("../../libs/chat.php");
require_once("../operacionesGlobales.php");
require_once("archivo_lib.php");
?>
<?php verify_thread_token();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>
</head>

<body style="font-size:12px;">
<?php
if( isset($_FILES['file']) && $_FILES['file']['name']) {
	subir_archivo();
}
?>


<form name="frm_upload_file" enctype="multipart/form-data" method="post" action="" >
    <input type="hidden" name="thread" value="<?php echo $_GET['thread'];?>" />
	<input type="hidden" name="token" value="<?php echo $_GET['token'];?>" />
  <label>Compartir un archivo: 
  <span style="color:#666; font-style:italic; font-size:11px; ">
    (Máx: 2Mb) (pdf, zip, rar, jpg)
    </span>
    <input type="file" name="file" id="file" />
  </label>
  
  <input type="submit" name="enviar" id="enviar" value="Enviar" />
</form>


</body>
</html>
<?php
require_once("../../libs/common.php");
require_once("../../libs/chat.php");
require_once("../operacionesGlobales.php");
require_once("tema_lib.php");
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

function nuevoAjax()
{ 
	/* Crea el objeto AJAX. Esta funcion es generica para cualquier utilidad de este tipo */
	var xmlhttp=false; 
	try 
	{ 
		// Creacion del objeto AJAX para navegadores no IE
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP"); 
	}
	catch(e)
	{ 
		try
		{ 
			// Creacion del objeto AJAX para IE 
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); 
		} 
		catch(E) { xmlhttp=false; }
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') { 
		xmlhttp=new XMLHttpRequest(); 
	} 

	return xmlhttp; 
} 


function MostrarConsulta(id_tema_padre){
		capaContenedora = document.getElementById("div_lista_temas_hijo");
		url = "tema_lib.php?accion=cargar_temas_hijo&id_tema_padre="+id_tema_padre;

		urlfinal = url;
		ajax=nuevoAjax();
		ajax.open("GET", urlfinal);
		ajax.onreadystatechange=function() {			
			if (ajax.readyState==4) {
				capaContenedora.innerHTML = ajax.responseText;
			}
		}
		ajax.send(null);
}


function validar_envio(frm){
	if(frm.tema_padre.value!=""){
		if(frm.tema_hijo.value!=""){
			return true;
		}
	}
	return false;
}
</script>

</head>

<body style="font-size:12px;">


<?php
if( isset($_POST['asignar_tema']) && isset($_POST['tema_hijo']) ) {
	if( ! existe_tema_asignado($_POST['thread'],$_POST['tema_hijo']) ){
		$id = registrar_asignacion_tema($_POST['thread'],$_POST['tema_hijo']);
		if($id == NULL || $id==0){
			echo "Se produjo un error al registrar la asignación del tema.";
		}
		else{
			echo "<script>setTimeout (\"window.close()\", 5000); </script>";
		}
	}
}
?>

<?php
$lista_temas_asignados = lista_temas_asignados($_GET['thread']);
?>

<?php if($lista_temas_asignados==NULL){ ?>
<form name="frm_asignarTema" method="post" action="?thread=<?php echo $_GET['thread'];?>&token=<?php echo $_GET['token'];?>" onsubmit="return validar_envio(this);" >

  <input type="hidden" name="thread" value="<?php echo $_GET['thread'];?>" />
	<input type="hidden" name="token" value="<?php echo $_GET['token'];?>" />    
    
    <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #7595B9;">
      <tr bgcolor="#FBEDD9">
        <th height="14" colspan="2" bgcolor="#003366" style="color:#FFF;">ASIGNACI&Oacute;N DE TEMA DE LA CONVERSACI&Oacute;N</th>
      </tr>
      <tr bgcolor="#D3E7FE">
        <th width="100" height="33">Categor&iacute;a</th>
        <td><?php echo generar_select_lista_temas("0","activo","padre"); ?></td>
      </tr>
      <tr bgcolor="#F0F8FF">
        <th height="32">Tema</th>
        <td><div id="div_lista_temas_hijo" style=" float:left"></div></td>
      </tr>
      <tr>
        <td colspan="2" align="right"><input type="submit" name="asignar_tema" id="asignar_tema" value="Asignar &gt;&gt;" /></td>
      </tr>
    </table>

</form>
<br />
<br />
<?php } ?>



<?php if($lista_temas_asignados!=NULL){ ?>
    <div id="temas_asignados" style="background-color:#F2F2F2; padding:5px; border-bottom:#999 1px solid; border-top:#999 1px solid; ">
        <strong>TEMAS ASIGNADOS:</strong> 
        <?php
            echo "<ul>";
            foreach($lista_temas_asignados as $tema) {
                $tema_padre = consultar_tema($tema["id_tema_padre"]);                
                $boton_eliminar = "<a href=\"tema_lib.php?accion=eliminar_asignacion_tema&asignacion=".$tema["id_thread_tema"]."&thread=".$_GET["thread"]."&token=".$_GET["token"]."\"><img src=\"".$mibewroot."/images/dash/close.gif\" title=\"Eliminar\" border=\"0\"></a>";
				//$boton_eliminar = "<a href=\"tema_lib.php?accion=eliminar_asignacion_tema&asignacion=".$tema["id_thread_tema"]."&thread=".$_GET["thread"]."&token=".$_GET["token"]."\">(Borrar asignación)</a>";
                echo "<li>".$tema["nombre_tema"]." <span style=\"color:#666666;\">(CATEGOR&Iacute;A: ".$tema_padre["nombre_tema"].")</span> &nbsp;&nbsp;".$boton_eliminar."</li>";
            }
            echo "</ul>"; 
        ?>
    </div>
    <div align="center"><a href="javascript:window.close();">(cerrar ventana)</a></div>
<?php } ?>
</body>
</html>
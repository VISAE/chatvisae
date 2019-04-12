<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>
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


function MostrarConsulta(){
		capaContenedora = document.getElementById("list_files");
		url = "archivo_lib.php?accion=recargarLista&thread=<?php echo $_GET['thread'];?>&token=<?php echo $_GET['token'];?><?php if(isset($_GET['agent'])) echo "&agent=1";?>";

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
</script>
</head>

<body style="font-size:12px;">

<div id="list_files"></div>
<script>
	MostrarConsulta(); 
	setInterval("MostrarConsulta()", 5000);
</script>

</body>
</html>
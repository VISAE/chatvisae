<?php
require_once("../../libs/common.php");
require_once("../../libs/chat.php");
require_once("../operacionesGlobales.php");
require_once("valoracion_lib.php");
?>
<?php verify_thread_token();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="shortcut icon" href="<?php echo $mibewroot;?>/images/favicon.ico" type="image/x-icon">
<style>
.combo_list{
	font-size:10px;
}
</style>


<script LANGUAGE="JavaScript">



function validar_envio(frm){
	if(frm.tema_padre.value!=""){
		if(frm.tema_hijo.value!=""){
			return true;
		}
	}
	return false;
}
</script>





<script>
//***************************************************************************************************
//para tipo radio
function chequearOpcionGrupo(campo)
{
	
	for (var i=0;i < campo.length;i++)
	{	var elemento = campo[i];
		if (elemento.type == "radio"){
			if (elemento.checked)
				return (true);
		}		
	}
	return (false);
}

//***************************************************************************************************
function obtenerValorRadioSeleccionado(campo)
{
    for(i=0;i<campo.length;i++){
        if(campo[i].checked) 
			return campo[i].value;
	}
	return "";
}
//***************************************************************************************************
function validar_frmValoracionSolicitud(frm){
	
	okfrm = true;
	if(okfrm &&  ! chequearOpcionGrupo( frm.elements['criterio_amabilidad'] ) ){
			alert("Debe calificar de 1 a 5 la amabilidad en la respuesta.");  okfrm = false;
	}
	valor = obtenerValorRadioSeleccionado( frm.elements['criterio_amabilidad'] );
	if(okfrm && (valor == "1" || valor == "2" || valor == "3") && frm.aclaracion_amabilidad.value=="" ){
			alert("Indique ¿Cuál es la razón por la que califica el aspecto de amabilidad en la respuesta con dicho valor?");  okfrm = false;
	}
	if(okfrm &&  frm.aclaracion_amabilidad.value.length > 500 ){
		alert("Ha sobrepasado los 500 caracteres permitidos en el campo en el que indica ¿Cuál es la razón por la que califica el aspecto de amabilidad en la respuesta con dicho valor?. "); frm.aclaracion_amabilidad.focus(); okfrm = false;
	}
	
	
	if(okfrm &&  ! chequearOpcionGrupo( frm.elements['criterio_respuesta_adecuada'] ) ){
			alert("Indique si la respuesta que se le brindó, resolvió su inquietud adecuadamente.");  okfrm = false;
	}
	
	
	
	if(okfrm &&  ! chequearOpcionGrupo( frm.elements['criterio_rapidez_respuesta'] ) ){
			alert("Debe calificar de 1 a 5 la rapidez en la respuesta.");  okfrm = false;
	}
	valor = obtenerValorRadioSeleccionado( frm.elements['criterio_rapidez_respuesta'] );
	if(okfrm && (valor == "1" || valor == "2" || valor == "3") && frm.aclaracion_rapidez_respuesta.value=="" ){
			alert("Indique ¿Cuál es la razón por la que califica el aspecto de rapidez en la respuesta con dicho valor?");  okfrm = false;
	}
	if(okfrm &&  frm.aclaracion_rapidez_respuesta.value.length > 500 ){
		alert("Ha sobrepasado los 500 caracteres permitidos en el campo en el que indica ¿Cuál es la razón por la que califica el aspecto de rapidez en la respuesta con dicho valor?. "); frm.aclaracion_amabilidad.focus(); okfrm = false;
	}
	
	
	
	if(okfrm &&  ! chequearOpcionGrupo( frm.elements['criterio_claridad_respuesta'] ) ){
			alert("Debe calificar de 1 a 5 la claridad en la respuesta.");  okfrm = false;
	}
	valor = obtenerValorRadioSeleccionado( frm.elements['criterio_claridad_respuesta'] );
	if(okfrm && (valor == "1" || valor == "2" || valor == "3") && frm.aclaracion_claridad_respuesta.value=="" ){
			alert("Indique ¿Cuál es la razón por la que califica el aspecto de claridad en la respuesta con dicho valor?");  okfrm = false;
	}
	if(okfrm &&  frm.aclaracion_claridad_respuesta.value.length > 500 ){
		alert("Ha sobrepasado los 500 caracteres permitidos en el campo en el que indica ¿Cuál es la razón por la que califica el aspecto de claridad en la respuesta con dicho valor?. "); frm.aclaracion_amabilidad.focus(); okfrm = false;
	}
	
	
	
	if(okfrm &&  ! chequearOpcionGrupo( frm.elements['criterio_recomendar_sau'] ) ){
			alert("Indique si recomendaría el Servicio de Atención al Usuario de la UNAD a familiares y amigos. ");  okfrm = false;
	}
	
			
	if(okfrm &&  frm.sugerencia.value.length > 500 ){
		alert("Ha sobrepasado los 500 caracteres permitidos para el campo de sugerencias. "); frm.sugerencia.focus(); okfrm = false;
	}
	
	
	if(okfrm){
		if(confirm("¿Está seguro de registrar estos datos?")){
			okfrm = true;			
		}
		else
			okfrm = false;
		
	}
	return okfrm;
	
	

}
//***************************************************************************************************
function validarSeleccionCriterioValoracion(campoCriterio,campoAclaracion,divAclaracion){
	valor = campoCriterio.value ;
	
	if(valor == "1" || valor == "2" || valor == "3" ){
		document.getElementById(divAclaracion).style.display = "block";
	}
	else{
		campoAclaracion.value="";
		document.getElementById(divAclaracion).style.display = "none";
	}
}
//***************************************************************************************************
</script>



</head>

<body style="font-size:12px;">


<?php
$valoracion_realizada = existe_valoracion_realizada($_GET['thread']);

if( ! $valoracion_realizada ){
	if( isset($_POST['thread']) && isset($_POST['criterio_amabilidad']) ) {		
		//captura VARIABLES POR POST 
		$cantidad = count($_POST);
		$tags = array_keys($_POST);// obtiene los nombres de las varibles
		$valores = array_values($_POST);// obtiene los valores de las varibles
		$fm=array();
		// crea las variables y les asigna el valor
		for($i=0;$i<$cantidad;$i++){
			$$tags[$i]=$valores[$i];
			$fm[$tags[$i]] = $$tags[$i];
		}
		
		
		$id = registrar_valoracion($fm);
		if($id == NULL || $id==0){
			echo "Se produjo un error al registrar la valoraci&oacute;n del servicio.";
		}
		else
			$valoracion_realizada = existe_valoracion_realizada($_GET['thread']);
	}
}

?>




<div align="center" style="width:100%">
<div style="min-width:200px; max-width:800px;">

<table id="cabecera" width="100%">
    <tr>
        <td><img src="<?php echo $mibewroot."/images/unad_2013.png"; ?>" border="0" alt=""/></td>
        <td align="center" valign="middle">
            <div style="color:#215CA5; font-size:16px; font-weight:bold;">
            Asesor&iacute;a Virtual<br />
            VISAE - UNAD
            </div>
        </td>
    </tr>
</table>

<br />

<?php if( ! $valoracion_realizada ){ ?>
<form id="frm_valoracion" name="frm_valoracion" method="post" action="form_valoracion.php?thread=<?php echo $_GET['thread'];?>&token=<?php echo $_GET['token'];?>" onsubmit="return validar_frmValoracionSolicitud(this)" >
    <input name="thread" type="hidden" id="thread" value="<?php echo $_GET['thread'];?>" />
    <input name="token" type="hidden" id="token" value="<?php echo $_GET['token'];?>" />
    

<table width="100%" border="1" cellspacing="0" cellpadding="0" align="center" bordercolor="#DDDDDD"> 
    <tr bgcolor="#F7F7F7">
    	<td width="91%" height="20" bgcolor="#FFB148"><div align="center"><strong>Valoraci&oacute;n del Servicio Prestado</strong></div>
        </td>
    	<td width="9%" align="center" bgcolor="#FFB148">[<?php echo $_GET['thread'];?>]</td>
    </tr>
    <tr>
		<td colspan="2"><div align="justify" style="background-color:#EFFFD7; font-weight:bold;">Muchas gracias por utilizar nuestro servicio de asesor&iacute;a. Sus sugerencias son importantes para mejorar y prestarle un mejor servicio. </div></td>
    </tr>
                
                
    <tr>
    <td colspan="2">
            <br />
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td> 
                <div style="float:left;"><strong style="color:#555">Califique de 1 a 5 la amabilidad en la respuesta, en el que 5 es excelente  y  1 deficiente:</strong></div>
                <div align="right" style="margin-right:10px;">
                    <input type="radio" name="criterio_amabilidad" value="1" onclick="validarSeleccionCriterioValoracion(this,this.form.aclaracion_amabilidad,'divAclaracion_amabilidad')" />1
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="criterio_amabilidad" value="2" onclick="validarSeleccionCriterioValoracion(this,this.form.aclaracion_amabilidad,'divAclaracion_amabilidad')" />2
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="criterio_amabilidad" value="3" onclick="validarSeleccionCriterioValoracion(this,this.form.aclaracion_amabilidad,'divAclaracion_amabilidad')" />3
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="criterio_amabilidad" value="4" onclick="validarSeleccionCriterioValoracion(this,this.form.aclaracion_amabilidad,'divAclaracion_amabilidad')" />4
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="criterio_amabilidad" value="5" onclick="validarSeleccionCriterioValoracion(this,this.form.aclaracion_amabilidad,'divAclaracion_amabilidad')" />5
                </div>
                <div id="divAclaracion_amabilidad" style="display:none;">
                	&iquest;Cu&aacute;l es la raz&oacute;n por la que califica el aspecto con este valor?
               	  <textarea name="aclaracion_amabilidad" cols="10" rows="3" style="width:80%"></textarea>
                </div>
                <hr />
                </td>
              </tr>
              <tr>
                <td>
                <div style="float:left;"><strong style="color:#555">&iquest;La respuesta que se le brind&oacute;, resolvi&oacute; su inquietud adecuadamente?</strong></div>
                <div align="right" style="margin-right:130px;">
                    <input type="radio" name="criterio_respuesta_adecuada" value="SI" />SI
                    &nbsp;&nbsp;&nbsp;&nbsp;
                     <input type="radio" name="criterio_respuesta_adecuada" value="NO" />NO
                 </div>
                <hr /></td>
              </tr>
              <tr>
                <td> 
                <div style="float:left;"><strong style="color:#555">Califique de 1 a 5 la rapidez en la respuesta, en el que 5 es excelente  y  1 deficiente:</strong></div>
                <div align="right" style="margin-right:10px;">
                    <input type="radio" name="criterio_rapidez_respuesta" value="1" onclick="validarSeleccionCriterioValoracion(this,this.form.aclaracion_rapidez_respuesta,'divAclaracion_rapidez_respuesta')" />1
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="criterio_rapidez_respuesta" value="2" onclick="validarSeleccionCriterioValoracion(this,this.form.aclaracion_rapidez_respuesta,'divAclaracion_rapidez_respuesta')" />2
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="criterio_rapidez_respuesta" value="3" onclick="validarSeleccionCriterioValoracion(this,this.form.aclaracion_rapidez_respuesta,'divAclaracion_rapidez_respuesta')" />3
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="criterio_rapidez_respuesta" value="4" onclick="validarSeleccionCriterioValoracion(this,this.form.aclaracion_rapidez_respuesta,'divAclaracion_rapidez_respuesta')" />4
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="criterio_rapidez_respuesta" value="5" onclick="validarSeleccionCriterioValoracion(this,this.form.aclaracion_rapidez_respuesta,'divAclaracion_rapidez_respuesta')" />5
                </div>
                <div id="divAclaracion_rapidez_respuesta" style="display:none;">
                	&iquest;Cu&aacute;l es la raz&oacute;n por la que califica el aspecto con este valor?
                	<textarea name="aclaracion_rapidez_respuesta" cols="10" rows="3" style="width:80%"></textarea>
                </div>
                <hr />
                </td>
              </tr>
              <tr>
                <td> 
                <div style="float:left;"><strong style="color:#555">Califique de 1 a 5 la claridad en la respuesta, en el que 5 es excelente  y  1 deficiente:</strong></div>
                <div align="right" style="margin-right:10px;">
                    <input type="radio" name="criterio_claridad_respuesta" value="1" onclick="validarSeleccionCriterioValoracion(this,this.form.aclaracion_claridad_respuesta,'divAclaracion_claridad_respuesta')" />1
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="criterio_claridad_respuesta" value="2" onclick="validarSeleccionCriterioValoracion(this,this.form.aclaracion_claridad_respuesta,'divAclaracion_claridad_respuesta')" />2
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="criterio_claridad_respuesta" value="3" onclick="validarSeleccionCriterioValoracion(this,this.form.aclaracion_claridad_respuesta,'divAclaracion_claridad_respuesta')" />3
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="criterio_claridad_respuesta" value="4" onclick="validarSeleccionCriterioValoracion(this,this.form.aclaracion_claridad_respuesta,'divAclaracion_claridad_respuesta')" />4
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="criterio_claridad_respuesta" value="5" onclick="validarSeleccionCriterioValoracion(this,this.form.aclaracion_claridad_respuesta,'divAclaracion_claridad_respuesta')" />5
                </div>
                <div id="divAclaracion_claridad_respuesta" style="display:none;">
                	&iquest;Cu&aacute;l es la raz&oacute;n por la que califica el aspecto con este valor?
                	<textarea name="aclaracion_claridad_respuesta" cols="10" rows="3" style="width:80%"></textarea>
                </div>
                <hr />
                </td>
              </tr>
              <tr>
                <td>
                <div style="float:left;"><strong style="color:#555">&iquest;Recomendar&iacute;a el Servicio de Asesor&iacute;a virtual de la VISAE a otra persona?</strong></div>
                <div align="right" style="margin-right:130px;">
                    <input type="radio" name="criterio_recomendar_sau" value="SI" />SI
                    &nbsp;&nbsp;&nbsp;&nbsp;
                     <input type="radio" name="criterio_recomendar_sau" value="NO" />NO
                 </div>
                <hr /></td>
              </tr>
              <tr>
                <td>
                <div ><strong style="color:#555">Cual de los siguientes aspectos destaca en cuanto al Servicio de Asesor&iacute;a virtual de la VISAE:</strong></div>
                <div align="left" style="margin-left:580px;">
                <input type="checkbox" name="criterio_resalta_accesibilidad" value="SI" />Facilidad de acceso al sistema<br />
                <input type="checkbox" name="criterio_resalta_innovacion" value="SI" />Innovaci&oacute;n del sistema<br />
                <input type="checkbox" name="criterio_resalta_utilidad" value="SI" />Utilidad del sistema
                   
                </div>
                <hr /></td>
              </tr>
              <tr>
                <td><strong style="color:#555">Sugerencias:</strong><br />
                <textarea name="sugerencia" cols="10" rows="3" id="sugerencia"  style="width:90%"></textarea>
                <hr />
                </td>
              </tr>
            </table>    
                    
		</td>
    </tr>
                
    <tr>
    	<td colspan="2" align="right">
        <input type="submit" name="Submit" value="Valorar servicio &gt;&gt;" />
       
        </td>
    </tr>            
                
            
</table>

</form>
<?php } else{ 
$valoracion = consultar_valoracion($_GET['thread']);
?>

<table width="100%" border="1" cellspacing="0" cellpadding="0" align="center" bordercolor="#DDDDDD"> 
    <tr bgcolor="#F7F7F7">
    	<td width="91%" height="20" bgcolor="#FFB148"><div align="center"><strong>Valoraci&oacute;n del Servicio Prestado</strong></div></td>
    	<td width="9%" align="center" bgcolor="#FFB148">[<?php echo $_GET['thread'];?>]</td>
    </tr>                
                
    <tr>
    <td colspan="2">
            <br /><span style=" font-weight:bold; font-style:italic;">Valoraci&oacute;n realizada el <?php echo $valoracion['fecha_registro']; ?></span>
            <br /><hr />
            <table width="95%" border="0" align="center" cellpadding="0">
              <tr>
                <td> <strong>Califique de 1 a 5 la amabilidad en la respuesta, en el que 5 es excelente  y  1 deficiente: </strong><br />
                Respuesta: <?php echo $valoracion['criterio_amabilidad']; ?> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $valoracion['aclaracion_amabilidad']; ?>
                
                  <hr />
                </td>
              </tr>
              <tr>
                <td><strong>&iquest;La respuesta que se le brind&oacute;, resolvi&oacute; su inquietud adecuadamente?
                </strong><br />
                Respuesta: <?php echo $valoracion['criterio_respuesta_adecuada']; ?>
                <hr /></td>
              </tr>
              <tr>
                <td> <strong>Califique de 1 a 5 la rapidez en la respuesta, en el que 5 es excelente  y  1 deficiente: </strong><br />
                Respuesta: <?php echo $valoracion['criterio_rapidez_respuesta']; ?> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $valoracion['aclaracion_rapidez_respuesta']; ?>
                
                  <hr />
                </td>
              </tr>
              <tr>
                <td> <strong>Califique de 1 a 5 la claridad en la respuesta, en el que 5 es excelente  y  1 deficiente: </strong><br />
                Respuesta: <?php echo $valoracion['criterio_claridad_respuesta']; ?> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $valoracion['aclaracion_claridad_respuesta']; ?>
                
                  <hr />
                </td>
              </tr>
              <tr>
                <td><strong>&iquest;Recomendar&iacute;a el Servicio de asesor&iacute;a virtual de la VISAE a otra persona?</strong><br />
                Respuesta: <?php echo $valoracion['criterio_recomendar_sau']; ?>
                <hr /></td>
              </tr>
              <tr>
                <td><strong>Aspecto que destaca en cuanto al Servicio de Asesor&iacute;a de la VISAE:</strong> 
                <?php
                $criterios_resaltados="<br>";
				if($valoracion['criterio_resalta_accesibilidad']=="SI") $criterios_resaltados.="Facilidad de acceso al sistema"."<br>";
				if($valoracion['criterio_resalta_innovacion']=="SI") $criterios_resaltados.="Innovaci&oacute;n del sistema"."<br>";
				if($valoracion['criterio_resalta_utilidad']=="SI") $criterios_resaltados.="Utilidad del sistema"."<br>";
                
				echo $criterios_resaltados;
				?>
                <hr />
                </td>
              </tr>
              <tr>
                <td><strong>Sugerencias:</strong> <?php echo $valoracion['sugerencia'];   ?>
                <hr />
                </td>
              </tr>
            </table>    
                    
		</td>
    </tr>
            
</table>


	
<?php }?>

</div>
</div>
<br />
<br />



</body>
</html>
<?php
/*
 * This file is part of Mibew Messenger project.
 * 
 */


/* acciones por llamado directo a este fichero */
if(isset($_GET['accion'])){	
	$accion = $_GET['accion'];
	$thread = $_GET['thread'];
	$token = $_GET['token'];
}

//-------------------------------------------------------------------------




function consultar_valoracion($thread){
global $mibew_encoding;
	$link = connect();
	$query = "select * ";
	$query.= " from chat_valoracion ";		
	$query.= " where threadid=".mysql_real_escape_string($thread);
	
	$row = select_one_row($query, $link);
	mysql_close($link);

	return $row;
}


function existe_valoracion_realizada($thread) {
	
	$link = connect();
	
	$conditions =  array();
	$conditions[] = "threadid=".$thread;
	$countfields = "threadid";
	
	$total = db_rows_count("chat_valoracion",$conditions,$countfields,$link);

	mysql_close($link);
	
	if($total==0)
		return false;
	else
		return true;

}

function registrar_valoracion($fm){	
	$link = connect();
	
	$columnas='threadid,criterio_amabilidad,criterio_respuesta_adecuada,criterio_rapidez_respuesta,criterio_claridad_respuesta,criterio_recomendar_sau,criterio_resalta_accesibilidad,criterio_resalta_innovacion,criterio_resalta_utilidad,sugerencia,aclaracion_amabilidad,aclaracion_rapidez_respuesta,aclaracion_claridad_respuesta';
			
	$resalta_accesibilidad = "NO"; $resalta_innovacion = "NO"; $resalta_utilidad = "NO";
	if($fm['criterio_resalta_rapidez']=="SI") $resalta_rapidez="SI";
	if($fm['criterio_resalta_accesibilidad']=="SI") $resalta_accesibilidad="SI";
	if($fm['criterio_resalta_innovacion']=="SI") $resalta_innovacion="SI";
	if($fm['criterio_resalta_utilidad']=="SI") $resalta_utilidad="SI";
	if($fm['criterio_resalta_claridad']=="SI") $resalta_claridad="SI";
	
	
	
	$valores="'$fm[thread]',
	'$fm[criterio_amabilidad]',
	'$fm[criterio_respuesta_adecuada]',
	'$fm[criterio_rapidez_respuesta]',
	'$fm[criterio_claridad_respuesta]',
	'$fm[criterio_recomendar_sau]',
	'$resalta_accesibilidad',
	'$resalta_innovacion',
	'$resalta_utilidad',
	'".mysql_real_escape_string($fm['sugerencia'])."',
	'".mysql_real_escape_string($fm['aclaracion_amabilidad'])."',
	'".mysql_real_escape_string($fm['aclaracion_rapidez_respuesta'])."',
	'".mysql_real_escape_string($fm['aclaracion_claridad_respuesta'])."'";
	
	
	$query = "insert into chat_valoracion (".$columnas.") values (".$valores.")";
	
	perform_query($query,$link);	
	$id = mysql_insert_id($link);
	
	mysql_close($link);

	return $id;
}


//*****************************************************************************************************
//*****************************************************************************************************
//*****************************************************************************************************

function notificar_valoracion($threadid,$token,$email,$link)
{
	global $url_app, $mibewroot, $kind_info, $mysqlprefix, $mibew_encoding;
	
	
	if( ! valoracion_ya_notificada($threadid,$link)){//solo si no se ha notificado la valoracion
		//actualizar_email_usuario($threadid,$email,$link);
		
		//Construccion y envío del mensaje de enlace al formulario de valoracion
		$message = "Lo invitamos a contestar una encuesta sobre la prestación de este servicio, a través del siguiente enlace ";
		$message.= $url_app.$mibewroot."/modulo/valoracion/form_valoracion.php?thread=".$threadid."&token=".$token."&";
		$message.= " su opinión es importante para mejorar y prestarle un mejor servicio.";
		
		$message = myiconv("utf-8", $mibew_encoding, utf8_encode($message));
		
		post_message_($threadid, $kind_info, $message, $link);
		
				
		//actualización de estado de notificacion de la valoracion
		$query = sprintf("update ${mysqlprefix}chatthread set valoracion_notificada = '1' where threadid = %s ", intval($threadid));
		perform_query($query, $link);
		
		//envío de correo electrónico con enlace para la valoración del servicio
		$toaddr	= $email ;
		$reply_to = "";
		$subject = "Valore el servicio prestado a través de la Asesoría Virtual de la VISAE-UNAD [$threadid]";
		
		$message_mail = "Gracias por utilizar la Asesoría virtual de la VISAE de la UNAD. <br /><br />";
		$message_mail.= "Lo invitamos a contestar una encuesta sobre la prestación de este servicio, a través del siguiente enlace ";
		$message_mail.= "<a href=\"".$url_app.$mibewroot."/modulo/valoracion/form_valoracion.php?thread=".$threadid."&token=".$token."\">";	
		$message_mail.= $url_app.$mibewroot."/modulo/valoracion/form_valoracion.php?thread=".$threadid."&token=".$token."</a>";	
		$message_mail.= "<br /><br />Su opinión es importante para mejorar y prestarle un mejor servicio.<br /><br />";
		$message_mail.= "Si ya realizó la valoración del servicio, haga caso omiso a este mensaje.";
		$message_mail.= "<br /><br />";
		$message_mail.= "Cordial saludo,<br /><br />Asesoría virtual<br />VISAE - UNAD";
		$message_mail.= "<br />";
		
	
		$body = $message_mail;
		
		 mibew_mail_gmail($toaddr, $reply_to, $subject, $body, $link);
	}
}


function valoracion_ya_notificada($threadid,$link=NULL){
	$se_crea_conexion = false;
	if($link==NULL){
		$link = connect();
		$se_crea_conexion = true;
	}
		
	$query = "select valoracion_notificada ";
	$query.= " from chatthread ";		
	$query.= " where threadid=".mysql_real_escape_string($threadid);
	
	$row = select_one_row($query, $link);
	
	if($se_crea_conexion){
		mysql_close($link);
	}
	
	if($row['valoracion_notificada']=="1")
		return true;
	else
		return false;
}

//*****************************************************************************************************
//*****************************************************************************************************
//*****************************************************************************************************

?>
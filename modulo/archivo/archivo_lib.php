<?php
/*
 * This file is part of Mibew Messenger project.
 * 
 */


/* acciones por llamado directo */
if(isset($_GET['accion'])){
	require_once("../../libs/common.php");
	require_once("../../libs/chat.php");
	require_once("../operacionesGlobales.php");
	verify_thread_token();
	
	$accion = $_GET['accion'];
	$thread = $_GET['thread'];
	$token = $_GET['token'];
	$agent = $_GET['agent'];
	
	if($accion == "recargarLista" ){
			echo generar_vista_lista_archivos($thread,$token,$agent); 
	}elseif($accion == "removerArchivo"){
		remover_archivo($thread,$token,$agent,$_GET['file']);
		
		$url_location = "listar_archivos.php?thread=$thread&token=$token";
		if($agent=="1")
			$url_location.="&agent=$agent";
		header("location: $url_location");
	}
}







function listar_archivos($threadid,$state) {
	require_once("../../libs/common.php");
	$link = connect();
	$query = "select id_archivo,nombre_original,nombre_servidor,fecha_registro,fecha_removido from chat_archivo where threadid = $threadid ";
	
	if($state=="activo")
		$query.=" and fecha_removido is null";
	else if($state=="fecha_removido")
		$query.=" and fecha_removido is not null";
		
	$query.=" order by id_archivo DESC";
	
	$result = select_multi_assoc($query, $link);
	mysql_close($link);
	return $result;
}




function generar_vista_lista_archivos($threadid, $token, $agent=0) {
	global $mibewroot;
	$max_upload_file = 3;
	$files = listar_archivos($threadid,"activo");
	$total_files = count($files);
	$group_list = "Archivos compartidos: <span style=\"color:#666; font-style:italic; font-size:11px;\">($total_files/$max_upload_file)</span>";
	foreach($files as $file) {
		
		$id_archivo = $file['id_archivo'];
		$filename = $file['nombre_original'];
		$filenameserver = $file['nombre_servidor'];
		$fecha_registro = $file['fecha_registro'];
		$fecha_removido = $file['fecha_removido'];
		
		
		$delete = "";
		if($agent=="1") {
			$delete = "<a href=\"archivo_lib.php?accion=removerArchivo&file=$id_archivo&thread=$threadid&token=$token&agent=$agent\"><img src=\"".$mibewroot."/images/dash/close.gif\" title=\"Eliminar\" border=\"0\"></a>";
		}

		$group_list .= "<li>$delete <a href=\"".$mibewroot."/modulo/archivo/repositorio/".$filenameserver."\" title=\"\" target=\"_blank\">".$filename."</a> 
					<span style=\"color:#999; font-size:10px;\">($fecha_registro)</span>
		</li>";
		
	}
	return $group_list;
}


function generar_vista_historica_lista_archivos($threadid) {
	global $mibewroot;
	$files = listar_archivos($threadid,"all");
	$total_files = count($files);
	$group_list = "Archivos compartidos: <span style=\"color:#666; font-style:italic; font-size:11px;\">($total_files)</span>";
	foreach($files as $file) {
		
		$id_archivo = $file['id_archivo'];
		$filename = $file['nombre_original'];
		$filenameserver = $file['nombre_servidor'];
		$fecha_registro = $file['fecha_registro'];
		$fecha_removido = $file['fecha_removido'];
		
		
		$group_list .= "<li><a href=\"".$mibewroot."/modulo/archivo/repositorio/".$filenameserver."\" title=\"\" target=\"_blank\">".$filename."</a> 
					<span style=\"color:#999; font-size:10px;\">(creado: $fecha_registro) (eliminado: $fecha_removido)</span>
		</li>";
	}
	return $group_list;
}

function obtener_numero_archivos_thread($thread,$state){
	$link = connect();
	$conditions =  array();
	$conditions[] = "threadid=".$thread;
	$countfields = "id_archivo";
	
	if($state=="activo")
		$conditions[]="fecha_removido is null";
	else if($state=="removido")
		$conditions[]="fecha_removido is not null";
		

	$total_files = db_rows_count("chat_archivo",$conditions,$countfields,$link);
	mysql_close($link);
	return $total_files;
}

function subir_archivo(){
	$errors = "";
	$message_ok = "";
	
	$max_upload_file = 3;
	$total_files = obtener_numero_archivos_thread($_POST['thread'],"activo");

	if($total_files >= $max_upload_file ){
		$errors = "Ha alcanzado la cantidad máxima de archivos compartidos. ($total_files/$max_upload_file)";
	}
	else{
		$cod = substr(md5(uniqid(rand())),0,5);
		$max_uploaded_size = 2000000; //2MB 
		
		$valid_types = array("pdf","zip","rar", "jpg");
	
		$orig_filename = $_FILES['file']['name'];
		$tmp_file_name = $_FILES['file']['tmp_name'];
	
		$ext = strtolower(substr($orig_filename, 1 + strrpos($orig_filename, ".")));
		$new_file_name = $_POST['thread']."_".$cod.".".$ext;
	
		$file_size = $_FILES['file']['size'];
		if ($file_size == 0 || $file_size > $max_uploaded_size ||  $_FILES['archivo']['error']==1) {	
			$errors = "Ha excedido el tamaño de archivo para subir";
		} elseif(!in_array($ext, $valid_types)) {
			$errors = "Extensión de archivo invalida para subir";
		} else {
			$file_local_dir = "repositorio/";
			$full_file_path = $file_local_dir.$new_file_name;
			
			if (file_exists($full_file_path)) {
				unlink($full_file_path);
			}
			if (!move_uploaded_file($_FILES['file']['tmp_name'], $full_file_path)) {
				$errors = "Error al mover el archivo";
			} 
			else {
				$id = registrar_archivo($_POST['thread'],$orig_filename,$new_file_name);
				if($id == NULL || $id==0){
					unlink($full_file_path);
					$errors = "Error al registrar el archivo";
				}else{
					$message_ok = "Archivo enviado con éxito ($orig_filename)";
				}
			}
		}
	}
	
	if($errors!=""){
		echo "<div id=\"div_errors\" style=\"color:#FF6262;\" >".$errors."</div><script>setTimeout (\"document.getElementById('div_errors').innerHTML=''\", 7000);</script>";
	}
	if($message_ok!=""){
		echo "<div id=\"div_notify\" style=\"color:#0C3;\" >".$message_ok."</div><script>setTimeout (\"document.getElementById('div_notify').innerHTML=''\", 7000);</script>";
	}
}




function registrar_archivo($threadid,$nombre_original,$nombre_servidor) {
	$link = connect();
	$query = sprintf(
		"insert into chat_archivo (threadid,nombre_original,nombre_servidor,fecha_registro) values ('%s','%s','%s',CURRENT_TIMESTAMP)",
			mysql_real_escape_string($threadid),
			mysql_real_escape_string($nombre_original),
			mysql_real_escape_string($nombre_servidor));

	perform_query($query,$link);
	$id = mysql_insert_id($link);
	
	mysql_close($link);
	
	return $id;
}





function remover_archivo($thread,$token,$agent,$file){
	$link = connect();
	$file_row = select_one_row("select * from chat_archivo where id_archivo = ".mysql_real_escape_string($file)."", $link );
	
	$file_local_dir = "repositorio/";
	$full_file_path = $file_local_dir.$file_row['nombre_servidor'];
	
	//------- activar en caso de querer eliminar definitivamente del archivo del servidor y comentarear el siguiente performquery----------
	//if (file_exists($full_file_path)) {
	//	unlink($full_file_path);
	//}		
	//perform_query("delete from chat_archivo where id_archivo = $file",$link);
	//-----------------------------------------------------------------------------------------------------------------------
		
	perform_query("update chat_archivo set fecha_removido=now() where id_archivo = $file",$link);
	mysql_close($link);
}


?>
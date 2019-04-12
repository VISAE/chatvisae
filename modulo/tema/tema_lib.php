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
	
	if($accion == "cargar_temas_hijo" ){
		require_once("../../libs/common.php");
		$id_tema_padre = $_GET['id_tema_padre'];		
		echo generar_select_lista_temas($id_tema_padre, "1", "hijo"); 
	}
	elseif($accion == "eliminar_asignacion_tema"){
		require_once("../../libs/common.php");
		eliminar_asignacion_tema($thread,$token,$_GET['asignacion']);
		$url_location = "form_asignacion.php?thread=$thread&token=$token";		
		header("location: $url_location");
	}
}

//-------------------------------------------------------------------------



function lista_temas($id_tema_padre, $estado_activo) {
	$result = NULL;
	if($id_tema_padre!=""){
		$link = connect();
		$query = "select id_tema, id_proceso, id_tema_padre, nombre, estado_activo, fecha_registro ";
		$query.= " from chat_tema ";		
		$query.= " where id_tema_padre=$id_tema_padre ";
		
		if($estado_activo=="activo")
			$query.=" and estado_activo=1";
		else if($state=="inactivo")
			$query.=" and estado_activo=0";
			
		$query.=" order by nombre ASC";
		
		$result = select_multi_assoc($query, $link);
		mysql_close($link);
	}
	return $result;
}




function generar_select_lista_temas($id_tema_padre, $estado_activo, $tipo_lista) {
	//----------------------------------------
	//Posibles valores en variables de origen
	//tipo_lista:(padre,hijo)
	//----------------------------------------
	
	global $mibewroot;
	$lista = lista_temas($id_tema_padre, $estado_activo);
	
	$onchange = "";
	if($tipo_lista=="padre"){
		$onchange = "onchange=\"MostrarConsulta(this.value)\"";
	}
	
	
	$select_lista = "<select name=\"tema_$tipo_lista\" class=\"combo_list\" $onchange > <option value=\"\" >Seleccione...</option>";
	
	if($lista!=NULL){
		foreach($lista as $registro) {
			$select_lista.= "<option value=\"".$registro['id_tema']."\" >".$registro['nombre']."</option>";
		}
	}
	$select_lista.= "</select>";
	
	return $select_lista;
}


function consultar_tema($id_tema) {

	$link = connect();
	$query = "select t.id_tema, t.id_proceso, t.id_tema_padre, t.nombre as nombre_tema, t.estado_activo as estado_tema, p.nombre as nombre_proceso, p.estado_activo as estado_proceso ";
	$query.= " from chat_proceso_sgc p, chat_tema t ";		
	$query.= " where t.id_tema=$id_tema and t.id_proceso=p.id_proceso ";
	
	$row = select_one_row($query, $link);
	mysql_close($link);

	return $row;
}

function lista_temas_asignados($threadid) {
	$link = connect();
	$query = "select id_thread_tema,id_tema ";
	$query.= " from chat_thread_tema ";		
	$query.= " where threadid=$threadid ";	
	
	$lista = select_multi_assoc($query, $link);
	mysql_close($link);
		
	$result = array();
	if($lista!=NULL){
		foreach($lista as $registro) {
			
			$tema = consultar_tema($registro["id_tema"]);
			$tema["id_thread_tema"] = $registro["id_thread_tema"];
			$result[] = $tema;
		}
	}

	return $result;
}

function existe_tema_asignado($thread,$id_tema) {
	
	$link = connect();
	
	$conditions =  array();
	$conditions[] = "threadid=".$thread;
	$conditions[] = "id_tema=".$id_tema;
	$countfields = "id_tema";
	
	$total = db_rows_count("chat_thread_tema",$conditions,$countfields,$link);
	
	mysql_close($link);
	
	if($total==0)
		return false;
	else
		return true;

}

function registrar_asignacion_tema($threadid,$id_tema){	
	$link = connect();
	$query = sprintf(
		"insert into chat_thread_tema (threadid,id_tema) values ('%s','%s')",
			mysql_real_escape_string($threadid),
			mysql_real_escape_string($id_tema));
	
	perform_query($query,$link);
	$id = mysql_insert_id($link);
	
	mysql_close($link);

	return $id;
}


function eliminar_asignacion_tema($thread,$token,$id_thread_tema){
	$link = connect();
	$asignacion = select_one_row("select * from chat_thread_tema where id_thread_tema = ".mysql_real_escape_string($id_thread_tema), $link );
	
	if($asignacion!=NULL && $asignacion['threadid']==$thread){
		perform_query("delete from chat_thread_tema where id_thread_tema = $id_thread_tema",$link);
	}
	mysql_close($link);
}






?>
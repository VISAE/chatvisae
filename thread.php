<?php
/*
 * Copyright 2005-2013 the original author or authors.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

require_once('libs/common.php');
require_once('libs/chat.php');
require_once('libs/operator.php');
require_once('modulo/tema/tema_lib.php');
require_once('modulo/valoracion/valoracion_lib.php');
require_once('libs/notify.php');
require_once('libs/phpmailer/class.phpmailer.php');

$act = verifyparam( "act", "/^(refresh|post|rename|close|ping)$/");
$token = verifyparam( "token", "/^\d{1,10}$/");
$threadid = verifyparam( "thread", "/^\d{1,10}$/");
$isuser = verifyparam( "user", "/^true$/", "false") == 'true';
$outformat = ((verifyparam( "html", "/^on$/", "off") == 'on') ? "html" : "xml");
$istyping = verifyparam( "typed", "/^1$/", "") == '1';

if($threadid == 0 && ($token == 123 || $token == 124)) {
	require_once('libs/demothread.php');
	$lastid = verifyparam( "lastid", "/^\d{1,10}$/", 0);
	demo_process_thread($act,$outformat,$lastid,$isuser,$token == 123,$istyping,$act=="post"?getrawparam('message') : "");
	exit;
}

$thread = thread_by_id($threadid);
if( !$thread || !isset($thread['ltoken']) || $token != $thread['ltoken'] ) {
	die("wrong thread");
}

function show_ok_result($resid) {
	start_xml_output();
	echo "<$resid></$resid>";
	exit;
}

function show_error($message) {
	start_xml_output();
	echo "<error><descr>$message</descr></error>";
	exit;
}

ping_thread($thread, $isuser,$istyping);

if( !$isuser && $act != "rename" ) {
	$operator = check_login();
	check_for_reassign($thread,$operator);
}

if( $act == "refresh" ) {
	$lastid = verifyparam( "lastid", "/^\d{1,10}$/", -1);
	print_thread_messages($thread, $token, $lastid, $isuser,$outformat, $isuser ? null : $operator['operatorid']);
	exit;

} else if( $act == "post" ) {
	$lastid = verifyparam( "lastid", "/^\d{1,10}$/", -1);
	$message = getrawparam('message');

	$kind = $isuser ? $kind_user : $kind_agent;
	$from = $isuser ? $thread['userName'] : $thread['agentName'];

	if(!$isuser && $operator['operatorid'] != $thread['agentId']) {
		show_error("cannot send");
	}

	$link = connect();
	$postedid = post_message_($threadid,$kind,$message,$link,$from,null,$isuser ? null : $operator['operatorid'] );
	if($isuser && $postedid && $thread["shownmessageid"] == 0) {
		commit_thread( $thread['threadid'], array('shownmessageid' => intval($postedid)), $link);
	}
	mysql_close($link);
	print_thread_messages($thread, $token, $lastid, $isuser, $outformat, $isuser ? null : $operator['operatorid']);
	exit;

} else if( $act == "rename" ) {

	loadsettings();
	if( $settings['usercanchangename'] != "1" ) {
		show_error("server: forbidden to change name");
	}

	$newname = getrawparam('name');

	if (!preg_match('/^\s*$/', $newname)) {
		rename_user($thread, $newname);
		$data = strtr(base64_encode(myiconv($mibew_encoding,"utf-8",$newname)), '+/=', '-_,');
		setcookie($namecookie, $data, time()+60*60*24*365);
		show_ok_result("rename");
	}

} else if( $act == "ping" ) {
	show_ok_result("ping");

} else if( $act == "close" ) {
	/*//-------------- INICIO segmento original ---------------------
	if( $isuser || $thread['agentId'] == $operator['operatorid']) {
		close_thread($thread, $isuser);
	}
	show_ok_result("closed");
	//-------------- FIN segmento original ---------------------*/
	
		
	
	if( $isuser || $thread['agentId'] == $operator['operatorid']) {
		if($thread['agentId'] == $operator['operatorid'] && lista_temas_asignados($thread['threadid'])==NULL ){
			//solo si es operador quien cierra la conversacion y aún no ha asignado tema, solicita dicha asignacion antes de cerrar la ventana				
			
			$message= "Sr(a). ".$thread['agentName'].", antes de finalizar debe asignar el tema de la conversación.";		
			$message = myiconv("utf-8", $mibew_encoding, utf8_encode($message));
			$link = connect();
			$id_mensaje = post_message_($thread['threadid'], $kind_for_agent, $message, $link);
			mysql_close($link);
			show_error("Falta asignar el tema");
			
		}else{
			close_thread($thread, $isuser);
			show_ok_result("closed");
		}
	}
	

}

?>
<?php
/*
 * This file is part of Mibew Messenger project.
 * 
 */

//------------- validator of thread -----------------------------------
function  verify_thread_token(){
	$token = verifyparam( "token", "/^\d{1,8}$/");
	$threadid = verifyparam( "thread", "/^\d{1,8}$/");
	
	$thread = thread_by_id($threadid);
	if( !$thread || !isset($thread['ltoken']) || $token != $thread['ltoken'] ) {
		die("wrong thread");
	}
}
//----------------------------------------------------------------



?>
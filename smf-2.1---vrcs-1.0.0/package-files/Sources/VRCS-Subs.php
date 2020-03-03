<?php

if (!defined('SMF')){
	die('You are not allowed to access this file directly');
}
db_extend('packages');

function regReferral($memberID){
	global $smcFunc;
	
	while (!$smcFunc['db_insert']('ignore',
		'{db_prefix}vrcs_link',
		array('id_member' => 'int', 'id_reflink' => 'int'),
		array($memberID, mt_rand(1, mt_getrandmax())),
		array('id_member', 'id_reflink'),
		1
		));
}

?>

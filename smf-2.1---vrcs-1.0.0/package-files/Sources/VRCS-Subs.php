<?php

if (!defined('SMF')){
	die('You are not allowed to access this file directly');
}
db_extend('packages');

function regReferral($memberID){
	global $smcFunc;
	$cookieValue = getReflinkCookie();

	$smcFunc['db_query']('', 'CALL smf_vrcs_addmembers ('.(int)$memberID.','.(int)$cookieValue.')');

	while (!$smcFunc['db_insert']('ignore',
		'{db_prefix}vrcs_link',
		array('id_member' => 'int', 'id_reflink' => 'int'),
		array($memberID, mt_rand(1, mt_getrandmax())),
		array('id_member', 'id_reflink'),
		1
		));
}

	// Set/delete reflink cookie file.
function setReflinkCookie($vrcsCookieValue){
	global $vrcsCookieName;

	$expires = !empty($vrcsCookieValue) ? time() + 15768000 : 0;
	$cookie_url = url_parts(!empty($modSettings['localCookies']), !empty($modSettings['globalCookies']));
	smf_setcookie($vrcsCookieName, $vrcsCookieValue, $expires, $cookie_url[1], $cookie_url[0], 0, 0);
}

	// Get reflink cookie.
function getReflinkCookie(){
	global $vrcsCookieName;

	$vrcsCookieValue = 0;
	if (isset($_COOKIE[$vrcsCookieName]))
		$vrcsCookieValue = (int)$_COOKIE[$vrcsCookieName];
	return $vrcsCookieValue;
}

	// Set reflink cookie if not present yet.
function reflinkCookie($refLink){
	if (!empty(getReflinkCookie()))
		return;
	setReflinkCookie($refLink);
}

?>

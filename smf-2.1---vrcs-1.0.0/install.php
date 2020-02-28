<?php

// Security Check.
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF')){
	require_once dirname(__FILE__) . '/SSI.php';
}
elseif (!defined('SMF')){
	die('<strong>Unable to install:</strong> Please make sure that you have copied this file in the same location as the index.php of your SMF.');
}

// Load the SMF database functions.
db_extend('packages');

global $db_prefix, $db_server, $db_user, $db_passwd, $db_name;

// Create new table.
$smcFunc['db_create_table']('{db_prefix}vrcs_link', array(
		array(
			'name' => 'id_member',
			'type' => 'mediumint',
			'unsigned' => '1',
			'default' => '0'
		),
		array(
			'name' => 'id_reflink',
			'type' => 'int',
			'unsigned' => '1',
		),
		array(
			'name' => 'resource_name',
			'type' => 'varchar',
			'size' => '32',
			'default' => 'base link name'
		),
		array(
			'name' => 'counter_l1',
			'type' => 'mediumint',
			'unsigned' => '1',
			'default' => '0'
		),
		array(
			'name' => 'posts_l1',
			'type' => 'mediumint',
			'unsigned' => '1',
			'default' => '0'
		),
	),
	array(
		array(
			'type' => 'unique',
			'columns' => array(
				'id_reflink'
			)
		)
	),
	array(),
	'ignore'
);

$smcFunc['db_create_table']('{db_prefix}vrcs_stat', array(
		array(
			'name' => 'id_member',
			'type' => 'mediumint',
			'unsigned' => '1',
			'default' => '0'
		),
		array(
			'name' => 'id_referer',
			'type' => 'int',
			'unsigned' => '1',
			'default' => '0'
		),
		array(
			'name' => 'counter_l2',
			'type' => 'mediumint',
			'unsigned' => '1',
			'default' => '0'
		),
		array(
			'name' => 'posts_l2',
			'type' => 'mediumint',
			'unsigned' => '1',
			'default' => '0'
		),
		array(
			'name' => 'counter_l3',
			'type' => 'mediumint',
			'unsigned' => '1',
			'default' => '0'
		),
		array(
			'name' => 'posts_l3',
			'type' => 'mediumint',
			'unsigned' => '1',
			'default' => '0'
		),
	),
	array(
		array(
			'type' => 'unique',
			'columns' => array(
				'id_member'
			)
		)
	),
	array(),
	'ignore'
);

$smcFunc['db_query']('','
	INSERT INTO {db_prefix}vrcs_stat (id_member) 
	SELECT id_member 
		FROM {db_prefix}members
		WHERE id_member NOT IN (SELECT id_member FROM {db_prefix}vrcs_stat)
	', array()
);

$request = $smcFunc['db_query']('','
	SELECT id_member
	FROM {db_prefix}members
	WHERE id_member NOT IN (SELECT id_member FROM {db_prefix}vrcs_link)
	', array()
);

while ($row = $smcFunc['db_fetch_assoc']($request))
	while (!$smcFunc['db_insert']('ignore',
		'{db_prefix}vrcs_link',
		array('id_member' => 'int', 'id_reflink' => 'int'),
		array($row['id_member'], mt_rand(1, mt_getrandmax())),
		array('id_member', 'id_reflink'),
		1
	));

$smcFunc['db_free_result']($request);

$vrcs_sql = file_get_contents(__DIR__.'/vrcs.sql');
$vrcs_sql = preg_replace ('/smf_/', $db_prefix, $vrcs_sql);
$db_link = mysqli_connect($db_server, $db_user, $db_passwd, $db_name);
mysqli_multi_query($db_link, $vrcs_sql);
mysqli_store_result($db_link);

// Are we done?
if (SMF == 'SSI'){
	echo "Database installation complete!\n";
}

?>
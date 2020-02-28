<?php

// If we have found SSI.php and we are outside of SMF, then we are running standalone.
if (file_exists (dirname (__FILE__) . '/SSI.php') && !defined ('SMF'))
{
	require_once(dirname (__FILE__) . '/SSI.php');
	db_extend ('packages');
}
// If we are outside SMF and can't find SSI.php, then throw an error.
elseif (!defined ('SMF'))
{
	die ('<b>Error:</b> Cannot uninstall - please verify you put this file in the same place as SMF\'s SSI.php.');
}


$smcFunc['db_query']('','DROP TRIGGER IF EXISTS {db_prefix}vrcs_posts', array());
$smcFunc['db_query']('','DROP PROCEDURE IF EXISTS {db_prefix}vrcs_addmembers', array());
$smcFunc['db_drop_table']('{db_prefix}vrcs_link');
$smcFunc['db_drop_table']('{db_prefix}vrcs_stat');
//$smcFunc['db_drop_table']('{db_prefix}vrcs_refstat');
global $modSettings;

// And tell SMF we've updated $modSettings.
updateSettings (array (
	'settings_updated' => time (),
));


// Are we done?
if (SMF == 'SSI')
{
    echo 'Database drop table complete!';
}

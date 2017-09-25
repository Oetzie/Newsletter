<?php

	$settings = array();

    $settings[0] = $modx->newObject('modSystemSetting');
    $settings[0]->fromArray(array(
        'key' 		=> PKG_NAME_LOWER.'.branding_url',
        'value' 	=> 'http://www.oetzie.nl',
        'xtype' 	=> 'textfield',
        'namespace' => PKG_NAME_LOWER,
        'area' 		=> PKG_NAME_LOWER
    ), '', true, true);

    $settings[1] = $modx->newObject('modSystemSetting');
    $settings[1]->fromArray(array(
        'key' 		=> PKG_NAME_LOWER.'.branding_url_help',
        'value' 	=> '//www.werkvanoetzie.nl/extras/newsletter',
        'xtype' 	=> 'textfield',
        'namespace' => PKG_NAME_LOWER,
        'area' 		=> PKG_NAME_LOWER
    ), '', true, true);

	$settings[2] = $modx->newObject('modSystemSetting');
	$settings[2]->fromArray(array(
		'key' 		=> PKG_NAME_LOWER.'.cronjob',
		'value' 	=> '0',
		'xtype' 	=> 'combo-boolean',
		'namespace' => PKG_NAME_LOWER,
		'area' 		=> PKG_NAME_LOWER
	), '', true, true);
	
	$settings[3] = $modx->newObject('modSystemSetting');
	$settings[3]->fromArray(array(
		'key' 		=> PKG_NAME_LOWER.'.email',
		'value' 	=> '',
		'xtype' 	=> 'textfield',
		'namespace' => PKG_NAME_LOWER,
		'area' 		=> PKG_NAME_LOWER
	), '', true, true);
	
	$settings[4] = $modx->newObject('modSystemSetting');
	$settings[4]->fromArray(array(
		'key' 		=> PKG_NAME_LOWER.'.log_email',
		'value' 	=> '',
		'xtype' 	=> 'textfield',
		'namespace' => PKG_NAME_LOWER,
		'area' 		=> PKG_NAME_LOWER
	), '', true, true);
	
	$settings[5] = $modx->newObject('modSystemSetting');
	$settings[5]->fromArray(array(
		'key' 		=> PKG_NAME_LOWER.'.log_lifetime',
		'value' 	=> '7',
		'xtype' 	=> 'textfield',
		'namespace' => PKG_NAME_LOWER,
		'area' 		=> PKG_NAME_LOWER
	), '', true, true);
	
	$settings[6] = $modx->newObject('modSystemSetting');
	$settings[6]->fromArray(array(
		'key' 		=> PKG_NAME_LOWER.'.log_send',
		'value' 	=> '1',
		'xtype' 	=> 'combo-boolean',
		'namespace' => PKG_NAME_LOWER,
		'area' 		=> PKG_NAME_LOWER
	), '', true, true);
	
	$settings[7] = $modx->newObject('modSystemSetting');
	$settings[7]->fromArray(array(
		'key' 		=> PKG_NAME_LOWER.'.name',
		'value' 	=> 'MODX Newsletter',
		'xtype' 	=> 'textfield',
		'namespace' => PKG_NAME_LOWER,
		'area' 		=> PKG_NAME_LOWER
	), '', true, true);
	
	$settings[8] = $modx->newObject('modSystemSetting');
	$settings[8]->fromArray(array(
		'key' 		=> PKG_NAME_LOWER.'.template',
		'value' 	=> '',
		'xtype' 	=> 'textfield',
		'namespace' => PKG_NAME_LOWER,
		'area' 		=> PKG_NAME_LOWER
	), '', true, true);
	
	$settings[9] = $modx->newObject('modSystemSetting');
	$settings[9]->fromArray(array(
		'key' 		=> PKG_NAME_LOWER.'.token',
		'value' 	=> '',
		'xtype' 	=> 'textfield',
		'namespace' => PKG_NAME_LOWER,
		'area' 		=> PKG_NAME_LOWER
	), '', true, true);
	
	return $settings;
	
?>
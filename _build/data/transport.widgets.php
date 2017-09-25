<?php

	$widgets = array();
	
	foreach (glob($sources['widgets'].'/*.php') as $key => $value) {
		$name = str_replace('.widget.php', '', substr($value, strrpos($value, '/') + 1, strlen($value)));

		$widgets[$name] = $modx->newObject('modDashboardWidget');
		$widgets[$name]->fromArray(array(
			'id'			=> 'id',
			'name'			=> PKG_NAME_LOWER.'.widget_'.$name,
			'description'	=> PKG_NAME_LOWER.'.widget_'.$name.'_desc',
			'type'			=> 'file',
			'size' 			=> 'half',
			'content' 		=> '[[++core_path]]components/'.PKG_NAME_LOWER.'/elements/widgets/'.$name.'.widget.php',
			'namespace' 	=> PKG_NAME_LOWER,
			'lexicon' 		=> PKG_NAME_LOWER.':default'
		));
	}
	
	return $widgets;

?>
<?php

	return array(
		array(
	        'name' 		=> 'hash',
	        'desc' 		=> 'newslettercronjob_snippet_hash_desc',
	        'type' 		=> 'textfield',
	        'options' 	=> '',
	        'value'		=> sha1(PKG_NAME_LOWER.strtotime(date('d-m-Y H:i:s'))),
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    ),
	);

?>
<?php

	/**
	 * Newsletter
	 *
	 * Copyright 2016 by Oene Tjeerd de Bruin <info@oetzie.nl>
	 *
	 * This file is part of Newsletter, a real estate property listings component
	 * for MODX Revolution.
	 *
	 * Newsletter is free software; you can redistribute it and/or modify it under
	 * the terms of the GNU General Public License as published by the Free Software
	 * Foundation; either version 2 of the License, or (at your option) any later
	 * version.
	 *
	 * Newsletter is distributed in the hope that it will be useful, but WITHOUT ANY
	 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
	 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License along with
	 * Newsletter; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
	 * Suite 330, Boston, MA 02111-1307 USA
	 */

	$xpdo_meta_map['NewsletterNewslettersDetails']= array(
		'package' 	=> 'newsletter',
		'version' 	=> '1.0',
		'table' 	=> 'newsletter_newsletters_details',
		'extends' 	=> 'xPDOSimpleObject',
		'fields' 	=> array(
			'id'				=> null,
			'newsletter_id'		=> null,
			'lists'				=> null,
			'emails'			=> null,
			'timestamp'			=> null
		),	
		'fieldMeta'	=> array(
			'id' 		=> array(
				'dbtype' 	=> 'int',
				'precision' => '11',
				'phptype' 	=> 'integer',
				'null' 		=> false,
				'index' 	=> 'pk',
				'generated'	=> 'native'
			),
			'newsletter_id' => array(
				'dbtype' 	=> 'int',
				'precision' => '11',
				'phptype' 	=> 'integer',
				'null' 		=> false
			),
			'lists' => array(
				'dbtype' 	=> 'varchar',
				'precision' => '255',
				'phptype' 	=> 'string',
				'null' 		=> false
			),
			'emails' 	=> array(
				'dbtype' 	=> 'text',
				'phptype' 	=> 'string',
				'null' 		=> false
			),
			'timestamp' => array(
				'dbtype' 	=> 'timestamp',
				'phptype' 	=> 'timestamp',
				'null' 		=> false,
				'default'	=> '0000-00-00 00:00:00'
			)
		),
		'indexes'	=> array(
			'PRIMARY'	=> array(
				'alias' 	=> 'PRIMARY',
				'primary' 	=> true,
				'unique' 	=> true,
				'columns' 	=> array(
					'id' 		=> array(
						'collation' => 'A',
						'null' 		=> false,
					)
				)
			)
		),
		'aggregates' => array(
			'NewsletterNewsletters' => array(
				'local' 		=> 'newsletter_id',
				'class' 		=> 'NewsletterNewsletters',
				'foreign' 		=> 'id',
				'owner' 		=> 'foreign',
				'cardinality' 	=> 'one'
			)
		)
	);

?>
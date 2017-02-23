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

	$xpdo_meta_map['NewsletterNewsletters']= array(
		'package' 	=> 'newsletter',
		'version' 	=> '1.0',
		'table' 	=> 'newsletter_newsletters',
		'extends' 	=> 'xPDOSimpleObject',
		'fields' 	=> array(
			'id'			=> null,
			'resource_id'	=> null,
			'send_status'	=> null,
			'send_date'		=> null,
			'send_time'		=> null,
			'send_days'		=> null,
			'send_repeat'	=> null,
			'send_emails'	=> null,
			'hidden'		=> null,
			'editedon' 		=> null
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
			'resource_id' => array(
				'dbtype' 	=> 'int',
				'precision' => '11',
				'phptype' 	=> 'integer',
				'null' 		=> false
			),
			'send_status' => array(
				'dbtype' 	=> 'int',
				'precision' => '1',
				'phptype' 	=> 'integer',
				'null' 		=> false,
				'default'	=> 0
			),
			'send_date' => array(
				'dbtype' 	=> 'date',
				'phptype' 	=> 'date',
				'null' 		=> false,
				'default'	=> '0000-00-00'
			),
			'send_time' => array(
				'dbtype' 	=> 'time',
				'phptype' 	=> 'time',
				'null' 		=> false,
				'default'	=> '00:00:00'
			),
			'send_days' => array(
				'dbtype' 	=> 'varchar',
				'precision' => '75',
				'phptype' 	=> 'string',
				'null' 		=> false
			),
			'send_repeat' => array(
				'dbtype' 	=> 'int',
				'precision' => '11',
				'phptype' 	=> 'integer',
				'null' 		=> false,
				'default'	=> 1
			),
			'send_emails' => array(
				'dbtype' 	=> 'text',
				'precision' => '2048',
				'phptype' 	=> 'string',
				'null' 		=> false
			),
			'hidden'	=> array(
				'dbtype' 	=> 'int',
				'precision' => '1',
				'phptype' 	=> 'integer',
				'null' 		=> false,
				'default'	=> 0
			),
			'editedon' 	=> array(
				'dbtype' 	=> 'timestamp',
				'phptype' 	=> 'timestamp',
				'attributes' => 'ON UPDATE CURRENT_TIMESTAMP',
				'null' 		=> false
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
			'modResource' => array(
				'local'			=> 'resource_id',
				'class' 		=> 'modResource',
				'foreign' 		=> 'id',
				'owner' 		=> 'foreign',
				'cardinality' 	=> 'one'
			),
			'NewsletterListsNewsletters' => array(
				'local' 		=> 'id',
				'class' 		=> 'NewsletterListsNewsletters',
				'foreign'		=> 'newsletter_id',
				'owner' 		=> 'local',
				'cardinality' 	=> 'many'
			),
			'NewsletterNewslettersDetails' => array(
				'local' 		=> 'id',
				'class' 		=> 'NewsletterNewslettersDetails',
				'foreign'		=> 'newsletter_id',
				'owner' 		=> 'local',
				'cardinality' 	=> 'many'
			)
		)
	);

?>
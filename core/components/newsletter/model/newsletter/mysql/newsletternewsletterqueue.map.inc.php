<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

$xpdo_meta_map['NewsletterNewsletterQueue'] = [
    'package'       => 'newsletter',
    'version'       => '1.0',
    'table'         => 'newsletter_newsletter_queue',
    'extends'       => 'xPDOSimpleObject',
    'tableMeta'     => [
        'engine'        => 'InnoDB'
    ],
    'fields'        => [
        'id'            => null,
        'newsletter_id' => null,
        'type'          => null,
        'emails'        => null,
        'date'          => null,
        'days'          => null,
        'repeat'        => null,
        'log'           => null,
        'status'        => null,
        'editedon'      => null
    ],
    'fieldMeta'     => [
        'id'            => [
            'dbtype'        => 'int',
            'precision'     => '11',
            'phptype'       => 'integer',
            'null'          => false,
            'index'         => 'pk',
            'generated'     => 'native'
        ],
        'newsletter_id' => [
            'dbtype'        => 'int',
            'precision'     => '11',
            'phptype'       => 'integer',
            'null'          => false
        ],
        'type'          => [
            'dbtype'        => 'varchar',
            'precision'     => '10',
            'phptype'       => 'string',
            'null'          => false
        ],
        'emails'        => [
            'dbtype'        => 'text',
            'phptype'       => 'string',
            'null'          => true
        ],
        'date'          => [
            'dbtype'        => 'timestamp',
            'phptype'       => 'timestamp',
            'null'          => true,
            'default'       => '0000-00-00 00:00:00'
        ],
        'days'          => [
            'dbtype'        => 'varchar',
            'precision'     => '15',
            'phptype'       => 'string',
            'null'          => true
        ],
        'repeat'        => [
            'dbtype'        => 'int',
            'precision'     => '11',
            'phptype'       => 'integer',
            'null'          => false,
            'default'       => 1
        ],
        'log'           => [
            'dbtype'        => 'text',
            'phptype'       => 'string',
            'null'          => true
        ],
        'status'        => [
            'dbtype'        => 'int',
            'precision'     => '1',
            'phptype'       => 'integer',
            'null'          => true,
            'default'       => 0
        ],
        'editedon'      => [
            'dbtype'        => 'timestamp',
            'phptype'       => 'timestamp',
            'attributes'    => 'ON UPDATE CURRENT_TIMESTAMP',
            'null'          => false,
            'default'       => '0000-00-00 00:00:00'
        ]
    ],
    'indexes'       => [
        'PRIMARY'       => [
            'alias'         => 'PRIMARY',
            'primary'       => true,
            'unique'        => true,
            'columns'       => [
                'id'            => [
                    'collation'     => 'A',
                    'null'          => false
                ]
            ]
        ]
    ],
    'aggregates'    => [
        'Newsletter'    => [
            'local'         => 'newsletter_id',
            'class'         => 'NewsletterNewsletter',
            'foreign'       => 'id',
            'owner'         => 'local',
            'cardinality'   => 'one'
        ],
        'List'      => [
            'local'         => 'id',
            'class'         => 'NewsletterNewsletterQueueList',
            'foreign'       => 'queue_id',
            'owner'         => 'local',
            'cardinality'   => 'many'
        ]
    ]
];

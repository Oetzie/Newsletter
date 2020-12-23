<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

$xpdo_meta_map['NewsletterNewsletter'] = [
    'package'       => 'newsletter',
    'version'       => '1.0',
    'table'         => 'newsletter_newsletter',
    'extends'       => 'xPDOSimpleObject',
    'tableMeta'     => [
        'engine'        => 'InnoDB'
    ],
    'fields'        => array(
        'id'            => null,
        'resource_id'   => null,
        'filter'        => null,
        'hidden'        => null,
        'editedon'      => null
    ),
    'fieldMeta'     => [
        'id'            => [
            'dbtype'        => 'int',
            'precision'     => '11',
            'phptype'       => 'integer',
            'null'          => false,
            'index'         => 'pk',
            'generated'     => 'native'
        ],
        'resource_id'   => [
            'dbtype'        => 'int',
            'precision'     => '11',
            'phptype'       => 'integer',
            'null'          => false
        ],
        'filter'        => [
            'dbtype'        => 'varchar',
            'precision'     => '75',
            'phptype'       => 'string',
            'null'          => true
        ],
        'hidden'        => [
            'dbtype'        => 'int',
            'precision'     => '1',
            'phptype'       => 'integer',
            'null'          => false,
            'default'       => 0
        ],
        'editedon'      => [
            'dbtype'        => 'timestamp',
            'phptype'       => 'timestamp',
            'attributes'    => 'ON UPDATE CURRENT_TIMESTAMP',
            'null'          => false
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
        'modResource'   => [
            'local'         => 'resource_id',
            'class'         => 'modResource',
            'foreign'       => 'id',
            'owner'         => 'foreign',
            'cardinality'   => 'one'
        ],
        'NewsletterListNewsletter' => [
            'local'         => 'id',
            'class'         => 'NewsletterListNewsletter',
            'foreign'       => 'newsletter_id',
            'owner'         => 'local',
            'cardinality'   => 'many'
        ],
        'NewsletterNewsletterDetail' => [
            'local'         => 'id',
            'class'         => 'NewsletterNewsletterDetail',
            'foreign'       => 'newsletter_id',
            'owner'         => 'local',
            'cardinality'   => 'many'
        ]
    ]
];

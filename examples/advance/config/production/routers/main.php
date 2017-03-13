<?php

use Ant\Types\RouterConfig;

return [
    (new RouterConfig())
        ->setName('aloha')
        ->setPath('/')
        ->allowGET()
        ->setHandler(['\AntExample\Application\Controllers\Main', 'alohaAction']),
    'router' => [
        'main' => [
            'GET', '/main/[{name}[/]]', [
                'controller' => 'AntExample\Application\Controllers\Main',
                'action'     => 'mainAction'
            ]
        ]
    ]
];
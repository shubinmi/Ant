<?php

use Ant\Types\RouterConfig;
use AntExample\Application\Controllers\Main;

return [
    'router' => [
        (new RouterConfig())
            ->setName('seo')
            ->setPath('/seo')
            ->allowGET()
            ->setHandler([new Main(), 'seoAction']),
        'main' => [
            ['GET'], '/main/[{name}[/]]', [
                'controller' => 'AntExample\Application\Controllers\Main',
                'action'     => 'mainAction'
            ]
        ],

    ]
];
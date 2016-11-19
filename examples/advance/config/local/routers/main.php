<?php

return [
    'router' => [
        'main' => [
            'GET', '/[{name}[/]]', [
                'controller' => 'AntExample\Application\Controllers\Main',
                'action'     => 'mainAction'
            ]
        ]
    ]
];
<?php

return [
    'router' => [
        'main' => [
            'GET', '/[{name}[/]]', [
                'controller' => 'Application\Controllers\Main',
                'action'     => 'mainAction'
            ]
        ]
    ]
];
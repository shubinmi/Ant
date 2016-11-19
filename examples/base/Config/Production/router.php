<?php

return [
    'router' => [
        'main' => [
            'GET', '/[{name}[/]]', [
                'controller' => 'AntExample\Controllers\Main',
                'action'     => 'mainAction'
            ]
        ]
    ]
];
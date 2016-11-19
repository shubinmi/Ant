<?php

return [
    'router' => [
        'main' => [
            ['GET', 'POST'], '/[{name}[/]]', [
                'controller' => 'AntExample\Controllers\Main',
                'action'     => 'mainAction'
            ]
        ]
    ]
];
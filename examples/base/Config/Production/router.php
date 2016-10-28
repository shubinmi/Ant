<?php

return [
    'router' => [
        'main' => [
            'GET', '/[{name}[/]]', [
                'controller' => 'Controllers\Main',
                'action'     => 'mainAction'
            ]
        ]
    ]
];
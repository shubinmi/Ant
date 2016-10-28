<?php

return [
    'router' => [
        'main' => [
            ['GET', 'POST'], '/[{name}[/]]', [
                'controller' => 'Controllers\Main',
                'action'     => 'mainAction'
            ]
        ]
    ]
];
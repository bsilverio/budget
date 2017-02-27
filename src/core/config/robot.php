<?php

return [
    'directions' => ['N','E','S','W'],
    'movement_values' => [
        'N' => ['y',-1],
        'S' => ['y',1],
        'E' => ['x',1],
        'W' => ['x',-1]
    ],
    'command_values' => [
        'M' => 0,
        'L' => -1,
        'R' => 1
    ]
];

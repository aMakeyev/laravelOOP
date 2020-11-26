<?php

return [
    'create' => [
        'target' => ['required', 'integer', 'min:1'],
        'subject' => ['required', 'min:1', 'max:1000'],
        'body' => ['required', 'min:1', 'max:30000'],
    ],
    'update' => [
        'target' => ['required', 'integer', 'min:1'],
        'subject' => ['required', 'min:1', 'max:1000'],
        'body' => ['required', 'min:1', 'max:30000'],
    ],
];

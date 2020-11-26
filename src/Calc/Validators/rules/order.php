<?php

return [
    'create' => [],
    'update' => [
        'contractor'          => ['required'],
        'status'              => ['required','integer','min:1'],

        'called_at'           => ['date_format:d.m.Y'],
        'next_call_at'        => ['date_format:d.m.Y'],
    ],
];

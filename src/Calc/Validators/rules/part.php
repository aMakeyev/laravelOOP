<?php

return [
    'create' => [
        'title'      => ['required', 'min:3', 'max:255', 'text'],
        'article'    => ['required', 'min:3', 'max:255', 'article', 'unique:parts,article'],
        'group_id'   => ['required', 'integer', 'exists:parts_groups,id'],
        'unit'       => ['required', 'integer', 'min:1'],
        'unit_price' => ['required', 'numeric', 'min:0'],
    ],
    'update' => [
        'title'      => ['required', 'min:3', 'max:255', 'text'],
        'article'    => ['required', 'min:3', 'max:255', 'article', 'unique:parts,article'],
        'group_id'   => ['required', 'integer', 'exists:parts_groups,id'],
        'unit'       => ['required', 'integer', 'min:1'],
        'unit_price' => ['required', 'numeric', 'min:0'],
    ],
];

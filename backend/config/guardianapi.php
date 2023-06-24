<?php

return [
    'api_key' => env('THE_GUARDIAN_API'),
    'base_url' => 'https://content.guardianapis.com/search',
    'param' => 'thumbnail,byline,trailText,headline&page-size=30',

    'options' => [
        'verify' => false,
        'timeout' => 30,
    ],
];

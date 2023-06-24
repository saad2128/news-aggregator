<?php

return [
    'api_key' => env('NYTIMES'),
    'base_url' => 'https://api.nytimes.com/svc/search/v2/articlesearch.json',
    'options' => [
        'verify' => false,
        'timeout' => 30,
    ],
];
<?php

return [
    'api_key' => env('NEWS_API'),
    'base_url' => 'https://newsapi.org/v2/',
    'endpoints' => [
        'top_headlines' => 'top-headlines',
    ],
    'options' => [
        'verify' => false,
        'timeout' => 30,
    ],
];

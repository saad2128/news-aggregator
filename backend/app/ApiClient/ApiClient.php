<?php

namespace App\ApiClient;

use Illuminate\Support\Facades\Http;

class ApiClient
{
    private $options;

    public function __construct($options = [])
    {
        $this->options = $options;
    }

    public function get($url)
    {
        return Http::withOptions($this->options)->get($url);
    }
}
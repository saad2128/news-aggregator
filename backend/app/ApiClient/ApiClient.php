<?php

namespace App\ApiClient;

use Illuminate\Support\Facades\Http;

class ApiClient
{
    private $verify;
    private $timeout;

    public function __construct($verify = true, $timeout = 30)
    {
        $this->verify = $verify;
        $this->timeout = $timeout;
    }

    public function get($url)
    {
        return Http::withOptions(['verify' => $this->verify])->timeout($this->timeout)->get($url);
    }
}
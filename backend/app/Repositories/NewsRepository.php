<?php

namespace App\Repositories;

use App\Models\News;

class NewsRepository
{
    public function checkDuplicateNews($url)
    {
        return News::where('url', $url)->exists();
    }

    public function createNews($newsData)
    {
        return News::create($newsData);
    }
}

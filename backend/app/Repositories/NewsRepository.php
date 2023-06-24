<?php

namespace App\Repositories;

use App\Models\Author;
use App\Models\News;
use App\Models\Source;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class NewsRepository
{
    public function checkDuplicateNews($url)
    {
        return News::where('url', $url)->exists();
    }

    public function createNews($newsData){
        /**
         * Attaching authors
         */
        $author_ids = [];

        $raw_author = Arr::get($newsData, 'raw_author');
        if ( ! empty( $raw_author ) ) {
            $authors = str_replace( ['(and)', '(earlier)'], '', $raw_author );
            $authors = explode(',',str_replace( ['|', 'and', ';'] ,',', $authors));
            //Removing empty value
            $authors = array_filter($authors);

            foreach ( $authors as $author ) {
                $author_slug = Str::slug($author);
                $author_model = Author::firstOrCreate(['author_slug' => $author_slug], ['author_slug' => $author_slug, 'author_name' => $author]);
                $author_ids[] = $author_model->id;
            }
        }


        $news = News::query()->create($newsData);
        if ( count( $author_ids ) ) {
            $news->author()->attach($author_ids);
        }
        return $news;
    }
}

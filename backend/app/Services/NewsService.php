<?php

namespace App\Services;

use App\Models\Author;
use App\Models\News;
use App\Models\Source;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Repositories\NewsRepository;
use App\ApiClient\ApiClient;
use Illuminate\Support\Facades\Config;

class NewsService
{
    private $newsRepository;
    private $apiClient;

    public function __construct(NewsRepository $newsRepository, ApiClient $apiClient)
    {
        $this->newsRepository = $newsRepository;
        $this->apiClient = $apiClient;
    }

    public function getFromNewsAPI()
    {
        try {
            $newsAPIConfig = Config::get('newsapi');
            $apiKey = $newsAPIConfig['api_key'];
            $baseUrl = $newsAPIConfig['base_url'];
            $endpoint = $newsAPIConfig['endpoints']['top_headlines'];

            $newsAPIUrl = $baseUrl . $endpoint . '?country=us&pageSize=30&apiKey=' . $apiKey;

            $newsAPIHttp = $this->apiClient->get($newsAPIUrl);

            if (!$newsAPIHttp->ok()) {
                throw new \Exception('Failed to fetch news from NewsAPI.');
            }

            $results = json_decode($newsAPIHttp->body(), true);

            foreach ($results['articles'] as $article) {
                $article_url = $article['url'];

                //Checking duplicate entries.
                $found_duplicate = News::query()->select('id')->where('url', $article_url)->first();
                if ($found_duplicate) {
                    continue;
                }

                $newsData = [
                    'title' => $article['title'],
                    'slug' => Str::slug($article['title']),
                    'description' => $article['description'],
                    'url' => $article['url'],
                    'url_to_image' => $article['urlToImage'],
                    'content' => $article['content'],
                    'published_at' => Carbon::parse($article['publishedAt']),
                    'apiSource' => 'NewsAPI',
                ];

                /**
                 * Adding source
                 */

                $source_slug = Arr::get($article, 'source.id');
                $source_name = Arr::get($article, 'source.name');
                if (empty($source_slug)) {
                    $source_slug = Str::slug($source_name);
                }

                $source_model = Source::firstOrCreate(['source_slug' => $source_slug],
                    ['source_slug' => $source_slug, 'source' => $source_name]);

                $newsData['raw_author'] = Arr::get($article, 'author');
                $newsData['source_id'] = $source_model->id;
                $this->newsRepository->createNews($newsData);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error in getFromNewsAPI: ' . $e->getMessage());
            return false;
        }
    }

    public function getFromGuardian()
    {
        try {
            $guardianAPIConfig = Config::get('guardianapi');
            $apiKey = $guardianAPIConfig['api_key'];
            $baseUrl = $guardianAPIConfig['base_url'];
            $param = $guardianAPIConfig['param'];
            $guardianAPIUrl = $baseUrl . '?api-key=' . $apiKey . '&show-fields=' . $param;
            $guardianAPIHttp = $this->apiClient->get($guardianAPIUrl);

            if (!$guardianAPIHttp->ok()) {
                throw new \Exception('Failed to fetch news from The Guardian.');
            }

            $results = json_decode($guardianAPIHttp->body(), true);

            $source_model = Source::firstOrCreate(['source_slug' => 'the-guardian'],
                ['source_slug' => 'the-guardian', 'source' => 'The Guardian']);

            foreach ($results['response']['results'] as $article) {
                $article_url = $article['webUrl'];

                //Checking duplicate entries.
                $found_duplicate = News::query()->select('id')->where('url', $article_url)->first();
                if ($found_duplicate) {
                    continue;
                }

                $title = Arr::get($article, 'fields.headline');
                $description = Arr::get($article, 'fields.trailText');
                $thumbnail = Arr::get($article, 'fields.thumbnail');

                $newsData = [
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'description' => $description,
                    'url' => $article_url,
                    'url_to_image' => $thumbnail,
                    'published_at' => Carbon::parse($article['webPublicationDate']),
                    'apiSource' => 'TheGuardian',
                ];

                $newsData['raw_author'] = Arr::get($article, 'fields.byline');
                $newsData['source_id'] = $source_model->id;

                $this->newsRepository->createNews($newsData);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error in getFromGuardian: ' . $e->getMessage());
            return false;
        }
    }

    public function getFromNyTimes()
    {
        try {
            $nytimesAPIConfig = Config::get('nytimesapi');
            $apiKey = $nytimesAPIConfig['api_key'];
            $baseUrl = $nytimesAPIConfig['base_url'];

            $nytimesAPIUrl = $baseUrl . '?api-key=' . $apiKey;

            $nyTimesAPIHttp = $this->apiClient->get($nytimesAPIUrl);

            if (!$nyTimesAPIHttp->ok()) {
                throw new \Exception('Failed to fetch news from The New York Times');
            }

            $results = json_decode($nyTimesAPIHttp->body(), true);

            $source_name = 'The New York Times';
            $source_slug = Str::slug($source_name);
            $source_model = Source::firstOrCreate(
                ['source_slug' => $source_slug],
                ['source_slug' => $source_slug, 'source' => $source_name]
            );

            foreach ($results['response']['docs'] as $article) {
                $article_url = $article['web_url'];

                //Checking duplicate entries.
                $found_duplicate = $this->newsRepository->checkDuplicateNews($article_url);
                if ($found_duplicate) {
                    continue;
                }

                $title = Arr::get($article, 'headline.main');
                $description = Arr::get($article, 'abstract');
                $thumbnail = '';
                if (!empty($article['multimedia'])) {
                    foreach ($article['multimedia'] as $media) {
                        if (Arr::get($media, 'format') === 'thumbnail') {
                            $thumbnail = $media['url'];
                            break;
                        }
                    }
                }

                $newsData = [
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'description' => $description,
                    'url' => $article_url,
                    'url_to_image' => $thumbnail,
                    'published_at' => Carbon::parse($article['pub_date']),
                    'apiSource' => 'NyTimes',
                ];

                $newsData['raw_author'] = Arr::get($article, 'byline.original');
                $newsData['source_id'] = $source_model->id;
                $this->newsRepository->createNews($newsData);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error in getFromNyTimes: ' . $e->getMessage());
            return false;
        }
    }


}

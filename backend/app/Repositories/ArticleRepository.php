<?php

namespace App\Repositories;

use App\Models\Author;
use App\Models\News;
use App\Models\Source;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ArticleRepository
{
    public function getArticles($search, $authors, $sources)
    {
        $articlesQuery = News::query();

        $this->applySearchFilters($articlesQuery, $search);
        $this->applyAuthorFilters($articlesQuery, $authors);
        $this->applySourceFilters($articlesQuery, $sources);
        $this->applyPreferredFilters($articlesQuery);

        return $articlesQuery->with('source')
            ->orderBy('published_at', 'desc')
            ->paginate(10, ['*'], 'page');
    }

    private function applyPreferredFilters($query)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $preference = json_decode($user->preference, true);
            $preferredAuthors = (array)Arr::get($preference, 'authors');
            $preferredSources = (array)Arr::get($preference, 'sources');

            if (count($preferredAuthors) || count($preferredSources)) {
                $query->where(function ($subQuery) use ($preferredAuthors, $preferredSources) {
                    $subQuery->whereHas('author', function ($authorQuery) use ($preferredAuthors) {
                        $authorQuery->whereIn('authors.id', $preferredAuthors);
                    })->orWhereHas('source', function ($sourceQuery) use ($preferredSources) {
                        $sourceQuery->whereIn('sources.id', $preferredSources);
                    });
                });
            }
        }
    }

    private function applySearchFilters($query, $search)
    {
        if (!empty($search)) {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
    }

    private function applyAuthorFilters($query, $authors)
    {
        if (count($authors)) {
            $query->whereHas('author', function ($subQuery) use ($authors) {
                $subQuery->whereIn('authors.id', $authors);
            });
        }
    }

    private function applySourceFilters($query, $sources)
    {
        if (count($sources)) {
            $query->whereHas('source', function ($subQuery) use ($sources) {
                $subQuery->whereIn('sources.id', $sources);
            });
        }
    }

    public function getAuthors($search)
    {
        $authorsQuery = Author::query();

        $user = Auth::user();
        if ($user) {
            $preferredAuthorIds = $user->preferred_author_ids;

            if (is_array($preferredAuthorIds) && count($preferredAuthorIds)) {
                $authorsQuery->whereIn('id', $preferredAuthorIds);
            }
        }

        if (!empty($search)) {
            $authorsQuery->where('author_name', 'LIKE', "%{$search}%");
        }

        return $authorsQuery->orderBy('author_name')->simplePaginate(10, ['*']);
    }

    public function getSources($search)
    {
        $sourcesQuery = Source::query();

        $user = Auth::user();
        if ($user) {
            $preferredSourceIds = $user->preferred_source_ids;

            if (is_array($preferredSourceIds) && count($preferredSourceIds)) {
                $sourcesQuery->whereIn('id', $preferredSourceIds);
            }
        }

        if (!empty($search)) {
            $sourcesQuery->where('source', 'LIKE', "%{$search}%");
        }

        return $sourcesQuery->orderBy('source')->simplePaginate(10, ['*'], 'page');
    }
}

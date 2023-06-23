<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\News;
use App\Models\Source;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
      public function getArticles(Request $request)
    {
        $articlesQuery = News::query();
        $search = $request->search;
        Log::info('Value of $search: ' . json_encode($search));
        $authors = $this->getFilteredList($request->authors);
        $sources = $this->getFilteredList($request->sources);

        $this->applySearchFilters($articlesQuery, $search);
        $this->applyAuthorFilters($articlesQuery, $authors);
        $this->applySourceFilters($articlesQuery, $sources);
        $this->applyPreferredFilters($articlesQuery, $request);

        $results = $articlesQuery->with('source')
            ->orderBy('published_at', 'desc')
            ->paginate(10, ['*'], 'page');

        return response()->json([
            'status' => true,
            'results' => $results,
        ]);
    }

    private function applyPreferredFilters($query, $request)
    {
        if (Auth::check()) {
            $preference = json_decode($request->user()->preference, true);
            $preferredAuthors = (array) Arr::get($preference, 'authors');
            $preferredSources = (array) Arr::get($preference, 'sources');

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

    private function getFilteredList($list)
    {
        return array_filter(explode(',', $list));
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

    public function getAuthors(Request $request)
    {
        $authorsQuery = Author::query();

        $user = $request->user();
        if ($user) {
            $preferredAuthorIds = $user->preferred_author_ids;

            if (is_array($preferredAuthorIds) && count($preferredAuthorIds)) {
                $authorsQuery->whereIn('id', $preferredAuthorIds);
            }
        }

        $search = $request->search;
        if (!empty($search)) {
            $authorsQuery->where('author_name', 'LIKE', "%{$search}%");
        }

        $authors = $authorsQuery->orderBy('author_name')->simplePaginate(10, ['*']);

        return response()->json([
            'status' => true,
            'results' => $authors
        ]);
    }

    public function getSources(Request $request)
    {
        $sourcesQuery = Source::query();

        $user = $request->user();
        if ($user) {
            $preferredSourceIds = $user->preferred_source_ids;

            if (is_array($preferredSourceIds) && count($preferredSourceIds)) {
                $sourcesQuery->whereIn('id', $preferredSourceIds);
            }
        }

        $search = $request->search;
        if (!empty($search)) {
            $sourcesQuery->where('source', 'LIKE', "%{$search}%");
        }

        $sources = $sourcesQuery->orderBy('source')->simplePaginate(10, ['*'], 'page');

        return response()->json([
            'status' => true,
            'results' => $sources
        ]);
    }


}

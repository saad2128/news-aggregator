<?php

namespace App\Services;

use App\Repositories\ArticleRepository;
use Illuminate\Support\Facades\Log;

class ArticleService
{
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function getArticles($request)
    {
        try {
            $search = $request->search;
            $authors = $this->getFilteredList($request->authors);
            $sources = $this->getFilteredList($request->sources);

            $articles = $this->articleRepository->getArticles($search, $authors, $sources);

            return [
                'status' => true,
                'results' => $articles,
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving articles: ' . $e->getMessage());

            return [
                'status' => false,
                'message' => 'Failed to retrieve articles.',
            ];
        }
    }

    private function getFilteredList($list)
    {
        return array_filter(explode(',', $list));
    }

    public function getAuthors($request)
    {
        try {
            $search = $request->search;
            $authors = $this->articleRepository->getAuthors($search);

            return [
                'status' => true,
                'results' => $authors,
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving authors: ' . $e->getMessage());

            return [
                'status' => false,
                'message' => 'Failed to retrieve authors.',
            ];
        }
    }

    public function getSources($request)
    {
        try {
            $search = $request->search;
            $sources = $this->articleRepository->getSources($search);

            return [
                'status' => true,
                'results' => $sources,
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving sources: ' . $e->getMessage());

            return [
                'status' => false,
                'message' => 'Failed to retrieve sources.',
            ];
        }
    }
}

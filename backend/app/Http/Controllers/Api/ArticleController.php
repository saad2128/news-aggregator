<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ArticleService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    private $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function getArticles(Request $request)
    {
        $response = $this->articleService->getArticles($request);

        if ($response['status']) {
            return response()->json([
                'status' => true,
                'results' => $response['results'],
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => $response['message'],
            ], 500);
        }
    }

    public function getAuthors(Request $request)
    {
        $response = $this->articleService->getAuthors($request);

        if ($response['status']) {
            return response()->json([
                'status' => true,
                'results' => $response['results'],
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => $response['message'],
            ], 500);
        }
    }

    public function getSources(Request $request)
    {
        $response = $this->articleService->getSources($request);

        if ($response['status']) {
            return response()->json([
                'status' => true,
                'results' => $response['results'],
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => $response['message'],
            ], 500);
        }
    }
}

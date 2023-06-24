<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\News;
use App\Models\Source;
use App\Services\NewsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class FetchNewsController extends Controller
{

    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function fetchNews(): JsonResponse
    {
        try {
            $successNewsAPI = $this->newsService->getFromNewsAPI();
            $successGuardian = $this->newsService->getFromGuardian();
            $successNyTimes = $this->newsService->getFromNyTimes();

            if ($successNewsAPI && $successGuardian && $successNyTimes) {

                return response()->json(['success' => true]);

            } else {

                return response()->json(['success' => false, 'message' => 'Failed to fetch news.']);
            }
        } catch (\Exception $e) {
            Log::error('Error in fetchNews: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while fetching news.']);
        }
    }

}

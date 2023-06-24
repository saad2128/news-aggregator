<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PreferencesService;
use Illuminate\Http\Request;

class PreferencesController extends Controller
{
    private $preferencesService;

    public function __construct(PreferencesService $preferencesService)
    {
        $this->preferencesService = $preferencesService;
    }

    /**
     * Get all authors and sources for the preferences.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPreferencesPageResources(Request $request)
    {
        try {
            $resources = $this->preferencesService->getPreferencesPageResources($request->user());

            return response()->json([
                'status' => true,
                'results' => $resources,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve preferences page resources.',
            ], 500);
        }
    }

    public function savePreferences(Request $request)
    {
        try {
            $this->preferencesService->savePreferences($request->user(), $request->all());

            return response()->json([
                'status' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to save preferences.',
            ], 500);
        }
    }
}

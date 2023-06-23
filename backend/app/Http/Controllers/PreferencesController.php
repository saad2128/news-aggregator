<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Source;
use App\Models\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class PreferencesController extends Controller
{

    /**
     * Get all authors and sources for the preferences
     * Note: there is many ways to optimize.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPreferencesPageResources(Request $request){
        $four_hours_in_secs = 60 * 60 * 4;

        $authors = Cache::remember('authors', $four_hours_in_secs, function () {
            return Author::orderBy('author_name', 'asc')->get();
        });

        $sources = Cache::remember('sources', $four_hours_in_secs, function () {
            return Source::orderBy('source', 'asc')->get();
        });

        $preference = $request->user()->preference;

        return response()->json([
            'status' => true,
            'results' => [
                'authors' => $authors,
                'sources' => $sources,
                'preference' => $preference ? $preference : '{}',
            ]
        ]);
    }

    public function savePreferences( Request $request ){
        $fields = $request->all();

        if ( ! is_array( Arr::get( $fields, 'authors' ) ) ) {
            $fields['authors'] = [];
        }

        if ( ! is_array( Arr::get( $fields, 'sources' ) ) ) {
            $fields['sources'] = [];
        }

        UserMeta::query()->updateOrCreate(
            [ 'user_id' => $request->user()->id, 'meta_key' => 'preference' ],
            [ 'meta_value' => json_encode($fields) ],
        );

        return response()->json([
            'status' => true,
        ]);
    }

}

<?php

namespace App\Repositories;

use App\Models\Author;
use App\Models\Source;
use App\Models\UserMeta;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class PreferencesRepository
{
    public function getPreferencesPageResources($user)
    {
        $four_hours_in_secs = 60 * 60 * 4;

        $authors = Cache::remember('authors', $four_hours_in_secs, function () {
            return Author::orderBy('author_name', 'asc')->get();
        });

        $sources = Cache::remember('sources', $four_hours_in_secs, function () {
            return Source::orderBy('source', 'asc')->get();
        });

        $preference = $user->preference;

        return [
            'authors' => $authors,
            'sources' => $sources,
            'preference' => $preference ? $preference : '{}',
        ];
    }

    public function savePreferences($user, $fields)
    {
        if (!is_array(Arr::get($fields, 'authors'))) {
            $fields['authors'] = [];
        }

        if (!is_array(Arr::get($fields, 'sources'))) {
            $fields['sources'] = [];
        }

        UserMeta::query()->updateOrCreate(
            ['user_id' => $user->id, 'meta_key' => 'preference'],
            ['meta_value' => json_encode($fields)]
        );
    }
}

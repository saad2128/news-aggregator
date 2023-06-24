<?php

namespace App\Services;

use App\Repositories\PreferencesRepository;
use App\Models\User;

class PreferencesService
{
    private $preferencesRepository;

    public function __construct(PreferencesRepository $preferencesRepository)
    {
        $this->preferencesRepository = $preferencesRepository;
    }

    public function getPreferencesPageResources(User $user)
    {
        return $this->preferencesRepository->getPreferencesPageResources($user);
    }

    public function savePreferences(User $user, array $fields)
    {
        return $this->preferencesRepository->savePreferences($user, $fields);
    }
}

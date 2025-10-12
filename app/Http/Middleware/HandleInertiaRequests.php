<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Laravel\Jetstream\Jetstream;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => fn () => $request->user()?->load('role'),
            ],
            'jetstream' => [
                'hasTeamFeatures' => Jetstream::hasTeamFeatures(),
                'canCreateTeams' => Jetstream::hasTeamFeatures() && $request->user()?->can('create', Jetstream::newTeamModel()),
            ],
        ]);
    }
}

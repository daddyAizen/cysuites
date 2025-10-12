<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Laravel\Jetstream\Jetstream;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that’s loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     */
    public function share(Request $request): array
    {
        // Detect which guard is authenticated
        $user = auth('web')->user();    // Normal users (staff/admin)
        $guest = auth('guest')->user(); // Guests

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => fn () => $user
                    ? $user->load('role') // If user logged in under 'web'
                    : $guest,             // Otherwise load guest data
            ],

            'jetstream' => [
                'hasTeamFeatures' => Jetstream::hasTeamFeatures(),
                'canCreateTeams' => Jetstream::hasTeamFeatures()
                    && $user
                    && $user->can('create', Jetstream::newTeamModel()),
            ],
        ]);
    }
}

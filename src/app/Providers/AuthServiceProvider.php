<?php

namespace App\Providers;

use App\Models\Crew;
use App\Models\Refugee;
use App\Models\Team;
use App\Policies\CrewPolicy;
use App\Policies\RefugeePolicy;
use App\Policies\TeamPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
        Refugee::class => RefugeePolicy::class,
        Crew::class => CrewPolicy::class,
        ApiLog::class => ApiLogPolicy::class,
        Field::class => FieldPolicy::class,
        User::class => UserPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}

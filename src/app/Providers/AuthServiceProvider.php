<?php

namespace App\Providers;

use App\Models\ApiLog;
use App\Models\Crew;
use App\Models\Cytoscape;
use App\Models\Event;
use App\Models\Field;
use App\Models\ListControl;
use App\Models\Permission;
use App\Models\Refugee;
use App\Models\Role;
use App\Models\Source;
use App\Models\User;
use App\Policies\ApiLogPolicy;
use App\Policies\CrewPolicy;
use App\Policies\CytoscapePolicy;
use App\Policies\EventPolicy;
use App\Policies\FieldPolicy;
use App\Policies\ListControlPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RefugeePolicy;
use App\Policies\RolePolicy;
use App\Policies\SourcePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Refugee::class => RefugeePolicy::class,
        Crew::class => CrewPolicy::class,
        ApiLog::class => ApiLogPolicy::class,
        Field::class => FieldPolicy::class,
        User::class => UserPolicy::class,
        ListControl::class => ListControlPolicy::class,
        Cytoscape::class => CytoscapePolicy::class,
        Permission::class => PermissionPolicy::class,
        Role::class => RolePolicy::class,
        Event::class => EventPolicy::class,
        Source::class => SourcePolicy::class,
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

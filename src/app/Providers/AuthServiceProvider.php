<?php

namespace App\Providers;

use App\Models\Crew;
use App\Models\Cytoscape;
use App\Models\Event;
use App\Models\Field;
use App\Models\ListControl;
use App\Models\Permission;
use App\Models\Place;
use App\Models\Refugee;
use App\Models\Role;
use App\Models\Source;
use App\Models\User;
use App\Policies\CrewPolicy;
use App\Policies\CytoscapePolicy;
use App\Policies\EventPolicy;
use App\Policies\FieldPolicy;
use App\Policies\ListControlPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\PlacePolicy;
use App\Policies\RefugeePolicy;
use App\Policies\RolePolicy;
use App\Policies\SourcePolicy;
use App\Policies\UserPolicy;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event as EventFacade;
use Illuminate\Support\Facades\Log;

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
        Field::class => FieldPolicy::class,
        User::class => UserPolicy::class,
        ListControl::class => ListControlPolicy::class,
        Cytoscape::class => CytoscapePolicy::class,
        Permission::class => PermissionPolicy::class,
        Role::class => RolePolicy::class,
        Event::class => EventPolicy::class,
        Source::class => SourcePolicy::class,
        Place::class => PlacePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


        EventFacade::listen(function (Login $event) {
            Log::info("User login", [
                "tag" => "user_event",
                "type" => "login",
                "user_id" => $event->user->getAuthIdentifier(),
                "crew_id" => $event->user->crew->id ?? null,
                "ip" => request()->ip(),
            ]);
        });
        EventFacade::listen(function (Logout $event) {
            Log::info("User logout", [
                "tag" => "user_event",
                "type" => "logout",
                "user_id" => $event->user->getAuthIdentifier(),
                "crew_id" => $event->user->crew->id ?? null,
                "ip" => request()->ip(),
            ]);
        });
        EventFacade::listen(function (Failed $event) {
            Log::info("User login fail", [
                "tag" => "user_event",
                "type" => "failed",
                "user_id" => $event->user?->getAuthIdentifier(),
                "crew_id" => $event->user?->crew->id ?? null,
                "ip" => request()->ip(),
            ]);
        });
    }
}

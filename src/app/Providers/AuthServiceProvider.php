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
use App\Services\LogHelper;
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

        // Logging user logins/logout
        EventFacade::listen(function (Login $event) {
            $logContext = LogHelper::getLogContext('user_event', 'login', false);
            $logDetails = [
                "user_id" => $event->user->getAuthIdentifier() ?? "unknown",
                "crew_id" => $event->user->crew->id ?? "unknown",
            ];
            Log::info("User login", array_merge($logContext, $logDetails));
        });
        EventFacade::listen(function (Logout $event) {
            $logContext = LogHelper::getLogContext('user_event', 'logout', false);
            $logDetails = [
                "user_id" => $event->user->getAuthIdentifier() ?? "unknown",
                "crew_id" => $event->user->crew->id ?? "unknown",
            ];
            Log::info("User logout", array_merge($logContext, $logDetails));
        });
        EventFacade::listen(function (Failed $event) {
            $logContext = LogHelper::getLogContext('user_event', 'logout', false);
            $logDetails = [
                "user_id" => $event->user?->getAuthIdentifier() ?? "unknown",
                "crew_id" => $event->user?->crew->id ?? "unknown",
            ];
            Log::info("User login fail", array_merge($logContext, $logDetails));
        });
    }
}

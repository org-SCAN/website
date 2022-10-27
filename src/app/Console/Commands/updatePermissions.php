<?php

namespace App\Console\Commands;

use App\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class updatePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add the new route permissions to the Permissions table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $routesWithPermissions = Permission::getRoutesWithPermission();
        $routesList = Route::getRoutes()->get();
        $routes = array();
        foreach ($routesList as $route) {
            if ($route->uri != '/' && array_key_exists('as', $route->action)) {
                $route_name = $route->action["as"];
                $route_base = explode(".", $route_name)[0];
                if (in_array($route_base, $routesWithPermissions)) {
                    array_push($routes, $route->action["as"]);
                }
            }
        }

        foreach ($routes as $value) {
            if (!Permission::where('controller_route', $value)->exists()) {
                Permission::create(
                    [
                        'controller_route' => $value,
                        'policy_route' => Permission::getPolicyRouteNameFromRouteName($value)
                    ]
                );
            }
        }


        return Command::SUCCESS;
    }
}

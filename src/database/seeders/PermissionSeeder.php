<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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
            Permission::create(
                [
                    'controller_route' => $value,
                    'policy_route' => Permission::getPolicyRouteNameFromRouteName($value)
                ]
            );
        }
    }
}

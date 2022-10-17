<?php

namespace Database\Seeders;


use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $obj_json = file_get_contents(config('jsonDataset.path') . "/" . "roles" . ".json");
        // interpret the json format as an array
        $array_json = json_decode($obj_json, true);

        // make the inserts
        foreach ($array_json as $role) {
            $created_role = Role::create($role["role"]);
            if ($role["permissions"] == "*") {
                $role["permissions"] = Permission::all();
            }
            foreach ($role["permissions"] as $permission) {
                if (!($permission instanceof Permission)) {
                    $permission = Permission::firstWhere("controller_route", $permission);
                }
                $created_role->permissions()->attach($permission->id);
            }
        }
    }
}

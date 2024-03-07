<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use Illuminate\View\View;

class RoleController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Role::class, 'role');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $roles = Role::orderBy("name")->get();
        return view("roles.index", compact("roles"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRoleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRoleRequest $request)
    {
        $role = $request->validated();
        $created_role = Role::create($role["role"]);
        $created_role->permissions()->attach($role["permissions"] ?? []);
        return redirect()->route("roles.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $route_bases = Role::getRouteBases();
        $sorted_permissions = Role::getSortedPermisisons($route_bases);
        return view("roles.create", compact("sorted_permissions", "route_bases"));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Role $role
     * @return View
     */
    public function show(Role $role)
    {
        return view("roles.show", compact("role"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Role $role
     * @return View
     */
    public function edit(Role $role)
    {
        $route_bases = Role::getRouteBases();
        $sorted_permissions = Role::getSortedPermisisons($route_bases);
        return view("roles.edit", compact("sorted_permissions", "role", "route_bases"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRoleRequest $request
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $upd = $request->validated();
        // if permissions are not set, set them to an empty array
        $upd["permissions"] = $upd["permissions"] ?? [];
        $db_permissions = $role->permissions->pluck("id")->toArray();
        $to_attach = array_diff($upd["permissions"], $db_permissions);
        $to_detach = array_diff($db_permissions, $upd["permissions"]);
        $role->update($upd["role"]);
        $role->permissions()->attach($to_attach);
        $role->permissions()->detach($to_detach);
        return redirect()->route("roles.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route("roles.index");
    }
}

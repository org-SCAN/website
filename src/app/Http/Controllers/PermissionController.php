<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\View\View;

class PermissionController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Permission::class, 'permission');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $permissions = Permission::all();
        return view("permission.index", compact("permissions"));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Permission $permission
     * @return View
     */
    public function show(Permission $permission)
    {
        return view("permission.show", compact("permission"));
    }
}

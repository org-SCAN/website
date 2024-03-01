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
        $this->authorizeResource(Permission::class, 'permissions');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $permissions = Permission::all();
        return view("permissions.index", compact("permissions"));
    }
}

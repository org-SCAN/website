<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUsersRequest;
use App\Models\Field;
use App\Models\Link;
use App\Models\Refugee;
use App\Models\User;
use App\Models\UserRole;
use DB;
use Laravel\Jetstream\Jetstream;


class ManageUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '405 Forbidden');

        $users = User::all();
        return view("users.index", compact("users"));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       // abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

       // $roles = User::pluck('title', 'id');

        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        //abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        User::create($request->validated());
        return redirect()->route('users.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(String $id)
    {
        //abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = User::find($id);
        $role = $user->role;
        $roles = UserRole::all();
        return view("users.show", compact("user","role","roles"));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @param $request
     * @param $user
     * @return \Illuminate\Http\Response
     */
    public function edit( $user)
    {
        //abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_found = User::find($user);

        return view("users.edit", compact("user_found"));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @param $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUsersRequest $request, $id)
    {
        $id->update($request->validated());
        //$user->roles()->sync($request->input('roles', []));

        return redirect()->route('users.index');

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id->delete();

       return redirect()->route('users.index');
    }
}

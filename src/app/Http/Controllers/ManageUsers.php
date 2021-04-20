<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUsers;
use App\Models\Role;
use App\Models\User;


class ManageUsers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '405 Forbidden');

        $users = User::with('roles')->get();

        return view('users.index', compact('users'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       // abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {
        //abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('users.show', compact('user'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('users.show', compact('user'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @param $request
     * @param $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $request, $user)
    {
        //abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        $user->load('roles');

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @param $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUsers $request, $id, $user)
    {
        $user->update($request->validated());
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('users.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $user)
    {
        //abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return redirect()->route('users.index');
    }
}

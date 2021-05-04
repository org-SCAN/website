<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUsersRequest;
use App\Models\User;


class ManageUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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


        $user = User::find($id);

        return view("users.show", compact("user"));

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

        $user = User :: where("id",$id);
        $user ->delete();
        $users = User::all();
        return view("users.index", compact("users"));
    }
}

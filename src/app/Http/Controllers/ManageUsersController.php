<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUsersRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


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
        return view("user.index", compact("users"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create');
    }


    /**
     * Create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function store(StoreUserRequest $request)
    {
        $user = $request->validated();
        DB::transaction(function () use ($user) {
            return tap(User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
                'role' => $user['role'],
            ]), function (User $created_user) {
                $this->createTeam($created_user);
                $created_user->genToken();
                $created_user->genRole();
            });
        });

        return redirect()->route('user.index');
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
        return view("user.show", compact("user"));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @param $request
     * @param $user
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        $user_found = User::find($id);
        return view("user.edit", compact("user_found"));

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
       // $id->update($request->validated());
        //$user->roles()->sync($request->input('roles', []));

        $user = $request->validated();

        User::find($id)
            ->update($user);

        return redirect()->route('user.index');

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
        return view("user.index", compact("users"));
    }
}

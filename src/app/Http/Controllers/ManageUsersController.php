<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUsersRequest;
use App\Models\RoleRequest;
use App\Models\Team;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class ManageUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = User::all();
        $request_roles = RoleRequest::where("granted", null)->get();
        return view("user.index", compact("users", "request_roles"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('user.create');
    }


    /**
     * Create a personal team for the user.
     *
     * @param User $user
     * @return void
     */
    protected function createTeam(User $user)
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }

    /**
     * Create a newly registered user.
     *
     * @param array $input
     * @return User
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
                $created_user->genToken();
                $created_user->genRole();
            });
        });

        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(String $id)
    {
        $user = User::find($id);
        $roles = UserRole::all();
        return view("user.show", compact("user", "roles"));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @param $request
     * @param $user
     * @return Response
     */
    public function edit( $id)
    {
        $user_found = User::find($id);
        return view("user.edit", compact("user_found"));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @param $user
     * @return Response
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
     * @return Response
     */
    public function destroy($id)
    {

        $user = User:: where("id", $id);
        $user->delete();
        $users = User::all();
        return view("user.index", compact("users"));
    }


    /**
     * Add user role request to the request list
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function RequestRole(Request $request, $id)
    {

        $role = $request->input('role');
        $user = User::find($id);

        if ($user->getRoleid() == $role) {
            return redirect()->back();
        }
        RoleRequest::create(['user' => $user->id, 'role' => $role]);
        return redirect()->back();
    }

    /**
     * Grant the user role request
     *
     * @param $id
     * @return RedirectResponse
     */
    public function GrantRole($id)
    {
        $request = RoleRequest::find($id);
        $user = User::find($request->getUserId());
        $user->update(["role" => $request->getRoleId()]);
        $request->update(["granted" => date("Y-m-d H:i:s")]);

        return redirect()->back();
    }

    /**
     * Grant the user role request
     *
     * @param $id
     * @return RedirectResponse
     */
    public function RejectRole($id)
    {
        $request = RoleRequest::find($id);
        $request->update(["granted" => date("Y-m-d H:i:s")]);

        return redirect()->back();
    }
}

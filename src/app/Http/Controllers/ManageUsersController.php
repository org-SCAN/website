<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequestRoleRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateCrewRequest;
use App\Http\Requests\UpdateUsersRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Models\Role;
use App\Models\RoleRequest;
use App\Models\User;
use App\Notifications\InviteUserNotification;
use App\Rules\NotLastMoreImportantRole;
use App\Rules\NotLastUser;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class ManageUsersController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->authorizeResource(User::class,
            'user');
    }


    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index() {
        //get all users and group them by crew_id
        $users = User::orderBy('crew_id')->orderBy('name')->get();


        $request_roles = RoleRequest::where("granted",
            null)->get();
        return view("user.index",
            compact("users",
                "request_roles"));
    }

    /**
     * Create a newly registered user.
     *
     * @param  array  $input
     * @return RedirectResponse
     */
    public function store(StoreUserRequest $request) {
        $user = $request->validated();

        // if the user is invited, we don't need to set a password, but we do need to send an ivitation email
        if (isset($user["invite"])) {
            // create a random password
            $user["password"] = Str::random(25);
        }

        $created_user = User::create([
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => Hash::make($user['password']),
            'role_id' => $user['role'],
            "crew_id" => $user['team'],
        ]);

        $created_user->genToken();
        $created_user->genRole();
        // send the invitation email
        if (isset($user["invite"])) {
            //send a resert password email
            //ddd($created_user);
            $created_user->notify(new InviteUserNotification(Auth::user()));
        }

        return redirect()->route('user.index')->with('success',
            'User created successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create() {
        return view('user.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return View
     */
    public function show(User $user) {
        $roles = Role::orderBy("name")->get();
        return view("user.show",
            compact("user",
                "roles"));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     * @return View
     */
    public function edit(User $user) {
        $user_found = $user;
        return view("user.edit",
            compact("user_found"));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return RedirectResponse
     */
    public function destroy(User $user) {
        // if there is no more user then error

        $rules = [
            "user" => [
                new notLastUser,
                new notLastMoreImportantRole($user),
            ],
        ];

        $v = Validator::make(["user" => $user->id],
            $rules);
        if ($v->fails()) {
            return redirect()->back()->withErrors(['cantDeleteUser' => $v->errors()]);
        } else {
            $user->delete();
            return redirect()->route("user.index");
        }

    }

    /**
     * Add user role request to the request list
     *
     * @param  StoreRequestRoleRequest  $request
     * @param  String  $id
     * @return RedirectResponse
     */
    public function RequestRole(StoreRequestRoleRequest $request) {
        $this->authorize('requestRole',
            $request->user());

        $user = $request->user();
        $role = $request->input('role');

        if ($user->role->id == $role) {
            return redirect()->back()->withErrors(['cantRequestRole' => "You already have this role"]);
        }
        RoleRequest::create([
            'user_id' => $user->id,
            'role_id' => $role,
        ]);
        return redirect()->back();
    }

    /**
     * Grant the user role request
     *
     * @param $id
     * @return RedirectResponse
     */
    public function GrantRole($id) {
        $this->authorize('grantRole',
            Auth::user());

        $request = RoleRequest::find($id);
        $user = $request->user;
        $user->update(["role_id" => $request->role->id]);
        $request->update(["granted" => date("Y-m-d H:i:s")]);

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  User  $user
     * @return RedirectResponse
     */
    public function update(UpdateUsersRequest $request,
        User $user) {

        $changes = $request->validated();
        $user->update($changes);

        return redirect()->route('user.index');

    }

    /**
     * Grant the user role request
     *
     * @param $id
     * @return RedirectResponse
     */
    public function RejectRole($id) {

        $this->authorize('grantRole',
            Auth::user());

        $request = RoleRequest::find($id);
        $request->update(["granted" => date("Y-m-d H:i:s")]);

        return redirect()->back();
    }

    /**
     * Add user role request to the request list
     *
     * @param  Request  $request
     * @param  User  $user
     * @return RedirectResponse
     */
    public function ChangeTeam(UpdateCrewRequest $request) {
        $this->authorize('changeTeam',
            $request->user());
        $crew = $request->input('name');
        $user = $request->user();
        $user->crew_id = $crew;
        $user->save();
        return redirect()->back();
    }

    /**
     * Change user language
     *
     * @param  Request  $request
     * @param  User  $user
     * @return RedirectResponse
     */
    public function ChangeLanguage(UpdateLanguageRequest $request) {
        $this->authorize('changeLanguage',
            $request->user());
        $language = $request->input('language_id');
        $user = $request->user();
        $user->language_id = $language;
        $user->save();
        return redirect()->route('language', ["locale"=>strtolower($user->language->API_language_key)]);
    }

    /**
     * Send an invitation email to the user
     * Redirect to the user show page
     *
     * @param  User  $user
     * @return RedirectResponse
     **/
    public function invite(User $user) {
        $this->authorize('invite',
            Auth::user());
        $user->notify(new InviteUserNotification(Auth::user()));
        return redirect()->back()->with('inviteSuccess',
            'Invitation sent successfully.');
    }
}

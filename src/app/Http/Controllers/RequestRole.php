<?php

namespace App\Http\Controllers;

use App\Models\RoleRequest;
use App\Models\User;
use App\Models\UserRole;
use Auth;
use DB;
use Illuminate\Http\Request;

class RequestRole extends Controller
{
    // GET request to redirect
    public function getrequest()
    {
        return redirect('/user');

    }

    //POST request to request role change
    public function request(Request $request)
    {
        $role_id = $request->input('role');
        $role = UserRole::find($role_id);
        $user_id = $request->input('user_id');
        $user = User::find($user_id);
        if ($user->role == $role->role) {
            return redirect()->back();
        }
        RoleRequest::where('user_id', $user_id)->delete();
        $test = RoleRequest::create(['user_id' => $user_id, 'role' => $role_id]);
        return redirect()->back();
    }

    //POST request to grant role change
    public function grant(Request $request)
    {
        $role_id = $request->input('role');
        $role = UserRole::find($role_id);
        $user_id = $request->input('user_id');
        $user = User::find($user_id);
        if ($user->role == $role->role) {
            return redirect()->back();
        }
        $granted = RoleRequest::where('user_id', $user_id)->first();
        $granted->role = $role_id;
        $granted->granted = true;
        $granted->save();
        $user = $user->update(['role' => $role_id]);
        return redirect()->back();
    }
}

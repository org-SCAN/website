<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\RoleRequest;
use Laravel\Jetstream\Jetstream;
use Auth;
use DB;

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
        $roles = Jetstream::$roles;
        $roless=array();
        foreach(array_values($roles) as $role){
            array_push($roless,strtolower($role->name));
        }
        $team_id = $request->input('user_current_team_id');
        $user_id = $request->input('user_id');
        $team_user = DB::table('team_user')->where('team_id', $team_id)->Where('user_id',$user_id)->first();
        if($team_user->role==$roless[$role_id]){
            return redirect()->back();
        }
        RoleRequest::where('user_id',$user_id)->where('team_id',$team_id)->delete();
        $test = RoleRequest::create(['user_id'=>$user_id,'team_id'=>$team_id,'role'=>ucwords($roless[$role_id])]);
        return redirect()->back();
    }
    //POST request to grant role change
    public function grant(Request $request)
    {
        $role_id = $request->input('role');
        $roles = Jetstream::$roles;
        $roless=array();
        foreach(array_values($roles) as $role){
            array_push($roless,$role->key);
        }
        $team_id = $request->input('user_current_team_id');
        $user_id = $request->input('user_id');
        $team_user = DB::table('team_user')->where('team_id', $team_id)->Where('user_id',$user_id)->first();
        if($team_user->role==$roless[$role_id]){
            return redirect()->back();
        }
        $granted = RoleRequest::where('user_id',$user_id)->where('team_id',$team_id)->first();
        $granted->granted = true;
        $granted->save();
        $team_user = DB::table('team_user')->where('team_id', $team_id)->Where('user_id',$user_id)->update(['role' => $roless[$role_id]]);
        return redirect()->back();
    }
    /*
    // Test function
    public function post(Request $request)
    {
        $role = $request->input('role');
        $team_id = $request->input('user_current_team_id');
        $user_id = $request->input('user_id');
        $user = Auth::user();
        $team = Team::find($team_id);
        if($user->ownsTeam($team)){
            return "True";
        }
        return "False";
    }
    */
}

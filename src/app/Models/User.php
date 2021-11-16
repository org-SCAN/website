<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Uuids;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', "token", "role"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Indicate the role of the user according the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getRoleAttribute($value){
        $user_role = UserRole::find($value);
        return empty($user_role) ? "" : $user_role->role;
    }

    /**
     * Indicate the the UUID stored in DB
     * @param $value Is the id of the element
     * @return String
     */
    public function getRoleId(){
        return (!empty($this->attributes['role']) ? $this->attributes['role'] : "");
    }

    /**
     * Create a personal Token API.
     *
     * @return void
     */
    public function genToken()
    {
        $token = $this->createToken('api_token', ["read", "create", "update"])->plainTextToken;
        $this->token = Crypt::encryptString(md5($this->id) . $token);
        $this->save();
    }

    /**
     * Generate a role by default
     *
     */

    public function genRole()
    {
        if (empty($this->getRoleId())) {
            $users = User::all();
            $userAdmin = User::where("role", UserRole::orderBy("importance", "desc")->first()->id)->get();
            if ($users->isEmpty() || $userAdmin->isEmpty()) {
                $this->role = UserRole::orderBy("importance", "desc")->first()->id;
            } else {

                $this->role = UserRole::orderBy("importance")->first()->id;

            }

            $this->save();
        }
    }

    /**
     * Return an API token
     *
     * @return string|string[]|null
     */
    public function getToken()
    {
        $encrypted_token = $this->token;
        $id = $this->id;
        $decypted = Crypt::decryptString($encrypted_token);
        $unsalt = preg_replace('/' . md5($id) . '/', '', $decypted, 1);
        return preg_replace('/[0-9]+\|/', '', $unsalt, 1);
    }

    /**
     * Create the default user
     *
     */
    public static function createDefaultUser()
    {
        $new_user = self::create([
            'name' => "Default user",
            'email' => env("DEFAULT_EMAIL"),
            'password' => Hash::make(env("DEFAULT_PASSWORD")),
        ]);
        $new_user->genToken();
        $new_user->genRole();
    }


    public function hasPermission(string $routeName)
    {
        return UserRole::find($this->getRoleId())->hasPermission($routeName);
    }
}

<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Uuids;
    use SoftDeletes;

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
        'name', 'email', 'password', "token", "role_id", "crew_id"
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
        'email_verified_at' => 'datetime'
    ];

    public function crew(){
        return $this->belongsTo(Crew::class)->withDefault();
    }

    public function role(){
        return $this->belongsTo(UserRole::class)->withDefault();
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
        if (empty($this->role->id)) {
            $users = User::all();
            $userAdmin = User::where("role_id", UserRole::biggestRole())->get();
            if ($users->isEmpty() || $userAdmin->isEmpty()) {
                $this->role_id = UserRole::biggestRole();
            } else {
                $this->role_id = UserRole::smallestRole();
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
            'crew_id' => Crew::getDefaultCrewId()
        ]);
        $new_user->genToken();
        $new_user->genRole();
    }
}

<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
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
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the user email
     *
     * @param $value
     * @return string (user's email)
     */
    public function getUserAttribute($value){
        return User::find($value)->email;
    }

    /**
     * Return the guid of the user
     *
     * @return guid
     */
    public function getUserId()
    {
        return $this->attributes['user'];
    }

    public static function getUser($push_id)
    {
        return self::find($push_id)->user;
    }

    public static function createFromRequest($request, $model = null)
    {
        $log = array();
        $log["user"] = $request->user()->id;
        $log["application_id"] = $request->header("Application-id"); //Pas sur du tout
        $log["api_type"] = $request->path();
        $log["http_method"] = $request->method();
        $log["model"] = $model;
        $log["ip"] = $request->ip();

        return self::create($log);
    }

    public function getPushedDatas(){
        $base_name = "\App\Models\\";

        $pushed_datas = ($base_name . $this->model)::where("api_log", $this->id)->get();
        $res = array();
        foreach ($pushed_datas as $pushed_data) {
            $res[$pushed_data->id] = $pushed_data->getRepresentativeValue();
        }

        return $res;
    }
}

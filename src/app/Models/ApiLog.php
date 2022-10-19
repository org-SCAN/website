<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiLog extends Model
{
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
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public static function getUser($push_id)
    {
        return self::find($push_id)->user_id;
    }

    public function crew()
    {
        return $this->belongsTo(Crew::class)->withDefault();
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withTrashed();
    }

    public static function createFromRequest($request, $model = null)
    {
        $log = array();
        $log["user_id"] = $request->user()->id;
        $log["application_id"] = (!empty($request->header("Application-id")) ? $request->header("Application-id") : (!empty($request->validated()[0]["application_id"]) ? $request->validated()[0]["application_id"] : "website"));
        $log["api_type"] = $request->path();
        $log["http_method"] = $request->method();
        $log["model"] = $model;
        $log["ip"] = $request->ip();
        $log["crew_id"] = $request->user()->crew->id;

        return self::create($log);
    }

    public function getPushedDatas(){
        $base_name = "\App\Models\\";

        $pushed_datas = ($base_name . $this->model)::where("api_log", $this->id)->get();
        $res = array();
        foreach ($pushed_datas as $pushed_data) {
            $res[$pushed_data->id] = $pushed_data->bestDescriptiveValue;
        }

        return $res;
    }
}

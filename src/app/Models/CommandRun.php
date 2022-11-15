<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CommandRun extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;
    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    /**
     * Start the command
     * @return CommandRun
     */
    public static function start(string $command): CommandRun {

        $commandRun = CommandRun::create([
            "command" => $command,
            "status" => "started",
            "started_at" => now(),
        ]);
        return $commandRun;
    }

    /**
     * Get the ended_at date of the last command run
     *
     * @param  string  $command
     * @return CommandRun
     */

    public static function lastEnded(string $command): CommandRun|null {
        $lastCommandRun = CommandRun::where("command",
            $command)->orderBy("ended_at",
            "desc")->first();
        return $lastCommandRun;
    }

    /**
     * End the command
     * @return CommandRun
     */
    public function end(): CommandRun {
        $this->status = "ended";
        $this->ended_at = now();
        $this->save();
        return $this;
    }
}

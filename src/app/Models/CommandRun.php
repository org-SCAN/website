<?php

namespace App\Models;

use App\Console\Kernel;
use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Str;


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
        return CommandRun::whereCommand($command)->orderByDesc("ended_at")->first();
    }

    /**
     * Get the next due date for the command
     *
     * @param  string  $command
     * @return Carbon
     */

    public static function nextDue(string $command): Carbon|null {
        // Execute php artisan schedule:list to see the schedule

        new Kernel(app(),
            new Dispatcher());
        $schedule = app(Schedule::class);
        $tasks = collect($schedule->events());
        $matches = $tasks->filter(function ($item) use
        (
            $command
        ) {
            return Str::contains($item->command,
                $command);
        });

// assuming you get something back in that collection
        if ($matches->count() > 0) {
            return $matches->first()->nextRunDate();
        }
        return null;

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

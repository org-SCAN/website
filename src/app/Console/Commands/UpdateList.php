<?php

namespace App\Console\Commands;

use App\Models\ListControl;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class UpdateList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lists:update {listName?} {--seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to update the ListControl by adding new lists';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // Step 1 : get all stored lists
        $lists = ListControl::all()->pluck('name')->toArray();
        // Step 2 : Compare with json
        $json_content = json_decode(file_get_contents(config('jsonDataset.path')."/".'config/lists_controls.json'),
            true);

        foreach ($json_content as $new_list) {
            if (!in_array($new_list['name'],
                $lists)) {
                $new_list["id"] = (string) Str::uuid();
                $structure = null;
                if (key_exists('structure',
                    $new_list)) {
                    $structure = $new_list["structure"];
                    unset($new_list["structure"]);
                }
                // add the list to the control list
                $list = ListControl::create($new_list);
                //save the structure
                if ($structure != null) {
                    foreach ($structure as $field) {
                        $struct = $list->structure()->create([
                            "field" => $field,
                        ]);
                        if ($field == $list->displayed_value) {
                            $list->update(["displayed_value" => $struct->id]);
                        }
                    }
                }


                // Step 3 : Add and seed the DB if needed
                if ($this->option("seed")) {
                    //call the seeder
                    Artisan::call('db:seed',
                        ['--class' => $new_list["name"].'Seeder']);
                }
            }
        }

        return Command::SUCCESS;
    }
}

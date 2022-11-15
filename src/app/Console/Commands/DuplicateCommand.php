<?php

namespace App\Console\Commands;

use App\Models\CommandRun;
use App\Models\Crew;
use App\Models\Duplicate;
use Illuminate\Console\Command;

class DuplicateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'duplicate:compute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command computes the duplicates';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        // Step 1 : Add the command to the command_run table
        $command = CommandRun::start('duplicate:compute');

        // Step 2 : Compute the duplicates
        // 2.1 : Get all the crew

        $crews = Crew::all();

        // 2.2 : For each crew, get all the refugees

        foreach ($crews as $crew) {
            //print INFO
            $this->info("Computing duplicates for crew ".$crew->name);

            // 2.3 : For each refugee, compute similarity with all the other refugees.
            // Do not compute the similarity with the same refugee,
            // Do not compute the similarity with a refugee that has already been compared and not edited since.
            //Do not compute the similarity with refugees that has been marked as resolve.
            $crewSimilarities = Duplicate::compute($crew);
            $this->info(count($crewSimilarities)." persons computed for crew ".$crew->name);

            // Step 3 : Add the similarities to the duplicates table
            foreach ($crewSimilarities as $person => $personSimilarities) {
                foreach ($personSimilarities as $person2 => $similarity) {
                    $duplicate = Duplicate::updateOrCreate([
                        "person1_id" => $person,
                        "person2_id" => $person2,
                    ]);
                    $duplicate->similarity = $similarity;
                    $duplicate->command_run_id = $command->id;
                    $duplicate->save();
                }
            }
        }

        // Step 4 : Mark the command as finished
        $command->end();

        return Command::SUCCESS;
    }


}

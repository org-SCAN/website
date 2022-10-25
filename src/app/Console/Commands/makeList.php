<?php

namespace App\Console\Commands;

use App\Models\ListControl;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Carbon;

class makeList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:list
                            {id : The id of the list to create (this id should exists in ListControl table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to create a new list.';

    /**
     * Execute the console command.
     *
     * @return int
     */

    /**
     * Filesystem instance
     * @var Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    public function handle()
    {
        // Check if the id exists
        $list = ListControl::findOr($this->argument('id'), function () {
            return "The given id doesn't match our records ";
        });

        if (!($list instanceof ListControl)) {
            $this->error($list);
            return Command::INVALID;
        }

        //create the model
        $path = $this->getSourceFilePath('app/Models', $list->name); //get the path to witch the model has to be created
        $stubModelVariables = [
            'namespace' => 'App\\Models',
            'class' => $list->name,
        ];
        $content = $this->getStubContents($this->getStubPath('model.listControl'), $stubModelVariables); //replace the variable onto the stub

        if (!$this->files->exists($path)) {
            $this->files->put($path, $content);
            $this->info("File : {$path} created");
        } else {
            $this->info("File : {$path} already exits");
            return Command::INVALID;
        }

        //create the migration

        $model = 'App\Models\\'.$list->name;
        $model_elem = new $model;

        $stubMigrationVariable = [
            "table" => $model_elem->getTable(),
            "listFields" => ""
        ];

        $migration_name = Carbon::now()->format('Y_m_d_His')."_create_".$stubMigrationVariable["table"]."_table";
        $path = $this->getSourceFilePath('database/migrations', $migration_name); //get the path to witch the model has to be created


        foreach($list->structure as $field){
            $stubMigrationVariable["listFields"].= '$table->string("'.$field->field.'"'.");\n\t\t\t";
        }

        $content = $this->getStubContents($this->getStubPath('migration.createList'),$stubMigrationVariable); //replace the variable onto the stub

        if (!$this->files->exists($path)) {
            $this->files->put($path, $content);
            $this->info("File : {$path} created");
        } else {
            $this->info("File : {$path} already exits");
            return Command::INVALID;
        }

        return Command::SUCCESS;
    }

    /**
     * Get the full path of generate class
     *
     * @return string
     */
    public function getSourceFilePath($path, $name)
    {
        return base_path($path) .'/' .$name . '.php';
    }

    /**
     * Replace the stub variables(key) with the desire value
     *
     * @param $stub
     * @param array $stubVariables
     * @return bool|mixed|string
     */
    public function getStubContents($stub , $stubVariables = [])
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace)
        {
            $contents = str_replace('{{ '.$search.' }}' , $replace, $contents);
        }
        return $contents;
    }

    /**
     * Return the stub file path
     * @return string
     *
     */
    public function getStubPath($name)
    {
        return __DIR__ . "/../../../stubs/$name.stub";
    }
}

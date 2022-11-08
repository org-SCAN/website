<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeResourceTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:resourceTest {route} {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will create a resource test file for the given model.
    The file will be created in the tests/Feature folder.
    The test will check that the user can access the page and
    that the user can\'t access the page if he doesn\'t have the permission.
    Checked permissions are: *.index, *.show, *.create, *.edit, *.destroy.
    It will also check that the user can\'t access the page if he is not authenticated.';

    /**
     * Filesystem instance
     * @var Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     * @param  Filesystem  $files
     */
    public function __construct(
        Filesystem $files
    ) {
        parent::__construct();

        $this->files = $files;
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $route = $this->argument('route');
        $model = $this->argument('model');
        $model = str_replace('App\\Models\\',
            '', $model);
        $model = str_replace('App\\Models',
            '', $model);
        $model = str_replace('App\\',
            '', $model);
        $model = str_replace('App',
            '', $model);
        $model = str_replace('\\',
            '', $model);
        $model = str_replace('/',
            '', $model);


        //create the test file
        $path = $this->getSourceFilePath("tests/Feature",
            "{$model}Test"); //get the path to witch the model has to be created
        $stubModelVariables = [
            'namespace' => 'Tests\Feature',
            'class' => $model.'Test',
            'route' => $route,
            'model' => $model,
        ];
        $content = $this->getStubContents($this->getStubPath('test.resource'),
            $stubModelVariables); //replace the variable onto the stub

        if (!$this->files->exists($path)) {
            $this->files->put($path,
                $content);
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
    public function getSourceFilePath(
        $path,
        $name
    ) {
        return base_path($path).'/'.$name.'.php';
    }

    /**
     * Replace the stub variables(key) with the desire value
     *
     * @param $stub
     * @param  array  $stubVariables
     * @return bool|mixed|string
     */
    public function getStubContents(
        $stub,
        $stubVariables = []
    ) {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('{{ '.$search.' }}',
                $replace,
                $contents);
        }
        return $contents;
    }

    /**
     * Return the stub file path
     * @return string
     *
     */
    public function getStubPath(
        $name
    ) {
        return __DIR__."/../../../stubs/$name.stub";
    }
}

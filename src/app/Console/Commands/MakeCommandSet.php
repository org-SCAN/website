<?php

namespace App\Console\Commands;

class MakeCommandSet
{


    /**
     * Get the full path of generate class
     *
     * @return string
     */
    static function getSourceFilePath(
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
    static function getStubContents(
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
    static function getStubPath(
        $name
    ) {
        return __DIR__."/../../../stubs/$name.stub";
    }
}

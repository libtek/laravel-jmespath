<?php

return [

    /*
    |--------------------------------------------------------------------------
    | jmespath.php runtime
    |--------------------------------------------------------------------------
    |
    | \JmesPath\CompilerRuntime::class or \JmesPath\AstRuntime::class
    |
    | See https://github.com/jmespath/jmespath.php#runtimes for details
    |
    */

    'runtime' => \JmesPath\CompilerRuntime::class,

    /*
    |--------------------------------------------------------------------------
    | Compiled Expressions Location
    |--------------------------------------------------------------------------
    |
    | The location to store compiled expressions when using CompilerRuntime.
    | Falsy values will use the path returned by sys_get_temp_dir().
    |
    */

    'compile_path' => storage_path('app/jmespath'),

    /*
    |--------------------------------------------------------------------------
    | Saved expressions
    |--------------------------------------------------------------------------
    |
    | JMESPath expressions to pre-compile with "jmes:compile" Artisan command.
    |
    */

    'expressions' => [
        //'foo.*.baz',
    ],

];

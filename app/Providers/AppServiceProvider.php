<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        $defaultConnection = config('database.default');
        $driver = config("database.connections.{$defaultConnection}.driver");
        if ($driver !== 'sqlite') {
            return;
        }

        $database = config("database.connections.{$defaultConnection}.database");
        if (!is_string($database) || $database === '' || $database === ':memory:') {
            return;
        }

        $isAbsolutePath = str_starts_with($database, DIRECTORY_SEPARATOR)
            || str_starts_with($database, '\\\\')
            || preg_match('/^[A-Za-z]:\\\\/', $database) === 1;

        if (!$isAbsolutePath) {
            $database = base_path($database);
            config(["database.connections.{$defaultConnection}.database" => $database]);
        }

        if (!is_file($database)) {
            $directory = dirname($database);
            if ($directory !== '' && !is_dir($directory)) {
                @mkdir($directory, 0775, true);
            }

            @touch($database);
        }
    }
}

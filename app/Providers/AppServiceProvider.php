<?php

namespace App\Providers;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        $this->setFilesystemMacroGetPathUsingId();

        $this->logExecutedDBQueries();
    }

    /**
     * Set the filesystem macro to get directory using id.
     *
     * @return void
     */
    protected function setFilesystemMacroGetPathUsingId(): void
    {
        $this->app['filesystem']->macro('getPathUsingId',
            function (int|string $id, string $path = '') {
                return 'id_' . $id . ($path ? '/' . $path : $path);
            }
        );
    }

    /**
     * Log database queries when the application is in debug mode.
     *
     * @return void
     */
    protected function logExecutedDBQueries(): void
    {
        if (! $this->app->hasDebugModeEnabled()) {
            return;
        }

        $filename = storage_path('logs/queries.log');
        $separator = '------------------------------' . PHP_EOL;

        if (file_exists($filename)) {
            file_put_contents($filename, $separator, LOCK_EX);
        }

        Event::listen(QueryExecuted::class, function($query) use ($filename, $separator) {
            $conn     = 'Connection: ' . $query->connectionName . PHP_EOL;
            $sql      = 'SQL: ' . $query->sql . PHP_EOL;
            $bindings = 'Bindings: ' . implode(', ', (array) $query->bindings) . PHP_EOL;
            $time     = 'Time: ' . $query->time . ' ms' . PHP_EOL;
            $data     = $conn . $sql . $bindings . $time . $separator;

            file_put_contents($filename, $data, FILE_APPEND | LOCK_EX);
        });
    }
}

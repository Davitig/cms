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

        $this->setFilesystemLocalCmsUsersMacro();

        $this->logExecutedDBQueries();
    }

    /**
     * Set the filesystem local cms users macro.
     *
     * @return void
     */
    protected function setFilesystemLocalCmsUsersMacro(): void
    {
        // get user directory
        $this->app['filesystem']->macro('getUserDirectory', function (int|string $id) {
            return 'id_' . $id;
        });

        // get user photos directory
        $this->app['filesystem']->macro('getUserPhotosDirectory',
            function (int|string $id, ?string $fileName = null) {
                $idDir = $this->getUserDirectory($id);

                $photosDir = $this->getConfig()['directories']['photos'] ?? null;

                return $photosDir
                    ? $idDir . '/' . $photosDir . ($fileName ? '/' . $fileName : '')
                    : $idDir;
            });
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
            @unlink($filename);
        }

        file_put_contents($filename, $separator);

        Event::listen(QueryExecuted::class, function($query) use ($filename, $separator) {
            $conn     = 'Connection: ' . $query->connectionName . PHP_EOL;
            $sql      = 'SQL: ' . $query->sql . PHP_EOL;
            $bindings = 'Bindings: ' . implode(', ', (array) $query->bindings) . PHP_EOL;
            $time     = 'Time: ' . $query->time . ' ms' . PHP_EOL;
            $data     = $conn . $sql . $bindings . $time . $separator;

            $flags = FILE_APPEND | LOCK_EX;

            file_put_contents($filename, $data, $flags);
        });
    }
}

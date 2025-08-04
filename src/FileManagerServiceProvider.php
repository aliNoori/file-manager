<?php
namespace FileManager;

use FileManager\Console\Commands\FixModelNamespace;
use FileManager\Contracts\FileManagerInterface;
use Illuminate\Support\ServiceProvider;
use FileManager\Services\FileManagerService;

class FileManagerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FileManagerInterface::class, FileManagerService::class);

        $this->mergeConfigFrom(__DIR__.'/config/filemanager.php', 'filemanager');

        // Register artisan commands
        $this->commands([
            FixModelNamespace::class,
        ]);

    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/config/filemanager.php' => config_path('filemanager.php'),
        ], 'config');

        $this->publishes([
            __DIR__. '/Database/Migrations' => database_path('migrations'),
        ], 'file-manager-migrations');

        $this->publishes([
            __DIR__. '/Models' => app_path('Models'),
        ], 'file-manager-models');


        $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
    }
}

<?php

namespace NocturnalSm\Generator;

use Illuminate\Support\ServiceProvider;
use NocturnalSm\Generator\CodeNumber;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class GeneratorProvider extends ServiceProvider
{
    public function boot(Filesystem $filesystem)
    {
        $this->publishes([
            __DIR__.'/../database/migrations/create_codenumber_table.php.stub' => $this->getMigrationFileName($filesystem),
        ], 'migrations');            
    }

    public function register()
    {
        $this->app->singleton(CodeNumber::class, function ($app) {
            return new CodeNumber;
        });        
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @param Filesystem $filesystem
     * @return string
     */
    protected function getMigrationFileName(Filesystem $filesystem): string
    {
        $timestamp = date('Y_m_d_His');

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem) {
                return $filesystem->glob($path.'*_create_codenumber_table.php');
            })->push($this->app->databasePath()."/migrations/{$timestamp}_create_codenumber_table.php")
            ->first();
    }
}

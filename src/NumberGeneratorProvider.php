<?php

namespace NocturnalSm\NumberGenerator;

use Illuminate\Support\ServiceProvider;
use NocturnalSm\NumberGenerator\NumberGenerator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class NumberGeneratorProvider extends ServiceProvider
{
    public function boot(Filesystem $filesystem)
    {
        $this->publishes([
            __DIR__.'/../database/migrations/create_numbergenerator_tables.php.stub' => $this->getMigrationFileName($filesystem),
        ], 'migrations');            
    }

    public function register()
    {
        $this->app->singleton(NumberGenerator::class, function ($app) {
            return new NumberGenerator;
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
                return $filesystem->glob($path.'*_create_numbergenerator_table.php');
            })->push($this->app->databasePath()."/migrations/{$timestamp}_create_numbergenerator_table.php")
            ->first();
    }
}

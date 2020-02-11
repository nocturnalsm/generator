<?php

namespace NocturnalSm\NumberGenerator;

use Illuminate\Support\ServiceProvider;
use NocturnalSm\NumberGenerator\NumberGenerator;

class NumberGeneratorProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../database/migrations/create_numbergenerator_tables.php.stub' => $this->getMigrationFileName($filesystem),
        ], 'migrations');     

        $this->publishes([
            __DIR__.'/../config/numbergenerator.php' => config_path('numbergenerator.php'),
        ], 'config');

    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/numbergenerator.php', 'numbergenerator'
        );
        $this->app->singleton(NumberGenerator::class, function ($app) {
            return new NumberGenerator;
        });        
    }
}

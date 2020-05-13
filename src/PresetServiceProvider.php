<?php

namespace JarIt\LaravelPreset;

use Laravel\Ui\UiCommand;
use Illuminate\Support\ServiceProvider;

class PresetServiceProvider extends ServiceProvider
{
    public function boot()
    {
        UiCommand::macro('jar-it', function ($command) {
            Preset::install();

            $command->info('Jar IT scaffolding installed successfully.');
            $command->info('Please run "yarn" to compile your fresh scaffolding.');
        });
    }
}

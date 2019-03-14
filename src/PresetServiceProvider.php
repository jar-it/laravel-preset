<?php

namespace JarIt\LaravelPreset;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Console\PresetCommand;

class PresetServiceProvider extends ServiceProvider
{
    public function boot()
    {
        PresetCommand::macro('jar-it', function ($command) {
            Preset::install();

            $command->info('Jar IT scaffolding installed successfully.');
            $command->info('Please run "yarn" to compile your fresh scaffolding.');
        });
    }
}

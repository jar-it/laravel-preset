<?php

namespace JarIt\LaravelPreset;

use Illuminate\Support\Arr;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\Presets\Preset as BasePreset;

class Preset extends BasePreset {

    public static function install()
    {
        static::updatePackages();
        static::updateCodeStyles();
        static::updateBootstrapping();
        static::updateStyles();
        static::updateViews();
        static::updateComponents();
        static::updateTestCase();
    }

    protected static function updatePackageArray(array $packages)
    {
        return [
            'laravel-mix' => '^4.0.14',
            'laravel-mix-purgecss' => '^4.1',
            'tailwindcss' => '^1.0.1',
            'vue' => '^2.5.17',
            'vue-template-compiler' => '^2.6.4',
            'eslint' => '^5.15.1',
            'eslint-plugin-vue' => '^5.2.2'
        ] + Arr::except($packages, [
            'bootstrap',
            'boostrap-sass',
            'laravel-mix',
            'jquery',
        ]);
    }

    protected static function updateCodeStyles()
    {
        copy(__DIR__.'/stubs/.eslintrc', base_path('.eslintrc'));
    }

    protected static function updateBootstrapping()
    {
        copy(__DIR__.'/stubs/tailwind.config.js', base_path('tailwind.config.js'));

        copy(__DIR__.'/stubs/webpack.mix.js', base_path('webpack.mix.js'));

        copy(__DIR__.'/stubs/resources/js/bootstrap.js', resource_path('js/bootstrap.js'));
    }

    protected static function updateStyles()
    {
        tap(new Filesystem, function ($filesystem) {
            $filesystem->deleteDirectory(resource_path('sass'));
            $filesystem->delete(public_path('js/app.js'));
            $filesystem->delete(public_path('css/app.css'));

            if (! $filesystem->isDirectory($directory = resource_path('css'))) {
                $filesystem->makeDirectory($directory, 0755, true);
            }
        });

        copy(__DIR__.'/stubs/resources/css/app.css', resource_path('css/app.css'));
    }

    protected static function updateViews()
    {
        (new Filesystem)->copyDirectory(__DIR__.'/stubs/resources/views', resource_path('views'));
    }

    protected static function updateComponents()
    {
        (new Filesystem)->copyDirectory(__DIR__.'/stubs/resources/js/components', resource_path('js/components'));
    }

    protected static function updateTestCase()
    {
        copy(__DIR__.'/stubs/tests/TestCase.php', base_path('tests/TestCase.php'));
    }
}

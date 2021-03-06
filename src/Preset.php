<?php

namespace JarIt\LaravelPreset;

use Illuminate\Support\Arr;
use Illuminate\Filesystem\Filesystem;
use Laravel\Ui\Presets\Preset as BasePreset;

class Preset extends BasePreset 
{
    const INSTALL_PACKAGES = [
        'laravel-mix-purgecss' => '^5.0',
        'tailwindcss' => '^1.6.2',
        'vue' => '^2.6.11',
        'vue-template-compiler' => '^2.6.11',
        'eslint' => '^6.8.0',
        'eslint-plugin-vue' => '^6.1.2'
    ];

    const REMOVE_PACKAGES = [
        'sass',
        'sass-loader'
    ];

    public static function install()
    {
        static::updatePackages();
        static::updateCodeStyles();
        static::updateBootstrapping();
        static::updateStyles();
        static::updateViews();
        static::updateComponents();
    }

    protected static function updatePackageArray(array $packages)
    {
        return static::INSTALL_PACKAGES + Arr::except($packages, static::REMOVE_PACKAGES);
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

        copy(__DIR__.'/stubs/resources/js/app.js', resource_path('js/app.js'));

        copy(__DIR__.'/stubs/resources/js/root.js', resource_path('js/root.js'));
    }

    protected static function updateStyles()
    {
        tap(new Filesystem, function ($filesystem) {
            $filesystem->deleteDirectory(resource_path('sass'));

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
}

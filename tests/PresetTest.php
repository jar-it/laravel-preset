<?php

use JarIt\LaravelPreset\Preset;
use Orchestra\Testbench\TestCase;
use Illuminate\Filesystem\Filesystem;

class PresetTest extends TestCase
{
    protected function setUp(): void
    {
        $zip = new ZipArchive();

        if ($zip->open(__DIR__.'/stubs/laravel.zip')) {
            $zip->extractTo(__DIR__.'/fixtures/');
            $zip->close();
        }

        parent::setUp();

        $this->artisan('ui jar-it');
    }

    protected function tearDown(): void
    {
        (new Filesystem)->deleteDirectory(__DIR__.'/fixtures/laravel-master');
    }

    /** @test **/
    public function it_updates_javascript_packages()
    {
        $packages = with(file_get_contents(base_path('package.json')), function ($file) {
            return json_decode($file, true)['devDependencies'];
        });

        collect(Preset::INSTALL_PACKAGES)->each(function ($version, $package) use ($packages) {
            $this->assertArrayHasKey($package, $packages);
            $this->assertEquals($version, $packages[$package]);
        });

        // Since packages can be removed while also being added (i.e specifying different version)
        // we will exclude the packages that are in both places
        collect(Preset::REMOVE_PACKAGES)->diff(array_keys(Preset::INSTALL_PACKAGES))
            ->each(function ($package) use ($packages) {
                $this->assertArrayNotHasKey($package, $packages);
            });
    }

    /** @test **/
    public function it_configures_code_styles()
    {
        $this->assertFileEquals($this->presetPath('stubs/.eslintrc'), base_path('.eslintrc'));
    }

    /** @test **/
    public function it_updates_bootstrapping()
    {
        $this->assertFileEquals($this->presetPath('stubs/tailwind.config.js'), base_path('tailwind.config.js'));
        $this->assertFileEquals($this->presetPath('stubs/webpack.mix.js'), base_path('webpack.mix.js'));
        $this->assertFileEquals($this->presetPath('stubs/resources/js/bootstrap.js'), base_path('resources/js/bootstrap.js'));
        $this->assertFileEquals($this->presetPath('stubs/resources/js/app.js'), base_path('resources/js/app.js'));
        $this->assertFileEquals($this->presetPath('stubs/resources/js/root.js'), base_path('resources/js/root.js'));
    }

    /** @test **/
    public function it_removes_unused_style_paths()
    {
        $this->assertFalse((new Filesystem)->exists(resource_path('sass')));
    }

    /** @test **/
    public function it_updates_stylesheet()
    {
        $this->assertFileEquals($this->presetPath('stubs/resources/css/app.css'), resource_path('css/app.css'));
    }

    /** @test **/
    public function it_copies_views_directory()
    {
        collect((new Filesystem)->files($this->presetPath('stubs/resources/views')))->each(function ($file) {
            $expected = $file->getPathname();
            $actual = resource_path("views/{$file->getFilename()}");

            $this->assertFileEquals($expected, $actual, "File {$expected}.");
        });
    }

    /** @test **/
    public function it_copies_components_directory()
    {
        collect((new Filesystem)->files($this->presetPath('stubs/resources/js/components')))->each(function ($file) {
            $expected = $file->getPathname();
            $actual = resource_path("js/components/{$file->getFilename()}");

            $this->assertFileEquals($expected, $actual, "File {$expected}.");
        });
    }

    /** @test **/
    public function it_updates_testcase()
    {
        $this->assertFileEquals($this->presetPath('stubs/tests/TestCase.php'), base_path('tests/TestCase.php'));
    }

    protected function getPackageProviders($app)
    {
        return [
            'Laravel\Ui\UiServiceProvider',
            'JarIt\LaravelPreset\PresetServiceProvider',
        ];
    }

    protected function getBasePath()
    {
        return realpath(__DIR__.'/fixtures/laravel-master');
    }

    protected function presetPath($path = '')
    {
        return __DIR__."/../src".($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

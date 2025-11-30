<?php

namespace Zaynasheff\PipelineSales;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Zaynasheff\PipelineSales\Testing\TestsPipelineSales;

class PipelineSalesServiceProvider extends PackageServiceProvider
{
    public static string $name = 'pipeline-sales';

    public static string $viewNamespace = 'pipeline-sales';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('zaynasheff/pipeline-sales');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations();
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        $this->app->register(\Zaynasheff\PipelineSales\PipelineSalesPanelProvider::class);

        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Register Livewire Kanban component
        Livewire::component(
            'pipeline-sales.pipeline-board',
            \Zaynasheff\PipelineSales\Livewire\PipelineBoard::class
        );

        // Publish stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/pipeline-sales/{$file->getFilename()}"),
                ], 'pipeline-sales-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsPipelineSales);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'zaynasheff/pipeline-sales';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('pipeline-sales', __DIR__ . '/../resources/dist/components/pipeline-sales.js'),
            Css::make('pipeline-sales-styles', __DIR__ . '/../resources/dist/pipeline-sales.css'),
            Js::make('pipeline-sales-scripts', __DIR__ . '/../resources/dist/pipeline-sales.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [

            \Zaynasheff\PipelineSales\Commands\EnableMultitenancyCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }


}

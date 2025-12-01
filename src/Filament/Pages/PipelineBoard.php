<?php

namespace Zaynasheff\PipelineSales\Filament\Pages;

use Filament\Pages\Page;

class PipelineBoard extends Page
{
    /**
     * @var mixed|string|null
     */
    public static ?string $pluginNavigationLabel = null;

    public static ?string $pluginNavigationGroup = null;

    public static ?string $pluginNavigationIcon = null;

    public static ?int $pluginNavigationSort = null;

    public static ?bool $pluginNavigationHidden = null;

    public static ?string $pluginPageTitle = null;

    protected static ?string $slug = 'pipeline-board';

    protected static string $view = 'pipeline-sales::filament.pages.pipeline-board';

    public static function getNavigationLabel(): string
    {
        return static::$pluginNavigationLabel ?? 'Pipeline Board';
    }

    public static function getNavigationGroup(): ?string
    {
        return static::$pluginNavigationGroup;
    }

    public static function getNavigationIcon(): ?string
    {
        return static::$pluginNavigationIcon ?? 'heroicon-o-view-columns';
    }

    public static function getNavigationSort(): ?int
    {
        return static::$pluginNavigationSort;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return ! static::$pluginNavigationHidden;
    }

    public function getTitle(): string
    {
        return static::$pluginPageTitle ?? 'Pipeline Board';
    }
}

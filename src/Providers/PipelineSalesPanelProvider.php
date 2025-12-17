<?php

namespace Zaynasheff\PipelineSales\Providers;

use Filament\Panel;
use Filament\PanelProvider;

class PipelineSalesPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {

        return $panel
            ->id('pipeline-sales')                // unique panel ID
            ->path('pipeline-sales')

            ->discoverResources(in: __DIR__ . '/../Filament/Resources', for: 'Zaynasheff\\PipelineSales\\Filament\\Resources')
            ->discoverPages(
                in: __DIR__ . '/../Filament/Pages', // Убедитесь, что этот путь физически существует
                for: 'Zaynasheff\\PipelineSales\\Filament\\Pages' // Убедитесь, что это пространство имен совпадает с файлами страниц
            )
            ->discoverWidgets(in: __DIR__ . '/../Filament/Widgets', for: 'Zaynasheff\\PipelineSales\\Filament\\Widgets');

    }
}

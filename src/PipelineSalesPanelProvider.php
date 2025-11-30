<?php

namespace Zaynasheff\PipelineSales;

use Filament\Panel;
use Filament\PanelProvider;

class PipelineSalesPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('pipeline-sales')
            ->path('pipeline-sales')
            ->pages([
                // Register your custom pages here
                \Zaynasheff\PipelineSales\Filament\Pages\PipelineBoard::class,
            ])
            ->resources([
                // Register resources if you have them
            ])
            ->widgets([
                // If you need widgets
            ]);
    }
}

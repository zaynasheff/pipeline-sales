<?php

namespace Zaynasheff\PipelineSales\Providers;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;

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

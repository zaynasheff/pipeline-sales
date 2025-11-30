<?php

namespace Zaynasheff\PipelineSales;

use Filament\Contracts\Plugin;
use Filament\Panel;

class PipelineSalesPlugin implements Plugin
{
    public function getId(): string
    {
        return 'pipeline-sales';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}

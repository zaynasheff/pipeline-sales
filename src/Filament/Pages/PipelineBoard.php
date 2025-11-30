<?php

namespace Zaynasheff\PipelineSales\Filament\Pages;

use Filament\Pages\Page;

class PipelineBoard extends Page
{
    protected static string $view = 'pipeline-sales::filament.pages.pipeline-board';

    protected static ?string $navigationIcon = 'heroicon-o-view-columns';

    protected static ?string $navigationLabel = 'Pipeline Board';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?string $slug = 'pipeline-board';

    /**
     * Title displayed in the header
     */
    protected static ?string $title = 'Pipeline Board';

    /**
     * Livewire component that handles the logic
     */
    public string $livewireComponent = 'pipeline-sales.pipeline-board';
}

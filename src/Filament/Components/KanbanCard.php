<?php

namespace Zaynasheff\PipelineSales\Filament\Components;

use Livewire\Component;
use Zaynasheff\PipelineSales\Models\Deal;

class KanbanCard extends Component
{
    public Deal $deal;

    public function getName(): string
    {
        return 'kanban-card';
    }
    public function render()
    {
        return view('pipeline-sales::components.kanban-card');
    }
}

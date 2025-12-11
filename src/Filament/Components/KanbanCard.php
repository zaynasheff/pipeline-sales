<?php

namespace Zaynasheff\PipelineSales\Filament\Components;


use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\MaxWidth;
use Livewire\Component;
use Zaynasheff\PipelineSales\Models\Deal;

class KanbanCard extends Component implements HasActions,HasForms
{
    use InteractsWithActions,InteractsWithForms;

    public Deal $deal;

    public function getName(): string
    {
        return 'kanban-card';
    }

    protected $listeners = ['dealUpdated' => 'refreshDeal'];

    public function refreshDeal(string $uuid): void
    {
        if ($uuid === $this->deal->uuid) {
            $this->deal->refresh();
        }
    }
    public function viewDealAction(): Action
    {
        return Action::make('viewDeal')
            ->modalHeading($this->deal->stage->name)
            ->modalWidth(MaxWidth::SevenExtraLarge)
            ->modalCancelAction(false)
            ->record($this->deal)
            ->modalSubmitAction(false)
            ->modalContent(
                view('pipeline-sales::components.card-modal', [
                    'deal' => $this->deal,
                ])
            );
    }
    public function render()
    {
        return view('pipeline-sales::components.kanban-card');
    }
}

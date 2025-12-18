<?php

namespace Zaynasheff\PipelineSales\Filament\Components;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Zaynasheff\PipelineSales\Models\Deal;

class CardModalDropdownActions extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public Deal $deal;

    public function mount(Deal $deal): void
    {
        $this->deal = $deal;

        $this->mountAction('deleteDeal');
    }

    public function deleteDealAction(): Action
    {
        return Action::make('deleteDeal')
            ->label(__('pipeline-sales::pipeline-sales.delete'))
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->action(function () {
                $this->deal->delete();
                $this->dispatch('dealDeleted', id: 'kanban-column');
            });
    }

    public function render()
    {
        return view('pipeline-sales::components.card-modal-dropdown-actions');
    }
}
